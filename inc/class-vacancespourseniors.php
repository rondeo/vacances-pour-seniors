<?php
/**
 * Class Vacances Pour Seniors
 *
 * PHP version 7
 *
 * @author  Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 * @package VacancesPourSeniors
 */

define( 'BOOKING_FORM_ID', 1669 );

/**
 * Autoload
 */
require_once get_template_directory() . '/vendor/autoload.php';


$timber = new Timber\Timber();
$dotenv = Dotenv\Dotenv::create( get_template_directory() );

$dotenv->load();


/**
 * Dirname
 *
 * Tell Timber where are views
 */
Timber::$dirname = array( 'views' );


/**
 * VacancesPourSeniors
 */
class VacancesPourSeniors extends Timber {

	/**
	 * The name of the theme
	 *
	 * @access private
	 * @var    str
	 */
	private $theme_name;


	/**
	 * The version of this theme
	 *
	 * @access private
	 * @var    str
	 */
	private $theme_version;


	/**
	 * Manifest
	 *
	 * @access private
	 * @var    arr
	 */
	private $theme_manifest;


	/**
	 * Config
	 *
	 * @access private
	 * @var    arr
	 */
	private $config;


	/**
	 * Construct
	 *
	 * Initialize the class and set its properties.
	 *
	 * @access public
	 * @param  str $theme_name    The theme name.
	 * @param  str $theme_version The theme version.
	 */
	public function __construct( $theme_name, $theme_version ) {
		$this->theme_name    = $theme_name;
		$this->theme_version = $theme_version;

		$this->load_dependencies();
		$this->setup();

		$this->theme_manifest = get_theme_manifest();

		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );

