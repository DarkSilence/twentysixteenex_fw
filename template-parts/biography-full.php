<?php
/**
 * The template part for displaying an Author biography
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen_Ex
 * @since Twenty Sixteen Ex 1.0
 */
?>

<div class="author-info-full" itemscope="itemscope" itemtype="http://schema.org/Person">
	
	<h1 class="author-title"><span class="author-heading"><?php _e( 'Author:', 'twentysixteenex' ); ?></span> <span itemprop="name"><?= get_the_author() ?></span></h1>

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

		<p class="author-bio" itemprop="description">
			<?php the_author_meta( 'description' ); ?>
		</p><!-- .author-bio -->

		<nav class="social-links">
			<ul class="social-links-container">
<?php

if ( ( get_the_author_meta('user_url') != 'http://' ) && ( get_the_author_meta('user_url') != '' ) ) echo '<li><a href="'. get_the_author_meta('user_url') .'" itemprop="sameAs">'. get_the_author_meta('user_url') .'</a></li>';
if ( get_the_author_meta('telephone') != '' ) echo '<li itemprop="telephone">'. get_the_author_meta('telephone') .'</li>';
if ( get_the_author_meta('skype') != '' ) echo '<li><a href="skype:'. get_the_author_meta('skype') .'?chat">'. get_the_author_meta('skype') .'</a></li>';
if ( get_the_author_meta('icq') != '' ) echo '<li><a href="http://www.icq.com/whitepages/cmd.php?uin='. get_the_author_meta('icq') .'&action=message">'. get_the_author_meta('icq') .'</a></li>';
if ( get_the_author_meta('jabber') != '' ) echo '<li><a href="gtalk:chat?jid='. get_the_author_meta('jabber') .'">'. get_the_author_meta('jabber') .'</a></li>';
if ( get_the_author_meta('googleplus') != '' ) echo '<li><a rel="author" href="'.  get_the_author_meta('googleplus') .'?rel=author" itemprop="sameAs">'. get_the_author_meta('googleplus') .'</a></li>';
if ( get_the_author_meta('facebook') != '' ) echo '<li><a href="'. get_the_author_meta('facebook') .'" itemprop="sameAs">'. get_the_author_meta('facebook') .'</a></li>';
if ( get_the_author_meta('vk') != '' ) echo '<li><a href="'. get_the_author_meta('vk') .'" itemprop="sameAs">'. get_the_author_meta('vk') .'</a></li>';
if ( get_the_author_meta('twitter') != '' ) echo '<li><a href="'. get_the_author_meta('twitter') .'" itemprop="sameAs">'. get_the_author_meta('twitter') .'</a></li>';

?>
			</ul>
		</nav>
	</div><!-- .author-description -->

	<meta itemprop="image" content="<?= esc_url( get_avatar_url( get_the_author_meta( 'user_email' ) ) ) ?>" />

</div><!-- .author-info -->
