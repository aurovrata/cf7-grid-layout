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
   * Displays extra column in cf7 post table
   * Hooked on 'manage_wpcf7_contact_form_posts_custom_column'
   *
   * @since 1.0.0
   * @param      string    $column     column to display.
   * @param     string    $cf7_post_id     cf7 form post id.
  **/
  function show_cf7_key_column( $column, $cf7_post_id ) {
  	switch ( $column ) {
  		case 'cf7_key':
  			$key = get_post_meta($cf7_post_id, '_smart_grid_cf7_form_key', true);
  			if ( empty($key) ) {
  				$key = 'form_'.wp_create_nonce( 'cf7_key_'.$cf7_post_id );
          update_post_meta($cf7_post_id, '_smart_grid_cf7_form_key', $key);
  			}
        echo '<span class="cf7-smart-grid cf7-form-key">'.$key.'</span>';
  			break;
  	}
  }
  /**
   * Add an extra column to the cf7 post table
   * Hooked on 'manage_edit-wpcf7_contact_form_columns'
   * @since 1.0.0
   * @param      Array     $columns    an array of columsn to show .
   * @return     Array     modified array of columns.
  **/
  public function add_cf7_key_column($columns){
    $columns['cf7_key']=__('Form key','cf7-grid-layout');
    return $columns;
  }
  /**
   * Add a quick edit action to the cf7 post table
   * Hooked on 'post_row_actions'
   *
   * @since 1.0.0
   * @param      string    $p1     .
   * @return     string    $p2     .
  **/
  public function add_cf7_post_action($actions, $post){
    //check for your post type
    if('trash'==$post->post_status) return array();

    if ("wpcf7_contact_form" == $post->post_type){
      $actions["inline hide-if-no-js"] = '<a href="#" class="editinline" aria-label="Quick edit &#8220;'.$post->post_title.'&#8221; inline">Quick&nbsp;Edit</a><span class="display-none cf7_post_slug">'.$post->post_name.'</span>';
    }
    return $actions;
  }
  /**
   *
   *
   * @since 1.0.0
   * @param      string    $p1     .
   * @return     string    $p2     .
  **/
  public function quick_edit_box( $column_name, $post_type ) {
    if("wpcf7_contact_form" != $post_type){
      return;
    }
    static $printNonce = TRUE;
    if ( $printNonce ) {
        $printNonce = FALSE;
        wp_nonce_field( plugin_basename( __DIR__ ), 'cf7_key_nonce' );
    }
    switch ( $column_name ) {
      case 'cf7_key':
    ?>
    <fieldset class="inline-edit-col-left inline-edit-cf7">
      <legend class="inline-edit-legend">Quick Edit</legend>
      <div class="inline-edit-col column-<?php echo $column_name; ?>">
        <label class="inline-edit-group">
          <span class="cf7-smart-grid cf7-form-key-label">Form key</span><input name="_smart_grid_cf7_form_key" type="text"/>
        </label>
      </div>
    </fieldset>
    <?php
      break;
      default:
        echo '';
        break;
    }
  }
  /**
   * Save teh cf7 key
   * Hooked on 'save_post'
   * @since 1.0.0
   * @param      string    $post_id     cf7 form post id.
   * @param     string    $post     cf7 form post.
  **/
  public function save_cf7_key($post_id, $post){
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
      return ;
    }
    // Check permissions
    if ( 'wpcf7_contact_form' != $post->post_type ) {
      return;
    }
    if ( !current_user_can( 'edit_post', $post_id ) ){
      return;
    }

    if (isset($_POST['_smart_grid_cf7_form_key']) && ($post->post_type != 'revision')) {
      $cf7_key = trim($_POST['_smart_grid_cf7_form_key']);
      $cf7_key = str_replace(' ','-',$cf7_key);
      $cf7_key =  strtolower($cf7_key);
      if ($cf7_key){
        update_post_meta( $post_id, '_smart_grid_cf7_form_key', $cf7_key);
      }
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
