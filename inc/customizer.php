<?php
/**
 * Twenty Sixteen Ex Customizer functionality
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen_Ex
 * @since Twenty Sixteen Ex 1.0
 */

/**
 * Sets up the WordPress core custom header and custom background features.
 *
 * @since Twenty Sixteen Ex 1.0
 *
 * @see twentysixteenex_header_style()
 */
function twentysixteenex_custom_header_and_background() {
	$color_scheme             = twentysixteenex_get_color_scheme();
	$default_background_color = trim( $color_scheme[0], '#' );
	$default_text_color       = trim( $color_scheme[3], '#' );

	/**
	 * Filter the arguments used when adding 'custom-background' support in Twenty Sixteen Ex.
	 *
	 * @since Twenty Sixteen Ex 1.0
	 *
	 * @param array $args {
	 *     An array of custom-background support arguments.
	 *
	 *     @type string $default-color Default color of the background.
	 * }
	 */
	add_theme_support( 'custom-background', apply_filters( 'twentysixteenex_custom_background_args', array(
		'default-color' => $default_background_color,
	) ) );

	/**
	 * Filter the arguments used when adding 'custom-header' support in Twenty Sixteen Ex.
	 *
	 * @since Twenty Sixteen Ex 1.0
	 *
	 * @param array $args {
	 *     An array of custom-header support arguments.
	 *
	 *     @type string $default-text-color Default color of the header text.
	 *     @type int      $width            Width in pixels of the custom header image. Default 1200.
	 *     @type int      $height           Height in pixels of the custom header image. Default 280.
	 *     @type bool     $flex-height      Whether to allow flexible-height header images. Default true.
	 *     @type callable $wp-head-callback Callback function used to style the header image and text
	 *                                      displayed on the blog.
	 * }
	 */
	add_theme_support( 'custom-header', apply_filters( 'twentysixteenex_custom_header_args', array(
		'default-text-color'     => $default_text_color,
		'width'                  => 4096,
		'height'                 => 280,
		'flex-height'            => true,
		'wp-head-callback'       => 'twentysixteenex_header_style',
	) ) );
}
add_action( 'after_setup_theme', 'twentysixteenex_custom_header_and_background' );

if ( ! function_exists( 'twentysixteenex_header_style' ) ) :
/**
 * Styles the header text displayed on the site.
 *
 * Create your own twentysixteenex_header_style() function to override in a child theme.
 *
 * @since Twenty Sixteen Ex 1.0
 *
 * @see twentysixteenex_custom_header_and_background().
 */
function twentysixteenex_header_style() {
	// If the header text option is untouched, let's bail.
	if ( display_header_text() ) {
		return;
	}

	// If the header text has been hidden.
	?>
	<style type="text/css" id="twentysixteenex-header-css">
		.site-branding {
			margin: 0 auto 0 0;
		}

		.site-branding .site-title,
		.site-description {
			clip: rect(1px, 1px, 1px, 1px);
			position: absolute;
		}
	</style>
	<?php
}
endif; // twentysixteenex_header_style

/**
 * Adds postMessage support for site title and description for the Customizer.
 *
 * @since Twenty Sixteen Ex 1.0
 *
 * @param WP_Customize_Manager $wp_customize The Customizer object.
 */
