<?php
/**
 * Custom Twenty Sixteen Ex template tags
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen_Ex
 * @since Twenty Sixteen Ex 1.0
 */

if ( ! function_exists( 'twentysixteenex_get_author_posts_url' ) ) :
/**
 * Returns author's profile url
 *
 * @since Twenty Sixteen Ex 1.0
 */

function twentysixteenex_get_author_posts_url( $id ) {
	
	$replace_id = intval( get_theme_mod( 'theme_custom_admin_url_id', '0' ) );

	if ( $replace_id && $id === $replace_id ) {

		$replace_url = get_theme_mod( 'theme_custom_admin_url_url', '' );
		return $replace_url ? home_url( user_trailingslashit( $replace_url ) ) : get_author_posts_url( $id ) ;

	}

	return get_author_posts_url( $id );

}

endif;

if ( ! function_exists( 'twentysixteenex_get_question_status' ) ) :
/**
 * Returns questions count (for Q&A integration)
 *
 * @since Twenty Sixteen Ex 1.0
 */

function twentysixteenex_get_question_status( $question_id = 0 ) {
	
	if ( !function_exists( 'get_answer_count' ) ) return '';

	if ( !$question_id ) $question_id = get_the_ID();

	$count = get_answer_count( $question_id );

	if ( get_post_meta( $question_id, '_accepted_answer', true ) )
		$status = 'answered-accepted';
	elseif ( $count > 0 )
		$status = 'answered';
	else
		$status = 'unanswered';
		
	$status = apply_filters( 'qa_question_status', $status );

	return '<span class="question-status '. $status .'">'. number_format_i18n( $count ) .' '. _n( 'answer', 'answers', $count, QA_TEXTDOMAIN ) .'</span>';

}

endif;

if ( ! function_exists( 'twentysixteenex_get_html_schema' ) ) :
/**
 * Get schema type for HTML
 *
 * @since Twenty Sixteen Ex 1.0
 */

function twentysixteenex_get_html_schema() {
	
	$schema = 'http://schema.org/';
	$type = 'Blog';

	// Is single post
	if ( is_search() ) {

		$type = 'SearchResultsPage';

	}
	elseif ( function_exists( 'is_qa_page' ) && is_qa_page() ) {

		$type = 'QAPage';
		
	}

	return 'itemscope="itemscope" itemtype="' . $schema . $type . '"';

}

endif;

if ( ! function_exists( 'twentysixteenex_get_html_schema_publisher' ) ) :
/**
 * Get structured schema.org publisher
 *
 * @since Twenty Sixteen Ex 1.0
 */

function twentysixteenex_get_html_schema_publisher() {
	
	$logo_url = get_theme_mod( 'theme_schema_org_logo_600x60', '' );
	$logo_size = get_theme_mod( 'theme_schema_org_logo_600x60_size', '' );

	if ( 
		!$logo_url ||
		!$logo_size ||
		!is_array( $logo_size ) ||
		!isset( $logo_size['width'] ) ||
		!isset( $logo_size['height'] )
	)
		return '';

	$publisher_name = esc_attr( get_bloginfo( 'name', 'display' ) );
	$logo_url = esc_url( $logo_url );
	$logo_size['width'] = absint( $logo_size['width'] );
	$logo_size['height'] = absint( $logo_size['height'] );

	return <<<HTML

<div itemprop="publisher" itemscope="itemscope" itemtype="https://schema.org/Organization">
	<div itemprop="logo" itemscope="itemscope" itemtype="https://schema.org/ImageObject">
		<meta itemprop="url" content="{$logo_url}" />
		<meta itemprop="width" content="{$logo_size['width']}" />
		<meta itemprop="height" content="{$logo_size['height']}" />
	</div>
	<meta itemprop="name" content="{$publisher_name}" />
</div>
HTML;

}

endif;

if ( ! function_exists( 'twentysixteenex_get_post_images' ) ) :
/**
 * Get post's images
 *
 * @since Twenty Sixteen Ex 1.0
 */

function twentysixteenex_get_post_images( $post_id = 0 ) {

	$images = [];

	$thePostID = $post_id ? $post_id : get_the_ID();

	$at_args = array( 'numberposts' => 5, 'post_parent' => $thePostID, 'post_type' => 'attachment' );
	$attachments = get_posts( $at_args );

	if ( isset( $attachments ) && is_array( $attachments ) && count( $attachments ) ) {

		foreach( $attachments as $attachment ) {

			if ( wp_attachment_is_image( $attachment->ID ) ) {

				$src = wp_get_attachment_image_src( $attachment->ID, 'medium' );

				if ( isset( $src ) && is_array( $src ) ) {

					$images[] = $src;

				}
			}
		}
	}

	return $images ? $images : NULL;

}

