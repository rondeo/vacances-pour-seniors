<?php
/**
 * Class Region
 *
 * PHP version 7
 *
 * @author  Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 * @package VacancesPourSeniors
 */

/**
 * Region
 */
class Region {

	/**
	 * The version of the theme.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $version    The current version of this theme.
	 */
	private $theme_version;


	/**
	 * Construct function
	 *
	 * @param str $theme_version The theme version.
	 * @access public
	 */
	public function __construct( $theme_version ) {
		$this->theme_version = $theme_version;

		add_action( 'init', array( $this, 'register_taxonomy' ) );

		add_filter( 'manage_edit-region_columns', array( $this, 'add_custom_columns' ) );
		add_action( 'manage_region_custom_column', array( $this, 'render_custom_columns' ), 10, 3 );
	}

	/**
	 *
	 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_$taxonomy_id_columns
	 */
	function add_custom_columns( $columns ) {
		$new_columns = array();

		foreach( $columns as $key => $value ) {

			$new_columns[ $key ] = $value;

			if ( $key === 'name' ) {
				$new_columns['city']  = __( 'Ville', 'VacancesPourSeniors' );
			}

		}
		return $new_columns;
	}


	/**
	 *
	 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_$taxonomy_id_columns
	 */
	function render_custom_columns( $value, $column_name, $term_id ) {

		switch ( $column_name ) {
			case 'city':
				$terms = get_terms( array( 'taxonomy' => 'city' ) );
				$cities = [];

				foreach( $terms as $term ) {

					$region = get_field( 'region', 'city_' . $term->term_id );

					if ( is_object( $region ) && $region->term_id === $term_id ) {
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
			'name'                       => _x( 'Régions', 'Taxonomy General Name', 'VacancesPourSeniors' ),
			'singular_name'              => _x( 'Région', 'Taxonomy Singular Name', 'VacancesPourSeniors' ),
			'menu_name'                  => __( 'Région', 'VacancesPourSeniors' ),
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
		register_taxonomy( 'region', array(), $args );
	}
}