		parent::__construct();
	}


	/**
	 * Load dependencies description
	 *
	 * @access private
	 * @name   load_dependencies
	 */
	private function load_dependencies() {
		include_once get_template_directory() . '/inc/utilities.php';

		include_once get_template_directory() . '/inc/helpers.php';

		include_once get_template_directory() . '/inc/reset.php';
		// include_once get_template_directory() . '/inc/acf.php';

		include_once get_template_directory() . '/inc/template-functions.php';
		include_once get_template_directory() . '/inc/template-tags.php';

		include_once get_template_directory() . '/inc/class-admin.php';
		include_once get_template_directory() . '/inc/class-settings.php';

		include_once get_template_directory() . '/inc/customizer/contact.php';
		// include_once get_template_directory() . '/inc/customizer/link.php';

		include_once get_template_directory() . '/inc/post-types/class-residence.php';
		include_once get_template_directory() . '/inc/post-types/class-specialoffers.php';
		include_once get_template_directory() . '/inc/post-types/class-event.php';
		include_once get_template_directory() . '/inc/post-types/class-discovery.php';

		include_once get_template_directory() . '/inc/taxonomies/class-residencetype.php';
		include_once get_template_directory() . '/inc/taxonomies/class-residencegroup.php';
		include_once get_template_directory() . '/inc/taxonomies/class-residencestatus.php';
		include_once get_template_directory() . '/inc/taxonomies/class-residencecomfort.php';
		include_once get_template_directory() . '/inc/taxonomies/class-residencesociallife.php';
		include_once get_template_directory() . '/inc/taxonomies/class-residenceaccommodation.php';

		include_once get_template_directory() . '/inc/taxonomies/class-city.php';
		include_once get_template_directory() . '/inc/taxonomies/class-country.php';
		include_once get_template_directory() . '/inc/taxonomies/class-region.php';
		include_once get_template_directory() . '/inc/taxonomies/class-department.php';

		new Residence( $this->get_theme_version() );
		new SpecialOffers( $this->get_theme_version() );
		new Event( $this->get_theme_version() );
		new Discovery( $this->get_theme_version() );

		new ResidenceType( $this->get_theme_version() );
		new ResidenceGroup( $this->get_theme_version() );
		new ResidenceStatus( $this->get_theme_version() );
		new ResidenceComfort( $this->get_theme_version() );
		new ResidenceSocialLife( $this->get_theme_version() );
		new ResidenceAccommodation( $this->get_theme_version() );

		new City( $this->get_theme_version() );
		new Country( $this->get_theme_version() );
		new Region( $this->get_theme_version() );
		new Department( $this->get_theme_version() );

		new Settings( $this->get_theme_name(),  $this->get_theme_version() );

		if ( is_admin() ) {
			new Admin( $this->get_theme_name(), $this->get_theme_version() );
		}

		add_action( 'customize_register', 'customize_contact' );
		// add_action( 'customize_register', 'cardinalcapital_customize_link' );
	}


	/**
	 * Add to Twig
	 *
	 * @param obj $twig Twig environment.
	 * @access public
	 */
	public function add_to_twig( $twig ) {

		if ( function_exists( 'post_class' ) ) {
			$twig->addFunction(
				new Twig\TwigFunction(
					'post_class',
					function ( $class = '', $post_id = null ) {
						return post_class( $class, $post_id );
					}
				)
			);
		}

		if ( function_exists( 'body_class' ) ) {
			$twig->addFunction(
				new Twig\TwigFunction(
					'body_class',
					function ( $args = '' ) {
						return body_class( $args );
					}
				)
			);
		}

		if ( function_exists( 'html_class' ) ) {
			$twig->addFunction(
				new Twig\TwigFunction(
					'html_class',
					function ( $args = '' ) {
						return html_class( $args );
					}
				)
			);
		}

		if ( function_exists( 'yoast_breadcrumb' ) ) {
			$twig->addFunction(
				new Twig\TwigFunction(
					'yoast_breadcrumb',
					function () {
						return yoast_breadcrumb();
					}
				)
			);
		}

		if ( function_exists( 'get_body_class' ) ) {
			$twig->addFunction(
				new Twig\TwigFunction(
					'get_body_class',
					function () {
						return get_body_class();
					}
				)
			);
		}

		if ( function_exists( 'do_shortcode' ) ) {
			$twig->addFunction(
				new Twig\TwigFunction(
					'do_shortcode',
					function ( $id ) {
						return do_shortcode( "[contact-form-7 id=\"{$id}\"]" );
					}
				)
			);
		}

		if ( function_exists( 'wp_get_document_title' ) ) {
			$twig->addFunction(
				new Twig\TwigFunction(
					'wp_get_document_title',
					function () {
						return wp_get_document_title();
					}
				)
			);
		}

		if ( function_exists( 'get_the_residence_type_name' ) ) {
			$twig->addFunction(
				new Twig\TwigFunction(
					'get_the_residence_type_name',
					function ( $residence_id = null ) {
						return get_the_residence_type_name( $residence_id );
					}
				)
			);
		}

		if ( function_exists( 'get_the_residence_comforts' ) ) {
			$twig->addFunction(
				new Twig\TwigFunction(
					'get_the_residence_comforts',
					function ( $residence_id = null ) {
						return get_the_residence_comforts( $residence_id );
					}
				)
			);
		}

		if ( function_exists( 'get_the_residence_social_lives' ) ) {
			$twig->addFunction(
				new Twig\TwigFunction(
					'get_the_residence_social_lives',
					function ( $residence_id = null ) {
						return get_the_residence_social_lives( $residence_id );
					}
				)
			);
		}

		if ( function_exists( 'get_the_residence_accommodations' ) ) {
			$twig->addFunction(
				new Twig\TwigFunction(
					'get_the_residence_accommodations',
					function ( $residence_id = null ) {
						return get_the_residence_accommodations( $residence_id );
					}
				)
			);
		}

		if ( function_exists( 'get_the_residence_group_name' ) ) {
			$twig->addFunction(
				new Twig\TwigFunction(
					'get_the_residence_group_name',
					function ( $residence_id = null ) {
						return get_the_residence_group_name( $residence_id );
					}
				)
			);
		}

		if ( function_exists( 'get_the_residence_status_name' ) ) {
			$twig->addFunction(
				new Twig\TwigFunction(
					'get_the_residence_status_name',
					function ( $residence_id = null ) {
						return get_the_residence_status_name( $residence_id );
					}
				)
			);
		}

		if ( function_exists( 'get_the_city_name' ) ) {
			$twig->addFunction(
				new Twig\TwigFunction(
					'get_the_city_name',
					function ( $residence_id = null ) {
						return get_the_city_name( $residence_id );
					}
				)
			);
		}

		if ( function_exists( 'get_the_department_code' ) ) {
			$twig->addFunction(
				new Twig\TwigFunction(
					'get_the_department_code',
					function ( $department_term_id = null ) {
						return get_the_department_code( $department_term_id );
					}
				)
			);
		}

		if ( function_exists( 'get_the_modified_date' ) ) {
			$twig->addFunction(
				new Twig\TwigFunction(
					'get_the_modified_date',
					function ( $d = 'YY-MM-d', $post = null ) {
						return get_the_modified_date( $d, $post );
					}
				)
			);
		}

		if ( function_exists( 'get_the_residence_thumbnail' ) ) {
			$twig->addFunction(
				new Twig\TwigFunction(
					'get_the_residence_thumbnail',
					function ( $post = null ) {
						return get_the_residence_thumbnail( $post );
					}
				)
			);
		}

		if ( function_exists( 'get_the_code' ) ) {
			$twig->addFunction(
				new Twig\TwigFunction(
					'get_the_code',
					function ( $post = null ) {
						return get_the_code( $post );
					}
				)
			);
		}

		if ( function_exists( 'get_the_region_name' ) ) {
			$twig->addFunction(
				new Twig\TwigFunction(
					'get_the_region_name',
					function ( $post_id = null ) {
						return get_the_region_name( $post_id );
					}
				)
			);
		}

		if ( function_exists( 'get_average_ratings_residence' ) ) {
			$twig->addFunction(
				new Twig_SimpleFunction(
					'get_average_ratings_residence',
					function( $residence_id = null ) {
						return get_average_ratings_residence( $residence_id );
					}
				)
			);
		}

		if ( function_exists( 'get_extended' ) ) {
			$twig->addFunction(
				new Twig_SimpleFunction(
					'get_extended',
					function( $content ) {
						return get_extended( $content );
					}
				)
			);
		}

		return $twig;
	}


	/**
	 * Add to context
	 *
	 * @param  obj $context Context.
	 * @return $context
	 * @access public
	 */
	public function add_to_context( $context ) {
		global $wp, $post;

		// Menus.
		$menus = get_registered_nav_menus();
		foreach ( $menus as $menu => $value ) {
			$context['menu'][ $menu ] = new TimberMenu( $menu );
		}

		$context['current_url'] = home_url( add_query_arg( array(), $wp->request ) );
		$context['manifest']    = $this->theme_manifest;

		// Share and Socials links.
		$socials = array(
			array(
				'title'     => 'LinkedIn',
				'slug'      => 'linkedin',
				'name'      => __( 'Partager sur LinkedIn' ),
				'url'       => 'https://www.linkedin.com/shareArticle?mini=true&url=' . home_url( $wp->request ),
				'link'      => get_option( 'linkedin' )
			),
			array(
				'title'     => 'Facebook',
				'slug'      => 'facebook',
				'name'      => __( 'Partager sur Facebook' ),
				'url'       => 'https://www.facebook.com/sharer.php?u=' . home_url( $wp->request ),
				'link'      => get_option( 'facebook' )
			),
			array(
				'title'     => 'Twitter',
				'slug'      => 'twitter',
				'name'      => __( 'Partager sur Twitter' ),
				'url'       => 'https://twitter.com/intent/tweet?url=' . home_url( $wp->request ),
				'link'      => get_option( 'twitter' )
			),
			array(
				'title'     => 'Mail',
				'slug'      => 'envelope',
				'name'      => __( 'Partager par Mail' ),
				'url'       => 'mailto:?&amp;body=' . home_url( $wp->request )
			)
		);

		foreach ( $socials as $social ) {
			if ( ! empty( $social['link'] ) ) {
				$context['socials'][$social['slug']] = $social;
			}
			$context['shares'][$social['slug']] = $social;
		}

		return $context;
	}


	/**
	 * Setup
	 *
	 * @access public
	 */
	public function setup() {
		/*
		* Let WordPress manage the document title.
		*/
		add_theme_support( 'title-tag' );

		/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @see https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
		add_theme_support( 'post-thumbnails' );

		/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		// Register nav menus.
		register_nav_menus(
			array(
				'main'      => __( 'Main' ),
				'footer_01' => __( 'Footer 01' ),
				'footer_02' => __( 'Footer 02' ),
				'footer_03' => __( 'Footer 03' ),
			)
		);

		add_post_type_support( 'page', 'excerpt' );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}


	/**
	 * List webfonts used by the theme.
	 *
	 * @return array
	 * @access public
	 */
	public function webfonts() {
		return array(
			'Montserrat' => 'https://fonts.googleapis.com/css?family=Montserrat:300,500,600,700,800&display=swap'
		);
	}


	/**
	 * Enqueue styles.
	 *
	 * @access public
	 */
	public function enqueue_style() {
		wp_dequeue_style( 'wp-block-library' );

		// Add custom fonts, used in the main stylesheet.
		$webfonts = array();
		foreach ( $this->webfonts() as $name => $url ) {
			wp_register_style(
				'font-' . $name,
				$url,
				array(),
				$this->get_theme_version()
			);
			$webfonts[] = 'font-' . $name;
		}

		// Theme stylesheet.
		wp_register_style(
			$this->theme_name . '-main',
			get_template_directory_uri() . '/' . get_theme_manifest()['main.css'],
			$webfonts,
			$this->get_theme_version()
		);

		wp_enqueue_style( $this->theme_name . '-main' );
	}


	/**
	 * Admin enqueue scripts
	 *
	 * @return
	 */
	public function admin_enqueue_scripts() {
		wp_register_script(
			$this->theme_name . '-admin',
			get_template_directory_uri() . '/' . get_theme_manifest()['admin.js'],
			array( 'jquery' ),
			$this->get_theme_version(),
			true
		);

		wp_enqueue_script( $this->theme_name . '-admin' );
	}


	/**
	 * Enqueue scripts
	 *
	 * @access public
	 */
	public function enqueue_scripts() {

		wp_deregister_script( 'wp-embed' );

		if ( getenv( 'PRODUCTION' === true ) ) {
			wp_deregister_script( 'jquery' );
		}

		wp_register_script(
			$this->theme_name . '-main',
			get_template_directory_uri() . '/' . get_theme_manifest()['main.js'],
			// array( 'wp-util' ),
			array(),
			$this->get_theme_version(),
			true
		);

		wp_enqueue_script(
			'feature',
			'//cdnjs.cloudflare.com/ajax/libs/feature.js/1.0.1/feature.min.js',
			array(),
			$this->get_theme_version(),
			false
		);
		wp_add_inline_script(
			'feature',
			'document.documentElement.className=document.documentElement.className.replace("no-js","js"),feature.touch&&!navigator.userAgent.match(/Trident\/(6|7)\./)&&(document.documentElement.className=document.documentElement.className.replace("no-touch","touch"));'
		);

		wp_localize_script(
			$this->theme_name . '-main',
			'wp',
			array(
				'template_directory_uri' => get_template_directory_uri(),
				'base_url'               => site_url(),
				'home_url'               => home_url( '/' ),
				'ajax_url'               => admin_url( 'admin-ajax.php' ),
				'current_url'            => get_permalink(),
				'nonce'                  => wp_create_nonce( 'security' ),
			)
		);

		wp_enqueue_script( $this->theme_name . '-main' );
	}

	/**
	 * Defer script
	 *
	 * @access public
	 * @param  str $tag    Tag.
	 * @param  str $handle Handle.
	 * @param  str $src    Src.
	 * @return str
	 */
	public function defer_scripts( $tag, $handle, $src ) {
		if ( 'feature' !== $handle ) {
			return $tag;
		}

		return str_replace( ' src', ' defer="defer" src', $tag );
	}


	/**
	 * Retrieve the version number of the theme.
	 *
	 * @since  1.0.0
	 * @return string    The version number of the plugin.
	 */
	public function get_theme_version() {
		return $this->theme_version;
	}


	/**
	 * The name of the theme used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since  1.0.0
	 * @return string    The name of the plugin.
	 */
	public function get_theme_name() {
		return $this->theme_name;
	}
}

$wp_theme = wp_get_theme();

new VacancesPourSeniors( 'VacancesPourSeniors', $wp_theme->Version );
