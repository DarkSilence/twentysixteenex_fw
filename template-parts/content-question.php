<?php
/**
 * The template part for displaying content
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen_Ex
 * @since Twenty Sixteen Ex 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('type-page'); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark"><span class="qa-status-icon '. ( is_question_answered() ? 'qa-answered-icon' : 'qa-unanswered-icon' ) .'"></span> ', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
	</header><!-- .entry-header -->

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
</article><!-- #post-## -->
