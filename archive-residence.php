<?php
/**
 * Archive: residence
 *
 * @package VacancesPourSeniors
 * @file    archive-residence.php
 * @author  Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */

use Timber\Timber;

if ( ! class_exists( 'Timber' ) ) {
	echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';
	return;
}

$context = Timber::context();

$context['posts']       = Timber::get_posts();
$context['found_posts'] = $wp_query->found_posts;
$context['pagination']  = Timber::get_pagination();

$context['action'] = 'ajax_load_residences';
$context['title']  = __( 'Les résidences', 'VacancesPourSeniors' );
$context['text']   = '<p>' . __( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt, exercitationem, suscipit. Veniam deleniti sunt tempora rem asperiores tempore molestias porro numquam maiores officiis tenetur, modi reiciendis ut, quae, laboriosam reprehenderit!', 'VacancesPourSeniors' ) . '</p>';

$templates = array( 'pages/archive.html.twig' );

Timber::render( $templates, $context );
