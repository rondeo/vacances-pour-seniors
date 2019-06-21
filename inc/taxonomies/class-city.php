<?php
/**
 * Class City
 *
 * PHP version 7
 *
 * @author  Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 * @package VacancesPourSeniors
 */

/**
 * City
 */
class City {

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

		add_filter( 'manage_edit-city_columns', array( $this, 'add_custom_columns' ) );
		add_action( 'manage_city_custom_column', array( $this, 'render_custom_columns' ), 10, 3 );

		add_action( 'quick_edit_custom_box', array( $this, 'add_quick_edit' ), 10, 2 );
		add_action( 'edited_city', array( $this, 'save_quick_edit' ), 10, 2 );
	}


	/**
	 * Add quick edit
	 *
	 * @param str $column_name Name of the column to edit.
	 * @param str $post_type   The taxonomy name, if any.
	 *
	 * @access public
	 * @see https://developer.wordpress.org/reference/hooks/quick_edit_custom_box/
	 */
	public function add_quick_edit( $column_name, $post_type ) {
		switch ( $column_name ) {
			case 'postal_code':
				$context = Timber::get_context();

				$context['title'] = __( 'Code postal', 'VacancesPourSeniors' );
				$context['name']  = $column_name;
				$context['type']  = 'input';

				Timber::render( 'partials/custom-column.html.twig', $context );

				break;

			case 'department':
				$args = array(
					'taxonomy'   => 'department',
					'hide_empty' => false,
				);

				$context = Timber::get_context();

				$context['options'] = Timber::get_terms( $args );
				$context['title']   = __( 'Départements', 'VacancesPourSeniors' );
				$context['name']    = $column_name;
				$context['type']    = 'select';

				Timber::render( 'partials/custom-column.html.twig', $context );

				break;

			case 'region':
				$args = array(
					'taxonomy'   => 'region',
					'hide_empty' => false,
				);

				$context = Timber::get_context();

				$context['options'] = Timber::get_terms( $args );
				$context['title']   = __( 'Région', 'VacancesPourSeniors' );
				$context['name']    = $column_name;
				$context['type']    = 'select';

				Timber::render( 'partials/custom-column.html.twig', $context );

				break;
		}
	}


	/**
	 * Save quick edit
	 *
	 * @param int $term_id Term ID.
	 * @param int $tt_id   Term taxonomy ID.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/edited_taxonomy/
	 */
	public function save_quick_edit( $term_id, $tt_id ) {
		if ( empty( $_POST ) ) {
			return $term_id;
		}

		// Department.
		if ( isset( $_POST['department'] ) && ! empty( $_POST['department'] ) ) {
			update_field( 'field_5cf914d06ac1f', intval( $_POST['department'] ), 'city_' . $term_id );
		}

		// Region.
		if ( isset( $_POST['region'] ) && ! empty( $_POST['region'] ) ) {
			update_field( 'field_5cf8e06ba8a23', intval( $_POST['region'] ), 'city_' . $term_id );
		}

		// Postal code.
		if ( isset( $_POST['postal_code'] ) && ! empty( $_POST['postal_code'] ) ) {
			update_term_meta( $term_id, 'postal_code', sanitize_text_field( wp_unslash( $_POST['postal_code'] ) ) );
		}
	}


	/**
	 * Add custom columns
	 *
	 * @param arr $columns Columns.
	 *
	 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_$taxonomy_id_columns
	 */
	public function add_custom_columns( $columns ) {
		$new_columns = array();

		foreach ( $columns as $key => $value ) {

			if ( 'posts' === $key ) {
				$new_columns['postal_code'] = __( 'Code postal', 'VacancesPourSeniors' );
				$new_columns['department']  = __( 'Département', 'VacancesPourSeniors' );
				$new_columns['region']      = __( 'Région', 'VacancesPourSeniors' );
				$new_columns['country']     = __( 'Pays', 'VacancesPourSeniors' );
			}

			$new_columns[ $key ] = $value;
		}
		return $new_columns;
	}


	/**
	 * Render custom columns
	 *
	 * @param str $content     Blank string.
	 * @param str $column_name Name of the column.
	 * @param int $term_id     Term ID.
	 *
	 * @see https://core.trac.wordpress.org/browser/tags/5.2/src/wp-admin/includes/class-wp-terms-list-table.php#L604
	 */
	public function render_custom_columns( $content, $column_name, $term_id ) {

		switch ( $column_name ) {
			case 'postal_code':
				$postal_code = get_field( 'postal_code', 'city_' . $term_id );
				$department  = get_field( 'department', 'city_' . $term_id );

				if ( is_object( $department ) ) {
					$code = get_field( 'code', 'department_' . $department->term_id );

					if ( $code ) {
						echo '<input class="js-department-code" type="hidden" value="', esc_attr( $code ), '">';
					}
				}

				if ( $postal_code ) {
					echo esc_html( $postal_code );
				} else {
					echo '—';
				}

				break;

			case 'department':
				$department = get_field( 'department', 'city_' . $term_id );

				if ( is_object( $department ) ) {
					echo '<a href="', esc_url( get_edit_term_link( $department->term_id, 'department' ) ), '">', esc_html( $department->name ), '</a>';
				} else {
					echo '—';
				}

				break;

			case 'country':
				$country = get_field( 'country', 'city_' . $term_id );

				if ( $country ) {
					echo '<a href="', esc_url( get_edit_term_link( $country->term_id, 'country' ) ), '">', esc_html( $country->name ), '</a>';
				} else {
					echo '—';
				}

				break;

			case 'region':
				$region = get_field( 'region', 'city_' . $term_id );

				if ( is_object( $region ) ) {
					echo '<a href="', esc_url( get_edit_term_link( $region->term_id, 'region' ) ) . '">', esc_html( $region->name ), '</a>';
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
			'name'                       => _x( 'Villes', 'Taxonomy General Name', 'VacancesPourSeniors' ),
			'singular_name'              => _x( 'Ville', 'Taxonomy Singular Name', 'VacancesPourSeniors' ),
			'menu_name'                  => __( 'Villes', 'VacancesPourSeniors' ),
			'all_items'                  => __( 'All Items', 'VacancesPourSeniors' ),
			'parent_item'                => __( 'Parent Item', 'VacancesPourSeniors' ),
			'parent_item_colon'          => __( 'Parent Item:', 'VacancesPourSeniors' ),
			'new_item_name'              => __( 'New Item Name', 'VacancesPourSeniors' ),
			'add_new_item'               => __( 'Ajouter une nouvelle ville', 'VacancesPourSeniors' ),
			'edit_item'                  => __( 'Edit Item', 'VacancesPourSeniors' ),
			'update_item'                => __( 'Mettre à jour la ville', 'VacancesPourSeniors' ),
			'view_item'                  => __( 'View Item', 'VacancesPourSeniors' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'VacancesPourSeniors' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'VacancesPourSeniors' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'VacancesPourSeniors' ),
			'popular_items'              => __( 'Popular Items', 'VacancesPourSeniors' ),
			'search_items'               => __( 'Search Items', 'VacancesPourSeniors' ),
			'not_found'                  => __( 'Aucune ville n\'a été trouvée', 'VacancesPourSeniors' ),
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
		register_taxonomy( 'city', array( 'residence', 'discovery', 'event' ), $args );
	}
}
