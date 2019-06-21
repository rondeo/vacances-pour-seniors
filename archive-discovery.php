<?php
/**
 * Single: special offers
 *
 * @package VacancesPourSeniors
 * @file    archive-specialoffers.php
 * @author  Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */

use Timber\Timber;

if ( ! class_exists( 'Timber' ) ) {
	echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';
	return;
}

$context = Timber::context();

$context['posts'] = Timber::get_posts();
$context['found_posts'] = $wp_query->found_posts;
$context['pagination'] = Timber::get_pagination();
$context['title'] = __( 'Terroirs et découvertes', 'VacancesPourSeniors' );
$context['text']  = '<p>' . __( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ea voluptatibus debitis ipsa, accusamus recusandae delectus, hic deleniti ut dolorum voluptate nulla, ratione vitae! Vitae reiciendis inventore praesentium quo ut sed?', 'VacancesPourSeniors' ) . '</p>';

$context['action'] = 'ajax_load_discoveries';

$templates = array( 'pages/archive.html.twig' );

Timber::render( $templates, $context );
