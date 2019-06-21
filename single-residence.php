<?php
/**
 * Single: Residence
 *
 * @package VacancesPourSeniors
 * @file    single-residence.php
 * @author  Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */

use Timber\Timber;

if ( ! class_exists( 'Timber' ) ) {
	echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';
	return;
}

$context = Timber::context();

$context['residence'] = new TimberPost();
$context['ratings']   = array(
	array(
		'name'  => 'accommodation',
		'id'    => 'accommodation',
		'label' => __( 'Logement' ),
	),
	array(
		'name'  => 'cleanliness',
		'id'    => 'cleanliness',
		'label' => __( 'Propreté' ),
	),
	array(
		'name'  => 'equipments',
		'id'    => 'equipments',
		'label' => __( 'Équipements' ),
	),
	array(
		'name'  => 'environment',
		'id'    => 'environment',
		'label' => __( 'Environnement' ),
	),
	array(
		'name'  => 'on-site-reception',
		'id'    => 'on-site-reception',
		'label' => __( 'Accueil sur place' ),
	)
);

$context['region'] = get_the_region( $post->id );
$context['events'] = Timber::get_posts( array( 'post_type' => 'event' ) );

$events_count = count( $context['events'] );

for ( $i = 0; $i < $events_count; $i++ ) {
	$event = $context['events'][ $i ];
	$event_region = get_the_region( $event->id );

	if ( ! is_object( $event_region ) ) {
		unset( $context['events'][ $i ] );
		continue;
	}

	if ( $event_region->name !== $context['region']->name ) {
		unset( $context['events'][ $i ] );
		continue;
	}
}

$context['discoveries'] = Timber::get_posts( array( 'post_type' => 'discovery' ) );

$discoveries_count = count( $context['discoveries'] );

for ( $i = 0; $i < $discoveries_count; $i++ ) {
	$discovery = $context['discoveries'][ $i ];
	$discovery_region = get_the_region( $discovery->id );

	if ( ! is_object( $discovery_region ) ) {
		unset( $context['discoveries'][ $i ] );
		continue;
	}

	if ( $discovery_region->name !== $context['region']->name ) {
		unset( $context['discoveries'][ $i ] );
		continue;
	}
}

$context['unit_tag'] = sprintf( 'wpcf7-f%1$d-p%2$d-o%3$d', absint( BOOKING_FORM_ID ), get_the_ID(), 1 );
$context['booking_form_url'] = apply_filters( 'wpcf7_form_action_url', wpcf7_get_request_uri() . '#' . $context['unit_tag'] );

$templates = array( 'pages/single-residence.html.twig' );

Timber::render( $templates, $context );
