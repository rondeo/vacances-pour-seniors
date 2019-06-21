<?php
/**
 * Contact
 *
 * @category Contact
 * @package  CardinalCapital
 * @author   Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/19h47/cardinalcapital
 */

/**
 * Contact
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function customize_contact( $wp_customize ) {

	// Add contact section.
	$wp_customize->add_section(
		'contact',
		array(
			'title'       => __( 'Contact' ),
			'description' => __( 'Contact settings' ),
		)
	);

	// Facebook.
	$wp_customize->add_setting(
		'facebook',
		array(
			'type'      => 'option',
			'transport' => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'facebook',
		array(
			'label'       => __( 'Facebook' ),
			'description' => __( 'Indiquer l\'URL du compte Facebook' ),
			'section'     => 'contact',
			'settings'    => 'facebook',
		)
	);

	// Linkedin.
	$wp_customize->add_setting(
		'linkedin',
		array(
			'type'      => 'option',
			'transport' => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'linkedin',
		array(
			'label'       => __( 'Linkedin' ),
			'description' => __( 'Linkedin URL' ),
			'section'     => 'contact',
			'settings'    => 'linkedin',
		)
	);

	// Phone number.
	$wp_customize->add_setting(
		'phone_number',
		array(
			'type'      => 'option',
			'transport' => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'phone_number',
		array(
			'label'       => __( 'Phone number' ),
			'description' => __( 'Phone number' ),
			'section'     => 'contact',
			'settings'    => 'phone_number',
		)
	);

	// Email.
	$wp_customize->add_setting(
		'email',
		array(
			'type'      => 'option',
			'transport' => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'email',
		array(
			'label'       => __( 'Email' ),
			'description' => __( 'Email address' ),
			'section'     => 'contact',
			'settings'    => 'email',
		)
	);
}
