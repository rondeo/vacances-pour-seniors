<?php
/**
 * Class Residence comfort
 *
 * PHP version 7
 *
 * @author  Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 * @package VacancesPourSeniors
 */

/**
 * Residence group tag class
 */
class ResidenceComfort {

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
		add_action( 'admin_head', array( $this, 'css' ) );

		add_filter( 'manage_edit-residence_comfort_columns', array( $this, 'add_custom_columns' ) );
		add_action( 'manage_residence_comfort_custom_column', array( $this, 'render_custom_columns' ), 10, 3 );
	}


	/**
	 * Register Custom Taxonomy
	 *
	 * @return void
	 */
	public function register_taxonomy() {

		$labels = array(
			'name'                       => _x( 'Les espaces de confort', 'Taxonomy General Name', 'VacancesPourSeniors' ),
			'singular_name'              => _x( 'Espace de confort', 'Taxonomy Singular Name', 'VacancesPourSeniors' ),
			'menu_name'                  => __( 'Espaces de confort', 'VacancesPourSeniors' ),
			'all_items'                  => __( 'All Items', 'VacancesPourSeniors' ),
			'parent_item'                => __( 'Parent Item', 'VacancesPourSeniors' ),
			'parent_item_colon'          => __( 'Parent Item:', 'VacancesPourSeniors' ),
			'new_item_name'              => __( 'New Item Name', 'VacancesPourSeniors' ),
			'add_new_item'               => __( 'Ajouter un nouvel Espace de confort', 'VacancesPourSeniors' ),
			'edit_item'                  => __( 'Edit Item', 'VacancesPourSeniors' ),
			'update_item'                => __( 'Update Item', 'VacancesPourSeniors' ),
			'view_item'                  => __( 'View Item', 'VacancesPourSeniors' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'VacancesPourSeniors' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'VacancesPourSeniors' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'VacancesPourSeniors' ),
			'popular_items'              => __( 'Popular Items', 'VacancesPourSeniors' ),
			'search_items'               => __( 'Search Items', 'VacancesPourSeniors' ),
			'not_found'                  => __( 'Aucun espace de confort n\'a été trouvé', 'VacancesPourSeniors' ),
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
			'update_count_callback' => '_update_post_term_count',
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => true,
			'show_tagcloud'      => true,
			'show_in_quick_edit' => false,
			'show_admin_column'  => true,
			'show_in_rest'       => true,
		);
		register_taxonomy( 'residence_comfort', array( 'residence' ), $args );
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

			if ( 'name' === $key ) {
				$new_columns['pictogram'] = __( 'Pictogramme', 'VacancesPourSeniors' );
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
			case 'pictogram':
				$pictogram = get_field( 'pictogram', 'residence_comfort_' . $term_id );

				if ( is_array( $pictogram ) ) {
					echo '<a href="' . get_edit_term_link( $term_id, 'residence_comfort' ) . '">';
					echo '<img src="' . $pictogram['sizes']['thumbnail'] . '" alt="' . $pictogram['alt'] . '" width="50" height="50">';
					echo '</a>';
				} else {
					echo '—';
				}

				break;

			default:
				break;
		}
	}


	/**
	 * CSS
	 */
	public function css() {
		$current_screen = get_current_screen();

		if ( 'residence_comfort' !== $current_screen->taxonomy && 'edit-tags' !== $current_screen->base ) {
			return false;
		}

		if ( ! is_admin() ) {
			return false;
		}

		?>
		<style>
			.fixed .column-pictogram {
				vertical-align: middle;
			}

			.column-pictogram a {
				display: block;
			}
			.column-pictogram a img {
				display: inline-block;
				vertical-align: middle;
				width: 50px;
				height: 50px;
				object-fit: contain;
				object-position: center;
			}
		</style>
		<?php

		return true;
	}
}
