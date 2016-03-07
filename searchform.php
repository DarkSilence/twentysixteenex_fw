<?php
/**
 * Template for displaying search forms in Twenty Sixteen Ex
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen_Ex
 * @since Twenty Sixteen Ex 1.0
 */
?>

<form role="search" method="get" class="search-form" action="<?= esc_url( home_url( '/' ) ) ?>">
	<label>
		<span class="screen-reader-text"><?= _x( 'Search for:', 'label', 'twentysixteenex' ) ?></span>
		<input type="search" class="search-field" placeholder="<?= esc_attr_x( 'Search &hellip;', 'placeholder', 'twentysixteenex' ) ?>" value="<?= get_search_query() ?>" name="s" title="<?= esc_attr_x( 'Search for:', 'label', 'twentysixteenex' ) ?>" />
	</label>
	<button type="submit" class="search-submit"><span class="screen-reader-text"><?= _x( 'Search', 'submit button', 'twentysixteenex' ) ?></span></button>
</form>
