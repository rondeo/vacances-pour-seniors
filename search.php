<?php
/**
 * Search
 *
 * @package VacancesPourSeniors
 * @author Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */

$context = Timber::get_context();
$context['title'] = sprintf( _n( '%d Résultat pour la recherche', '%d Résultats pour la recherche', $wp_query->found_posts, 'VacancesPourSeniors' ), $wp_query->found_posts, false ) . ' ' . get_search_query();

$context['posts'] = Timber::get_posts();

$templates = array( 'pages/search.html.twig' );

Timber::render( $templates, $context );
