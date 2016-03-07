<?php
/**
 * The template part for displaying single posts
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen_Ex
 * @since Twenty Sixteen Ex 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope="itemscope" itemtype="http://schema.org/BlogPosting">
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title" itemprop="headline">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<?php twentysixteenex_excerpt(); ?>

	<?php twentysixteenex_post_thumbnail(); ?>

	<div class="entry-content" itemprop="text">
		<?php

			the_content();

			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentysixteenex' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentysixteenex' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
			
			get_template_part( 'template-parts/starbar' );

		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php twentysixteenex_entry_meta(); ?>
		<?php
			edit_post_link(
				sprintf(
					/* translators: %s: Name of current post */
					__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'twentysixteenex' ),
					get_the_title()
				),
				'<span class="edit-link">',
				'</span>'
			);
		?>
	</footer><!-- .entry-footer -->

	<div>
		<?php
			if ( '' !== get_the_author_meta( 'description' ) ) {
				get_template_part( 'template-parts/biography' );
			}
		?>
	</div>

	<?php

		echo twentysixteenex_get_html_schema_publisher();
		echo twentysixteenex_get_html_schema_post_image();
		echo twentysixteenex_get_html_schema_main_entity_of_page();

	?>

</article><!-- #post-## -->
