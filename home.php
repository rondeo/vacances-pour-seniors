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

$context['posts']      = Timber::get_posts();
$context['pagination'] = Timber::get_pagination();
$context['node_type']  = 'blog';
$context['body_class'] = 'index';
$context['title']      = __( 'Blog' );
$context['text']       = '<p>' . __( 'Vestibulum viverra nibh dignissim ex iaculis, sit amet bibendum enim varius', 'VacancesPourSeniors' ) . '</p>';

$templates = array( 'pages/archive.html.twig' );

Timber::render( $templates, $context );
