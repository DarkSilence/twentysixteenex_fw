<?php
/**
 * The template for displaying image attachments
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen_Ex
 * @since Twenty Sixteen Ex 1.0
 */

get_header(); ?>

	<div class="site-inner">
		<div id="content" class="site-content">

			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">

					<?php
						// Start the loop.
						while ( have_posts() ) : the_post();
					?>

						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

							<header class="entry-header">
								<?php
									printf( '<h1 class="entry-title">'. __( 'Illustration &laquo;', 'twentysixteenex' ) .'<a class="underlined" href="%1$s" title="%2$s" rev="attachment">%3$s</a>'. _x( '&raquo;', 'Tail for enclosures like - All posts of type &laquo;...&raquo;', 'twentysixteenex' ) .'</h1>',
										esc_url( get_permalink( $post->post_parent ) ),
										esc_attr( wp_strip_all_tags( get_the_title( $post->post_parent ), true ) ),
										get_the_title( $post->post_parent )
									);
								?>
							</header><!-- .entry-header -->

							<div class="entry-content">

								<div class="entry-attachment">
									<?php
										/**
										 * Filter the default twentysixteenex image attachment size.
										 *
										 * @since Twenty Sixteen Ex 1.0
										 *
										 * @param string $image_size Image size. Default 'large'.
										 */
										$image_size = apply_filters( 'twentysixteenex_attachment_size', 'large' );

										next_image_link( false, wp_get_attachment_image( get_the_ID(), $image_size ) );
									?>

									<?php twentysixteenex_excerpt( 'entry-caption' ); ?>

								</div><!-- .entry-attachment -->

								<?php
									//the_content();
									wp_link_pages( array(
										'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentysixteenex' ) . '</span>',
										'after'       => '</div>',
										'link_before' => '<span>',
										'link_after'  => '</span>',
										'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentysixteenex' ) . ' </span>%',
										'separator'   => '<span class="screen-reader-text">, </span>',
									) );
								?>
							</div><!-- .entry-content -->

							<footer class="entry-footer">
								<?php twentysixteenex_entry_meta(); ?>
								<?php
									// Retrieve attachment metadata.
									$metadata = wp_get_attachment_metadata();
									if ( $metadata ) {
										printf( '<span class="full-size-link"><span class="screen-reader-text">%1$s </span><a href="%2$s" title="%3$s">%4$s &times; %5$s</a></span>',
											esc_html_x( 'Full size', 'Used before full size attachment link.', 'twentysixteenex' ),
											esc_url( wp_get_attachment_url() ),
											esc_attr( __('Source Image - ', 'twentysixteenex') . wp_strip_all_tags( get_the_title( $post->post_parent ), true ) ),
											absint( $metadata['width'] ),
											absint( $metadata['height'] )
										);
									}
								?>
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

						</article><!-- #post-## -->

						<nav id="image-navigation" class="navigation image-navigation">
							<div class="nav-links">
								<div class="nav-previous"><?php previous_image_link( false, __( 'Previous Image', 'twentysixteenex' ) ); ?></div>
								<div class="nav-next"><?php next_image_link( false, __( 'Next Image', 'twentysixteenex' ) ); ?></div>
							</div><!-- .nav-links -->
						</nav><!-- .image-navigation -->

						<?php
							// If comments are open or we have at least one comment, load up the comment template.
							if ( comments_open() || get_comments_number() ) {
								comments_template();
							}

							// Parent post navigation.
							the_post_navigation( array(
								'prev_text' => _x( '<span class="meta-nav">Published in</span><span class="post-title">%title</span>', 'Parent post link', 'twentysixteenex' ),
							) );
						// End the loop.
						endwhile;
					?>

				</main><!-- .site-main -->
			</div><!-- .content-area -->

			<?php get_sidebar(); ?>

		</div><!-- .site-content -->
	</div><!-- .site-inner -->

<?php get_footer(); ?>