function twentysixteenex_customize_register( $wp_customize ) {
	$color_scheme = twentysixteenex_get_color_scheme();

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

	// Add color scheme setting and control.
	$wp_customize->add_setting( 'color_scheme', array(
		'default'           => 'default',
		'sanitize_callback' => 'twentysixteenex_sanitize_color_scheme',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'color_scheme', array(
		'label'    => __( 'Base Color Scheme', 'twentysixteenex' ),
		'section'  => 'colors',
		'type'     => 'select',
		'choices'  => twentysixteenex_get_color_scheme_choices(),
		'priority' => 1,
	) );

	// Remove the core header textcolor control, as it shares the main text color.
	$wp_customize->remove_control( 'header_textcolor' );

	// Add link color setting and control.
	$wp_customize->add_setting( 'link_color', array(
		'default'           => $color_scheme[2],
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color', array(
		'label'       => __( 'Link Color', 'twentysixteenex' ),
		'section'     => 'colors',
	) ) );

	// Add main text color setting and control.
	$wp_customize->add_setting( 'main_text_color', array(
		'default'           => $color_scheme[3],
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'main_text_color', array(
		'label'       => __( 'Main Text Color', 'twentysixteenex' ),
		'section'     => 'colors',
	) ) );

	// Add secondary text color setting and control.
	$wp_customize->add_setting( 'secondary_text_color', array(
		'default'           => $color_scheme[4],
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'secondary_text_color', array(
		'label'       => __( 'Secondary Text Color', 'twentysixteenex' ),
		'section'     => 'colors',
	) ) );

	// Add buttons background color setting and control.
	$wp_customize->add_setting( 'buttons_background_color', array(
		'default'           => $color_scheme[5],
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'buttons_background_color', array(
		'label'       => __( 'Buttons Background Color', 'twentysixteenex' ),
		'section'     => 'colors',
	) ) );

	// Add buttons text color setting and control.
	$wp_customize->add_setting( 'buttons_text_color', array(
		'default'           => $color_scheme[6],
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'buttons_text_color', array(
		'label'       => __( 'Buttons Text Color', 'twentysixteenex' ),
		'section'     => 'colors',
	) ) );

	// Add buttons active background color setting and control.
	$wp_customize->add_setting( 'buttons_active_background_color', array(
		'default'           => $color_scheme[7],
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'buttons_active_background_color', array(
		'label'       => __( 'Active Buttons Background Color', 'twentysixteenex' ),
		'section'     => 'colors',
	) ) );

	// Add buttons active text color setting and control.
	$wp_customize->add_setting( 'buttons_active_text_color', array(
		'default'           => $color_scheme[8],
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'buttons_active_text_color', array(
		'label'       => __( 'Active Buttons Text Color', 'twentysixteenex' ),
		'section'     => 'colors',
	) ) );

	// Add buttons disabled background color setting and control.
	$wp_customize->add_setting( 'buttons_disabled_background_color', array(
		'default'           => $color_scheme[9],
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'buttons_disabled_background_color', array(
		'label'       => __( 'Disabled Buttons Background Color', 'twentysixteenex' ),
		'section'     => 'colors',
	) ) );

	// Add buttons disabled text color setting and control.
	$wp_customize->add_setting( 'buttons_disabled_text_color', array(
		'default'           => $color_scheme[10],
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'buttons_disabled_text_color', array(
		'label'       => __( 'Disabled Buttons Text Color', 'twentysixteenex' ),
		'section'     => 'colors',
	) ) );

	// Footer Image
	$wp_customize->add_section( 'theme_footer_section', array(
		'title' => __( 'Footer', 'twentysixteenex' ),
		'priority' => 60
	) );
 
	$wp_customize->add_setting( 'theme_footer', array( 
		'sanitize_callback' => 'sanitize_theme_footer',
	) );
 
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'theme_footer', array(
		'label' => __( 'Footer Image', 'twentysixteenex' ),
		'section' => 'theme_footer_section',
		'settings' => 'theme_footer'
	) ) );

	// Logo
	$wp_customize->add_section( 'theme_logo_section', array(
		'title' => __( 'Logo Image', 'twentysixteenex' ),
		'priority' => 70
	) );
 
	$wp_customize->add_setting( 'theme_logo', array( 
		'sanitize_callback' => 'sanitize_theme_logo',
	) );
 
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'theme_logo', array(
		'label' => __( 'Logo Image', 'twentysixteenex' ),
		'section' => 'theme_logo_section',
		'settings' => 'theme_logo'
	) ) );

	// Delemiters
	$wp_customize->add_section( 'theme_delemiter_section', array(
		'title' => __( 'Delemiters', 'twentysixteenex' ),
		'priority' => 80
	) );
 
	$wp_customize->add_setting( 'theme_delemiter_small', array( 
		'sanitize_callback' => 'sanitize_theme_delemiter_small',
	) );
 
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'theme_delemiter_small', array(
		'label' => __( 'Delemiter Small', 'twentysixteenex' ),
		'section' => 'theme_delemiter_section',
		'settings' => 'theme_delemiter_small'
	) ) );

	$wp_customize->add_setting( 'theme_delemiter_large', array( 
		'sanitize_callback' => 'sanitize_theme_delemiter_large',
	) );
 
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'theme_delemiter_large', array(
		'label' => __( 'Delemiter Large', 'twentysixteenex' ),
		'section' => 'theme_delemiter_section',
		'settings' => 'theme_delemiter_large'
	) ) );

	// Text constants
	$wp_customize->add_section( 'theme_text_constants_section', array(
		'title' => __( 'Text Constants', 'twentysixteenex' ),
		'priority' => 100
	) );
 
	$wp_customize->add_setting( 'theme_text_constants_vk_app_id', array( 
		'sanitize_callback' => 'sanitize_theme_digit',
	) );
 
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'theme_text_constants_vk_app_id', array(
		'label' => __( 'VK App ID', 'twentysixteenex' ),
		'section' => 'theme_text_constants_section',
		'settings' => 'theme_text_constants_vk_app_id'
	) ) );

	$wp_customize->add_setting( 'theme_text_constants_fb_app_id', array( 
		'sanitize_callback' => 'sanitize_theme_digit',
	) );
 
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'theme_text_constants_fb_app_id', array(
		'label' => __( 'Facebook App ID', 'twentysixteenex' ),
		'section' => 'theme_text_constants_section',
		'settings' => 'theme_text_constants_fb_app_id'
	) ) );

	$wp_customize->add_setting( 'theme_text_constants_tracking_ga', array() );
 
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'theme_text_constants_tracking_ga', array(
		'label' => __( 'Google Analytics Tracking Code', 'twentysixteenex' ),
		'section' => 'theme_text_constants_section',
		'settings' => 'theme_text_constants_tracking_ga'
	) ) );

	$wp_customize->add_setting( 'theme_text_constants_tracking_metrica', array( ) );
 
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'theme_text_constants_tracking_metrica', array(
		'label' => __( 'Yandex.Metrica Tracking Code', 'twentysixteenex' ),
		'section' => 'theme_text_constants_section',
		'settings' => 'theme_text_constants_tracking_metrica'
	) ) );

	// Custom Admin' Author's page URL
	$wp_customize->add_section( 'theme_custom_admin_url_section', array(
		'title' => __( 'Custom URL for Admin\'s Profile', 'twentysixteenex' ),
		'priority' => 110
	) );
 
	$wp_customize->add_setting( 'theme_custom_admin_url_id', array( 
		'sanitize_callback' => 'sanitize_theme_digit',
	) );
 
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'theme_custom_admin_url_id', array(
		'label' => __( 'For user with ID', 'twentysixteenex' ),
		'section' => 'theme_custom_admin_url_section',
		'settings' => 'theme_custom_admin_url_id'
	) ) );

	$wp_customize->add_setting( 'theme_custom_admin_url_url', array() );
 
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'theme_custom_admin_url_url', array(
		'label' => __( 'replace author\'s page URL with (e.g. /contacts, /about)', 'twentysixteenex' ),
		'section' => 'theme_custom_admin_url_section',
		'settings' => 'theme_custom_admin_url_url'
	) ) );

	// Scheme.org stuff
	$wp_customize->add_section( 'theme_schema_org', array(
		'title' => __( 'Scheme.org', 'twentysixteenex' ),
		'priority' => 110
	) );
 
	$wp_customize->add_setting( 'theme_schema_org_short_website_name', array() );
 
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'theme_schema_org_short_website_name', array(
		'label' => __( 'Short WebSite name', 'twentysixteenex' ),
		'section' => 'theme_schema_org',
		'settings' => 'theme_schema_org_short_website_name'
	) ) );

	// Logo 600*60
	$wp_customize->add_setting( 'theme_schema_org_logo_600x60', array( 
		'sanitize_callback' => 'sanitize_schema_org_logo',
	) );
 
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'theme_schema_org_logo_600x60', array(
		'label' => __( 'Logo 600×60', 'twentysixteenex' ),
		'section' => 'theme_schema_org',
		'settings' => 'theme_schema_org_logo_600x60'
	) ) );


}
add_action( 'customize_register', 'twentysixteenex_customize_register', 11 );

