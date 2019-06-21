<?php
/**
 * Index
 *
 * @package VacancesPourSeniors
 * @file    404.php
 * @author  Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */

use Timber\Timber;

if ( ! class_exists( 'Timber' ) ) {
	echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';
	return;
}

$context = Timber::context();

$context['post']       = array( 'title' => '404', 'text' => __( 'Oops! Cette page ne peut pas être trouvée.', 'VacancesPourSeniors' ) );
$context['node_type']  = 'default-page';
$context['body_class'] = 'index';

$templates = array( 'index.html.twig' );

Timber::render( $templates, $context );