endif;

if ( ! function_exists( 'twentysixteenex_get_html_schema_post_image' ) ) :
/**
 * Get schema.org meta for post's image
 *
 * @since Twenty Sixteen Ex 1.0
 */

function twentysixteenex_get_html_schema_post_image() {

	$images_arr = twentysixteenex_get_post_images();

	if ( !is_null( $images_arr ) && is_array( $images_arr ) && isset( $images_arr[0] ) ) {

		$img = $images_arr[0];

		if ( isset( $img[0] ) && isset( $img[1] ) && isset( $img[2] ) ) {

			$image['url'] = esc_url( $img[0] );
			$image['width'] = absint( $img[1] );
			$image['height'] = absint( $img[2] );
			
		}		
	}

	if ( !isset( $image ) ) {

		$logo_url = get_theme_mod( 'theme_logo', '' );
		$logo_size = get_theme_mod( 'theme_logo_size', '' );

		if ( $logo_url && $logo_size && is_array( $logo_size ) && isset( $logo_size['width'] ) && isset( $logo_size['height'] ) ) {

			$image['url'] = esc_url( $logo_url );
			$image['width'] = absint( $logo_size['width'] );
			$image['height'] = absint( $logo_size['height'] );
			
		}		
	}

	if ( !isset( $image ) ) return '';

	return <<<HTML

<div itemprop="image" itemscope="itemscope" itemtype="https://schema.org/ImageObject">
	<meta itemprop="url" content="{$image['url']}" />
	<meta itemprop="width" content="{$image['width']}" />
	<meta itemprop="height" content="{$image['height']}" />
</div>
HTML;

}

endif;

if ( ! function_exists( 'twentysixteenex_get_html_schema_main_entity_of_page' ) ) :
/**
 * Get schema.org meta for post's image
 *
 * @since Twenty Sixteen Ex 1.0
 */

function twentysixteenex_get_html_schema_main_entity_of_page() {

	$url = esc_url( get_permalink() );

	return <<<HTML

<meta itemscope itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" itemid="{$url}" />
HTML;

}

endif;

if ( ! function_exists( 'twentysixteenex_get_website_header' ) ) :
/**
 * Returns questions count (for Q&A integration)
 *
 * @since Twenty Sixteen Ex 1.0
 */