/**
 * Registers color schemes for Twenty Sixteen Ex.
 *
 * Can be filtered with {@see 'twentysixteenex_color_schemes'}.
 *
 * The order of colors in a colors array:
 * 1. Main Background Color.
 * 2. Page Background Color.
 * 3. Link Color.
 * 4. Main Text Color.
 * 5. Secondary Text Color.
 *
 * @since Twenty Sixteen Ex 1.0
 *
 * @return array An associative array of color scheme options.
 */
function twentysixteenex_get_color_schemes() {
	/**
	 * Filter the color schemes registered for use with Twenty Sixteen Ex.
	 *
	 * The default schemes include 'default', 'dark', 'gray', 'red', and 'yellow'.
	 *
	 * @since Twenty Sixteen Ex 1.0
	 *
	 * @param array $schemes {
	 *     Associative array of color schemes data.
	 *
	 *     @type array $slug {
	 *         Associative array of information for setting up the color scheme.
	 *
	 *         @type string $label  Color scheme label.
	 *         @type array  $colors HEX codes for default colors prepended with a hash symbol ('#').
	 *                              Colors are defined in the following order: Main background, page
	 *                              background, link, main text, secondary text.
	 *     }
	 * }
	 */
	return apply_filters( 'twentysixteenex_color_schemes', array(
		'default' => array(
			'label'  => __( 'Default', 'twentysixteenex' ),
			'colors' => array(
				'#ffffff',
				'#ffffff',
				'#007acc',
				'#1a1a1a',
				'#686868',
				'#1a1a1a',
				'#ffffff',
				'#ffffff',
				'#1a1a1a',
				'#999999',
				'#ffffff',
			),
		),
		'paulov.ru' => array(
			'label'  => __( 'paulov.ru', 'twentysixteenex' ),
			'colors' => array(
				'#ffffff',
				'#ffffff',
				'#007acc',
				'#1a1a1a',
				'#686868',
				'#1a1a1a',
				'#ffffff',
				'#ffffff',
				'#1a1a1a',
				'#999999',
				'#ffffff',
			),
		),
		'free-writer.ru' => array(
			'label'  => __( 'free-writer.ru', 'twentysixteenex' ),
			'colors' => array(
				'#ffffff',
				'#ffffff',
				'#007acc',
				'#1a1a1a',
				'#686868',
				'#1a1a1a',
				'#ffffff',
				'#ffffff',
				'#1a1a1a',
				'#999999',
				'#ffffff',
			),
		),
		'dark' => array(
			'label'  => __( 'Dark', 'twentysixteenex' ),
			'colors' => array(
				'#262626',
				'#1a1a1a',
				'#9adffd',
				'#e5e5e5',
				'#c1c1c1',
				'#e5e5e5',
				'#1a1a1a',
				'#1a1a1a',
				'#e5e5e5',
				'#999999',
				'#ffffff',
			),
		),
		'gray' => array(
			'label'  => __( 'Gray', 'twentysixteenex' ),
			'colors' => array(
				'#616a73',
				'#4d545c',
				'#c7c7c7',
				'#f2f2f2',
				'#f2f2f2',
				'#f2f2f2',
				'#4d545c',
				'#4d545c',
				'#f2f2f2',
				'#999999',
				'#ffffff',
			),
		),
		'red' => array(
			'label'  => __( 'Red', 'twentysixteenex' ),
			'colors' => array(
				'#ffffff',
				'#ff675f',
				'#640c1f',
				'#402b30',
				'#402b30',
				'#402b30',
				'#ff675f',
				'#ff675f',
				'#402b30',
				'#999999',
				'#ffffff',
			),
		),
		'yellow' => array(
			'label'  => __( 'Yellow', 'twentysixteenex' ),
			'colors' => array(
				'#3b3721',
				'#ffef8e',
				'#774e24',
				'#3b3721',
				'#5b4d3e',
				'#3b3721',
				'#ffef8e',
				'#ffef8e',
				'#3b3721',
				'#999999',
				'#ffffff',
			),
		),
	) );
}

if ( ! function_exists( 'twentysixteenex_get_color_scheme' ) ) :
/**
 * Retrieves the current Twenty Sixteen Ex color scheme.
 *
 * Create your own twentysixteenex_get_color_scheme() function to override in a child theme.
 *
 * @since Twenty Sixteen Ex 1.0
 *
 * @return array An associative array of either the current or default color scheme HEX values.
 */
function twentysixteenex_get_color_scheme() {
	$color_scheme_option = get_theme_mod( 'color_scheme', 'default' );
	$color_schemes       = twentysixteenex_get_color_schemes();

	if ( array_key_exists( $color_scheme_option, $color_schemes ) ) {
		return $color_schemes[ $color_scheme_option ]['colors'];
	}

	return $color_schemes['default']['colors'];
}
endif; // twentysixteenex_get_color_scheme

if ( ! function_exists( 'twentysixteenex_get_color_scheme_choices' ) ) :
/**
 * Retrieves an array of color scheme choices registered for Twenty Sixteen Ex.
 *
 * Create your own twentysixteenex_get_color_scheme_choices() function to override
 * in a child theme.
 *
 * @since Twenty Sixteen Ex 1.0
 *
 * @return array Array of color schemes.
 */
function twentysixteenex_get_color_scheme_choices() {
	$color_schemes                = twentysixteenex_get_color_schemes();
	$color_scheme_control_options = array();

	foreach ( $color_schemes as $color_scheme => $value ) {
		$color_scheme_control_options[ $color_scheme ] = $value['label'];
	}

	return $color_scheme_control_options;
}
endif; // twentysixteenex_get_color_scheme_choices

if ( ! function_exists( 'image_size' ) ) :
/**
 * image_size
 *
 * Calculate an image width and height from URL
 *
 * @param  string $image url to image
 * @return array $size_return image size as array
 */
function image_size( $image ) {
	$size_return = array();
	if ($image == '') return $size_return;
	// convert the URL to a path
	$upload = wp_upload_dir();
	$imageurl = str_replace(array('http://', 'https://'), '', $image);
	$uploadurl = str_replace(array('http://', 'https://'), '', $upload['baseurl']);
	$image = str_replace($uploadurl, $upload['basedir'], $imageurl);
	// bail if the file no longer exists
	if ( ! file_exists( $image ) ) return $size_return;
	// determine image size
	$size = getimagesize( $image );
	if ( ! empty( $size ) ) {
		$size_return['width'] = $size[0];
		$size_return['height'] = $size[1];
	}
	return $size_return;
}
endif; // image_size

