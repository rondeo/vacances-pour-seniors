<?php
/**
 * Class Residence group
 *
 * PHP version 7
 *
 * @author  Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 * @package VacancesPourSeniors
 */

/**
 * Residence group tag class
 */
class ResidenceGroup {

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
	}


	/**
	 * Register Custom Taxonomy
	 *
	 * @return void
	 */
	public function register_taxonomy() {

		$labels = array(
			'name'                       => _x( 'Groupes', 'Taxonomy General Name', 'VacancesPourSeniors' ),
			'singular_name'              => _x( 'Groupe', 'Taxonomy Singular Name', 'VacancesPourSeniors' ),
			'menu_name'                  => __( 'Groupes', 'VacancesPourSeniors' ),
			'all_items'                  => __( 'All Items', 'VacancesPourSeniors' ),
			'parent_item'                => __( 'Parent Item', 'VacancesPourSeniors' ),
			'parent_item_colon'          => __( 'Parent Item:', 'VacancesPourSeniors' ),
			'new_item_name'              => __( 'New Item Name', 'VacancesPourSeniors' ),
			'add_new_item'               => __( 'Add New Item', 'VacancesPourSeniors' ),
			'edit_item'                  => __( 'Edit Item', 'VacancesPourSeniors' ),
			'update_item'                => __( 'Update Item', 'VacancesPourSeniors' ),
			'view_item'                  => __( 'View Item', 'VacancesPourSeniors' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'VacancesPourSeniors' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'VacancesPourSeniors' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'VacancesPourSeniors' ),
			'popular_items'              => __( 'Popular Items', 'VacancesPourSeniors' ),
			'search_items'               => __( 'Search Items', 'VacancesPourSeniors' ),
			'not_found'                  => __( 'Not Found', 'VacancesPourSeniors' ),
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
		register_taxonomy( 'residence_group', array( 'residence' ), $args );
	}
}
