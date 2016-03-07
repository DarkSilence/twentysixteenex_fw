<?php
/**
 * The template for displaying a question page
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen_Ex
 * @since Twenty Sixteen Ex 1.0
 */

global $user_ID, $post;

get_header( 'question' ); ?>

	<div class="site-inner">
		<div id="content" class="site-content">

			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">

					<header class="page-header">
						<?php the_qa_menu(); ?>
					</header><!-- .page-header -->

					<?php if ( ($user_ID == 0 && qa_visitor_can('read_questions')) || current_user_can( 'read_questions' ) ) { ?>

						<?php wp_reset_postdata(); ?>

						<div id="single-question" itemscope="itemscope" itemtype="http://schema.org/Question">
							<h1 class="page-title" itemprop="name"><?php the_title(); ?></h1>
							<div id="single-question-container">
								<?php the_question_voting(); ?>
								<div id="question-body">
									<div id="question-content" itemprop="text"><?php the_qa_author_box( get_the_ID() ); ?><?php the_content(); ?></div>
									<?php the_question_category(  __( 'Category:', QA_TEXTDOMAIN ) . ' <span class="question-category">', '', '</span>' ); ?>
									<?php the_question_tags( __( 'Tags:', QA_TEXTDOMAIN ) . ' <span class="question-tags">', ' ', '</span>' ); ?>
									<span id="qa-lastaction"><?php //_e( 'asked', QA_TEXTDOMAIN ); ?> <?php the_qa_time( get_the_ID() ); ?></span>

									<div class="question-meta">
										<?php the_qa_action_links( get_the_ID() ); ?>				
									</div>
								</div>
								<div class="clear"></div>
							</div>
						</div>

					<?php } ?>

					<?php if ( (( ($user_ID == 0 && qa_visitor_can('read_answers')) || current_user_can( 'read_answers' )) ) && is_question_answered() ) { ?>
						<div id="answer-list">
							<h2><?php the_answer_count(); ?></h2>
							<?php the_answer_list(); ?>
						</div>
					<?php } ?>
					<?php if ( ($user_ID == 0 && qa_visitor_can('publish_answers')) || current_user_can( 'publish_answers' ) ) { ?>
						<div id="edit-answer">
							<h2><?php _e( 'Your Answer', QA_TEXTDOMAIN ); ?></h2>
							<?php the_answer_form(); ?>
						</div>
					<?php } ?>

					<p><?php the_question_subscription(); ?></p>

				</main><!-- .site-main -->

				<?php get_sidebar( 'content-bottom' ); ?>

			</div><!-- .content-area -->

			<?php get_sidebar( 'question' ); ?>

		</div><!-- .site-content -->
	</div><!-- .site-inner -->

<?php get_footer( 'question' ); ?>