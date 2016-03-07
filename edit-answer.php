<?php
/**
 * The template for displaying edit answer page
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen_Ex
 * @since Twenty Sixteen Ex 1.0
 */

get_header( 'question' ); ?>

	<div class="site-inner">
		<div id="content" class="site-content">

			<div id="primary" class="content-area">
				<main id="main" class="site-main question-page" role="main">

					<header class="page-header">
						<?php the_qa_menu(); ?>
					</header><!-- .page-header -->

					<h1 class="page-title">Редактирование ответа</h1>

					<?php wp_reset_postdata(); ?>

					<div id="answer-form">
					<h2><?php printf( __( 'Answer for %s', QA_TEXTDOMAIN ), get_question_link( $post->post_parent ) ); ?></h2>
					<?php the_answer_form(); ?>
					</div>

				</main><!-- .site-main -->

				<?php get_sidebar( 'content-bottom' ); ?>

			</div><!-- .content-area -->

			<?php get_sidebar( 'question' ); ?>

		</div><!-- .site-content -->
	</div><!-- .site-inner -->

<?php get_footer( 'question' ); ?>