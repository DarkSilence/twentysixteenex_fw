<?php
/**
 * The template for displaying a page with user's questions
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen_Ex
 * @since Twenty Sixteen Ex 1.0
 */

get_header( 'question' ); ?>

	<div class="site-inner">
		<div id="content" class="site-content">

			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">

					<header class="page-header">
						<?php the_qa_menu(); ?>
					</header><!-- .page-header -->

					<div id="qa-user-box">
						<?php echo get_avatar( get_queried_object_id(), 128 ); ?>
						<?php the_qa_user_rep( get_queried_object_id() ); ?>
					</div>

					<table id="qa-user-details">
						<tr>
							<th><?php _e( 'Name', QA_TEXTDOMAIN ); ?></th>
							<td><strong><?php echo get_queried_object()->display_name; ?></strong></td>
						</tr>
						<tr>
							<th><?php _e( 'Member for', QA_TEXTDOMAIN ); ?></th>
							<td><?php echo human_time_diff( strtotime( get_queried_object()->user_registered ) ); ?></td>
						</tr>
					</table>

					<?php
					$answer_query = new WP_Query( array(
						'author' => get_queried_object_id(),
						'post_type' => 'answer',
						'posts_per_page' => 20,
						'update_post_term_cache' => false
					) );

					$fav_query = new WP_Query( array(
						'post_type' => 'question',
						'meta_key' => '_fav',
						'meta_value' => get_queried_object_id(),
						'posts_per_page' => 20,
					) );
					?>

					<div id="qa-user-tabs-wrapper">
						<ul id="qa-user-tabs">
							<li><a href="#qa-user-questions">
								<span id="user-questions-total"><?php echo number_format_i18n( $wp_query->found_posts ); ?></span>
								<?php echo _n( 'Question', 'Questions', $wp_query->found_posts, QA_TEXTDOMAIN ); ?>
							</a></li>

							<li><a href="#qa-user-answers">
								<span id="user-answers-total"><?php echo number_format_i18n( $answer_query->found_posts ); ?></span>
								<?php echo _n( 'Answer', 'Answers', $answer_query->found_posts, QA_TEXTDOMAIN ); ?>
							</a></li>
						</ul>

						<div id="qa-user-questions" class="site-main questions-listing">
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

						endif;
						?>
						</div>

						<div id="qa-user-answers">
							<ul>
							<?php
								while ( $answer_query->have_posts() ) : $answer_query->the_post();
									list( $up, $down ) = qa_get_votes( get_the_ID() );

									echo '<li>';
										echo "<div class='answer-score'>";
										echo number_format_i18n( $up - $down );
										echo "</div> ";
										the_answer_link( get_the_ID() );
									echo '</li>';
								endwhile;
							?>
							</ul>
						</div>
					</div>

				</main><!-- .site-main -->

				<?php get_sidebar( 'content-bottom' ); ?>

			</div><!-- .content-area -->

			<?php get_sidebar( 'question' ); ?>

		</div><!-- .site-content -->
	</div><!-- .site-inner -->

<?php get_footer( 'question' ); ?>