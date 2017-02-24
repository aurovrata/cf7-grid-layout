<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://syllogic.in
 * @since      1.0.0
 *
 * @package    Cf7_Grid_Layout
 * @subpackage Cf7_Grid_Layout/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cf7_Grid_Layout
 * @subpackage Cf7_Grid_Layout/public
 * @author     Aurovrata V. <vrata@syllogic.in>
 */
class Cf7_Grid_Layout_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function register_styles() {

    $plugin_dir = plugin_dir_url( __DIR__ );
    wp_register_style( 'cf7-jquery-ui', $plugin_dir . 'assets/jquery-ui/jquery-ui.min.css', array(), '1.12.1', 'all');
    wp_register_style( 'cf7-jquery-ui-theme', $plugin_dir . 'assets/jquery-ui/jquery-ui.theme.min.css', array(), '1.12.1', 'all');
    wp_register_style( 'cf7-jquery-ui-structure', $plugin_dir . 'assets/jquery-ui/jquery-ui.structure.min.css', array(), '1.12.1', 'all');
    wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cf7-grid-layout-public.css', array(), $this->version, 'all' );
		wp_register_style( 'smart-grid', $plugin_dir . 'assets/css.gs/smart-grid.css', array(), $this->version, 'all' );
    wp_register_style('select2-style', $plugin_dir . 'assets/select2/css/select2.min.css', array(), $this->version, 'all' );
    wp_register_style('jquery-nice-select-css', $plugin_dir . 'assets/jquery-nice-select/css/nice-select.css', array(), $this->version, 'all' );
    wp_register_style('jquery-toggles-css', $plugin_dir . 'assets/jquery-toggles/css/toggles.css', array(), $this->version, 'all' );
    wp_register_style('jquery-toggles-light-css', $plugin_dir . 'assets/jquery-toggles/css/themes/toggles-light.css', array('jquery-toggles-css'), $this->version, 'all' );
    //allow custom script registration
    do_action('smart_grid_register_styles');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function register_scripts() {

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cf7-grid-layout-public.js', array( 'jquery' ), $this->version, false );
    wp_register_script('jquery-select2', plugin_dir_url( __DIR__ ) . 'assets/select2/js/select2.min.js', array( 'jquery' ), $this->version, false );
    wp_register_script('jquery-nice-select', plugin_dir_url( __DIR__ ) . 'assets/jquery-nice-select/js/jquery.nice-select.min.js', array( 'jquery' ), $this->version, false );
    wp_register_script('jquery-toggles', plugin_dir_url( __DIR__ ) . 'assets/jquery-toggles/toggles.min.js', array( 'jquery' ), $this->version, true );
    //allow custom script registration
    do_action('smart_grid_register_scripts');
	}
  /**
   * Dequeue script 'contact-form-7'
   * hooked on 'wp_print_scripts', this canbe re0enqeued on specific cf7 shortcode calls
   * @since 1.0.0
  **/
  public function dequeue_cf7_scripts(){
    wp_dequeue_script('contact-form-7');
  }
  /**
   * Dequeue script 'contact-form-7'
   * hooked on 'wp_print_style', this canbe re0enqeued on specific cf7 shortcode calls
   * @since 1.0.0
  **/
  public function dequeue_cf7_styles(){
    wp_dequeue_style('contact-form-7');
  }
  /**
   * Enqueue scripts requried for cf7 shortcode
   * hooked on 'do_shortcode_tag',
   * @since 1.0.0
  **/
  public function cf7_shortcode_request($output, $tag, $attr){
    if('contact-form-7' != $tag){
      return $output;
    }
    wp_enqueue_script('contact-form-7');
    wp_enqueue_script($this->plugin_name);
    $class[]='has-validation';
    wp_enqueue_script('jquery-select2');
    wp_enqueue_style('select2-style');
    $class[]='has-select2';
    wp_enqueue_script('jquery-ui-accordion');
    $class[]='has-accordion';
    wp_enqueue_script('jquery-ui-tabs');
    $class[]='has-tabs';
    wp_enqueue_script('jquery-effects-core');
    $class[]='has-effects';
    wp_enqueue_script('jquery-nice-select');
    wp_enqueue_style('jquery-nice-select-css');
    $class[]='has-nice-select';
    wp_enqueue_script('jquery-toggles');
    wp_enqueue_style('jquery-toggles-css');
    wp_enqueue_style('jquery-toggles-light-css');
    $class[]='has-toggles';
    wp_enqueue_script('jquery-ui-dialog');
    //styles
    wp_enqueue_style('contact-form-7');
    wp_enqueue_style($this->plugin_name);
    wp_enqueue_style('smart-grid');
    $class[]='has-grid';
    wp_enqueue_style('cf7-jquery-ui-theme');
    wp_enqueue_style('cf7-jquery-ui-structure');
    wp_enqueue_style('cf7-jquery-ui');

    //get the key
    $cf7_id = $attr['id'];
    $cf7_key = get_post_meta($cf7_id, '_smart_grid_cf7_form_key', true);
    //allow custom script print
    do_action('smart_grid_enqueue_scripts', $cf7_key, $attr);
    //form id
    $css_id = apply_filters('cf7_smart_grid_form_id', $cf7_key, $attr);
    $output = '<div id="'.$css_id.'" class="cf7-smart-grid has-validation has-table has-accordion has-tabs has-toggles has-nice-select">'.$output.'</div>';
    return $output;
  }

  /**
	 * Register shortcode with CF7.
	 * Hooked  o 'wpcf7_init'
	 * This function registers a callback function to expand the shortcode for the googleMap form fields.
	 * @since 1.0.0
	 */
	public function add_cf7_shortcode() {
    if( function_exists('wpcf7_add_form_tag') ) {
      wpcf7_add_form_tag(
        array( 'smart-grid' ),
        array($this,'grid_shortcode_handler'),
        false //has name
      );
      wpcf7_add_form_tag( array( 'js-accordion' ), array($this,'tabs_shortcode_handler'),false );
      wpcf7_add_form_tag( array( 'js-tabs' ), array($this,'accordion_shortcode_handler'),false );

    }
	}
  /**
	 * Function for [smart-grid] shortcode handler.
	 * This function is called by cf7 directly, registered above.
	 *
	 * @since 1.0.0
	 * @param strng $tag the tag name designated in the tag help screen
	 * @return string a set of html fields to capture the googleMap information
	 */
	public function grid_shortcode_handler( $tag ) {
      //enqueue required scripts and styles
      //wp_enqueue_script($this->plugin_name);
      //wp_enqueue_script('jquery-select2');
      //wp_enqueue_script('jquery-ui-accordion');
      //wp_enqueue_script('jquery-ui-tabs');
      //styles
      wp_enqueue_style($this->plugin_name);
      wp_enqueue_style('smart-grid');
      //wp_enqueue_style('select2-style');

	    $tag = new WPCF7_FormTag( $tag );
      if ( empty( $tag->name ) ) {
    		return '';
    	}

      $plugin_url = plugin_dir_url( __DIR__ );
      ob_start();
	    include( plugin_dir_path( __FILE__ ) . '/partials/cf7-smart-grid.php');
      $html = ob_get_contents ();
      ob_end_clean();
	    return $html;
	}
}
