<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://syllogic.in
 * @since      1.0.0
 *
 * @package    Cf7_Grid_Layout
 * @subpackage Cf7_Grid_Layout/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cf7_Grid_Layout
 * @subpackage Cf7_Grid_Layout/admin
 * @author     Aurovrata V. <vrata@syllogic.in>
 */
class Cf7_Grid_Layout_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cf7-grid-layout-admin.css', array(), $this->version, 'all' );
    do_action('smart_grid_register_styles');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($page) {

    //debug_msg($screen, $this->custom_type );
    if('toplevel_page_wpcf7' == $page){
      wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cf7-grid-layout-admin.js', array( 'jquery' ), $this->version, false );
      return;
    }
    $screen = get_current_screen();
    if ('wpcf7_contact_form' != $screen->post_type){
      return;
    }

    switch( $screen->base ){
      case 'post':
		    //for the future
        break;
      case 'edit':
        wp_enqueue_script( $this->plugin_name.'-quick-edit', plugin_dir_url( __FILE__ ) . 'js/cf7-grid-layout-quick-edit.js', false, $this->version, true );
        break;
    }
	}
  
  /**
	 * Add to the wpcf7 tag generator.
	 * This function registers a callback function with cf7 to display
	 * the tag generator help screen in the form editor. Hooked on 'wpcf7_admin_init'
	 * @since 1.0.0
	 */
	function add_tag_generator() {
	    if ( class_exists( 'WPCF7_TagGenerator' ) ) {
	        $tag_generator = WPCF7_TagGenerator::get_instance();
          $tag_generator->add( 'js-tabs', __( 'Tabs', 'cf7-grid-layout' ), array($this,'tabs_tag_generator') );
          $tag_generator->add( 'smart-grid', __( 'Grid', 'cf7-grid-layout' ), array($this,'grid_tag_generator') );
	        $tag_generator->add( 'js-accordion', __( 'Accordion', 'cf7-grid-layout' ), array($this,'accordion_tag_generator') );
	    }
	}
  /**
	 * Tabs tag help screen.
	 *
	 * This function is called by cf7 plugin, and is registered with a hooked function above
	 *
	 * @since 1.0.0
	 * @param WPCF7_ContactForm $contact_form the cf7 form object
	 * @param array $args arguments for this form.
	 */
	function tabs_tag_generator( $contact_form, $args = '' ) {
    $args = wp_parse_args( $args, array() );
		include( plugin_dir_path( __FILE__ ) . '/partials/tag-tabs-display.php');
	}
  /**
	 * Smart grid tag help screen.
	 *
	 * This function is called by cf7 plugin, and is registered with a hooked function above
	 *
	 * @since 1.0.0
	 * @param WPCF7_ContactForm $contact_form the cf7 form object
	 * @param array $args arguments for this form.
	 */
	function grid_tag_generator( $contact_form, $args = '' ) {
    $args = wp_parse_args( $args, array() );
		include( plugin_dir_path( __FILE__ ) . '/partials/tag-grid-display.php');
	}
  /**
	 * Accordion tag help screen.
	 *
	 * This function is called by cf7 plugin, and is registered with a hooked function above
	 *
	 * @since 1.0.0
	 * @param WPCF7_ContactForm $contact_form the cf7 form object
	 * @param array $args arguments for this form.
	 */
	function accordion_tag_generator( $contact_form, $args = '' ) {
    $args = wp_parse_args( $args, array() );
		include( plugin_dir_path( __FILE__ ) . '/partials/tag-accordion-display.php');
	}

}
