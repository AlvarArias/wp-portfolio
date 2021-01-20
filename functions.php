<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_VERSION', '2.2.0' );

if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}

if ( ! function_exists( 'hello_elementor_setup' ) ) {
	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	function hello_elementor_setup() {
		$hook_result = apply_filters_deprecated( 'elementor_hello_theme_load_textdomain', [ true ], '2.0', 'hello_elementor_load_textdomain' );
		if ( apply_filters( 'hello_elementor_load_textdomain', $hook_result ) ) {
			load_theme_textdomain( 'hello-elementor', get_template_directory() . '/languages' );
		}

		$hook_result = apply_filters_deprecated( 'elementor_hello_theme_register_menus', [ true ], '2.0', 'hello_elementor_register_menus' );
		if ( apply_filters( 'hello_elementor_register_menus', $hook_result ) ) {
			register_nav_menus( array( 'menu-1' => __( 'Primary', 'hello-elementor' ) ) );
		}

		$hook_result = apply_filters_deprecated( 'elementor_hello_theme_add_theme_support', [ true ], '2.0', 'hello_elementor_add_theme_support' );
		if ( apply_filters( 'hello_elementor_add_theme_support', $hook_result ) ) {
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support(
				'html5',
				array(
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
				)
			);
			add_theme_support(
				'custom-logo',
				array(
					'height'      => 100,
					'width'       => 350,
					'flex-height' => true,
					'flex-width'  => true,
				)
			);

			/*
			 * Editor Style.
			 */
			add_editor_style( 'editor-style.css' );

			/*
			 * WooCommerce.
			 */
			$hook_result = apply_filters_deprecated( 'elementor_hello_theme_add_woocommerce_support', [ true ], '2.0', 'hello_elementor_add_woocommerce_support' );
			if ( apply_filters( 'hello_elementor_add_woocommerce_support', $hook_result ) ) {
				// WooCommerce in general.
				add_theme_support( 'woocommerce' );
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
				// zoom.
				add_theme_support( 'wc-product-gallery-zoom' );
				// lightbox.
				add_theme_support( 'wc-product-gallery-lightbox' );
				// swipe.
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'hello_elementor_setup' );

if ( ! function_exists( 'hello_elementor_scripts_styles' ) ) {
	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	function hello_elementor_scripts_styles() {
		$enqueue_basic_style = apply_filters_deprecated( 'elementor_hello_theme_enqueue_style', [ true ], '2.0', 'hello_elementor_enqueue_style' );
		$min_suffix          = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		if ( apply_filters( 'hello_elementor_enqueue_style', $enqueue_basic_style ) ) {
			wp_enqueue_style(
				'hello-elementor',
				get_template_directory_uri() . '/style' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if ( apply_filters( 'hello_elementor_enqueue_theme_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor-theme-style',
				get_template_directory_uri() . '/theme' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_scripts_styles' );

if ( ! function_exists( 'hello_elementor_register_elementor_locations' ) ) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	function hello_elementor_register_elementor_locations( $elementor_theme_manager ) {
		$hook_result = apply_filters_deprecated( 'elementor_hello_theme_register_elementor_locations', [ true ], '2.0', 'hello_elementor_register_elementor_locations' );
		if ( apply_filters( 'hello_elementor_register_elementor_locations', $hook_result ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action( 'elementor/theme/register_locations', 'hello_elementor_register_elementor_locations' );

if ( ! function_exists( 'hello_elementor_content_width' ) ) {
	/**
	 * Set default content width.
	 *
	 * @return void
	 */
	function hello_elementor_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'hello_elementor_content_width', 800 );
	}
}
add_action( 'after_setup_theme', 'hello_elementor_content_width', 0 );

if ( is_admin() ) {
	require get_template_directory() . '/includes/admin-functions.php';
}

if ( ! function_exists( 'hello_elementor_check_hide_title' ) ) {
	/**
	 * Check hide title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	function hello_elementor_check_hide_title( $val ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = \Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter( 'hello_elementor_page_title', 'hello_elementor_check_hide_title' );

/**
 * Wrapper function to deal with backwards compatibility.
 */
if ( ! function_exists( 'hello_elementor_body_open' ) ) {
	function hello_elementor_body_open() {
		if ( function_exists( 'wp_body_open' ) ) {
			wp_body_open();
		} else {
			do_action( 'wp_body_open' );
		}
	}
}
function concat_email_field($field_value,$field_name){
	$result = $field_name.$field_value;
	return $result;
}
function print_email($fields){
	$HTML_00 = '
<h3>Requestor information</h3>
<table width="100%" border="1" cellpadding="1" cellspacing="0">
	<tr>
		<td align="center" valign="top" bgcolor="#F2F2F2">Förnamn</td>	
		<td align="center" valign="top">'.$fields['form_fields']['name1'].'</td>	
	</tr>
	<tr>
		<td align="center" valign="top" bgcolor="#F2F2F2">Efterrnamn</td>	
		<td align="center" valign="top">'.$fields['form_fields']['field_9700c77'].'</td>	
	</tr>
	<tr>
		<td align="center" valign="top" bgcolor="#F2F2F2">Email</td>	
		<td align="center" valign="top">'.$fields['form_fields']['email1'].'</td>	
	</tr>
	<tr>
		<td align="center" valign="top" bgcolor="#F2F2F2">Telefonnummer</td>	
		<td align="center" valign="top">'.$fields['form_fields']['tel1'].'</td>	
	</tr>
	<tr>
		<td align="center" valign="top" bgcolor="#F2F2F2">Adress</td>	
		<td align="center" valign="top">'.$fields['form_fields']['message'].'</td>	
	</tr>
	<tr>
		<td align="center" valign="top" bgcolor="#F2F2F2">Postnummer</td>	
		<td align="center" valign="top">'.$fields['form_fields']['field_6ee6328'].'</td>	
	</tr>
	<tr>
		<td align="center" valign="top" bgcolor="#F2F2F2">Adress 2</td>	
		<td align="center" valign="top">'.$fields['form_fields']['field_6ee6328'].'</td>	
	</tr>
	<tr>
		<td align="center" valign="top" bgcolor="#F2F2F2">Leveransadress</td>	
		<td align="center" valign="top">'.$fields['form_fields']['field_3dd9a06'].'</td>	
	</tr>
</table>
';

	//Ung HälsoGrisskinka
	$HTML_01_title = "";
	$HTML_01a = "";
	$HTML_01b = "";

	//Ung HälsoGrisskinka title
	if($fields['form_fields']['field_768f841'] != '' or $fields['form_fields']['field_9b65d74'] != ''){
		$HTML_01_title = '<h3>Ung HälsoGrisskinka</h3>';
	}
	//Ung HälsoGrisskinka first item
	if($fields['form_fields']['field_768f841'] != ''){
		$HTML_01a = '
<table width="100%" border="1" cellpadding="1" cellspacing="0">
	<tr bgcolor="#F2F2F2">
		<td align="center" valign="top">Sockersaltad</td>	
		<td align="center" valign="top">Antal</td>	
		<td align="center" valign="top">Önskad vikt</td>	
		<td align="center" valign="top">Tilllagning</td>	
		<td align="center" valign="top">Griljering</td>	
	</tr>
	<tr>
		<td align="center" valign="top">'.$fields['form_fields']['field_0869156'].'</td>
		<td align="center" valign="top">'.$fields['form_fields']['field_768f841'].'</td>
		<td align="center" valign="top">'.$fields['form_fields']['field_df9df94'].'</td>
		<td align="center" valign="top">'.$fields['form_fields']['field_a48d19c'].'</td>
		<td align="center" valign="top">'.$fields['form_fields']['field_08986fe'].'</td>
	</tr>
</table>
<br />
';
	}
	//Ung HälsoGrisskinka second item
	if($fields['form_fields']['field_9b65d74'] != ''){
		$HTML_01b = '
<table width="100%" border="1" cellpadding="1" cellspacing="0">
	<tr bgcolor="#F2F2F2">
		<td align="center" valign="top">Lättrökt</td>	
		<td align="center" valign="top">Antal</td>	
		<td align="center" valign="top">Önskad vikt</td>	
		<td align="center" valign="top">Tilllagning</td>	
		<td align="center" valign="top">Griljering</td>	
	</tr>
	<tr>
		<td align="center" valign="top">'.$fields['form_fields']['field_39ffe77'].'</td>
		<td align="center" valign="top">'.$fields['form_fields']['field_9b65d74'].'</td>
		<td align="center" valign="top">'.$fields['form_fields']['field_34e4ebf'].'</td>
		<td align="center" valign="top">'.$fields['form_fields']['field_86a3415'].'</td>
		<td align="center" valign="top">'.$fields['form_fields']['field_f9f630a'].'</td>
</table>
<br />
';
	}
	$HTML_01 = $HTML_01_title.$HTML_01a.$HTML_01b;
	
	//HälsoGrisskinka
	$HTML_02_title = "";
	$HTML_02a = "";
	$HTML_02b = "";

	//HälsoGrisskinka title
	if($fields['form_fields']['field_e178560'] != '' or $fields['form_fields']['field_74d7751'] != ''){
		$HTML_02_title = '<h3>HälsoGrisskinka</h3>';
	}
	//HälsoGrisskinka first item
	if($fields['form_fields']['field_e178560'] != ''){
		$HTML_02a = '
<table width="100%" border="1" cellpadding="1" cellspacing="0">
	<tr bgcolor="#F2F2F2">
		<td align="center" valign="top">Sockersaltad</td>	
		<td align="center" valign="top">Antal</td>	
		<td align="center" valign="top">Önskad vikt</td>	
		<td align="center" valign="top">Tilllagning</td>	
		<td align="center" valign="top">Griljering</td>	
	</tr>
	<tr>
		<td align="center" valign="top">'.$fields['form_fields']['field_2853e0a'].'</td>
		<td align="center" valign="top">'.$fields['form_fields']['field_e178560'].'</td>
		<td align="center" valign="top">'.$fields['form_fields']['field_2505ddd'].'</td>
		<td align="center" valign="top">'.$fields['form_fields']['field_e52b1a7'].'</td>
		<td align="center" valign="top">'.$fields['form_fields']['field_de71a83'].'</td>
	</tr>
</table>
<br />
';
	}
	//HälsoGrisskinka second item
	if($fields['form_fields']['field_9b65d74'] != ''){
		$HTML_02b = '
<table width="100%" border="1" cellpadding="1" cellspacing="0">
	<tr bgcolor="#F2F2F2">
		<td align="center" valign="top">Lättrökt</td>	
		<td align="center" valign="top">Antal</td>	
		<td align="center" valign="top">Önskad vikt</td>	
		<td align="center" valign="top">Tilllagning</td>	
		<td align="center" valign="top">Griljering</td>	
	</tr>
	<tr>
		<td align="center" valign="top">'.$fields['form_fields']['field_63f9e06'].'</td>
		<td align="center" valign="top">'.$fields['form_fields']['field_74d7751'].'</td>
		<td align="center" valign="top">'.$fields['form_fields']['field_7b2138d'].'</td>
		<td align="center" valign="top">'.$fields['form_fields']['field_6c433b0'].'</td>
		<td align="center" valign="top">'.$fields['form_fields']['field_b3c03d7'].'</td>
</table>
<br />
';
	}
	$HTML_02 = $HTML_02_title.$HTML_02a.$HTML_02b;
        $HTML_03 = "";
	$HTML_03a = "";
        if($fields['form_fields']['field_0483d68'] != ''){
                $HTML_03a = '   
        <tr>
		<td align="center" valign="top">Ung Häslogris Revben</td>
                <td align="center" valign="top">'.$fields['form_fields']['field_9c2d4eb'].'</td>
                <td align="center" valign="top">'.$fields['form_fields']['field_0483d68'].'</td>
                <td align="center" valign="top">'.$fields['form_fields']['field_034f1cd'].'</td>
        </tr>';
        }
	$HTML_03b = "";
        if($fields['form_fields']['field_206ef10'] != ''){
                $HTML_03b = '   
        <tr>
		<td align="center" valign="top">Häslogris Revben</td>
                <td align="center" valign="top">'.$fields['form_fields']['field_4a69dbd'].'</td>
                <td align="center" valign="top">'.$fields['form_fields']['field_206ef10'].'</td>
                <td align="center" valign="top">'.$fields['form_fields']['field_56c2799'].'</td>
        </tr>';
        }
	$HTML_03c = "";
        if($fields['form_fields']['field_810194d'] != ''){
                $HTML_03c = '   
        <tr>
		<td align="center" valign="top">Prova några av våra berömda korvar</td>
                <td align="center" valign="top">'.$fields['form_fields']['field_bd1e493'].'</td>
                <td align="center" valign="top">'.$fields['form_fields']['field_810194d'].'</td>
                <td align="center" valign="top">'.$fields['form_fields']['field_8758389'].'</td>
        </tr>';
        }
	$HTML_03d = "";
        if($fields['form_fields']['field_042f858'] != ''){
                $HTML_03d = '   
        <tr>
		<td align="center" valign="top">Övriga Charkuterier</td>
                <td align="center" valign="top">'.$fields['form_fields']['field_bf0d2a8'].'</td>
                <td align="center" valign="top">'.$fields['form_fields']['field_042f858'].'</td>
                <td align="center" valign="top">'.$fields['form_fields']['field_3b84c77'].'</td>
        </tr>';
        }
	$HTML_03e = "";
        if($fields['form_fields']['field_24b6796'] != ''){
                $HTML_03e = '   
        <tr>
		<td align="center" valign="top">För det lilla extra på julbordet som sillen, brunkål, rödkål och köttbollarna</td>
                <td align="center" valign="top">'.$fields['form_fields']['field_cd527a3'].'</td>
                <td align="center" valign="top">'.$fields['form_fields']['field_24b6796'].'</td>
                <td align="center" valign="top">'.$fields['form_fields']['field_8f41566'].'</td>
        </tr>';
        }
	if(
		$fields['form_fields']['field_0483d68'] != '' or
		$fields['form_fields']['field_206ef10'] != '' or
		$fields['form_fields']['field_810194d'] != '' or
		$fields['form_fields']['field_042f858'] != '' or
		$fields['form_fields']['field_24b6796'] != ''
		
	){
		$HTML_03 = '
<h3>Other Products</h3>
<table width="100%" border="1" cellpadding="1" cellspacing="0">
        <tr bgcolor="#F2F2F2">
                <td align="center" valign="top">Kategori</td>   
                <td align="center" valign="top">Välj produkt</td>       
                <td align="center" valign="top">Antal</td>      
                <td align="center" valign="top">Önskad vikt</td>        
        </tr>'.$HTML_03a.$HTML_03b.$HTML_03c.$HTML_03d.$HTML_03e.'
	</table>
	<br />
';					
	}
	$HTML_04 = "";
	if($fields['form_fields']['field_c6a3dae'] != ''){
		$HTML_04 = '
<h3>Vi kan leverera till din rekoring</h3>
'.$fields['form_fields']['field_c6a3dae'];
	}
	return $HTML_00.$HTML_01.$HTML_02.$HTML_03.$HTML_04;
}
?>
