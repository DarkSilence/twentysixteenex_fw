<?php
/**
 * The template for displaying archive page with old questions
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen_Ex
 * @since Twenty Sixteen Ex 1.0
 */

get_header( 'question' ); ?>

	<div class="site-inner">
		<div id="content" class="site-content">

			<div id="primary" class="content-area">
				<main id="main" class="site-main questions-listing" role="main">

					<header class="page-header">
						<?php the_qa_error_notice(); ?>
						<?php the_qa_menu(); ?>
					</header><!-- .page-header -->

					<?php
						if ( !is_qa_page( 'unanswered' ) && !is_qa_page( 'ask' ) && !is_qa_page( 'edit' ) ) {

							echo '<h1 class="page-title">Вопросы по ремонту телефонов, ноутбуков и другой техники</h1>';

						} elseif ( is_qa_page( 'unanswered' ) ) {

							echo '<h1 class="page-title">Вопросы без ответов</h1>';

						} else {

							the_archive_title( '<h1 class="page-title">', '</h1>' );
							the_archive_description( '<div class="taxonomy-description">', '</div>' );
							
						}
					?>

					<?php if ( have_posts() ) : ?>

						<?php
						// Start the Loop.
						while ( have_posts() ) : the_post();

							/*
							 * Include the Post-Format-specific template for the content.
							 * If you want to override this in a child theme, then include a file
							 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
							 */

							get_template_part( 'template-parts/content', 'question' );

						// End the loop.
						endwhile;

						// Previous/next page navigation.
						the_posts_pagination( array(
							'prev_text'          => __( 'Previous page', 'twentysixteenex' ),
							'next_text'          => __( 'Next page', 'twentysixteenex' ),
							'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentysixteenex' ) . ' </span>',
						) );

					// If no content, include the "No posts found" template.
					else :
						get_template_part( 'template-parts/content', 'none' );

					endif;
					?>

				</main><!-- .site-main -->

				<?php get_sidebar( 'content-bottom' ); ?>

			</div><!-- .content-area -->

			<?php get_sidebar( 'question' ); ?>

		</div><!-- .site-content -->
	</div><!-- .site-inner -->

<?php get_footer( 'question' ); ?>