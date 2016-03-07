<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen_Ex
 * @since Twenty Sixteen Ex 1.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js" <?= twentysixteenex_get_html_schema() ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php endif; ?>

	<?php wp_head(); ?>
	<?php

		// VK API

		$vk_app_id = get_theme_mod( 'theme_text_constants_vk_app_id', '' );

		if ( $vk_app_id ) echo '<script>var vkAPPID = '. $vk_app_id .';</script>'."\n";

	?>

	<?php

		// FaceBook API

		$fb_app_id = get_theme_mod( 'theme_text_constants_fb_app_id', '' );

		if ( $fb_app_id ) echo '<script>var fbAPPID = "'. $fb_app_id .'";</script>'."\n";

	?>

	<?php

		// Google Analytics Tracking Code

		$tracking_ga = get_theme_mod( 'theme_text_constants_tracking_ga', '' );

		if ( $tracking_ga ) {

			echo $tracking_ga;

		}

		// Yandex Metrica Tracking Code

		$tracking_ya = get_theme_mod( 'theme_text_constants_tracking_metrica', '' );

		if ( $tracking_ya ) {

			echo $tracking_ya;

		}

	?>

	<?php
		echo twentysixteenex_get_website_header();
	?>

</head>

<body id="page" <?php body_class(); ?>>
	<?php
		/**
		 * Filter the default twentysixteenex custom header sizes attribute.
		 *
		 * @since Twenty Sixteen Ex 1.0
		 *
		 * @param string $custom_header_sizes sizes attribute
		 * for Custom Header. Default '(max-width: 709px) 85vw,
		 * (max-width: 909px) 81vw, (max-width: 1362px) 88vw, 1200px'.
		 */
		$custom_header_sizes = apply_filters( 'twentysixteenex_custom_header_sizes', '(max-width: 709px) 85vw, (max-width: 909px) 81vw, (max-width: 1362px) 88vw, 1200px' );
	?>
	<?php
		if ( 
			get_theme_mod( 'theme_logo', '' ) && 
			is_array( get_theme_mod( 'theme_logo_size', '' ) ) &&
			isset( get_theme_mod( 'theme_logo_size', '' )['height'] ) &&
			isset( get_theme_mod( 'theme_logo_size', '' )['width'] )
		) {

			$header_html = '<img src="'. esc_attr( get_theme_mod( 'theme_logo', '' ) ) .'" width="'. absint( get_theme_mod( 'theme_logo_size', '' )['width'] ) .'" height="'. absint( get_theme_mod( 'theme_logo_size', '' )['height'] ) .'" alt="'. get_bloginfo( 'name', 'display' ) .'" />';

		}
	?>

	<header id="masthead" class="site-header" <?= get_header_image() ? ' style="background-image: url( '. get_header_image() .' )"' : ''; ?> role="banner">
		<div class="site-header-container">
			<div class="site-header-main">
				<div class="site-branding<?= !isset( $header_html ) ? ' textual' : '' ?>">
					<?php if ( is_front_page() && is_home() ) : ?>
						<h1 class="site-title"><a href="<?= esc_url( home_url( '/' ) ) ?>" title="<?= wp_strip_all_tags( bloginfo( 'name' ) ) ?>" rel="home"><?= isset( $header_html ) ? $header_html : bloginfo( 'name' ); ?></a></h1>
					<?php else : ?>
						<p class="site-title"><a href="<?= esc_url( home_url( '/' ) ) ?>" title="<?= wp_strip_all_tags( bloginfo( 'name' ) ) ?>" rel="home"><?= isset( $header_html ) ? $header_html : bloginfo( 'name' ); ?></a></p>
					<?php endif;

					$description = get_bloginfo( 'description', 'display' );
					if ( $description || is_customize_preview() ) : ?>
						<p class="site-description"><?= $description ?></p>
					<?php endif; ?>
				</div><!-- .site-branding -->

				<div class="site-header-form">
					<?php include 'searchform.php'; ?>
				</div>

				<?php if ( has_nav_menu( 'primary' ) ) : ?>
					<button id="menu-toggle" class="menu-toggle lines-button x" aria-label="Toggle Navigation" role="button"><span class="lines"></span></button>

					<div id="site-header-menu" class="site-header-menu">
						<?php if ( has_nav_menu( 'primary' ) ) : ?>
							<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'twentysixteenex' ); ?>">
								<?php
									wp_nav_menu( array(
										'theme_location' => 'primary',
										'menu_class'     => 'primary-menu',
									 ) );
								?>
							</nav><!-- .main-navigation -->
						<?php endif; ?>
					</div><!-- .site-header-menu -->
				<?php endif; ?>

			</div><!-- .site-header-main -->
		</div><!-- .site-header-container -->
	</header><!-- .site-header -->
