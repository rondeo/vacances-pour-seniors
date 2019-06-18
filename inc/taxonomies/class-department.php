<?php
/**
 * Class Department
 *
 * PHP version 7
 *
 * @author  Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 * @package VacancesPourSeniors
 */

/**
 * Department
 */
class Department {

	/**
	 * The version of the theme.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $version    The current version of this theme.
	 */
	private $theme_version;

	private $residences;


	/**
	 * Construct function
	 *
	 * @param str $theme_version The theme version.
	 * @access public
	 */
	public function __construct( $theme_version ) {
		$this->theme_version = $theme_version;
		$this->residences = $this->get_residences();

		add_action( 'init', array( $this, 'register_taxonomy' ) );

		add_filter( 'manage_edit-department_columns', array( $this, 'add_custom_columns' ) );
		add_filter( 'manage_edit-department_sortable_columns', array( $this, 'sortable_code_column' ) );

		add_action( 'manage_department_custom_column', array( $this, 'render_custom_columns' ), 10, 3 );
	}

	function get_residences() {
		$args = array(
			'post_type'   => 'residence',
			'numberposts' => -1,
		);
		$residences = get_posts( $args );

		return $residences;
	}

	function sortable_code_column( $columns ) {
    	$columns['code'] = 'code';

    	return $columns;
	}

	/**
	 *
	 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_$taxonomy_id_columns
	 */
	function add_custom_columns( $columns ) {
		$new_columns = array();

		foreach( $columns as $key => $value ) {

			if ( $key === 'posts' ) {
				$new_columns['code']       = __( 'Code Insee', 'VacancesPourSeniors' );
				$new_columns['cities'] = __( 'Villes', 'VacancesPourSeniors' );
				$new_columns['residences'] = __( 'Résidences', 'VacancesPourSeniors' );
			}

			$new_columns[ $key ] = $value;
		}
		return $new_columns;
	}


	/**
	 *
	 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_$taxonomy_id_columns
	 */
	function render_custom_columns( $value, $column_name, $term_id ) {

		switch ( $column_name ) {
			case 'code':
				$code = get_field( 'code', 'department_' . $term_id );

				if ( null !== $code ) {
					echo $code;
				} else {
					echo '—';
				}

				break;

			case 'cities':
				$terms = get_terms( 'city' );
				$cities = array();

				foreach( $terms as $term ) {
					$department = get_field( 'department', 'city_' . $term->term_id );

					if ( is_object( $department ) && ($department->term_id === $term_id) ) {
						array_push(
							$cities,
							'<a href="' . get_edit_term_link( $term->term_id, 'city' ) . '">' . $term->name . '</a>'
						);
					}
				}

				if ( ! empty( $cities ) ) {
					echo implode( ', ', $cities );
				} else {
					echo '—';
				}

				break;

			case 'residences':
				$cities = array();

				foreach( $this->residences as $residence ) {

					$city = get_the_city( $residence->ID );
					$department = null;

					if ( is_object( $city ) ) {
						$department = get_field( 'department', 'city_' . $city->term_id );
					}

					if ( is_object( $department ) && ( $department->term_id === $term_id ) ) {
						array_push(
							$cities,
							'<a href="' . get_edit_post_link( $residence->ID ) . '">' . $residence->post_title . '</a>'
						);
					}
				}

				if ( ! empty( $cities ) ) {
					echo implode( ', ', $cities );
				} else {
					echo '—';
				}

				break;

			default:
				break;
		}
	}


	/**
	 * Register Custom Taxonomy
	 *
	 * @return void
	 */
	public function register_taxonomy() {

		$labels = array(
			'name'                       => _x( 'Départments', 'Taxonomy General Name', 'VacancesPourSeniors' ),
			'singular_name'              => _x( 'Département', 'Taxonomy Singular Name', 'VacancesPourSeniors' ),
			'menu_name'                  => __( 'Département', 'VacancesPourSeniors' ),
			'all_items'                  => __( 'All Items', 'VacancesPourSeniors' ),
			'parent_item'                => __( 'Parent Item', 'VacancesPourSeniors' ),
			'parent_item_colon'          => __( 'Parent Item:', 'VacancesPourSeniors' ),
			'new_item_name'              => __( 'New Item Name', 'VacancesPourSeniors' ),
			'add_new_item'               => __( 'Ajouter une nouvelle région', 'VacancesPourSeniors' ),
			'edit_item'                  => __( 'Edit Item', 'VacancesPourSeniors' ),
			'update_item'                => __( 'Mettre à jour la région', 'VacancesPourSeniors' ),
			'view_item'                  => __( 'View Item', 'VacancesPourSeniors' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'VacancesPourSeniors' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'VacancesPourSeniors' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'VacancesPourSeniors' ),
			'popular_items'              => __( 'Popular Items', 'VacancesPourSeniors' ),
			'search_items'               => __( 'Search Items', 'VacancesPourSeniors' ),
			'not_found'                  => __( 'Aucune région n\'a été trouvée', 'VacancesPourSeniors' ),
			'no_terms'                   => __( 'No items', 'VacancesPourSeniors' ),
			'items_list'                 => __( 'Items list', 'VacancesPourSeniors' ),
			'items_list_navigation'      => __( 'Items list navigation', 'VacancesPourSeniors' ),
		);

		$args = array(
			'labels'             => $labels,
			'hierarchical'       => false,
			'meta_box_cb'        => false,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => true,
			'show_tagcloud'      => true,
			'show_in_quick_edit' => false,
			'show_admin_column'  => true,
			'show_in_rest'       => true,
		);
		register_taxonomy( 'department', array( 'post' ), $args );
	}
}
