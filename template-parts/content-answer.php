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

	<?php

	list( $up, $down ) = qa_get_votes( get_the_ID() );

	?>

	echo '<li>';
		echo "<div class='answer-score'>";
		echo number_format_i18n( $up - $down );
		echo "</div> ";
		the_answer_link( get_the_ID() );
	echo '</li>';


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