if ( ! function_exists( 'sanitize_theme_footer' ) ) :
/**
 * Handles sanitization for Twenty Sixteen Ex theme footer.
 *
 * Create your own sanitize_theme_footer() function to override
 * in a child theme.
 *
 * @since Twenty Sixteen Ex 1.0
 *
 * @param string $value footer image URL.
 * @return string footer image URL.
 */
function sanitize_theme_footer( $value ) {

	if ( !$value ) {

		set_theme_mod( 'theme_footer_size', '' );
		return $value;

	}

	$size = image_size( $value );

	if ( !$size ) {

		set_theme_mod( 'theme_footer_size', '' );
		return '';

	}

	set_theme_mod( 'theme_footer_size', $size );
	return $value;
}
endif; // sanitize_theme_footer

if ( ! function_exists( 'sanitize_theme_logo' ) ) :
/**
 * Handles sanitization for Twenty Sixteen Ex theme logo.
 *
 * Create your own sanitize_theme_logo() function to override
 * in a child theme.
 *
 * @since Twenty Sixteen Ex 1.0
 *
 * @param string $value logo image URL.
 * @return string logo image URL.
 */
function sanitize_theme_logo( $value ) {

	if ( !$value ) {

		set_theme_mod( 'theme_logo_size', '' );
		return $value;

	}

	$size = image_size( $value );

	if ( !$size ) {

		set_theme_mod( 'theme_logo_size', '' );
		return '';

	}

	set_theme_mod( 'theme_logo_size', $size );
	return $value;
}
endif; // sanitize_theme_logo

if ( ! function_exists( 'sanitize_theme_delemiter_small' ) ) :
/**
 * Handles sanitization for Twenty Sixteen Ex theme logo.
 *
 * Create your own sanitize_theme_delemiter_small() function to override
 * in a child theme.
 *
 * @since Twenty Sixteen Ex 1.0
 *
 * @param string $value logo image URL.
 * @return string logo image URL.
 */
function sanitize_theme_delemiter_small( $value ) {

	if ( !$value ) {

		set_theme_mod( 'theme_delemiter_small_size', '' );
		return $value;

	}

	$size = image_size( $value );

	if ( !$size ) {

		set_theme_mod( 'theme_delemiter_small_size', '' );
		return '';

	}

	set_theme_mod( 'theme_delemiter_small_size', $size );
	return $value;
}
endif; // sanitize_theme_delemiter_small

if ( ! function_exists( 'sanitize_theme_delemiter_large' ) ) :
/**
 * Handles sanitization for Twenty Sixteen Ex theme logo.
 *
 * Create your own sanitize_theme_delemiter_large() function to override
 * in a child theme.
 *
 * @since Twenty Sixteen Ex 1.0
 *
 * @param string $value logo image URL.
 * @return string logo image URL.
 */
function sanitize_theme_delemiter_large( $value ) {

	if ( !$value ) {

		set_theme_mod( 'theme_delemiter_large_size', '' );
		return $value;

	}

	$size = image_size( $value );

	if ( !$size ) {

		set_theme_mod( 'theme_delemiter_large_size', '' );
		return '';

	}

	set_theme_mod( 'theme_delemiter_large_size', $size );
	return $value;
}
endif; // sanitize_theme_delemiter_large

if ( ! function_exists( 'sanitize_schema_org_logo' ) ) :
/**
 * Handles sanitization for Twenty Sixteen Ex theme logo.
 *
 * Create your own sanitize_schema_org_logo() function to override
 * in a child theme.
 *
 * @since Twenty Sixteen Ex 1.0
 *
 * @param string $value logo image URL.
 * @return string logo image URL.
 */
function sanitize_schema_org_logo( $value ) {

	if ( !$value ) {

		set_theme_mod( 'theme_schema_org_logo_600x60_size', '' );
		return $value;

	}

	$size = image_size( $value );

	if ( !$size ) {

		set_theme_mod( 'theme_schema_org_logo_600x60_size', '' );
		return '';

	}

	set_theme_mod( 'theme_schema_org_logo_600x60_size', $size );
	return $value;
}
endif; // sanitize_schema_org_logo

if ( ! function_exists( 'twentysixteenex_sanitize_color_scheme' ) ) :
/**
 * Handles sanitization for Twenty Sixteen Ex color schemes.
 *
 * Create your own twentysixteenex_sanitize_color_scheme() function to override
 * in a child theme.
 *
 * @since Twenty Sixteen Ex 1.0
 *
 * @param string $value Color scheme name value.
 * @return string Color scheme name.
 */
function twentysixteenex_sanitize_color_scheme( $value ) {
	$color_schemes = twentysixteenex_get_color_scheme_choices();

	if ( ! array_key_exists( $value, $color_schemes ) ) {
		return 'default';
	}

	return $value;
}
endif; // twentysixteenex_sanitize_color_scheme

if ( ! function_exists( 'sanitize_theme_digit' ) ) :
/**
 * Handles sanitization for Twenty Sixteen Ex color schemes.
 *
 * Create your own sanitize_theme_digit() function to override
 * in a child theme.
 *
 * @since Twenty Sixteen Ex 1.0
 *
 * @param string $value Color scheme name value.
 * @return string Color scheme name.
 */
function sanitize_theme_digit( $value ) {

	if ( !preg_match( '/^\d+$/im', $value ) ) {
		return '';
	}

	return $value;
}
endif; // sanitize_theme_digit

/**
 * Enqueues front-end CSS for color scheme.
 *
 * @since Twenty Sixteen Ex 1.0
 *
 * @see wp_add_inline_style()
 */
