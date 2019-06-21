<?php
/**
 * Link
 *
 * PHP version 7.2.10
 *
 * @category Link
 * @package  Cardinal
 * @author   Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/19h47/cardinalcapital
 */

/**
 * Link
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function cardinalcapital_customize_link( $wp_customize ) {

	/**
	 * Add contact section
	 */
	$wp_customize->add_section(
		'link',
		array(
			'capability'  => 'edit_theme_options',
			'description' => __( 'Pages settings' ),
			'title'       => __( 'Pages links' ),
		)
	);

	// Terms.
	$wp_customize->add_setting(
		'terms',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'terms',
		array(
			'description' => __( 'Terms page link' ),
			'label'       => __( 'Terms' ),
			'section'     => 'link',
			'type'        => 'dropdown-pages',
		)
	);

	// Privacy.
	$wp_customize->add_setting(
		'privacy',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'privacy',
		array(
			'description' => __( 'Privacy page link' ),
			'label'       => __( 'Privacy' ),
			'section'     => 'link',
			'type'        => 'dropdown-pages',
		)
	);


	// Cookie Policy.
	$wp_customize->add_setting(
		'cookie_policy',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'cookie_policy',
		array(
			'description' => __( 'Cookie policy page link' ),
			'label'       => __( 'Cookie policy' ),
			'section'     => 'link',
			'type'        => 'dropdown-pages',
		)
	);
}