function twentysixteenex_get_website_header() {

	// This stuff is only for the front page	
	if ( !is_front_page() ) return '';

	// Gathering all social links
	if ( ( $nav_menu_locations = get_nav_menu_locations() ) && isset( $nav_menu_locations[ 'social' ] ) ) {

		$social_menu_obj = wp_get_nav_menu_object( $nav_menu_locations[ 'social' ] );

		if ( $social_menu_obj && is_object( $social_menu_obj ) && isset( $social_menu_obj->term_id ) ) {

			$social_links_array = wp_get_nav_menu_items( $social_menu_obj->term_id );
			
			if ( $social_links_array ) {

				foreach ( $social_links_array as $social_link ) {

					$social_links_js_array[] = $social_link->url;
					
				}

				if ( isset( $social_links_js_array ) ) {

					$social_links_js_array = json_encode( $social_links_js_array, JSON_ERROR_NONE | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

				}
			}
		}
	}

	$website_name = esc_attr( get_theme_mod( 'theme_schema_org_short_website_name', '' ) ? get_theme_mod( 'theme_schema_org_short_website_name', '' ) : get_bloginfo('name', 'display') );
	$website_name_alt = esc_attr( get_bloginfo('name', 'display') );
	$website_url = esc_url( home_url( '/' ) );
	$logo_url = get_theme_mod( 'theme_schema_org_logo_600x60', '' );

	$script = <<<SCRIPT

	<script type="application/ld+json">
	{
		"@context":"http://schema.org",
		"@type":"WebSite",
		"name":"{$website_name}",
		"alternateName":"{$website_name_alt}",
		"url":"{$website_url}",
SCRIPT;
	
	if ( $logo_url ) {

		$logo_url = esc_url( $logo_url );

		$script .= <<<SCRIPT

		"image":"{$logo_url}",
SCRIPT;

	}

	if ( isset( $social_links_js_array ) ) {

		$logo_url = esc_url( $logo_url );

		$script .= <<<SCRIPT

		"sameAs":{$social_links_js_array},
SCRIPT;

	}
	
	$script .= <<<SCRIPT

		"potentialAction":{
			"@type":"SearchAction",
			"target":"{$website_url}?s={search_term_string}",
			"query-input":"required name=search_term_string"
		}
	}
	</script>
SCRIPT;

	return $script;

}

endif;

if ( ! function_exists( 'twentysixteenex_entry_meta' ) ) :
/**
 * Prints HTML with meta information for the categories, tags.
 *
 * Create your own twentysixteenex_entry_meta() function to override in a child theme.
 *
 * @since Twenty Sixteen Ex 1.0
 */
function twentysixteenex_entry_meta() {
	if ( 'post' === get_post_type() || 'question' === get_post_type() ) {
		$author_avatar_size = apply_filters( 'twentysixteenex_author_avatar_size', 49 );
		printf( '<span class="byline" itemtype="http://schema.org/Person" itemprop="author"><span class="author vcard">%1$s<span class="screen-reader-text">%2$s </span> <a rel="author" class="url fn n" href="%3$s" title="%4$s" itemprop="name">%5$s</a></span></span>',
			get_avatar( get_the_author_meta( 'user_email' ), $author_avatar_size ),
			_x( 'Author', 'Used before post author name.', 'twentysixteenex' ),
			esc_url( twentysixteenex_get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( wp_strip_all_tags( get_the_author() ) ),
			get_the_author()
		);
	}

	if ( in_array( get_post_type(), array( 'post', 'question', 'attachment' ) ) ) {
		twentysixteenex_entry_date();
	}

	$format = get_post_format();
	if ( current_theme_supports( 'post-formats', $format ) ) {
		printf( '<span class="entry-format">%1$s<a href="%2$s" title="%3$s">%4$s</a></span>',
			sprintf( '<span class="screen-reader-text">%s </span>', _x( 'Format', 'Used before post format.', 'twentysixteenex' ) ),
			esc_url( get_post_format_link( $format ) ),
			esc_attr( __( 'All posts of type &laquo;', 'twentysixteenex' ) . wp_strip_all_tags( get_post_format_string( $format ) ) . _x( '&raquo;', 'Tail for enclosures like - All posts of type &laquo;...&raquo;', 'twentysixteenex' ) ),
			get_post_format_string( $format )
		);
	}

	if ( 'post' === get_post_type() || 'question' === get_post_type() ) {
		twentysixteenex_entry_taxonomies();
	}

	if ( 'question' === get_post_type() ) {

		echo '<span class="comments-link">';
		if ( function_exists( 'the_question_score' ) ) the_question_score();
		echo '</span>';

		echo '<span class="comments-link">';
		if ( function_exists( 'the_question_status' ) ) {

			if ( function_exists( '_qa_html' ) && function_exists( 'qa_get_url' ) ) {

				echo apply_filters( 'qa_get_question_link', _qa_html( 'a', array( 'class' => 'question-link', 'href' => qa_get_url( 'single' ) .'#answer-list', 'title' => '' ), twentysixteenex_get_question_status() ) );
				
			} else {

				the_question_status();

			}
		}
		echo '</span>';

	} else {

		if ( ! is_singular() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link( sprintf( __( 'Leave a comment<span class="screen-reader-text"> on %s</span>', 'twentysixteenex' ), get_the_title() ) );
			echo '</span>';
		}		
	}
}
endif;

if ( ! function_exists( 'twentysixteenex_entry_date' ) ) :
/**
 * Prints HTML with date information for current post.
 *
 * Create your own twentysixteenex_entry_date() function to override in a child theme.
 *
 * @since Twenty Sixteen Ex 1.0
 */
function twentysixteenex_entry_date() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s" itemprop="datePublished">%2$s</time><meta itemprop="dateModified" content="%1$s" />';

	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s" itemprop="datePublished">%2$s</time><time class="updated" datetime="%3$s" itemprop="dateModified">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		get_the_date(),
		esc_attr( get_the_modified_date( 'c' ) ),
		get_the_modified_date()
	);

	printf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span><a href="%2$s" title="%3$s" rel="bookmark">%4$s</a></span>',
		_x( 'Posted on', 'Used before publish date.', 'twentysixteenex' ),
		esc_url( get_permalink() ),
		esc_attr( wp_strip_all_tags( get_the_title() ) ),
		$time_string
	);
}
endif;

