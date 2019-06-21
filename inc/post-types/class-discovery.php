<?php
/**
 * Class Discovery
 *
 * PHP version 7
 *
 * @author  Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 * @package VacancesPourSeniors
 */

/**
 * Discovery class
 *
 * @file   inc/post-types/class-dicovery.php
 * @author Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */
class Discovery {

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

		add_action( 'wp_ajax_nopriv_ajax_load_discoveries', array( $this, 'ajax_load_discoveries' ) );
		add_action( 'wp_ajax_ajax_load_discoveries', array( $this, 'ajax_load_discoveries' ) );
	}


	/**
	 * Register Custom Post Type
	 */
	public function register_post_type() {
		$labels = array(
			'name'                  => _x( 'Terroirs et découvertes', 'Post Type General Name', 'VacancesPourSeniors' ),
			'singular_name'         => _x( 'Terroirs et découvertes', 'Post Type Singular Name', 'VacancesPourSeniors' ),
			'menu_name'             => __( 'Terroirs et découvertes', 'VacancesPourSeniors' ),
			'name_admin_bar'        => __( 'Terroirs et découvertes', 'VacancesPourSeniors' ),
			'archives'              => __( 'Archive des Terroirs et découvertes', 'VacancesPourSeniors' ),
			'attributes'            => __( 'Item Attributes', 'VacancesPourSeniors' ),
			'parent_item_colon'     => __( 'Parent Item:', 'VacancesPourSeniors' ),
			'all_items'             => __( 'Tous les articles', 'VacancesPourSeniors' ),
			'add_new_item'          => __( 'Add New Item', 'VacancesPourSeniors' ),
			'add_new'               => __( 'Ajouter un article', 'VacancesPourSeniors' ),
			'new_item'              => __( 'New Item', 'VacancesPourSeniors' ),
			'edit_item'             => __( 'Edit Item', 'VacancesPourSeniors' ),
			'update_item'           => __( 'Update Item', 'VacancesPourSeniors' ),
			'view_item'             => __( 'View Item', 'VacancesPourSeniors' ),
			'view_items'            => __( 'View Items', 'VacancesPourSeniors' ),
			'search_items'          => __( 'Search Item', 'VacancesPourSeniors' ),
			'not_found'             => __( 'Aucun Terroirs et découvertes n\'a été trouvé', 'VacancesPourSeniors' ),
			'not_found_in_trash'    => __( 'Aucun Terroirs et découvertes n\'a été trouvé dans la Corbeille', 'VacancesPourSeniors' ),
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
			'slug'       => __( 'terroirs-et-decouvertes', 'VacancesPourSeniors' ),
			'with_front' => true,
			'pages'      => true,
			'feeds'      => true,
		);

		$args = array(
			'label'                 => __( 'Terroirs et découvertes', 'VacancesPourSeniors' ),
			'description'           => __( 'Description de l\'article', 'VacancesPourSeniors' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'taxonomies'            => array( 'city', 'department' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-location',
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
		register_post_type( 'discovery', $args );
	}


	/**
	 * "At a glance" items (dashboard widget): add the testimony.
	 *
	 * @param arr $items Items.
	 */
	public function at_a_glance( $items ) {
		$post_type   = 'discovery';
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
			#dashboard_right_now .dicovery-count:before { content: "\f488"; }
		</style>

		<?php

		if ( 'discovery' !== $typenow ) return false;

		?>
		<style></style>
		<?php

		return true;
	}

	/**
	 * Load posts with AJAX request.
	 */
	public function ajax_load_discoveries() {
		if ( ! isset( $_GET['nonce'] ) && ! wp_verify_nonce( sanitize_key( $_GET['nonce'] ), 'security' ) ) {
			return false;
		}

		$offset         = 0;
		$posts_per_page = 3;

		if ( isset( $_GET['offset'] ) ) {
			$offset = sanitize_text_field( wp_unslash( $_GET['offset'] ) );
		}

		if ( isset( $_GET['posts_per_page'] ) ) {
			$posts_per_page = sanitize_text_field( wp_unslash( $_GET['posts_per_page'] ) );
		}

		$args = array(
			'post_type'      => 'discovery',
			'posts_per_page' => $posts_per_page,
			'offset'         => $offset,
			'post_status'    => 'publish',
		);

		// Timber.
		$context          = Timber::get_context();
		$context['posts'] = Timber::get_posts( $args );

		Timber::render( 'partials/archive-listing.html.twig', $context );

		wp_die();
	}
}
