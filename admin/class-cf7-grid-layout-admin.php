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
    $screen = get_current_screen();
    if ('wpcf7_contact_form' != $screen->post_type){
      return;
    }

    switch( $screen->base ){
      case 'post':
        wp_enqueue_style( "cf7-grid-post-css", plugin_dir_url( __FILE__ ) . 'css/cf7-grid-layout-post.css', array(), $this->version, 'all' );
        //codemirror
        wp_enqueue_style( "codemirror-css", plugin_dir_url( __DIR__ ) . 'assets/codemirror/codemirror.css', array(), $this->version, 'all' );
        wp_enqueue_style( "codemirror-theme-css", plugin_dir_url( __DIR__ ) . 'assets/codemirror/theme/paraiso-light.css', array(), $this->version, 'all' );
        wp_enqueue_style( "codemirror-foldgutter-css", plugin_dir_url( __DIR__ ) . 'assets/codemirror/addon/fold/foldgutter.css', array(), $this->version, 'all' );
        wp_enqueue_style( 'smart-grid-css', plugin_dir_url( __DIR__ ) . 'assets/css.gs/smart-grid.min.css', array(), $this->version, 'all');
        wp_enqueue_style('dashicons');
        break;
      case 'edit':
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cf7-grid-layout-admin.css', array(), $this->version, 'all' );

        break;
    }

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
        $this->setup_cf7_object();

        wp_enqueue_script( 'cf7-grid-codemirror-js', plugin_dir_url( __FILE__ ) . 'js/cf7-grid-codemirror.js', array( 'jquery', 'jquery-ui-tabs' ), $this->version, false );
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cf7-grid-layout-admin.js', array('cf7-grid-codemirror-js', 'jquery-ui-sortable', 'jquery-ui-draggable' ), $this->version, false );
        wp_localize_script(
          $this->plugin_name,
          'cf7_grid_ajaxData',
          array(
            'url' => admin_url( 'admin-ajax.php' )
          )
        );
        wpcf7_admin_enqueue_scripts( 'wpcf7' );

        //codemirror script
        wp_enqueue_script( 'codemirror-js',
          plugin_dir_url( __DIR__ ) . 'assets/codemirror/codemirror.js',
          array(), $this->version, false
        );
        //fold code
        wp_enqueue_script( 'codemirror-foldcode-js',
          plugin_dir_url( __DIR__ ) . 'assets/codemirror/addon/fold/foldcode.js',
          array('codemirror-js'), $this->version, false
        );
        wp_enqueue_script( 'codemirror-foldgutter-js',
          plugin_dir_url( __DIR__ ) . 'assets/codemirror/addon/fold/foldgutter.js',
          array('codemirror-js'), $this->version, false
        );
        wp_enqueue_script( 'codemirror-indent-fold-js',
          plugin_dir_url( __DIR__ ) . 'assets/codemirror/addon/fold/indent-fold.js',
          array('codemirror-js'), $this->version, false
        );
        wp_enqueue_script( 'codemirror-xml-fold-js',
          plugin_dir_url( __DIR__ ) . 'assets/codemirror/addon/fold/xml-fold.js',
          array('codemirror-js'), $this->version, false
        );
        wp_enqueue_script( 'codemirror-brace-fold-js',
          plugin_dir_url( __DIR__ ) . 'assets/codemirror/addon/fold/brace-fold.js',
          array('codemirror-js'), $this->version, false
        );
        wp_enqueue_script( 'codemirror-comment-fold-js',
          plugin_dir_url( __DIR__ ) . 'assets/codemirror/addon/fold/comment-fold.js',
          array('codemirror-js'), $this->version, false
        );
        wp_enqueue_script( 'codemirror-mixed-js',
          plugin_dir_url( __DIR__ ) . 'assets/codemirror/mode/htmlmixed/htmlmixed.js',
          array('codemirror-js'), $this->version, false
        );
        wp_enqueue_script( 'codemirror-javascript-js',
          plugin_dir_url( __DIR__ ) . 'assets/codemirror/mode/javascript/javascript.js',
          array('codemirror-js'), $this->version, false
        );
        wp_enqueue_script( 'codemirror-xml-js',
          plugin_dir_url( __DIR__ ) . 'assets/codemirror/mode/xml/xml.js',
          array('codemirror-js'), $this->version, false
        );
        wp_enqueue_script( 'codemirror-css-js',
          plugin_dir_url( __DIR__ ) . 'assets/codemirror/mode/css/css.js',
          array('codemirror-js'), $this->version, false
        );
        //overlay for shortcode highligh
        wp_enqueue_script( 'codemirror-overlay-js',
          plugin_dir_url( __DIR__ ) . 'assets/codemirror/addon/mode/overlay.js',
          array('codemirror-js'), $this->version, false
        );
        //js beautify
        wp_enqueue_script( 'beautify-js',
          plugin_dir_url( __DIR__ ) . 'assets/beautify/beautify.js',
          array('jquery'), $this->version, false
        );
        wp_enqueue_script( 'beautify-html-js',
          plugin_dir_url( __DIR__ ) . 'assets/beautify/beautify-html.js',
          array('beautify-js'), $this->version, false
        );
        break;
      case 'edit':
        //wp_enqueue_script( $this->plugin_name.'-quick-edit', plugin_dir_url( __FILE__ ) . 'js/cf7-grid-layout-quick-edit.js', false, $this->version, true );

        break;
    }
	}

  /**
  * Adds a new sub-menu to replace the 'Add New' CF7 menu
  * Add a new sub-menu to the Contact main menu, as well as remove the current default
  *
  */
  public function add_cf7_sub_menu(){

    $hook = add_submenu_page(
      'wpcf7',
      __( 'Edit Contact Form', 'contact-form-7' ),
      __( 'Add New', 'contact-form-7' ),
      'wpcf7_edit_contact_forms',
      'post-new.php?post_type=wpcf7_contact_form'
    );
    //initial cf7 object when creating new form
    add_action( 'load-' . $hook, array($this, 'setup_cf7_object'));
    //remove_submenu_page( $menu_slug, $submenu_slug );
    remove_submenu_page( 'wpcf7', 'wpcf7-new' );
    remove_meta_box('slugdiv', 'wpcf7_contact_form', 'normal');
    //remove_meta_box('submitdiv', 'wpcf7_contact_form', 'normal');
  }
  /**
  * Change the submenu order
  * @since 1.0.0
  */
  public function change_cf7_submenu_order( $menu_ord ) {
      global $submenu;
      // Enable the next line to see all menu orders
      if(!isset($submenu['wpcf7']) ){
        return $menu_ord;
      }
      if( is_network_admin() ){
        return $menu_ord;
      }
      $arr = array();
      foreach($submenu['wpcf7'] as $menu){
        switch($menu[2]){
          case 'post-new.php?post_type=wpcf7_contact_form':
            //push to the front
            array_unshift($arr, $menu);
            break;
          default:
            $arr[]=$menu;
            break;
        }
      }
      $submenu['wpcf7'] = $arr;
      return $menu_ord;
  }
  /**
  * Modify the regsitered cf7 post tppe
  * THis function enables public capability and amind UI visibility for the cf7 post type. Hooked late on `register_post_type_args`
  * @since 1.0.0
  * @param    Array     $args   array of post parameters being registered
  * @param    String    $post_type  post type breing resgistered
  */

  public function modify_cf7_post_type_args($args, $post_type){
    if(class_exists('WPCF7_ContactForm') &&  $post_type === WPCF7_ContactForm::post_type  ) {
      //debug_msg($args, 'pre-args');
      $args['public'] = false;
      $args['show_ui']= true;
      $args['show_in_menu']= 'wpcf7';
      $args['supports'] = array('title', 'author');
      $args['labels']['add_new_item'] = 'Add New Form';
      $args['labels']['edit_item'] = 'Edit Form';
      $args['labels']['new_item'] = 'New Form';
      $args['labels']['view_item'] = 'View Form';
      $args['labels']['view_items'] = 'View Forms';
      $args['labels']['search_items'] = 'Search Forms';
      $args['labels']['not_found'] = 'No forms found.';
      $args['labels']['not_found_in_trash'] = 'No forms found in Trash.';
      $args['labels']['parent_item_colon'] = '';
      $args['labels']['attributes'] = 'Form Attributes';
      $args['labels']['insert_into_item'] = 'Insert into form';
      $args['labels']['uploaded_to_this_item'] = 'Uploaded to this form';
      $args['labels']['filter_items_list'] = 'Filter forms list';
      $args['labels']['items_list_navigation'] = 'Forms list navigation';
      $args['labels']['items_list'] = 'Forms list';
    }
    return $args;
  }
  /**
  * Function to add the metabox to the cf7 post edit screen
  * This adds the main editor, hooked on 'add_meta_boxes'
  * @since 1.0.0
  */
  public function main_editor_meta_box() {
    //add_meta_box( string $id, string $title, callable $callback, string $screen, string $context, string $priority, array $callback_args)
    if(class_exists('WPCF7_ContactForm') &&  post_type_exists( WPCF7_ContactForm::post_type ) ) {
      add_meta_box( 'meta-box-main-cf7-editor',
        __( 'Edit Contact Form', 'contact-form-7' ),
        array($this , 'main_editor_metabox_display'),
        WPCF7_ContactForm::post_type,
        'normal',
        'high'
      );
    }
  }
  /**
   * Function called on page load when new form is created
   * hooked to 'load-{page-hook}'
   * @since 1.0.0
  **/
  public function setup_cf7_object(){
    WPCF7_ContactForm::get_template( array(
			'locale' => isset( $_GET['locale'] ) ? $_GET['locale'] : null ) );
  }
  /**
  * Callback function to disolay the main editor meta box
  * @since 1.0.0
  */
  public function main_editor_metabox_display($post){
    if('auto-draft' !== $post->post_status){
      wpcf7_contact_form($post); //set the post
    }
    $post = wpcf7_get_current_contact_form();

  	if ( ! $post ) {
  		$post = WPCF7_ContactForm::get_template();
  	}

  	if(empty($post)){
      $post_id = -1;
    }else{
      $post_id = $post->ID;
    }
  	require_once WPCF7_PLUGIN_DIR . '/admin/includes/editor.php';
  	require_once plugin_dir_path( __FILE__ )  . '/partials/cf7-admin-editor-display.php';
  }

  /**
  * Function to add the metabox to the cf7 post edit screen
  * This adds the main editor, hooked on 'add_meta_boxes'
  * @since 1.0.0
  */
  public function info_meta_box() {
    //add_meta_box( string $id, string $title, callable $callback, string $screen, string $context, string $priority, array $callback_args)
    if(class_exists('WPCF7_ContactForm') &&  post_type_exists( WPCF7_ContactForm::post_type ) ) {
      add_meta_box( 'meta-box-cf7-info',
        __( 'Information', 'contact-form-7' ),
        array($this , 'info_metabox_display'),
        WPCF7_ContactForm::post_type,
        'side',
        'high'
      );
    }
  }
  /**
  * Callback function to disolay the main editor meta box
  * @since 1.0.0
  */
  public function info_metabox_display($post){
  	require_once plugin_dir_path( __FILE__ )  . '/partials/cf7-info-metabox-display.php';
  }
  /**
   * Display the editor panels (wpcf7 / codemirror / grid)
   *
   * @since 1.0.0
   * @param      string    $p1     .
   * @return     string    $p2     .
  **/
  public function grid_editor_panel($form_post){
    require_once plugin_dir_path( __FILE__ )  . '/partials/cf7-grid-layout-admin-display.php';
  }
  /**
   * Save cf7 post using ajax
   * Hooked to 'save_post_wpcf7_contact_form'
   * @since 1.0.0
  **/
  public function save_post($post_id, $post, $update){
    switch($post->post_status){
      case 'auto-draft':
      case 'trash':
        return;
      default:
        break;
    }
    //debug_msg($_POST, 'submitted ');
    $args = $_REQUEST;
		$args['id'] = $post_id;

		$args['title'] = isset( $_POST['post_title'] )
			? $_POST['post_title'] : null;

		$args['locale'] = isset( $_POST['wpcf7-locale'] )
			? $_POST['wpcf7-locale'] : null;

		$args['form'] = isset( $_POST['wpcf7-form'] )
			? $_POST['wpcf7-form'] : '';

		$args['mail'] = isset( $_POST['wpcf7-mail'] )
			? wpcf7_sanitize_mail( $_POST['wpcf7-mail'] )
			: array();

		$args['mail_2'] = isset( $_POST['wpcf7-mail-2'] )
			? wpcf7_sanitize_mail( $_POST['wpcf7-mail-2'] )
			: array();

		$args['messages'] = isset( $_POST['wpcf7-messages'] )
			? $_POST['wpcf7-messages'] : array();

		$args['additional_settings'] = isset( $_POST['wpcf7-additional-settings'] )
			? $_POST['wpcf7-additional-settings'] : '';

    //need to unhook this function so as not to loop infinitely
    remove_action('save_post_wpcf7_contact_form', array($this, 'save_post'), 10,3);
    $contact_form = wpcf7_save_contact_form( $args );
    add_action('save_post_wpcf7_contact_form', array($this, 'save_post'), 10,3);
    //flag as a grid form
    update_post_meta($post_id, 'cf7_grid_form', true);
  }
  /**
   * Redirect to post.php hack as for some reason saving redirects to edit.php
   * Hooked on 'wp_redirect'
   * @since 1.0.0
   * @param     String    $location     redirect url to filter .
   * @param     String    $status     page status.
   * @return    String    new redirect url.
  **/
  public function redirect_to_post($location, $status){
    if ( isset( $_POST['action'] ) &&  'editpost' == $_POST['action']){
      if('wpcf7_contact_form' == $_POST['post_type']){
        $location = admin_url('post.php?post='.$_POST['ID'].'&action=edit');
      }
    }
		return $location;
  }
}
