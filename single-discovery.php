<?php
/**
 * Single
 *
 * @package VacancesPourSeniors
 * @file    single.php
 * @author  Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */

use Timber\Timber;

if ( ! class_exists( 'Timber' ) ) {
	echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';
	return;
}

$context = Timber::context();

$context['post']      = new TimberPost();
$context['node_type'] = 'default-page';
$context['title']     = __( 'Les résidences les plus proches', 'VacancesPourSeniors' );

$context['city']          = get_the_city( $post->ID );
$context['department']    = get_the_terms( $post->ID, 'department' )[0];
$context['related_posts'] = Timber::get_posts( array( 'post_type' => 'residence', 'posts_per_page' => -1 ) );


$related_posts_count = count( $context['related_posts'] );

for ( $i = 0; $i < $related_posts_count; $i++ ) {
	$residence = $context['related_posts'][ $i ];
	$residence_city = get_the_city( $residence->ID );
	$residence_department = get_the_city_department( "city_{$residence_city->term_id}" );

	if ( ! is_object( $residence_department ) ) {
		unset( $context['related_posts'][ $i ] );
		continue;
	}

	if ( $residence_department->name !== $context['department']->name ) {
		unset( $context['related_posts'][ $i ] );
		continue;
	}
}

$templates = array( 'pages/single.html.twig' );

Timber::render( $templates, $context );