function twentysixteenex_color_scheme_css() {
	$color_scheme_option = get_theme_mod( 'color_scheme', 'default' );

	// Don't do anything if the default color scheme is selected.
	if ( 'default' === $color_scheme_option ) {
		return;
	}

	$color_scheme = twentysixteenex_get_color_scheme();

	// Convert main text hex color to rgba.
	$color_textcolor_rgb = twentysixteenex_hex2rgb( $color_scheme[3] );
	$background_color = twentysixteenex_hex2rgb( $color_scheme[0] );

	// If the rgba values are empty return early.
	if ( empty( $color_textcolor_rgb ) || empty( $background_color ) ) {
		return;
	}

	// If we get this far, we have a custom color scheme.
	$colors = array(
		'background_color'                  => $color_scheme[0],
		'link_color'                        => $color_scheme[2],
		'main_text_color'                   => $color_scheme[3],
		'secondary_text_color'              => $color_scheme[4],
		'buttons_background_color'          => $color_scheme[5],
		'buttons_text_color'                => $color_scheme[6],
		'buttons_active_background_color'   => $color_scheme[7],
		'buttons_active_text_color'         => $color_scheme[8],
		'buttons_disabled_background_color' => $color_scheme[9],
		'buttons_disabled_text_color'       => $color_scheme[10],
		'border_color'                      => vsprintf( 'rgba( %1$s, %2$s, %3$s, 0.2)', $color_textcolor_rgb ),
		'transparent_bg_color'              => vsprintf( 'rgba( %1$s, %2$s, %3$s, 0.75)', $background_color ),
		'transparent_bg_color_hex'          => '#BF'. substr( $color_scheme[0], 1 ),
	);

	$color_scheme_css = twentysixteenex_get_color_scheme_css( $colors );

	wp_add_inline_style( 'twentysixteenex-style', $color_scheme_css );
}
add_action( 'wp_enqueue_scripts', 'twentysixteenex_color_scheme_css' );

/**
 * Binds the JS listener to make Customizer color_scheme control.
 *
 * Passes color scheme data as colorScheme global.
 *
 * @since Twenty Sixteen Ex 1.0
 */
function twentysixteenex_customize_control_js() {
	wp_enqueue_script( 'color-scheme-control', get_template_directory_uri() . '/js/color-scheme-control.js', array( 'customize-controls', 'iris', 'underscore', 'wp-util' ), '20150825', true );
	wp_localize_script( 'color-scheme-control', 'colorScheme', twentysixteenex_get_color_schemes() );
}
add_action( 'customize_controls_enqueue_scripts', 'twentysixteenex_customize_control_js' );

/**
 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
 *
 * @since Twenty Sixteen Ex 1.0
 */
function twentysixteenex_customize_preview_js() {
	wp_enqueue_script( 'twentysixteenex-customize-preview', get_template_directory_uri() . '/js/customize-preview.js', array( 'customize-preview' ), '20150825', true );
}
add_action( 'customize_preview_init', 'twentysixteenex_customize_preview_js' );

/**
 * Returns CSS for the color schemes.
 *
 * @since Twenty Sixteen Ex 1.0
 *
 * @param array $colors Color scheme colors.
 * @return string Color scheme CSS.
 */
function twentysixteenex_get_color_scheme_css( $colors ) {
	$colors = wp_parse_args( $colors, array(
		'background_color'                  => '',
		'link_color'                        => '',
		'main_text_color'                   => '',
		'secondary_text_color'              => '',
		'buttons_background_color'          => '',
		'buttons_text_color'                => '',
		'buttons_active_background_color'   => '',
		'buttons_active_text_color'         => '',
		'buttons_disabled_background_color' => '',
		'buttons_disabled_text_color'       => '',
		'border_color'                      => '',
		'transparent_bg_color'              => '',
	) );

	$css = '';

	if (  
		!get_theme_mod( 'theme_logo', '' ) || 
		!is_array( get_theme_mod( 'theme_logo_size', '' ) ) ||
		!isset( get_theme_mod( 'theme_logo_size', '' )['height'] ) ||
		!isset( get_theme_mod( 'theme_logo_size', '' )['width'] )
	) {

		$css .= <<<CSS

	.site-branding.textual {
		background: {$colors['background_color']};
		background: transparent\9;
		background: {$colors['transparent_bg_color']};
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr={$colors['transparent_bg_color_hex']},endColorstr={$colors['transparent_bg_color_hex']});
		zoom: 1;
	}
	.site-branding.textual:nth-child(n) {
		filter: none;
	}
CSS;
		
	}

	/* Color Scheme */
	/* Background Color */
	$css .= <<<CSS
	body {
		background-color: {$colors['background_color']};
	}

	.site-header-menu {
		background: {$colors['background_color']};
		background: transparent\9;
		background: {$colors['transparent_bg_color']};
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr={$colors['transparent_bg_color_hex']},endColorstr={$colors['transparent_bg_color_hex']});
		zoom: 1;
	}
	.site-header-menu:nth-child(n) {
		filter: none;
	}

	.site-footer-menu {
		background: {$colors['background_color']};
		background: transparent\9;
		background: {$colors['transparent_bg_color']};
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr={$colors['transparent_bg_color_hex']},endColorstr={$colors['transparent_bg_color_hex']});
		zoom: 1;
	}
	.site-footer-menu:nth-child(n) {
		filter: none;
	}
CSS;

	/* Link Color */