if ( ! function_exists( 'twentysixteenex_entry_taxonomies' ) ) :
/**
 * Prints HTML with category and tags for current post.
 *
 * Create your own twentysixteenex_entry_taxonomies() function to override in a child theme.
 *
 * @since Twenty Sixteen Ex 1.0
 */
function twentysixteenex_entry_taxonomies() {
	$categories_list = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'twentysixteenex' ) );
	if ( $categories_list && twentysixteenex_categorized_blog() ) {
		printf( '<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
			_x( 'Categories', 'Used before category names.', 'twentysixteenex' ),
			$categories_list
		);
	}

	$tags_list = get_the_tag_list( '', _x( ', ', 'Used between list items, there is a space after the comma.', 'twentysixteenex' ) );
	if ( $tags_list ) {
		printf( '<span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
			_x( 'Tags', 'Used before tag names.', 'twentysixteenex' ),
			$tags_list
		);
	}
}
endif;

if ( ! function_exists( 'twentysixteenex_post_thumbnail' ) ) :
/**
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 *
 * Create your own twentysixteenex_post_thumbnail() function to override in a child theme.
 *
 * @since Twenty Sixteen Ex 1.0
 */
function twentysixteenex_post_thumbnail() {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	if ( is_singular() ) :
	?>

	<div class="post-thumbnail" itemprop="image">
		<?php the_post_thumbnail( 'post-thumbnail', array( 'itemprop' => 'image' ) ); ?>
	</div><!-- .post-thumbnail -->

	<?php else : ?>

	<a class="post-thumbnail" href="<?php the_permalink(); ?>" title="<?= wp_strip_all_tags( get_the_title() ) ?>" aria-hidden="true" itemprop="image">
		<?php the_post_thumbnail( 'post-thumbnail', array( 'alt' => the_title_attribute( 'echo=0' ), 'itemprop' => 'image' ) ); ?>
	</a>

	<?php endif; // End is_singular()
}
endif;

if ( ! function_exists( 'twentysixteenex_excerpt' ) ) :
	/**
	 * Displays the optional excerpt.
	 *
	 * Wraps the excerpt in a div element.
	 *
	 * Create your own twentysixteenex_excerpt() function to override in a child theme.
	 *
	 * @since Twenty Sixteen Ex 1.0
	 *
	 * @param string $class Optional. Class string of the div element. Defaults to 'entry-summary'.
	 */
	function twentysixteenex_excerpt( $class = 'entry-summary' ) {
		$class = esc_attr( $class );

		if ( has_excerpt() || is_search() ) : ?>
			<div class="<?php echo $class; ?>">
				<?php the_excerpt(); ?>
			</div><!-- .<?php echo $class; ?> -->
		<?php endif;
	}
endif;

if ( ! function_exists( 'twentysixteenex_excerpt_more' ) && ! is_admin() ) :
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... and
 * a 'Continue reading' link.
 *
 * Create your own twentysixteenex_excerpt_more() function to override in a child theme.
 *
 * @since Twenty Sixteen Ex 1.0
 *
 * @return string 'Continue reading' link prepended with an ellipsis.
 */
function twentysixteenex_excerpt_more() {
	$link = sprintf( '<a href="%1$s" class="more-link" title="%2$s">%3$s</a>',
		esc_url( get_permalink( get_the_ID() ) ),
		esc_attr( wp_strip_all_tags( get_the_title() ) ),
		/* translators: %s: Name of current post */
		sprintf( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentysixteenex' ), get_the_title( get_the_ID() ) )
	);
	return ' &hellip; ' . $link;
}
add_filter( 'excerpt_more', 'twentysixteenex_excerpt_more' );
endif;

/**
 * Determines whether blog/site has more than one category.
 *
 * Create your own twentysixteenex_categorized_blog() function to override in a child theme.
 *
 * @since Twenty Sixteen Ex 1.0
 *
 * @return bool True if there is more than one category, false otherwise.
 */
function twentysixteenex_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'twentysixteenex_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'twentysixteenex_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so twentysixteenex_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so twentysixteenex_categorized_blog should return false.
		return false;
	}
}

/**
 * Flushes out the transients used in twentysixteenex_categorized_blog().
 *
 * @since Twenty Sixteen Ex 1.0
 */
function twentysixteenex_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'twentysixteenex_categories' );
}
add_action( 'edit_category', 'twentysixteenex_category_transient_flusher' );
add_action( 'save_post',     'twentysixteenex_category_transient_flusher' );
