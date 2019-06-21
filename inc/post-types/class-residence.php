<?php
/**
 * Class Residence
 *
 * PHP version 7
 *
 * @author  Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 * @package VacancesPourSeniors
 */

/**
 * Residence class
 *
 * @file   inc/post-types/class-residence.php
 * @author Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */
class Residence {

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

		$this->register_post_type();

		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'admin_head', array( $this, 'css' ) );

		add_filter( 'dashboard_glance_items', array( $this, 'at_a_glance' ) );

		add_filter( 'manage_residence_posts_columns', array( $this, 'add_custom_columns' ) );
		add_action( 'manage_residence_posts_custom_column', array( $this, 'render_custom_columns' ), 10, 2 );

		add_action( 'wp_ajax_nopriv_ajax_load_residences', array( $this, 'ajax_load_residences' ) );
		add_action( 'wp_ajax_ajax_load_residences', array( $this, 'ajax_load_residences' ) );

		// add_action( 'quick_edit_custom_box', array( $this, 'add_quick_edit' ), 10, 2 );
		// add_action( 'save_post_residence', array( $this, 'save_quick_edit' ), 10, 3 );

		// add_filter( 'pre_get_posts', array( $this, 'pre_get_residences' ) );
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
			case 'taxonomy-residence_type':
				$args = array(
					'taxonomy'   => 'residence_type',
					'hide_empty' => false,
				);
				$context = Timber::get_context();

				$context['options'] = Timber::get_terms( $args );
				$context['title'] = __( 'Type', 'VacancesPourSeniors' );
				$context['name']  = $column_name;
				$context['type']  = 'select';

				Timber::render( 'partials/custom-column.html.twig', $context );

				break;

			case 'taxonomy-residence_group':
				$args = array(
					'taxonomy'   => 'residence_group',
					'hide_empty' => false,
				);

				$context = Timber::get_context();

				$context['options'] = Timber::get_terms( $args );
				$context['title']   = __( 'Groupes', 'VacancesPourSeniors' );
				$context['name']    = $column_name;
				$context['type']    = 'select';

				Timber::render( 'partials/custom-column.html.twig', $context );

				break;

			case 'taxonomy-city':
				$args = array(
					'taxonomy'   => 'city',
					'hide_empty' => false,
				);

				$context = Timber::get_context();

				$context['options'] = Timber::get_terms( $args );
				$context['title']   = __( 'Villes', 'VacancesPourSeniors' );
				$context['name']    = $column_name;
				$context['type']    = 'select';

				Timber::render( 'partials/custom-column.html.twig', $context );

				break;

			case 'taxonomy-residence_status':
				$args = array(
					'taxonomy'   => 'residence_status',
					'hide_empty' => false,
				);

				$context = Timber::get_context();

				$context['options'] = Timber::get_terms( $args );
				$context['title']   = __( 'Status', 'VacancesPourSeniors' );
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
	public function save_quick_edit( $post_id, $post, $update ) {
		if ( empty( $_POST ) ) {
			return $term_id;
		}

		// residence_status

		// Type.
		if ( isset( $_POST['taxonomy-residence_type'] ) && ! empty( $_POST['taxonomy-residence_type'] ) ) {
			wp_set_post_terms( $post_id, array( intval( $_POST['taxonomy-residence_type'] ) ), 'residence_type' );
		}

		// Group.
		if ( isset( $_POST['taxonomy-residence_group'] ) && ! empty( $_POST['taxonomy-residence_group'] ) ) {
			wp_set_post_terms( $post_id, array( intval( $_POST['taxonomy-residence_group'] ) ), 'residence_group' );
		}

		// Status.
		if ( isset( $_POST['taxonomy-residence_status'] ) && ! empty( $_POST['taxonomy-residence_status'] ) ) {
			wp_set_post_terms( $post_id, array( intval( $_POST['taxonomy-residence_status'] ) ), 'residence_status' );
		}

		// City.
		if ( isset( $_POST['taxonomy-city'] ) && ! empty( $_POST['taxonomy-city'] ) ) {
			wp_set_post_terms( $post_id, array( intval( $_POST['taxonomy-city'] ) ), 'city' );
		}
	}


	/**
	 * Register Custom Post Type
	 */
	public function register_post_type() {
		$labels = array(
			'name'                  => _x( 'Résidences', 'Post Type General Name', 'VacancesPourSeniors' ),
			'singular_name'         => _x( 'Résidence', 'Post Type Singular Name', 'VacancesPourSeniors' ),
			'menu_name'             => __( 'Résidences', 'VacancesPourSeniors' ),
			'name_admin_bar'        => __( 'Résidence', 'VacancesPourSeniors' ),
			'archives'              => __( 'Archive des résidences', 'VacancesPourSeniors' ),
			'attributes'            => __( 'Item Attributes', 'VacancesPourSeniors' ),
			'parent_item_colon'     => __( 'Parent Item:', 'VacancesPourSeniors' ),
			'all_items'             => __( 'Toutes les résidences', 'VacancesPourSeniors' ),
			'add_new_item'          => __( 'Add New Item', 'VacancesPourSeniors' ),
			'add_new'               => __( 'Ajouter une résidence', 'VacancesPourSeniors' ),
			'new_item'              => __( 'New Item', 'VacancesPourSeniors' ),
			'edit_item'             => __( 'Edit Item', 'VacancesPourSeniors' ),
			'update_item'           => __( 'Update Item', 'VacancesPourSeniors' ),
			'view_item'             => __( 'Voir la résidence', 'VacancesPourSeniors' ),
			'view_items'            => __( 'Voir les résidences', 'VacancesPourSeniors' ),
			'search_items'          => __( 'Search Item', 'VacancesPourSeniors' ),
			'not_found'             => __( 'Aucune résidence n\'a été trouvée', 'VacancesPourSeniors' ),
			'not_found_in_trash'    => __( 'Aucune résidence n\'a été trouvée dans la Corbeille', 'VacancesPourSeniors' ),
			'featured_image'        => __( 'Featured Image', 'VacancesPourSeniors' ),
			'set_featured_image'    => __( 'Set featured image', 'VacancesPourSeniors' ),
			'remove_featured_image' => __( 'Remove featured image', 'VacancesPourSeniors' ),
			'use_featured_image'    => __( 'Use as featured image', 'VacancesPourSeniors' ),
			'insert_into_item'      => __( 'Insert into item', 'VacancesPourSeniors' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'VacancesPourSeniors' ),
			'items_list'            => __( 'Items list', 'VacancesPourSeniors' ),
			'items_list_navigation' => __( 'Items list navigation', 'VacancesPourSeniors' ),
			'filter_items_list'     => __( 'Filter items list', 'VacancesPourSeniors' ),
		);

		$rewrite = array(
			'slug'       => __( 'residences', 'VacancesPourSeniors' ),
			'with_front' => true,
			'pages'      => true,
			'feeds'      => true,
		);

		$args = array(
			'label'                 => __( 'Résidence', 'VacancesPourSeniors' ),
			'description'           => __( 'Description de la Résidence', 'VacancesPourSeniors' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'comments', 'revisions' ),
			'taxonomies'            => array( 'residence_type', 'residence_group', 'residence_status', 'residence_comfort', 'residence_social_life', 'residence_accommodation', 'city' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-admin-multisite',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'post',
			'rewrite'               => $rewrite,
			'show_in_rest'          => true,
		);
		register_post_type( 'residence', $args );
	}


	/**
	 * "At a glance" items (dashboard widget): add the testimony.
	 *
	 * @param arr $items Items.
	 */
	public function at_a_glance( $items ) {
		$post_type   = 'residence';
		$post_status = 'publish';
		$object      = get_post_type_object( $post_type );

		$num_posts = wp_count_posts( $post_type );
		if ( ! $num_posts || ! isset( $num_posts->{ $post_status } ) || 0 === (int) $num_posts->{ $post_status } ) {
			return $items;
		}

		$text = sprintf(
			_n( '%1$s %4$s%2$s', '%1$s %4$s%3$s', $num_posts->{ $post_status } ),
			number_format_i18n( $num_posts->{ $post_status } ),
			strtolower( $object->labels->singular_name ),
			strtolower( $object->labels->name ),
			'pending' === $post_status ? 'Pending ' : ''
		);

		if ( current_user_can( $object->cap->edit_posts ) ) {
			$items[] = sprintf(
				'<a class="%1$s-count" href="edit.php?post_status=%2$s&post_type=%1$s">%3$s</a>',
				$post_type,
				$post_status,
				$text
			);
		} else {
			$items[] = sprintf( '<span class="%1$s-count">%s</span>', $text );
		}

		return $items;
	}

	/**
	 * CSS
	 */
	public function css() {
		global $typenow; ?>

		<style>
			#dashboard_right_now .residence-count:before { content: "\f541"; }
		</style>

		<?php

		if ( 'residence' !== $typenow ) return false;

		?>
		<style>
			.fixed .column-thumbnail {
				vertical-align: top;
				width: 80px;
			}

			.column-thumbnail a {
				display: block;
			}
			.column-thumbnail a img {
				display: inline-block;
				vertical-align: middle;
				width: 80px;
				height: 80px;
				object-fit: contain;
				object-position: center;
			}
		</style>
		<?php

		return true;
	}


	/**
	 * Add custom columns
	 *
	 * @param arr $columns Array of columns.
	 */
	public function add_custom_columns( $columns ) {

		$new_columns = array();

		foreach ( $columns as $key => $value ) {
			if ( 'title' === $key ) {
				$new_columns['thumbnail'] = __( 'Thumbnail' );
			}

			$new_columns[ $key ] = $value;
		}
		return $new_columns;
	}


	/**
	 * Render custom columns
	 *
	 * @param str $column_name The column name.
	 * @param int $post_id The ID of the post.
	 */
	public function render_custom_columns( $column_name, $post_id ) {
		switch ( $column_name ) {
			case 'thumbnail':
				$thumbnail = get_the_residence_thumbnail( $post_id );

				// var_dump( $thumbnail );

				if ( is_array( $thumbnail ) ) {
					echo '<a href="' . get_edit_post_link( $post_id ) . '">';
					echo '<img src="' . $thumbnail['sizes']['thumbnail'] . '" alt="' . $thumbnail['alt'] . '" width="150" height="150">';
					echo '</a>';
				} else {
					echo '—';
				}

				break;
		}
	}

	function pre_get_residences( $query ) {
		if ( is_admin() ) {
			return false;
		}

		if ( ! $query->is_main_query() ) {
			return false;
		}

	    if ( $query->is_search ) {
	        $query->set( 'post_type', array( 'residence' ) );
	    }
		return $query;
	}

	/**
	 * Load posts with AJAX request.
	 */
	public function ajax_load_residences() {
		$offset = isset( $_GET['offset'] ) ? $_GET['offset'] : 0;
		$posts_per_page = isset( $_GET['posts_per_page'] ) ? $_GET['posts_per_page'] : 3;

		$args = array(
			'post_type'      => 'residence',
			'posts_per_page' => $posts_per_page,
			'offset'         => $offset,
			'post_status'    => 'publish',
		);

		$context = Timber::get_context();
		$context['posts'] = Timber::get_posts( $args );

		Timber::render( 'partials/archive-listing.html.twig', $context );

		wp_die();
	}
}
