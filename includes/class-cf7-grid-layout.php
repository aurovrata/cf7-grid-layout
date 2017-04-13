<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://syllogic.in
 * @since      1.0.0
 *
 * @package    Cf7_Grid_Layout
 * @subpackage Cf7_Grid_Layout/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Cf7_Grid_Layout
 * @subpackage Cf7_Grid_Layout/includes
 * @author     Aurovrata V. <vrata@syllogic.in>
 */
class Cf7_Grid_Layout {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Cf7_Grid_Layout_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'cf7-grid-layout';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Cf7_Grid_Layout_Loader. Orchestrates the hooks of the plugin.
	 * - Cf7_Grid_Layout_i18n. Defines internationalization functionality.
	 * - Cf7_Grid_Layout_Admin. Defines all hooks for the admin area.
	 * - Cf7_Grid_Layout_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cf7-grid-layout-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cf7-grid-layout-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cf7-grid-layout-admin.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'assets/cf7-admin-table/cf7-admin-table-loader.php';
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cf7-grid-layout-public.php';

		$this->loader = new Cf7_Grid_Layout_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Cf7_Grid_Layout_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Cf7_Grid_Layout_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

    $plugin_admin = new Cf7_Grid_Layout_Admin( $this->get_plugin_name(), $this->get_version() );

    //enqueue styles
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
    //add new sub-menu
    $this->loader->add_action('admin_menu', $plugin_admin,  'add_cf7_sub_menu' );
    $this->loader->add_filter( 'custom_menu_order', $plugin_admin, 'change_cf7_submenu_order' );
    //modify cf7 post type
    $this->loader->add_action('register_post_type_args',  $plugin_admin, 'modify_cf7_post_type_args' , 20, 2 );
    //register dynamic dropdown taxonomy with cf7 post
    $this->loader->add_action('init',  $plugin_admin, 'register_dynamic_dropdown_taxonomy' , 20, 2 );
    //$this->loader->add_action('init',  $plugin_admin, 'modify_cf7_post_type' , 20 );
    //add some metabox to the wpcf7_contact_form post type
    $this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'main_editor_meta_box' );
    $this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'info_meta_box' );
    //save the post
    $this->loader->add_action('save_post_wpcf7_contact_form', $plugin_admin, 'save_post', 10,3);
    //ajax load cf7 form content
    $this->loader->add_action('wp_ajax_get_cf7_content', $plugin_admin, 'get_cf7_content');
    //hook for adding fields to sumit action metabox
    $this->loader->add_filter('post_submitbox_misc_actions', $plugin_admin, 'cf7_post_submit_action' ,10);
    
    /*
    CF7 Hooks
    */
    //save cf7 form post
    $this->loader->add_action( 'wpcf7_save_contact_form', $plugin_admin, 'save_factory_metas' , 10, 1);
    //print submit metabox
    $this->loader->add_action( 'wpcf7_admin_misc_pub_section', $plugin_admin, 'dynamic_select_choices' , 10, 1);
    $this->loader->add_action( 'wpcf7_admin_init', $plugin_admin, 'cf7_shortcode_tags' );
    //modify the default form template
    //$this->loader->add_filter( 'wpcf7_default_template', $plugin_admin, 'default_form_template', 5, 2);

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Cf7_Grid_Layout_Public( $this->get_plugin_name(), $this->get_version() );

    //register front-end scripts for CF7 forms
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'register_styles' );
    $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'register_scripts' );
    $this->loader->add_action( 'wp_print_scripts', $plugin_public, 'dequeue_cf7_scripts',100 );
		$this->loader->add_action( 'wp_print_styles', $plugin_public, 'dequeue_cf7_styles',100 );
    $this->loader->add_filter( 'do_shortcode_tag', $plugin_public, 'cf7_shortcode_request',10,3 );
    /*Shortcodes*/
    //add_shortcode('multi-cf7-form', array($plugin_public, 'multi_form_shortcode'));
    //add_shortcode('child-cf7-form', array($plugin_public, 'child_form_shortcode'));
    /* CF7 Hooks */
    //disable autloading of cf7 plugin scripts
    add_filter( 'wpcf7_load_js',  '__return_false' );
    add_filter( 'wpcf7_load_css', '__return_false' );

    //instroduced a dynamic taxonomy droppdown tag for forms
    $this->loader->add_action( 'wpcf7_init', $plugin_public, 'register_dynamic_taxonomy_shortcode' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Cf7_Grid_Layout_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
