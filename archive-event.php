<?php
/**
 * Archive: event
 *
 * @package VacancesPourSeniors
 * @file    archive-event.php
 * @author  Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */

use Timber\Timber;

if ( ! class_exists( 'Timber' ) ) {
	echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';
	return;
}

$context = Timber::context();

$context['posts'] = Timber::get_posts();
$context['pagination'] = Timber::get_pagination();
$context['title'] = __( 'Les évènements', 'VacancesPourSeniors' );
$context['text']  = '<p>' . __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed quam mi, hendrerit', 'VacancesPourSeniors' ) . '</p>';

$templates = array( 'pages/archive.html.twig' );

Timber::render( $templates, $context );
