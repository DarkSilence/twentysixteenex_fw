<?php
/**
 * Twenty Sixteen Ex functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen_Ex
 * @since Twenty Sixteen Ex 1.0
 */

/**
 * Workaround to fix such links http://site.ru/http://site.ru/
 */
require get_template_directory() . '/inc/wrong-urls-fix.php';

/**
 * Twenty Sixteen Ex only works in WordPress 4.4 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.4-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

if ( ! function_exists( 'twentysixteenex_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * Create your own twentysixteenex_setup() function to override in a child theme.
 *
 * @since Twenty Sixteen Ex 1.0
 */
function twentysixteenex_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Twenty Sixteen Ex, use a find and replace
	 * to change 'twentysixteenex' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'twentysixteenex', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1200, 9999 );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'twentysixteenex' ),
		'social'  => __( 'Social Links Menu', 'twentysixteenex' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'status',
		'audio',
		'chat',
	) );

	/*
	 * Remove Q&A default stylesheet
	 *
	 */
	add_theme_support( 'qa_style' );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'css/editor-style.css', twentysixteenex_fonts_url() ) );
}
endif; // twentysixteenex_setup
add_action( 'after_setup_theme', 'twentysixteenex_setup' );

/**
 * Infinite Scroll Theme Assets
 *
 * Register support for Twenty Sixteen Ex.
 */

/**
 * Add theme support for infinite scroll
 */
function twentysixteenex_infinite_scroll_init() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'render'    => 'twentysixteenex_infinite_scroll_render',
		'footer'    => 'content',
	) );
}
add_action( 'after_setup_theme', 'twentysixteenex_infinite_scroll_init' );

/**
 * Custom render function for Infinite Scroll.
 */
function twentysixteenex_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		if ( is_search() ) {
			get_template_part( 'template-parts/content', 'search' );
		} else {
			get_template_part( 'template-parts/content', get_post_format() );
		}
	}
}

/**
 * Sets the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 *
 * @since Twenty Sixteen Ex 1.0
 */
function twentysixteenex_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'twentysixteenex_content_width', 840 );
}
add_action( 'after_setup_theme', 'twentysixteenex_content_width', 0 );

/**
 * Registers a widget area.
 *
 * @link https://developer.wordpress.org/reference/functions/register_sidebar/
 *
 * @since Twenty Sixteen Ex 1.0
 */
