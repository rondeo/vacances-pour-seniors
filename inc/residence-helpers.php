<?php
/**
 * Vacances Pour Seniors helpers
 *
 * @package VacancesPourSeniors
 */

/**
 * Get the residence type term object
 *
 * @param int $residence_id The residence id.
 * @return WP_Term object
 */
function get_the_residence_type( $residence_id = null ) {
	return get_the_terms( $residence_id, 'type' );
}


/**
 * Get the residence type name
 *
 * @param int $residence_id The residence id.
 * @return string The type name.
 */
function get_the_residence_type_name( $residence_id = null ) {
	return get_the_residence_type( $residence_id )->name;
}


/**
 * Get the residence group term object
 *
 * @param int $residence_id The residence id.
 * @return WP_Term object
 */
function get_the_residence_group( $residence_id = null ) {
	return get_field( 'residence', $residence_id )['group'];
}


/**
 * Get the residence group name
 *
 * @param int $residence_id The residence id.
 * @return str The group name.
 */
function get_the_residence_group_name( $residence_id = null ) {
	return get_the_residence_group( $residence_id )->name;
}


/**
 * Get the residence status term object
 *
 * @param int $residence_id THe residence id.
 * @return WP_Term object
 */
function get_the_residence_status( $residence_id = null ) {
	return get_field( 'residence', $residence_id )['status'];
}


/**
 * Get the residence status name
 *
 * @param int $residence_id The residence id.
 */
function get_the_residence_status_name( $residence_id = null ) {
	return get_the_residence_status( $residence_id )->name;
}


/**
 * Get the residence thumbnail
 *
 * @param int $residence_id The residence ID.
 * @return mixed
 */
function get_the_residence_thumbnail( $residence_id = null ) {
	if ( ! $residence_id ) {
		$residence_id = (int) get_the_ID();
	}

	$gallery = get_field( 'gallery', $residence_id );

	if ( is_array( $gallery ) ) {
		return $gallery[0];
	}

	return false;
}


/**
 * Get the residence code
 *
 * @param int $residence_id The residence ID (default null).
 * @return mixed
 */
function get_the_residence_code( $residence_id = null ) {
	if ( ! $residence_id ) {
		$residence_id = (int) get_the_ID();
	}

	// Grab the city from residence.
	$city = get_the_residence_city( $residence_id );

	if ( null === $city ) {
		return false;
	}

	// Grab the department attach to the city.
	$department = get_the_city_department( 'city_' . $city->term_id );

	if ( null === $department ) {
		return false;
	}

	return get_field( 'code', 'department_' . $department->term_id );
}


/**
 * Get the residence city WP_Term object
 *
 * @param int $residence_id The residence id.
 * @return WP_Term object
 */
function get_the_residence_city( $residence_id = null ) {
	if ( ! $residence_id ) {
		$residence_id = (int) get_the_ID();
	}

	return get_the_terms( $residence_id, 'city' )[0];
}


/**
 * Get the residence city name
 *
 * @param int $residence_id The residence id.
 */
function get_the_residence_city_name( $residence_id = null ) {
	return get_the_residence_city( $residence_id )->name;
}


/**
 * Get the residence region name
 *
 *  @param int $residence_id The post ID.
 *  @return str|bool The region name.
 */
function get_the_residence_region( $residence_id = null ) {
	if ( ! $residence_id ) {
		$residence_id = (int) get_the_ID();
	}

	$city = get_the_residence_city( $residence_id );

	if ( null === $city ) {
		return false;
	}

	return get_the_city_region( 'city_' . $city->term_id )->name;
}


/**
 * Get the city department WP_Term object
 *
 * Get the ACF department field for a given city.
 *
 * @param str $id The city id (`city_{ term_id }`).
 * @return WP_Term object
 */
function get_the_city_department( $id ) {
	if ( null === $id ) {
		return false;
	}

	return get_field( 'department', $id );
}


/**
 * Get the city region WP_Term object
 *
 * Get the ACF region field for a given city.
 *
 * @param str $id The city id (`city_{ term_id }`).
 * @return WP_Term object
 */
function get_the_city_region( $id = null ) {
	if ( null === $id ) {
		return false;
	}

	return get_field( 'region', $id );
}
