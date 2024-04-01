<?php
/**
 * Plugin Name: 	GiveWP Donation Widgets for Elementor
 * Plugin URI: 		https://givewp.com/givewp-elementor-widgets
 * Description: 	All GiveWP shortcodes as Elementor Widgets
 * Version: 		2.0.1
 * Requires at least: 6.0
 * Requires PHP:    7.2
 * Author: 			GiveWP
 * Author URI: 		https://givewp.com
 * License:      	GNU General Public License v2 or later
 * License URI:  	http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:		dw4elementor
 */

// Exit if access directly.
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Class GiveWP_DW_4_Elementor
 */
final class GiveWP_DW_4_Elementor
{
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
	private function __construct()
	{
	}

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.5.0';


	/**
	 * Get instance.
	 *
	 * @return GiveWP_DW_4_Elementor
	 * @since
	 * @access public
	 *
	 */
	public static function get_instance()
	{
		if (!isset(self::$instance) && !(self::$instance instanceof GiveWP_DW_4_Elementor)) {
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
	private function setup()
	{
		self::$instance->setup_constants();

		register_activation_hook(GiveWP_DW_4_Elementor_FILE, array($this, 'install'));

		add_action('give_init', array($this, 'init'), 10, 1);

		add_action( 'admin_enqueue_scripts', array($this, 'load_admin_styles') );

	}


	/**
	 * Setup constants
	 *
	 * Defines useful constants to use throughout the add-on.
	 *
	 * @since
	 * @access private
	 */
	private function setup_constants()
	{

		// Defines addon version number for easy reference.
		if (!defined('GiveWP_DW_4_Elementor_VERSION')) {
			define('GiveWP_DW_4_Elementor_VERSION', '2.0.1');
		}

		// Set it to latest.
		if (!defined('GiveWP_DW_4_Elementor_MIN_GIVE_VERSION')) {
			define('GiveWP_DW_4_Elementor_MIN_GIVE_VERSION', '3.0.0');
		}

		if (!defined('GiveWP_DW_4_Elementor_FILE')) {
			define('GiveWP_DW_4_Elementor_FILE', __FILE__);
		}

		if (!defined('GiveWP_DW_4_Elementor_DIR')) {
			define('GiveWP_DW_4_Elementor_DIR', plugin_dir_path(GiveWP_DW_4_Elementor_FILE));
		}

		if (!defined('GiveWP_DW_4_Elementor_Widgets_Folder')) {
			define('GiveWP_DW_4_Elementor_Widgets_Folder', plugin_dir_path(GiveWP_DW_4_Elementor_FILE) . '/widgets/');
		}

		if (!defined('GiveWP_DW_4_Elementor_URL')) {
			define('GiveWP_DW_4_Elementor_URL', plugin_dir_url(GiveWP_DW_4_Elementor_FILE));
		}

		if (!defined('GiveWP_DW_4_Elementor_BASENAME')) {
			define('GiveWP_DW_4_Elementor_BASENAME', plugin_basename(GiveWP_DW_4_Elementor_FILE));
		}
	}

	/**
	 * Plugin installation
	 *
	 * @since
	 * @access public
	 */
	public function install()
	{
		// Bailout.
		if (!self::$instance->check_environment()) {
			return;
		}

		/**
		 * Set Trigger Date.
		 *
		 * @since  1.0.0
		 */

		// Number of days you want the notice delayed by.
		$delayindays = 15;

		// Create timestamp for when plugin was activated.
		$triggerdate = mktime( 0, 0, 0, date('m')  , date('d') + $delayindays, date('Y') );

		// If our option doesn't exist already, we'll create it with today's timestamp.
		if ( ! get_option( 'dw4elementor_activation_date' ) ) {
			add_option( 'dw4elementor_activation_date', $triggerdate, '', 'yes' );
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
	public function init($give)
	{

		self::$instance->load_files();

		// Set up localization.
		$this->load_textdomain();

		// Check if Elementor installed and activated
		if (!did_action('elementor/loaded')) {
			add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
			return;
		}

		// Check for required Elementor version
		if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
			add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
			return;
		}

		// Add Plugin actions
		add_action('elementor/widgets/register', [$this, 'init_widgets']);

		add_action('elementor/editor/before_enqueue_scripts', array($this, 'editor_enqueue_scripts'));

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
	public function admin_notice_missing_main_plugin()
	{

		if (isset($_GET['activate'])) unset($_GET['activate']);

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'dw4elementor'),
			'<strong>' . esc_html__('GiveWP Donation Widgets for Elementor', 'dw4elementor') . '</strong>',
			'<strong>' . esc_html__('Elementor', 'elementor-test-extension') . '</strong>'
		);

		printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
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
	public function admin_notice_minimum_elementor_version()
	{

		if (isset($_GET['activate'])) unset($_GET['activate']);

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'dw4elementor'),
			'<strong>' . esc_html__('GiveWP Donation Widgets for Elementor', 'dw4elementor') . '</strong>',
			'<strong>' . esc_html__('Elementor', 'dw4elementor') . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
	}


	/**
	 * Loads the plugin language files.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @return void
	 */
	public function load_textdomain()
	{

		// Set filter for Give's languages directory
		$give_lang_dir = dirname(plugin_basename(GiveWP_DW_4_Elementor_FILE)) . '/languages/';
		$give_lang_dir = apply_filters('dw4elementor_languages_directory', $give_lang_dir);

		// Traditional WordPress plugin locale filter.
		$locale = is_admin() && function_exists('get_user_locale') ? get_user_locale() : get_locale();
		$locale = apply_filters('plugin_locale', $locale, 'dw4elementor');

		unload_textdomain('dw4elementor');
		load_textdomain('dw4elementor', WP_LANG_DIR . '/givewp-donation-widgets-for-elementor/' . $locale . '.mo');
		load_plugin_textdomain('dw4elementor', false, $give_lang_dir);
	}


	/**
	 * Check plugin environment.
	 *
	 * @since  2.1.1
	 * @access public
	 *
	 * @return bool
	 */
	public function check_environment()
	{
		// Flag to check whether plugin file is loaded or not.
		$is_working = true;
		// Load plugin helper functions.
		if (!function_exists('is_plugin_active')) {
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
		require_once GiveWP_DW_4_Elementor_DIR . 'includes/admin/notice.php';
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
		$dir = GiveWP_DW_4_Elementor_Widgets_Folder;

		$files = glob($dir . '/*.php');

		foreach ($files as $file) {
			require($file);
		}

		// Register Donation History widget
		\Elementor\Plugin::instance()->widgets_manager->register(new \DW4Elementor_Donation_History_Widget());

		// Register Receipt widget
		\Elementor\Plugin::instance()->widgets_manager->register(new \DW4Elementor_GiveWP_Receipt_Widget());

		// Register Totals widget
		\Elementor\Plugin::instance()->widgets_manager->register(new \DW4Elementor_GiveWP_Totals_Widget());

		// Register Register widget
		\Elementor\Plugin::instance()->widgets_manager->register(new \DW4Elementor_GiveWP_Register_Widget());

		// Register Login widget
		\Elementor\Plugin::instance()->widgets_manager->register(new \DW4Elementor_GiveWP_Login_Widget());

		// Register Profile Editor widget
		\Elementor\Plugin::instance()->widgets_manager->register(new \DW4Elementor_GiveWP_Profile_Editor_Widget());

		// Register Donor Wall widget
		\Elementor\Plugin::instance()->widgets_manager->register(new \DW4Elementor_GiveWP_Donor_Wall_Widget());

		// Register Give Goal widget
		\Elementor\Plugin::instance()->widgets_manager->register(new \DW4Elementor_GiveWP_Goal_Widget());

		// Register Give Goal widget
		\Elementor\Plugin::instance()->widgets_manager->register(new \DW4Elementor_GiveWP_Form_Widget());

		// Register Give Goal widget
		\Elementor\Plugin::instance()->widgets_manager->register(new \DW4Elementor_GiveWP_Form_Grid_Widget());

		if ( $this->give_min_version( '2.9.0' ) ) {
			// Register Give Multi Form Goal widget
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \DW4Elementor_GiveWP_Multi_Form_Goal_Widget());
		}

		if ( class_exists('Give_Recurring')) {
			// Register Give Goal widget
			\Elementor\Plugin::instance()->widgets_manager->register(new \DW4Elementor_GiveWP_Subscriptions_Widget());
		}
	}

	/**
	 * Setup hooks
	 *
	 * @since
	 * @access private
	 */
	public function load_admin_styles() {
        wp_enqueue_style( 'dw4elementor-admin', GiveWP_DW_4_Elementor_URL . 'assets/dw4elementor-notice.css', array(), mt_rand(9,999), 'all' );
	}


	// editor styles
	public function editor_enqueue_scripts() {

		wp_enqueue_style('give-admin-styles', GIVE_PLUGIN_URL . '/assets/dist/css/admin.rtl.css', array(), GIVE_VERSION);

		// admin editor styles
		wp_enqueue_style('dw4elementor-admin-styles', GiveWP_DW_4_Elementor_URL . '/assets/dw4elementor-admin.css', array('give-admin-styles'), mt_rand(9, 999));
	}

	/**
	 * Check give min version
	 *
	 * @param string $version
	 *
	 * @return bool
	 */
	public function give_min_version( $version ) {
		return defined( 'GIVE_VERSION' ) && version_compare( GIVE_VERSION, $version, '>=' );
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
function GiveWP_DW_4_Elementor()
{
	return GiveWP_DW_4_Elementor::get_instance();
}

GiveWP_DW_4_Elementor();