$css .= <<<CSS
	a,
	.main-navigation a:hover,
	.main-navigation a:focus,
	.dropdown-toggle:hover,
	.dropdown-toggle:focus,
	.social-navigation a:hover:before,
	.social-navigation a:focus:before,
	.post-navigation a:hover .post-title,
	.post-navigation a:focus .post-title,
	.tagcloud a:hover,
	.tagcloud a:focus,
	.site-branding .site-title a:hover,
	.site-branding .site-title a:focus,
	.entry-title a:hover,
	.entry-title a:focus,
	.entry-footer a:hover,
	.entry-footer a:focus,
	.comment-metadata a:hover,
	.comment-metadata a:focus,
	.pingback .comment-edit-link:hover,
	.pingback .comment-edit-link:focus,
	.comment-reply-link,
	.comment-reply-link:hover,
	.comment-reply-link:focus,
	.required,
	.site-info a:hover,
	.site-info a:focus {
		color: {$colors['link_color']};
	}

	h1 a.underlined:hover,
	h1 a.underlined:focus {

		background: -webkit-linear-gradient(#fff,#fff),-webkit-linear-gradient(#fff,#fff),-webkit-linear-gradient({$colors['link_color']},{$colors['link_color']});
		background: -moz-linear-gradient(#fff,#fff),-moz-linear-gradient(#fff,#fff),-moz-linear-gradient({$colors['link_color']},{$colors['link_color']});
		background: -o-linear-gradient(#fff,#fff),-o-linear-gradient(#fff,#fff),-o-linear-gradient({$colors['link_color']},{$colors['link_color']});
		background: -ms-linear-gradient(#fff,#fff),-ms-linear-gradient(#fff,#fff),-ms-linear-gradient({$colors['link_color']},{$colors['link_color']});
		background: linear-gradient(#fff,#fff),linear-gradient(#fff,#fff),linear-gradient({$colors['link_color']},{$colors['link_color']});

		-webkit-background-size: .05em 4px,.25em 4px,2px 2px;
		-moz-background-size: .05em 4px,.25em 4px,2px 2px;
		background-size: .05em 4px,.25em 4px,2px 2px;

		background-repeat: no-repeat,no-repeat,repeat-x;
		background-position: 0 98%,101% 98%,0 95%;

	}

	mark,
	ins,
	.widget_calendar tbody a,
	.page-links a:hover,
	.page-links a:focus {
		background-color: {$colors['link_color']};
	}

	input[type="text"]:focus,
	input[type="email"]:focus,
	input[type="url"]:focus,
	input[type="password"]:focus,
	input[type="search"]:focus,
	textarea:focus,
	.tagcloud a:hover,
	.tagcloud a:focus {
		border-color: {$colors['link_color']};
	}
CSS;

	/* Buttons */
$css .= <<<CSS
	button,
	input[type="button"],
	input[type="reset"],
	input[type="submit"] {
		background-color: {$colors['buttons_background_color']};
		color: {$colors['buttons_text_color']};
	}

	button:hover,
	button:focus,
	input[type="button"]:hover,
	input[type="button"]:focus,
	input[type="reset"]:hover,
	input[type="reset"]:focus,
	input[type="submit"]:hover,
	input[type="submit"]:focus {
		background-color: {$colors['buttons_active_background_color']};
		color: {$colors['buttons_active_text_color']};
	}

	button[disabled],
	button[disabled]:hover,
	button[disabled]:focus,
	input[type="button"][disabled],
	input[type="button"][disabled]:hover,
	input[type="button"][disabled]:focus,
	input[type="reset"][disabled],
	input[type="reset"][disabled]:hover,
	input[type="reset"][disabled]:focus,
	input[type="submit"][disabled],
	input[type="submit"][disabled]:hover,
	input[type="submit"][disabled]:focus {
		background-color: {$colors['buttons_disabled_background_color']};
		color: {$colors['buttons_disabled_text_color']};
	}
CSS;

	/* Main Text Color */
$css .= <<<CSS
	body,
	blockquote cite,
	blockquote small,
	.main-navigation a,
	.dropdown-toggle,
	.social-navigation a,
	.post-navigation a,
	.pagination a:hover,
	.pagination a:focus,
	.widget-title a,
	.site-branding .site-title a,
	.entry-title a,
	.page-links > .page-links-title,
	.comment-author,
	.comment-reply-title small a:hover,
	.comment-reply-title small a:focus {
		color: {$colors['main_text_color']};
	}

	h1 a.underlined {
		background: -webkit-linear-gradient(#fff,#fff),-webkit-linear-gradient(#fff,#fff),-webkit-linear-gradient({$colors['main_text_color']},{$colors['main_text_color']});
		background: -moz-linear-gradient(#fff,#fff),-moz-linear-gradient(#fff,#fff),-moz-linear-gradient({$colors['main_text_color']},{$colors['main_text_color']});
		background: -o-linear-gradient(#fff,#fff),-o-linear-gradient(#fff,#fff),-o-linear-gradient({$colors['main_text_color']},{$colors['main_text_color']});
		background: -ms-linear-gradient(#fff,#fff),-ms-linear-gradient(#fff,#fff),-ms-linear-gradient({$colors['main_text_color']},{$colors['main_text_color']});
		background: linear-gradient(#fff,#fff),linear-gradient(#fff,#fff),linear-gradient({$colors['main_text_color']},{$colors['main_text_color']});

		-webkit-background-size: .05em 4px,.25em 4px,2px 2px;
		-moz-background-size: .05em 4px,.25em 4px,2px 2px;
		background-size: .05em 4px,.25em 4px,2px 2px;

		background-repeat: no-repeat,no-repeat,repeat-x;
		background-position: 0 98%,101% 98%,0 95%;
	}

	blockquote,
	.page-links a {
		border-color: {$colors['main_text_color']};
	}

	.page-links a {
		background-color: {$colors['main_text_color']};
	}
CSS;

	/* Secondary Text Color */

	/**
	 * IE8 and earlier will drop any block with CSS3 selectors.
	 * Do not combine these styles with the next block.
	 */
$css .= <<<CSS
	body:not(.search-results) .entry-summary {
		color: {$colors['secondary_text_color']};
	}

	blockquote,
	.post-password-form label,
	a:hover,
	a:focus,
	a:active,
	.post-navigation .meta-nav,
	.image-navigation,
	.comment-navigation,
	.widget_recent_entries .post-date,
	.widget_rss .rss-date,
	.widget_rss cite,
	.site-description,
	.author-bio,
	.entry-footer,
	.entry-footer a,
	.sticky-post,
	.taxonomy-description,
	.entry-caption,
	.comment-metadata,
	.pingback .edit-link,
	.comment-metadata a,
	.pingback .comment-edit-link,
	.comment-form label,
	.comment-notes,
	.comment-awaiting-moderation,
	.logged-in-as,
	.form-allowed-tags,
	.site-info,
	.site-info a,
	.wp-caption .wp-caption-text,
	.gallery-caption,
	.widecolumn label,
	.widecolumn .mu_register label {
		color: {$colors['secondary_text_color']};
	}

	.widget_calendar tbody a:hover,
	.widget_calendar tbody a:focus {
		background-color: {$colors['secondary_text_color']};
	}
CSS;

	/* Border Color */
$css .= <<<CSS
	fieldset,
	pre,
	abbr,
	acronym,
	table,
	th,
	td,
	input[type="text"],
	input[type="email"],
	input[type="url"],
	input[type="password"],
	input[type="search"],
	textarea,
	.main-navigation li,
	.main-navigation .primary-menu,
	.dropdown-toggle:after,
	.social-navigation a,
	.image-navigation,
	.comment-navigation,
	.tagcloud a,
	.entry-content,
	.entry-summary,
	.page-links a,
	.page-links > span,
	.comment-list article,
	.comment-list .pingback,
	.comment-list .trackback,
	.comment-reply-link,
	.no-comments,
	.widecolumn .mu_register .mu_alert {
		border-color: {$colors['main_text_color']};
		border-color: {$colors['border_color']};
	}

	hr,
	code {
		background-color: {$colors['main_text_color']};
		background-color: {$colors['border_color']};
	}

	@media screen and (min-width: 56.875em) {
		.main-navigation li:hover > a,
		.main-navigation li.focus > a {
			color: {$colors['link_color']};
		}

		.main-navigation ul ul,
		.main-navigation ul ul li {
			border-color: {$colors['border_color']};
		}

		.main-navigation ul ul:before {
			border-top-color: {$colors['border_color']};
			border-bottom-color: {$colors['border_color']};
		}
	}

CSS;

	return $css;

}


/**
 * Outputs an Underscore template for generating CSS for the color scheme.
 *
 * The template generates the css dynamically for instant display in the
 * Customizer preview.
 *
 * @since Twenty Sixteen Ex 1.0
 */
function twentysixteenex_color_scheme_css_template() {
	$colors = array(
		'background_color'                  => '{{ data.background_color }}',
		'link_color'                        => '{{ data.link_color }}',
		'main_text_color'                   => '{{ data.main_text_color }}',
		'secondary_text_color'              => '{{ data.secondary_text_color }}',
		'buttons_background_color'          => '{{ data.buttons_background_color }}',
		'buttons_text_color'                => '{{ data.buttons_text_color }}',
		'buttons_active_background_color'   => '{{ data.buttons_active_background_color }}',
		'buttons_active_text_color'         => '{{ data.buttons_active_text_color }}',
		'buttons_disabled_background_color' => '{{ data.buttons_disabled_background_color }}',
		'buttons_disabled_text_color'       => '{{ data.buttons_disabled_text_color }}',
		'border_color'                      => '{{ data.border_color }}',
		'transparent_bg_color'              => '{{ data.transparent_bg_color }}',
	);
	?>
	<script type="text/html" id="tmpl-twentysixteenex-color-scheme">
		<?php echo twentysixteenex_get_color_scheme_css( $colors ); ?>
	</script>
	<?php
}
add_action( 'customize_controls_print_footer_scripts', 'twentysixteenex_color_scheme_css_template' );

/**
 * Enqueues front-end CSS for the link color.
 *
 * @since Twenty Sixteen Ex 1.0
 *
 * @see wp_add_inline_style()
 */
function twentysixteenex_link_color_css() {
	$color_scheme    = twentysixteenex_get_color_scheme();
	$default_color   = $color_scheme[2];
	$link_color = get_theme_mod( 'link_color', $default_color );

	// Don't do anything if the current color is the default.
	if ( $link_color === $default_color ) {
		return;
	}

	/* Custom Link Color */
	$css = '
	a,
	.main-navigation a:hover,
	.main-navigation a:focus,
	.dropdown-toggle:hover,
	.dropdown-toggle:focus,
	.social-navigation a:hover:before,
	.social-navigation a:focus:before,
	.post-navigation a:hover .post-title,
	.post-navigation a:focus .post-title,
	.tagcloud a:hover,
	.tagcloud a:focus,
	.site-branding .site-title a:hover,
	.site-branding .site-title a:focus,
	.entry-title a:hover,
	.entry-title a:focus,
	.entry-footer a:hover,
	.entry-footer a:focus,
	.comment-metadata a:hover,
	.comment-metadata a:focus,
	.pingback .comment-edit-link:hover,
	.pingback .comment-edit-link:focus,
	.comment-reply-link,
	.comment-reply-link:hover,
	.comment-reply-link:focus,
	.required,
	.site-info a:hover,
	.site-info a:focus {
		color: %1$s;
	}

	mark,
	ins,
	.widget_calendar tbody a,
	.page-links a:hover,
	.page-links a:focus {
		background-color: %1$s;
	}

	input[type="text"]:focus,
	input[type="email"]:focus,
	input[type="url"]:focus,
	input[type="password"]:focus,
	input[type="search"]:focus,
	textarea:focus,
	.tagcloud a:hover,
	.tagcloud a:focus {
		border-color: %1$s;
	}

	@media screen and (min-width: 56.875em) {
		.main-navigation li:hover > a,
		.main-navigation li.focus > a {
			color: %1$s;
		}
	}
	';

	wp_add_inline_style( 'twentysixteenex-style', sprintf( $css, $link_color ) );
}
add_action( 'wp_enqueue_scripts', 'twentysixteenex_link_color_css', 11 );

/**
 * Enqueues front-end CSS for the buttons color.
 *
 * @since Twenty Sixteen Ex 1.0
 *
 * @see wp_add_inline_style()
 */
function twentysixteenex_buttons_color_css() {
	$color_scheme    = twentysixteenex_get_color_scheme();

	$default_buttons_background_color = $color_scheme[5];
	$default_buttons_text_color = $color_scheme[6];
	$default_buttons_active_background_color = $color_scheme[7];
	$default_buttons_active_text_color = $color_scheme[8];
	$default_buttons_disabled_background_color = $color_scheme[9];
	$default_buttons_disabled_text_color = $color_scheme[10];

	$buttons_background_color = get_theme_mod( 'buttons_background_color', $default_buttons_background_color );
	$buttons_text_color = get_theme_mod( 'buttons_text_color', $default_buttons_text_color );
	$buttons_active_background_color = get_theme_mod( 'buttons_active_background_color', $default_buttons_active_background_color );
	$buttons_active_text_color = get_theme_mod( 'buttons_active_text_color', $default_buttons_active_text_color );
	$buttons_disabled_background_color = get_theme_mod( 'buttons_disabled_background_color', $default_buttons_disabled_background_color );
	$buttons_disabled_text_color = get_theme_mod( 'buttons_disabled_text_color', $default_buttons_disabled_text_color );

	// Don't do anything if the current color is the default.
	if ( 
		$buttons_background_color === $default_buttons_background_color &&
		$buttons_text_color === $default_buttons_text_color &&
		$buttons_active_background_color === $default_buttons_active_background_color &&
		$buttons_active_text_color === $default_buttons_active_text_color &&
		$buttons_disabled_background_color === $default_buttons_disabled_background_color &&
		$buttons_disabled_text_color === $default_buttons_disabled_text_color 
	) {
		return;
	}

	/* Buttons */
	$css = '
	button,
	input[type="button"],
	input[type="reset"],
	input[type="submit"] {
		background-color: %1$s;
		color: %2$s;
	}

	button:hover,
	button:focus,
	input[type="button"]:hover,
	input[type="button"]:focus,
	input[type="reset"]:hover,
	input[type="reset"]:focus,
	input[type="submit"]:hover,
	input[type="submit"]:focus {
		background-color: %3$s;
		color: %4$s;
	}

	button[disabled],
	input[type="button"][disabled],
	input[type="reset"][disabled],
	input[type="submit"][disabled] {
		background-color: %5$s;
		color: %6$s;
	}
	';

	wp_add_inline_style( 'twentysixteenex-style', sprintf( $css, $buttons_background_color, $buttons_text_color, $buttons_active_background_color, $buttons_active_text_color, $buttons_disabled_background_color, $buttons_disabled_text_color ) );
}
add_action( 'wp_enqueue_scripts', 'twentysixteenex_buttons_color_css', 11 );

/**
 * Enqueues front-end CSS for the main text color.
 *
 * @since Twenty Sixteen Ex 1.0
 *
 * @see wp_add_inline_style()
 */
function twentysixteenex_main_text_color_css() {
	$color_scheme    = twentysixteenex_get_color_scheme();
	$default_color   = $color_scheme[3];
	$main_text_color = get_theme_mod( 'main_text_color', $default_color );

	// Don't do anything if the current color is the default.
	if ( $main_text_color === $default_color ) {
		return;
	}

	// Convert main text hex color to rgba.
	$main_text_color_rgb = twentysixteenex_hex2rgb( $main_text_color );

	// If the rgba values are empty return early.
	if ( empty( $main_text_color_rgb ) ) {
		return;
	}

	// If we get this far, we have a custom color scheme.
	$border_color = vsprintf( 'rgba( %1$s, %2$s, %3$s, 0.2)', $main_text_color_rgb );

	/* Custom Main Text Color */
	$css = '
	body,
	blockquote cite,
	blockquote small,
	.main-navigation a,
	.dropdown-toggle,
	.social-navigation a,
	.post-navigation a,
	.pagination a:hover,
	.pagination a:focus,
	.widget-title a,
	.site-branding .site-title a,
	.entry-title a,
	.page-links > .page-links-title,
	.comment-author,
	.comment-reply-title small a:hover,
	.comment-reply-title small a:focus {
		color: %1$s
	}

	blockquote,
	.page-links a {
		border-color: %1$s;
	}

	.page-links a {
		background-color: %1$s;
	}
	';

	/* Border Color */
	$css .= '
	fieldset,
	pre,
	abbr,
	acronym,
	table,
	th,
	td,
	input[type="text"],
	input[type="email"],
	input[type="url"],
	input[type="password"],
	input[type="search"],
	textarea,
	.main-navigation li,
	.main-navigation .primary-menu,
	.dropdown-toggle:after,
	.social-navigation a,
	.image-navigation,
	.comment-navigation,
	.tagcloud a,
	.entry-content,
	.entry-summary,
	.page-links a,
	.page-links > span,
	.comment-list article,
	.comment-list .pingback,
	.comment-list .trackback,
	.comment-reply-link,
	.no-comments,
	.widecolumn .mu_register .mu_alert {
		border-color: %1$s;
		border-color: %2$s;
	}

	hr,
	code {
		background-color: %1$s;
		background-color: %2$s;
	}

	@media screen and (min-width: 56.875em) {
		.main-navigation ul ul,
		.main-navigation ul ul li {
			border-color: %2$s;
		}

		.main-navigation ul ul:before {
			border-top-color: %2$s;
			border-bottom-color: %2$s;
		}
	}
	';

	wp_add_inline_style( 'twentysixteenex-style', sprintf( $css, $main_text_color, $border_color ) );
}
add_action( 'wp_enqueue_scripts', 'twentysixteenex_main_text_color_css', 11 );

/**
 * Enqueues front-end CSS for the secondary text color.
 *
 * @since Twenty Sixteen Ex 1.0
 *
 * @see wp_add_inline_style()
 */
function twentysixteenex_secondary_text_color_css() {
	$color_scheme    = twentysixteenex_get_color_scheme();
	$default_color   = $color_scheme[4];
	$secondary_text_color = get_theme_mod( 'secondary_text_color', $default_color );

	// Don't do anything if the current color is the default.
	if ( $secondary_text_color === $default_color ) {
		return;
	}

	/* Custom Secondary Text Color */

	/**
	 * IE8 and earlier will drop any block with CSS3 selectors.
	 * Do not combine these styles with the next block.
	 */
	$css = '
	body:not(.search-results) .entry-summary {
		color: %1$s;
	}

	blockquote,
	.post-password-form label,
	a:hover,
	a:focus,
	a:active,
	.post-navigation .meta-nav,
	.image-navigation,
	.comment-navigation,
	.widget_recent_entries .post-date,
	.widget_rss .rss-date,
	.widget_rss cite,
	.site-description,
	.author-bio,
	.entry-footer,
	.entry-footer a,
	.sticky-post,
	.taxonomy-description,
	.entry-caption,
	.comment-metadata,
	.pingback .edit-link,
	.comment-metadata a,
	.pingback .comment-edit-link,
	.comment-form label,
	.comment-notes,
	.comment-awaiting-moderation,
	.logged-in-as,
	.form-allowed-tags,
	.site-info,
	.site-info a,
	.wp-caption .wp-caption-text,
	.gallery-caption,
	.widecolumn label,
	.widecolumn .mu_register label {
		color: %1$s;
	}

	.widget_calendar tbody a:hover,
	.widget_calendar tbody a:focus {
		background-color: %1$s;
	}
	';

	wp_add_inline_style( 'twentysixteenex-style', sprintf( $css, $secondary_text_color ) );
}
add_action( 'wp_enqueue_scripts', 'twentysixteenex_secondary_text_color_css', 11 );
