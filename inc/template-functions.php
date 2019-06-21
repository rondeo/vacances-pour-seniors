<?php
/**
* Custom template tags for this theme
*
* Additional features to allow styling of the templates.
*
* PHP version 7.2.10
*
* @package  VacancesPourSeniors
* @since    1.0.0
* @license
*/


add_filter( 'body_class', 'custom_body_class' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function custom_body_class( $classes ) {
	// Home.
	if ( is_front_page() ) {
		$classes[] = 'Front-page';
	}

	if ( ! is_front_page() ) {
		$classes[] = 'Page';
	}

	if ( is_singular( 'residence' ) ) {
		$classes[] = 'Single-residence';
	}

	if ( is_single() && ! is_singular( 'residence' ) ) {
		$classes[] = 'Single';
	}

	if ( is_archive() ) {
		$classes[] = 'Archive';
	}

	return $classes;
}


/**
 * Returns information about the current post's discussion, with cache support.
 *
 * @see https://github.com/WordPress/twentynineteen/blob/2a8e9d8aa9d6f1150e58b21ee513a0f000a7d972/inc/template-functions.php#L160
 */
function get_discussion_data() {
	static $discussion, $post_id;

	$current_post_id = get_the_ID();

	if ( $current_post_id === $post_id ) {
		return $discussion; /* If we have discussion information for post ID, return cached object */
	} else {
		$post_id = $current_post_id;
	}

	$comments = get_comments(
		array(
			'post_id' => $current_post_id,
			'orderby' => 'comment_date_gmt',
			'order'   => get_option( 'comment_order', 'asc' ), /* Respect comment order from Settings » Discussion. */
			'status'  => 'approve',
			'number'  => 20, /* Only retrieve the last 20 comments, as the end goal is just 6 unique authors */
		)
	);

	$authors = array();

	foreach ( $comments as $comment ) {
		$authors[] = ( (int) $comment->user_id > 0 ) ? (int) $comment->user_id : $comment->comment_author_email;
	}

	$authors    = array_unique( $authors );

	$discussion = (object) array(
		'authors'   => array_slice( $authors, 0, 6 ),           /* Six unique authors commenting on the post. */
		'responses' => get_comments_number( $current_post_id ), /* Number of responses. */
	);

	return $discussion;
}
