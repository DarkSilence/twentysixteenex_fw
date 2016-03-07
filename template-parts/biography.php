<?php
/**
 * The template part for displaying an Author biography
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen_Ex
 * @since Twenty Sixteen Ex 1.0
 */
?>

<div class="author-info" itemscope="itemscope" itemtype="http://schema.org/Person">
	<div class="author-avatar">
		<?php
		/**
		 * Filter the Twenty Sixteen Ex author bio avatar size.
		 *
		 * @since Twenty Sixteen Ex 1.0
		 *
		 * @param int $size The avatar height and width size in pixels.
		 */
		$author_bio_avatar_size = apply_filters( 'twentysixteenex_author_bio_avatar_size', 100 );

		echo get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size );
		?>
	</div><!-- .author-avatar -->

	<div class="author-description">
		<h2 class="author-title"><span class="author-heading"><?php _e( 'Author:', 'twentysixteenex' ); ?></span> <span itemprop="name"><?php echo get_the_author(); ?></span></h2>

		<p class="author-bio" itemprop="description">
			<?php the_author_meta( 'description' ); ?>
			<a rel="author" class="author-link" title="<?php printf( __( 'View all posts by %s', 'twentysixteenex' ), wp_strip_all_tags( get_the_author() ) ); ?>" href="<?= esc_url( twentysixteenex_get_author_posts_url( get_the_author_meta( 'ID' ) ) ) ?>">
				<?php printf( __( 'View all posts by %s', 'twentysixteenex' ), get_the_author() ); ?>
			</a>
		</p><!-- .author-bio -->
	</div><!-- .author-description -->

	<meta itemprop="url" content="<?= esc_url( twentysixteenex_get_author_posts_url( get_the_author_meta( 'ID' ) ) ) ?>" />
	<meta itemprop="image" content="<?= esc_url( get_avatar_url( get_the_author_meta( 'user_email' ) ) ) ?>" />
</div><!-- .author-info -->
