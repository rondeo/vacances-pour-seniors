<?php
/**
 * Index
 *
 * @package VacancesPourSeniors
 * @file    index.php
 * @author  Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */

use Timber\Timber;

if ( ! class_exists( 'Timber' ) ) {
	echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';
	return;
}

$context = Timber::context();

// $context['post']       = Timber::get_post();
$context['pagination'] = Timber::get_pagination();
$context['node_type']  = 'frontPage';
$context['body_class'] = 'index';

$context['residences']     = Timber::get_posts( array( 'post_type' => 'residence' ) );
$context['events']         = Timber::get_posts( array( 'post_type' => 'event' ) );
$context['special_offers'] = Timber::get_posts( array( 'post_type' => 'special_offers' ) );
$context['discoveries']    = Timber::get_posts( array( 'post_type' => 'discovery' ) );

$templates = array( 'pages/front-page.html.twig' );

Timber::render( $templates, $context );