function twentysixteenex_widgets_init() {

	$delemiter_small_html = '';
	$delemiter_large_html = '';

	if ( 
		get_theme_mod( 'theme_delemiter_small', '' ) && 
		is_array( get_theme_mod( 'theme_delemiter_small_size', '' ) ) &&
		isset( get_theme_mod( 'theme_delemiter_small_size', '' )['height'] ) &&
		isset( get_theme_mod( 'theme_delemiter_small_size', '' )['width'] )
	) {

		$delemiter_small_html = '<div class="delemiter-small" style="background-image:url('. get_theme_mod( 'theme_delemiter_small', '' ) .');height:'. get_theme_mod( 'theme_delemiter_small_size', '' )['height'] .'px;"></div>';

	}

	if ( 
		get_theme_mod( 'theme_delemiter_large', '' ) && 
		is_array( get_theme_mod( 'theme_delemiter_large_size', '' ) ) &&
		isset( get_theme_mod( 'theme_delemiter_large_size', '' )['height'] ) &&
		isset( get_theme_mod( 'theme_delemiter_large_size', '' )['width'] )
	) {

		$delemiter_large_html = '<div class="delemiter-large" style="background-image:url('. get_theme_mod( 'theme_delemiter_large', '' ) .');height:'. get_theme_mod( 'theme_delemiter_large_size', '' )['height'] .'px;"></div>';

	}

	register_sidebar( array(
		'name'          => __( 'Sidebar', 'twentysixteenex' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'twentysixteenex' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>'. $delemiter_small_html,
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Content Bottom 1', 'twentysixteenex' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Appears at the bottom of the content on posts and pages.', 'twentysixteenex' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>'. $delemiter_large_html,
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Content Bottom 2', 'twentysixteenex' ),
		'id'            => 'sidebar-3',
		'description'   => __( 'Appears at the bottom of the content on posts and pages.', 'twentysixteenex' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>'. $delemiter_large_html,
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'twentysixteenex_widgets_init' );

if ( ! function_exists( 'twentysixteenex_fonts_url' ) ) :
/**
 * Register Google fonts for Twenty Sixteen Ex.
 *
 * Create your own twentysixteenex_fonts_url() function to override in a child theme.
 *
 * @since Twenty Sixteen Ex 1.0
 *
 * @return string Google fonts URL for the theme.
 */
function twentysixteenex_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	/* translators: If there are characters in your language that are not supported by Merriweather, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Merriweather font: on or off', 'twentysixteenex' ) ) {
		$fonts[] = 'Merriweather:400,700,900,400italic,700italic,900italic';
	}

	/* translators: If there are characters in your language that are not supported by Montserrat, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Montserrat font: on or off', 'twentysixteenex' ) ) {
		$fonts[] = 'Montserrat:400,700';
	}

	/* translators: If there are characters in your language that are not supported by Inconsolata, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Inconsolata font: on or off', 'twentysixteenex' ) ) {
		$fonts[] = 'Inconsolata:400';
	}

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( $subsets ),
		), 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}
endif;

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Twenty Sixteen Ex 1.0
 */
function twentysixteenex_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'twentysixteenex_javascript_detection', 0 );

/**
 * Enqueues scripts and styles.
 *
 * @since Twenty Sixteen Ex 1.0
 */
function twentysixteenex_scripts() {

	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'twentysixteenex-fonts', twentysixteenex_fonts_url(), array(), null );

	// Add Fontawesome icons
	wp_enqueue_style( 'fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css' );

	// Theme stylesheet.
	wp_enqueue_style( 'twentysixteenex-style', get_stylesheet_uri() );

	// Load Colors
	wp_enqueue_style( 'twentysixteenex-colors', get_template_directory_uri() . '/css/colors.css', array( 'twentysixteenex-style' ), '20160106' );

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'twentysixteenex-ie', get_template_directory_uri() . '/css/ie.css', array( 'twentysixteenex-style' ), '20160106' );
	wp_style_add_data( 'twentysixteenex-ie', 'conditional', 'lt IE 10' );

	// Load the Internet Explorer 8 specific stylesheet.
	wp_enqueue_style( 'twentysixteenex-ie8', get_template_directory_uri() . '/css/ie8.css', array( 'twentysixteenex-style' ), '20160106' );
	wp_style_add_data( 'twentysixteenex-ie8', 'conditional', 'lt IE 9' );

	// Load the Internet Explorer 7 specific stylesheet.
	wp_enqueue_style( 'twentysixteenex-ie7', get_template_directory_uri() . '/css/ie7.css', array( 'twentysixteenex-style' ), '20160106' );
	wp_style_add_data( 'twentysixteenex-ie7', 'conditional', 'lt IE 8' );

	// Load the html5 shiv.
	wp_enqueue_script( 'twentysixteenex-html5', get_template_directory_uri() . '/js/html5.js', array(), '20160106' );
	wp_script_add_data( 'twentysixteenex-html5', 'conditional', 'lt IE 9' );

	// Load the respond.
	wp_enqueue_script( 'twentysixteenex-respond', get_template_directory_uri() . '/js/respond.js', array(), '1.4.2' );
	wp_script_add_data( 'twentysixteenex-respond', 'conditional', 'IE 8' );

	wp_enqueue_script( 'comment-reply' );

	wp_enqueue_script( 'twentysixteenex-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20160106' );

	wp_enqueue_script( 'twentysixteenex-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20160106', true );
	
	wp_enqueue_script( 'twentysixteenex-tracksocial', get_template_directory_uri() . '/js/gatracksocial.js', array(), '20160106' );

	wp_localize_script( 'twentysixteenex-script', 'screenReaderText', array(
		'expand'   => __( 'expand child menu', 'twentysixteenex' ),
		'collapse' => __( 'collapse child menu', 'twentysixteenex' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'twentysixteenex_scripts' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @since Twenty Sixteen Ex 1.0
 *
 * @param array $classes Classes for the body element.
 * @return array (Maybe) filtered body classes.
 */
function twentysixteenex_body_classes( $classes ) {
	// Adds a class of custom-background-image to sites with a custom background image.
	if ( get_background_image() ) {
		$classes[] = 'custom-background-image';
	}

	// Adds a class of group-blog to sites with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of no-sidebar to sites without active sidebar.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'twentysixteenex_body_classes' );

/**
 * Converts a HEX value to RGB.
 *
 * @since Twenty Sixteen Ex 1.0
 *
 * @param string $color The original color, in 3- or 6-digit hexadecimal form.
 * @return array Array containing RGB (red, green, and blue) values for the given
 *               HEX code, empty array otherwise.
 */
function twentysixteenex_hex2rgb( $color ) {
	$color = trim( $color, '#' );

	if ( strlen( $color ) === 3 ) {
		$r = hexdec( substr( $color, 0, 1 ).substr( $color, 0, 1 ) );
		$g = hexdec( substr( $color, 1, 1 ).substr( $color, 1, 1 ) );
		$b = hexdec( substr( $color, 2, 1 ).substr( $color, 2, 1 ) );
	} else if ( strlen( $color ) === 6 ) {
		$r = hexdec( substr( $color, 0, 2 ) );
		$g = hexdec( substr( $color, 2, 2 ) );
		$b = hexdec( substr( $color, 4, 2 ) );
	} else {
		return array();
	}

	return array( 'red' => $r, 'green' => $g, 'blue' => $b );
}

/*
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images
 *
 * @since Twenty Sixteen Ex 1.0
 *
 * @param string $sizes A source size value for use in a 'sizes' attribute.
 * @param array  $size  Image size. Accepts an array of width and height
 *                      values in pixels (in that order).
 * @return string A source size value for use in a content image 'sizes' attribute.
 */
function twentysixteenex_content_image_sizes_attr( $sizes, $size ) {
	$width = $size[0];

	840 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 62vw, 840px';

	if ( 'page' === get_post_type() ) {
		840 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
	} else {
		840 > $width && 600 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 61vw, (max-width: 1362px) 45vw, 600px';
		600 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
	}

	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'twentysixteenex_content_image_sizes_attr', 10 , 2 );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails
 *
 * @since Twenty Sixteen Ex 1.0
 *
 * @param array $attr Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size Registered image size or flat array of height and width dimensions.
 * @return string A source size value for use in a post thumbnail 'sizes' attribute.
 */
function twentysixteenex_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	if ( 'post-thumbnail' === $size ) {
		is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 60vw, (max-width: 1362px) 62vw, 840px';
		! is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 88vw, 1200px';
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'twentysixteenex_post_thumbnail_sizes_attr', 10 , 3 );

/**
 * Skip 1-2 characrets length comments
 *
*/

add_filter( 'preprocess_comment', 'wpb_preprocess_comment' );

function wpb_preprocess_comment($comment) {

	if ( strlen( $comment['comment_content'] ) < 60 ) {

		wp_die( __( 'Comment is too short. Please use at least 3 characters.', 'twentysixteenex' ) );

	}

	return $comment;

}

/**
 * Modifies tag cloud widget arguments to have all tags in the widget same font size.
 *
 * @since Twenty Sixteen Ex 1.1
 *
 * @param array $args Arguments for tag cloud widget.
 * @return array A new modified arguments.
 */
function twentysixteenex_widget_tag_cloud_args( $args ) {
	$args['largest'] = 1;
	$args['smallest'] = 1;
	$args['unit'] = 'em';
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'twentysixteenex_widget_tag_cloud_args' );

/**
 * Add OpenGraph plugin support
 */
add_filter( 'jetpack_enable_open_graph', '__return_true' );

/**
 * Do not allow WP Post Rating plugin ruin our schema.org
 */
add_filter('wp_postratings_schema_itemtype', function() { return ''; } );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

// RELEASE ONLY
remove_action( 'wp_enqueue_scripts', 'twentysixteenex_color_scheme_css' );
remove_action( 'wp_enqueue_scripts', 'twentysixteenex_link_color_css', 11 );
remove_action( 'wp_enqueue_scripts', 'twentysixteenex_buttons_color_css', 11 );
remove_action( 'wp_enqueue_scripts', 'twentysixteenex_main_text_color_css', 11 );
remove_action( 'wp_enqueue_scripts', 'twentysixteenex_secondary_text_color_css', 11 );

/**
 * Filter to make dates gramatically correct in RU locale.
 */
require get_template_directory() . '/inc/ru-dates.php';

/**
 * Remove Generator meta info from header
 */
require get_template_directory() . '/inc/no-generators.php';

/**
 * SEO utils
 */
require get_template_directory() . '/inc/seo.php';

/**
 * SEO utils
 */
require get_template_directory() . '/inc/disable_wp_emojicons.php';