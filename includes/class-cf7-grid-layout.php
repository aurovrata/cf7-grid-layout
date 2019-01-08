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
	public function __construct($version) {

		$this->plugin_name = 'cf7-grid-layout';
		$this->version = $version;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
    $stored_version = get_option('_cf7sg_version', '1.2.2');
    if('1.2.2' === $stored_version){
      $this->upgrade_db();
    }
    update_option('_cf7sg_version', $version);
	}
  /**
  *Function to update the DB for v1.2.3 to ensure cf7sg managed forms are properly tagged as such.
  *
  *@since 1.2.3
  */
  private function upgrade_db(){
    global $wpdb;
    $rows = $wpdb->get_results(
      "SELECT post_id
      FROM $wpdb->postmeta
      WHERE meta_key LIKE '_cf7sg_has_tables'"
    );
    foreach($rows as $row){
      update_post_meta($row->post_id, '_cf7sg_managed_form', true);
    }
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
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/wordpress-gurus-debug-api.php';

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
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
    $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
    // @since 1.5.0 hack to enqueue js/css for other cf7 extensions.
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'popular_extentions_scripts', 999,0);
    $this->loader->add_action( 'admin_print_scripts', $plugin_admin, 'print_extentions_scripts', 1,0);
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
    $this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'helper_meta_box');
    //save the post
    $this->loader->add_action('save_post_wpcf7_contact_form', $plugin_admin, 'save_post', 10,3);
    //ajax load cf7 form content
    $this->loader->add_action('wp_ajax_get_cf7_content', $plugin_admin, 'get_cf7_content');
    //hook for adding fields to sumit action metabox
    $this->loader->add_filter('post_submitbox_misc_actions', $plugin_admin, 'cf7_post_submit_action' ,10);
    //cusotm sanitation rules for forms
    $this->loader->add_filter('wp_kses_allowed_html', $plugin_admin, 'custom_kses_rules' ,10, 2);
    /**
    * @since 2.1.0 make sure our dependent plugins exists.*/
    $this->loader->add_action( 'admin_init', $plugin_admin, 'check_plugin_dependency');
    /**
    *@since 2.3.0 redirect post.php for form duplicate*/
    $this->loader->add_filter('admin_init', $plugin_admin, 'duplicate_cf7_form');
    /** @since 2.6.0*/
    $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'pretty_admin_pointers' );
    $this->loader->add_action( 'cf7sg_plugin_pointers-edit-wpcf7_contact_form', $plugin_admin, 'edit_pointers' );
    /*
    CF7 Hooks
    */
    //save cf7 form post
    $this->loader->add_action( 'wpcf7_save_contact_form', $plugin_admin, 'save_factory_metas' , 10, 1);
    //print submit metabox
    $this->loader->add_action( 'wpcf7_admin_misc_pub_section', $plugin_admin, 'dynamic_select_choices' , 10, 1);
    $this->loader->add_action( 'wpcf7_admin_init', $plugin_admin, 'cf7_shortcode_tags' );
    //modify the default form template
    $this->loader->add_filter( 'wpcf7_default_template', $plugin_admin, 'default_cf7_form' , 5,2);
    /** @since 2.6.0*/
    $this->loader->add_filter( 'wpcf7_messages', $plugin_admin, 'disabled_message' , 5,2);


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
    $this->loader->add_filter( 'do_shortcode_tag', $plugin_public, 'cf7_shortcode_request',5,3 );
    
    //save grid fields
    $this->loader->add_action( 'wp_ajax_nopriv_save_grid_fields', $plugin_public, 'save_grid_fields' );
    $this->loader->add_action( 'wp_ajax_save_grid_fields', $plugin_public, 'save_grid_fields' );

    /* CF7 Hooks */
    //disable autloading of cf7 plugin scripts
    //add_filter( 'wpcf7_load_js',  '__return_false' );
    //add_filter( 'wpcf7_load_css', '__return_false' );
    //add hidden toggle status field when form loads, load after postion 10 to overcome Conditional Fields bug.
    $this->loader->add_filter( 'wpcf7_form_hidden_fields',  $plugin_public, 'add_hidden_fields', 20 );
    //instroduced a dynamic taxonomy droppdown tag for forms
    $this->loader->add_action( 'wpcf7_init', $plugin_public, 'register_cf7_shortcode' );
    //setup individual tag filers
    $this->loader->add_filter( 'wpcf7_posted_data', $plugin_public, 'setup_grid_values', 5, 1 );
    //filter cf7 validation
    $this->loader->add_filter( 'wpcf7_validate', $plugin_public, 'filter_wpcf7_validate', 30, 2 );
    //benchmark validation
    $this->loader->add_filter( 'wpcf7_validate_dynamic_select*', $plugin_public, 'validate_required', 30, 2 );
    $this->loader->add_filter( 'wpcf7_validate_benchmark*', $plugin_public, 'validate_required', 30, 2 );
    /**
    * @since 2.1 filter mail tags for tables and tabs.*/
    $this->loader->add_filter( 'wpcf7_mail_tag_replaced', $plugin_public, 'filter_table_tab_mail_tag', 30, 4 );
    //Post My CF7 Form hooks
    $this->loader->add_filter('cf7_2_post_echo_field_mapping_script', $plugin_public, 'load_tabs_table_field', 10, 6 );
    $this->loader->add_action('cf7_2_post_form_posted', $plugin_public, 'save_select2_custom_options', 10, 5 );
		//load the saved toggled status for saved submissions.
		$this->loader->add_filter( 'cf7_2_post_form_values', $plugin_public, 'load_saved_toggled_status' );
		/** track toggles
		*@since 1.1.0  */
		$this->loader->add_action('cf7_2_post_form_posted', $plugin_public, 'save_toggle_status', 10, 5 );
    /** @since 2.4.1 attache array file fields to mails */
    $this->loader->add_filter( 'wpcf7_mail_components', $plugin_public, 'wpcf7_mail_components' , 999,3);
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
