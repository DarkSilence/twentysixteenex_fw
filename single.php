<?php
/**
 * The template for displaying all single posts and attachments
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

						// Include the single post content template.
						get_template_part( 'template-parts/content', 'single' );

						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) {
							comments_template();
						}

						if ( is_singular( 'attachment' ) ) {
							// Parent post navigation.
							the_post_navigation( array(
								'prev_text' => _x( '<span class="meta-nav">Published in</span><span class="post-title">%title</span>', 'Parent post link', 'twentysixteenex' ),
							) );
						} elseif ( is_singular( 'post' ) ) {
							// Previous/next post navigation.
							the_post_navigation( array(
								'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next', 'twentysixteenex' ) . '</span> ' .
									'<span class="screen-reader-text">' . __( 'Next post:', 'twentysixteenex' ) . '</span> ' .
									'<span class="post-title">%title</span>',
								'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous', 'twentysixteenex' ) . '</span> ' .
									'<span class="screen-reader-text">' . __( 'Previous post:', 'twentysixteenex' ) . '</span> ' .
									'<span class="post-title">%title</span>',
							) );
						}

						// End of the loop.
					endwhile;
					?>

				</main><!-- .site-main -->

				<?php get_sidebar( 'content-bottom' ); ?>

			</div><!-- .content-area -->

			<?php get_sidebar(); ?>

		</div><!-- .site-content -->
	</div><!-- .site-inner -->

<?php get_footer(); ?>
