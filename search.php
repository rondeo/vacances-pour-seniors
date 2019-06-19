<?php
/**
 * Search
 *
 * @package VacancesPourSeniors
 * @author Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */
global $wp_query;
$number_results = $wp_query->found_posts;

$context = Timber::get_context();
$context['title'] = sprintf( _n( '%d Résultat pour la recherche', '%d Résultats pour la recherche', $number_results, 'pup' ), $number_results, false ) . ' ' . get_search_query();

$context['posts'] = Timber::get_posts();

$templates = array( 'pages/search.html.twig' );

Timber::render( $templates, $context );
