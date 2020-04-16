<?php
/**
 * Plugin Name: 	Donation Widgets for Elementor and GiveWP 
 * Plugin URI: 		https://givewp.com
 * Description: 	Add all GiveWP shortcode options as Elementor Widgets
 * Version: 		1.0
 * Author: 			GiveWP 
 * Author URI: 		https://givewp.com
 * License:      	GNU General Public License v2 or later
 * License URI:  	http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:		dw4elementor
 * 
 * NOTE: Most of this code was forked from FooGallery, and it's Extension Generator. 
 * Big props to Brad Vincent (@bradvin) for building a very useful, open source tool like this
 *
 */

// Exit if access directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class GiveWP_DW_4_Elementor
 */
final class GiveWP_DW_4_Elementor {
	/**
	 * Instance.
	 *
	 * @since
	 * @access private
	 * @var GiveWP_DW_4_Elementor
	 */
	private static $instance;

	/**
	 * Singleton pattern.
	 *
	 * @since
	 * @access private
	 */
	private function __construct() {
    }
    
    /**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';


	/**
	 * Get instance.
	 *
	 * @return GiveWP_DW_4_Elementor
	 * @since
	 * @access public
	 *
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof GiveWP_DW_4_Elementor ) ) {
			self::$instance = new GiveWP_DW_4_Elementor();
			self::$instance->setup();
		}

		return self::$instance;
	}


	/**
	 * Setup
	 *
	 * @since
	 * @access private
	 */
	private function setup() {
		self::$instance->setup_constants();

		register_activation_hook( GiveWP_DW_4_Elementor_FILE, array( $this, 'install' ) );
		add_action( 'give_init', array( $this, 'init' ), 10, 1 );
	}


	/**
	 * Setup constants
	 *
	 * Defines useful constants to use throughout the add-on.
	 *
	 * @since
	 * @access private
	 */
	private function setup_constants() {

		// Defines addon version number for easy reference.
		if ( ! defined( 'GiveWP_DW_4_Elementor_VERSION' ) ) {
			define( 'GiveWP_DW_4_Elementor_VERSION', '1.0' );
		}

		// Set it to latest.
		if ( ! defined( 'GiveWP_DW_4_Elementor_MIN_GIVE_VERSION' ) ) {
			define( 'GiveWP_DW_4_Elementor_MIN_GIVE_VERSION', '2.5' );
		}

		if ( ! defined( 'GiveWP_DW_4_Elementor_FILE' ) ) {
			define( 'GiveWP_DW_4_Elementor_FILE', __FILE__ );
		}

		if ( ! defined( 'GiveWP_DW_4_Elementor_DIR' ) ) {
			define( 'GiveWP_DW_4_Elementor_DIR', plugin_dir_path( GiveWP_DW_4_Elementor_FILE ) );
		}

		if ( ! defined( 'GiveWP_DW_4_Elementor_URL' ) ) {
			define( 'GiveWP_DW_4_Elementor_URL', plugin_dir_url( GiveWP_DW_4_Elementor_FILE ) );
		}

		if ( ! defined( 'GiveWP_DW_4_Elementor_BASENAME' ) ) {
			define( 'GiveWP_DW_4_Elementor_BASENAME', plugin_basename( GiveWP_DW_4_Elementor_FILE ) );
		}
	}

	/**
	 * Plugin installation
	 *
	 * @since
	 * @access public
	 */
	public function install() {
		// Bailout.
		if ( ! self::$instance->check_environment() ) {
			return;
		}
		
	}

	/**
	 * Plugin installation
	 *
	 * @param Give $give
	 *
	 * @return void
	 * @since
	 * @access public
	 *
	 */
	public function init( $give ) {

		self::$instance->load_files();

		// Set up localization.
        $this->load_textdomain();
        
        // Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return;
        }
        
        // Add Plugin actions
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
		//add_action( 'elementor/controls/controls_registered', [ $this, 'init_controls' ] );
	
    }
    
    /**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'elementor-test-extension' ),
			'<strong>' . esc_html__( 'Elementor Test Extension', 'elementor-test-extension' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-test-extension' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-test-extension' ),
			'<strong>' . esc_html__( 'Elementor Test Extension', 'elementor-test-extension' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-test-extension' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}


	/**
     * Loads the plugin language files.
     *
     * @since  1.0
     * @access public
     *
     * @return void
     */
    public function load_textdomain() {

        // Set filter for Give's languages directory
        $give_lang_dir = dirname( plugin_basename( GiveWP_DW_4_Elementor_FILE ) ) . '/languages/';
        $give_lang_dir = apply_filters( 'dw4elementor_languages_directory', $give_lang_dir );

        // Traditional WordPress plugin locale filter.
        $locale = is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
        $locale = apply_filters( 'plugin_locale', $locale, 'dw4elementor' );

        unload_textdomain( 'dw4elementor' );
        load_textdomain( 'dw4elementor', WP_LANG_DIR . '/donation-widgets-for-elementor/' . $locale . '.mo' );
        load_plugin_textdomain( 'dw4elementor', false, $give_lang_dir );

    }


	/**
	 * Check plugin environment.
	 *
	 * @since  2.1.1
	 * @access public
	 *
	 * @return bool
	 */
	public function check_environment() {
		// Flag to check whether plugin file is loaded or not.
		$is_working = true;
		// Load plugin helper functions.
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
		}

		return $is_working;
	}

	/**
	 * Load plugin files.
	 *
	 * @since
	 * @access private
	 */
	private function load_files() {
		require_once GiveWP_DW_4_Elementor_DIR . 'includes/helper-functions.php';
		//require_once GiveWP_DW_4_Elementor_DIR . 'includes/settings-screen.php';
	}
    
    /**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init_widgets() {

		// Include Widget files
		require_once( GiveWP_DW_4_Elementor_DIR . '/widgets/test-widget.php' );

		// Register widget
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_oEmbed_Widget() );

    }
    
    /**
	 * Init Controls
	 *
	 * Include controls files and register them
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init_controls() {

		// Include Control files
		require_once( __DIR__ . '/controls/test-control.php' );

		// Register control
		\Elementor\Plugin::$instance->controls_manager->register_control( 'control-type-', new \Elementor_Test_Control() );

	}

}

/**
 * The main function responsible for returning the one true GiveWP_DW_4_Elementor instance
 * to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $recurring = GiveWP_DW_4_Elementor(); ?>
 *
 * @return GiveWP_DW_4_Elementor|bool
 * @since 1.0
 *
 */
function GiveWP_DW_4_Elementor() {
	return GiveWP_DW_4_Elementor::get_instance();
}

GiveWP_DW_4_Elementor();