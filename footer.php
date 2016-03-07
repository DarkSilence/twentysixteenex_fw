<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen_Ex
 * @since Twenty Sixteen Ex 1.0
 */
?>
	<?php if ( has_nav_menu( 'primary' ) ) : ?>
		<div class="site-footer-container">
			<div class="site-footer-main">
				<div id="site-footer-main-menu" class="site-pre-footer-menu">
					<nav class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Footer Primary Menu', 'twentysixteenex' ); ?>">
						<?php
							wp_nav_menu( array(
								'theme_location' => 'primary',
								'menu_class'     => 'primary-menu',
							 ) );
						?>
					</nav><!-- .main-navigation -->
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( 
		get_theme_mod( 'theme_footer', '' ) && 
		is_array( get_theme_mod( 'theme_footer_size', '' ) ) &&
		isset( get_theme_mod( 'theme_footer_size', '' )['height'] ) &&
		isset( get_theme_mod( 'theme_footer_size', '' )['width'] )
	) : ?>
	<style type="text/css" media="screen">
		footer.site-footer {
			background-image: url( '<?= esc_attr( get_theme_mod( 'theme_footer', '' ) ) ?>' );
		}
	</style>
	<?php endif; // End header image check. ?>
	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-footer-container">
			<div class="site-footer-main">
				<div class="site-info">
					&copy;&nbsp;Ольга Салий, 2008-<?=date('Y')?>, сайт установил <a href="http://paulov.ru/" title="Роман Паулов">Роман Паулов</a>.
				</div><!-- .site-info -->
			</div><!-- .site-footer-main -->
		</div><!-- .site-footer-container -->
	</footer><!-- .site-footer -->

<?php wp_footer(); ?>

</body>
</html>