<?php
/**
 * Settings class
 *
 * @package VacancesPourSeniors
 */

/**
 * Class Settings
 */
class Settings {
	/**
	 * The unique identifier of this theme.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this theme.
	 */
	protected $theme_name;

	/**
	 * The version of the theme.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of this theme.
	 */
	protected $theme_version;

	/**
	 * Construct function
	 *
	 * @param str $theme_name    The theme name.
	 * @param str $theme_version The the version.
	 * @access public
	 */
	public function __construct( $theme_name, $theme_version ) {
		$this->theme_name    = $theme_name;
		$this->theme_version = $theme_version;

		add_action( 'admin_init', array( $this, 'settings_api_init' ) );
		add_action( 'init', array( $this, 'register_settings' ) );
	}

	/**
	 * Settings api init
	 *
	 * @access public
	 * @see https://codex.wordpress.org/Function_Reference/add_settings_field
	 */
	public function settings_api_init() {
		// Add the section to reading settings so we can add our fields to it.
		add_settings_section(
			'socials',
			'Socials',
			array( $this, 'socials_callback_function' ),
			'general'
		);

		// Add the field with the names and function to use for our new
		// settings, put it in our new section.
		add_settings_field(
			'twitter',
			'Twitter',
			array( $this, 'setting_callback_function' ),
			'general',
			'socials',
			array(
				'name'  => 'twitter',
				'label' => 'Twitter URL',
			)
		);

		add_settings_field(
			'linkedin',
			'LinkedIn',
			array( $this, 'setting_callback_function' ),
			'general',
			'socials',
			array(
				'name'  => 'linkedin',
				'label' => 'LinkedIn URL',
			)
		);

		add_settings_field(
			'email_public',
			'Public email',
			array( $this, 'setting_callback_function' ),
			'general',
			'socials',
			array(
				'name'  => 'email_public',
				'label' => 'Public email address',
			)
		);

		add_settings_field(
			'phone_public',
			__( 'Numéro de téléphone' ),
			array( $this, 'setting_callback_function' ),
			'general',
			'socials',
			array(
				'name'  => 'phone_public',
				'label' => __( 'Numéro de téléphone à afficher publiquement' ),
			)
		);
	}

	/**
	 * Register our setting so that $_POST handling is done for us and our
	 * callback function just has to echo the <input>.
	 */
	public function register_settings() {

		$args = array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => null,
			'show_in_graphql'   => true,
			'show_in_rest'      => true,
		);

		register_setting( 'general', 'twitter', $args );
		register_setting( 'general', 'linkedin', $args );
		register_setting( 'general', 'email_public', $args );
		register_setting( 'general', 'phone_public', $args );
	}


	/**
	 * Settings section callback function
	 *
	 * This function is needed if we added a new section. This function
	 * will be run at the start of our section
	 */
	public function socials_callback_function() {
		echo '<p>Socials urls</p>';
	}


	/**
	 * Callback function
	 *
	 * Creates a input text option.
	 *
	 * @param arr $args Array of args.
	 */
	public function setting_callback_function( $args ) {
		echo '<input name="' . esc_attr( $args['name'] ) . '" id="' . esc_attr( $args['name'] ) . '" type="text" value="' . esc_attr( get_option( $args['name'] ) ) . '" class="regular-text code" placeholder="' . esc_attr( $args['label'] ) . '" />';
		echo ' ' . esc_attr( $args['label'] );
	}
}
