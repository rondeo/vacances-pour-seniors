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
	return get_the_terms( $residence_id, 'residence_type' )[0];
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
	$group = get_the_residence_group( $residence_id );

	if ( null === $group ) {
		return false;
	}

	return $group->name;
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
 * Get the residence comforts
 *
 * Get the residence comforts term and convert it to a Timber object.
 *
 * @param int $residence_id The residence ID.
 *
 * @see https://timber.github.io/docs/reference/timber-helper/
 */
function get_the_residence_comforts( $residence_id = null ) {
	$terms = get_the_terms( $residence_id, 'residence_comfort' );
	$comforts = array();

	for ( $i = 0; $i < count( $terms ); $i++ ) {
		array_push(
			$comforts,
			Timber\Helper::convert_wp_object( $terms[ $i ] )
		);
	}

	return $comforts;
}


/**
 * Get the residence social lives
 */
function get_the_residence_social_lives( $residence_id = null ) {
	return get_the_terms( $residence_id, 'residence_social_life' );
}


/**
 * Get the residence accommodations
 */
function get_the_residence_accommodations( $residence_id = null ) {
	return get_the_terms( $residence_id, 'residence_accommodation' );
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
 * Get the code
 *
 * @param int $post_id The post ID (default null).
 * @return mixed
 */
function get_the_code( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = (int) get_the_ID();
	}

	// Grab the city from residence.
	$city = get_the_city( $post_id );

	if ( null === $city ) {
		return false;
	}

	// Grab the department attach to the city.
	$department = get_the_city_department( 'city_' . $city->term_id );

	if ( null === $department ) {
		return false;
	}

	if ( false === $department ) {
		return false;
	}

	return get_field( 'code', 'department_' . $department->term_id );
}


function get_the_department_code( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = (int) get_the_ID();
	}

	$department = get_the_terms( $post_id, 'department' )[0];

	if ( null === $department ) {
		return false;
	}

	return get_field( 'code', 'department_' . $department->term_id );
}




/**
 * Get the city WP_Term object
 *
 * @param int $post_id The post ID.
 * @return WP_Term object
 */
function get_the_city( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = (int) get_the_ID();
	}

	return get_the_terms( $post_id, 'city' )[0];
}


/**
 * Get the city name
 *
 * @param int $post_id The post ID.
 */
function get_the_city_name( $post_id = null ) {
	$city = get_the_city( $post_id );

	if ( ! is_object( $city ) ) {
		return false;
	}

	return $city->name;
}


/**
 * Get the region
 *
 *  @param int $post_id The post ID.
 *  @return str|bool The region name.
 */
function get_the_region( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = (int) get_the_ID();
	}

	$city = get_the_city( $post_id );

	if ( null === $city ) {
		return false;
	}

	$region = get_the_city_region( 'city_' . $city->term_id );

	if ( null === $region ) {
		return false;
	}

	return $region;
}


function get_the_region_name( $post_id ) {
	$region = get_the_region( $post_id );

	if ( ! is_object( $region ) ) {
		return false;
	}
	return $region->name;
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


/**
 * Get average ratings residence
 *
 * @param  int $residence_id The residence ID.
 * @return arr $ratings
 */
function get_average_ratings_residence( $residence_id ) {
	$comments = get_approved_comments( $residence_id );
	$average_ratings = array();

	if ( $comments ) {

		foreach ( RATINGS as $rating ) {
			$total = 0;
			$i = 0;

			foreach( $comments as $comment ) {
				$rate = get_comment_meta( $comment->comment_ID, $rating['id'], true );

				// var_dump( $rate );

				if ( isset( $rate ) && '' !== $rate ) {
					$i++;
					$total += $rate;
				}
			}

			$average_ratings[ $rating['id'] ] = array(
				'note'  => $total / $i,
				'label' => $rating['label']
			);
		}

	} else {
		return $average_ratings;
	}

	return $average_ratings;
}
