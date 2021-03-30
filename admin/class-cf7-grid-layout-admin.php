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
  * @since    4.2.1
  * @access   private
  * @var      string    $plugin_name    The ID of this plugin.
  */
  private static $admin_notice_pages = array(
    'admin.php'=>array('cf7sg_help'=>'page'),
    'edit.php'=>array('wpcf7_contact_form'=>'post_type'),
    'post.php'=> 'wpcf7_contact_form',
    'edit-tags.php'=>array('wpcf7_contact_form'=>'post_type'),
    'index.php'=>'',//dashboard ,
    'plugins.php'=>'',
    //'options-general.php'=>'page=',
  );
  /**
  * The ID of this plugin.
  *
  * @since    1.0.0
  * @access   private
  * @var      string    $plugin_name    The ID of this plugin.
  */
  private $plugin_name;

  /**
  * The ID of this plugin.
  *
  * @since    4.6.0
  * @access   public
  * @var    Constant    CF7SG_OPTION    Options key.
  */
  const CF7SG_OPTION = 'cf7sg-plugin-version';

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
  * get cf7 post type set by cf7 plugin.
  *
  *@since 3.0.0
  *@return string post type.
  */
  private function cf7_post_type(){
    $type = 'wpcf7_post_type_notset';
    if(class_exists('WPCF7_ContactForm') ) {
      $type = WPCF7_ContactForm::post_type;
    }
    return $type;
  }
  /**
  * Hack to add scripts/styles to edit page.
  * Hooked on 'admin_enqueue_scripts'.
  *@since 1.5.0
  */
  public function popular_extentions_scripts(){
    $screen = get_current_screen();
    if (empty($screen) || $this->cf7_post_type() != $screen->post_type){
      return;
    }
    $plugin_dir = plugin_dir_url( __DIR__ );

    switch( $screen->base ){
      case 'post':
        $page_hook = '';
        if('add' == $screen->action) $page_hook = 'contact_page_wpcf7-new';
        else $page_hook = 'toplevel_page_wpcf7';
        //unhook this function.
        remove_action('admin_enqueue_scripts', array($this, 'popular_extentions_scripts'),999,0);
        do_action('admin_enqueue_scripts', $page_hook);
        $plugin_page ='';
        add_action('admin_enqueue_scripts', array($this, 'popular_extentions_scripts'),999,0);
        break;
      case 'edit': //table.
        break;
    }
  }
  /**
  * Function to spoof the wpcf7 plugin admin scripts loading for extensions
  * Hooked on 'admin_print_scripts' and used to fix the issue with the mailchimp ext.
  *@since 1.5.1
  */
  public function print_extentions_scripts(){
    $screen = get_current_screen();
    if (empty($screen) || $this->cf7_post_type() != $screen->post_type){
      return;
    }
    global $plugin_page;
    $plugin_page = 'wpcf7';
  }


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles($page) {

    if( in_array($page, array('toplevel_page_wpcf7', 'contact_page_wpcf7-new')) ) return;

    $screen = get_current_screen();
    $plugin_dir = plugin_dir_url( __DIR__ );
    if('plugins'==$screen->base){
      /** @since 4.1.0 */
      wp_enqueue_style('plugin-update',$plugin_dir . 'admin/css/cf7sg-plugin-update.css', array(), $this->version, 'all');
      return;
    }
    if (empty($screen) || $this->cf7_post_type() != $screen->post_type){
      return;
    }
    switch( $screen->base ){
      case 'post':
        wp_enqueue_style( "cf7-grid-post-css", $plugin_dir . 'admin/css/cf7-grid-layout-post.css', array(), $this->version, 'all' );
        //dynamic tag
        wp_enqueue_style('cf7sg-dynamic-tag-css', $plugin_dir . 'admin/css/cf7sg-dynamic-tag.css', array(), $this->version, 'all' );
        //benchmark tag
        wp_enqueue_style('cf7sg-benchmark-tag-css', $plugin_dir . 'admin/css/cf7sg-benchmark-tag.css', array(), $this->version, 'all' );
        //codemirror
        wp_enqueue_style( "codemirror-css", $plugin_dir . 'assets/codemirror/codemirror.css', array(), $this->version, 'all' );
        /** @since 4.0 enable dark theme */
        wp_enqueue_style( "codemirror-theme-light-css", $plugin_dir . 'assets/codemirror/theme/paraiso-light.css', array(), $this->version, 'all' );
        wp_enqueue_style( "codemirror-theme-dark-css", $plugin_dir . 'assets/codemirror/theme/material-ocean.css', array(), $this->version, 'all' );
        wp_enqueue_style( "codemirror-foldgutter-css", $plugin_dir . 'assets/codemirror/addon/fold/foldgutter.css', array(), $this->version, 'all' );
        wp_enqueue_style( "codemirror-dialog-css", $plugin_dir . 'assets/codemirror/addon/dialog/dialog.css', array(), $this->version, 'all' );
        wp_enqueue_style( "codemirror-matchesonscrollbar-css", $plugin_dir . 'assets/codemirror/addon/search/matchesonscrollbar.css', array(), $this->version, 'all' );
        wp_enqueue_style( 'smart-grid-css', $plugin_dir . 'assets/css.gs/smart-grid.admin.css', array(), $this->version, 'all');
        wp_enqueue_style('dashicons');
        wp_enqueue_style('select2-style', $plugin_dir . 'assets/select2/css/select2.min.css', array(), $this->version, 'all' );
        /** @since 4.4.0 Enabled user style enqueue */
        do_action('cf7sg_enqueue_admin_editor_styles');
        break;
      case 'edit': //table.
        wp_enqueue_style( $this->plugin_name, $plugin_dir . 'admin/css/cf7-grid-layout-admin.css', array(), $this->version, 'all' );
        /** @since 4.4.0 Enabled user style enqueue */
        do_action('cf7sg_enqueue_admin_table_styles');
        break;
    }

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($page) {

    $plugin_dir = plugin_dir_url( __DIR__ );
    if( in_array($page, array('toplevel_page_wpcf7', 'contact_page_wpcf7-new')) ){
      wp_enqueue_script( $this->plugin_name, $plugin_dir . 'admin/js/cf7-grid-layout-admin.js', array( 'jquery' ), $this->version, true );
      return;
    }
    $screen = get_current_screen();
    if('plugins'==$screen->base){
      /** @since 4.1.0 */
        wp_enqueue_script('cf7sg-plugin-update', $plugin_dir . 'admin/js/cf7sg-plugin-update.js', array('jquery'));
        /* translators: message displayed when succesful update to validate new version */
        wp_localize_script('cf7sg-plugin-update', 'cf7sg',array(
          'msg'=> __('Validating your update...','cf7-grid-layout'),
          'nonce'=> wp_create_nonce( 'cf7sg_udpate_plugin' ),
          'error'=>__('Unable to validate, please reload!', 'cf7-grid-layout')
        ));
      return;
    }
    if ($this->cf7_post_type() != $screen->post_type){
      return;
    }

    switch( $screen->base ){
      case 'post':
        /* register codemirror editor & addons. */
        //codemirror script
        wp_register_script( 'cf7-codemirror-js',
          $plugin_dir . 'assets/codemirror/codemirror.js',
          null, "5.32", true
        );
        /** @since 3.1.2 initialise codemirror after library load and parse as attribute to anonymous functtion in cf7-grid-codemirror.js */
        wp_add_inline_script('cf7-codemirror-js',
        'const cmInitialSettings = {
          value:"",autoCloseTags:true,
          extraKeys: {"Ctrl-Space": "autocomplete", "Ctrl-/": "toggleComment", "Ctrl-J": "toMatchingTag"},
          lineNumbers: true, styleActiveLine: true,
          matchBrackets: true, tabSize:2, lineWrapping: true, addModeClass: true,
          foldGutter: true, autofocus:false,
          gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"]
        }
        const codeMirror_5_32 = CodeMirror(document.getElementById("cf7-codemirror"),cmInitialSettings);
        const cssCodeMirror_5_32 = CodeMirror(document.getElementById("cf7-css-codemirror"),cmInitialSettings);
        const jsCodeMirror_5_32 = CodeMirror(document.getElementById("cf7-js-codemirror"),cmInitialSettings);');
        //matchtags.
        wp_enqueue_script( 'codemirror-closetag-js',
          $plugin_dir . 'assets/codemirror/addon/edit/closetag.js',
          array('cf7-codemirror-js'), $this->version, true
        );
        //fold code
        wp_enqueue_script( 'codemirror-foldcode-js',
          $plugin_dir . 'assets/codemirror/addon/fold/foldcode.js',
          array('cf7-codemirror-js'), $this->version, true
        );
        wp_enqueue_script( 'codemirror-foldgutter-js',
          $plugin_dir . 'assets/codemirror/addon/fold/foldgutter.js',
          array('cf7-codemirror-js'), $this->version, true
        );
        wp_enqueue_script( 'codemirror-indent-fold-js',
          $plugin_dir . 'assets/codemirror/addon/fold/indent-fold.js',
          array('cf7-codemirror-js'), $this->version, true
        );
        wp_enqueue_script( 'codemirror-xml-fold-js',
          $plugin_dir . 'assets/codemirror/addon/fold/xml-fold.js',
          array('cf7-codemirror-js'), $this->version, true
        );
        wp_enqueue_script( 'codemirror-matchtag-js',
          $plugin_dir . 'assets/codemirror/addon/edit/matchtags.js',
          array('cf7-codemirror-js','codemirror-xml-fold-js'), $this->version, true
        );
        wp_enqueue_script( 'codemirror-brace-fold-js',
          $plugin_dir . 'assets/codemirror/addon/fold/brace-fold.js',
          array('cf7-codemirror-js'), $this->version, true
        );
        wp_enqueue_script( 'codemirror-comment-fold-js',
          $plugin_dir . 'assets/codemirror/addon/fold/comment-fold.js',
          array('cf7-codemirror-js'), $this->version, true
        );
        wp_enqueue_script( 'codemirror-mixed-js',
          $plugin_dir . 'assets/codemirror/mode/htmlmixed/htmlmixed.js',
          array('cf7-codemirror-js'), $this->version, true
        );
        wp_enqueue_script( 'codemirror-javascript-js',
          $plugin_dir . 'assets/codemirror/mode/javascript/javascript.js',
          array('cf7-codemirror-js'), $this->version, true
        );
        wp_enqueue_script( 'codemirror-xml-js',
          $plugin_dir . 'assets/codemirror/mode/xml/xml.js',
          array('cf7-codemirror-js'), $this->version, true
        );
        wp_enqueue_script( 'codemirror-css-js',
          $plugin_dir . 'assets/codemirror/mode/css/css.js',
          array('cf7-codemirror-js'), $this->version, true
        );
        //overlay for shortcode highligh
        wp_enqueue_script( 'codemirror-overlay-js',
          $plugin_dir . 'assets/codemirror/addon/mode/overlay.js',
          array('cf7-codemirror-js'), $this->version, true
        );
        /**
        * @since 1.3.0 enable search codemirror
        */
        //overlay for shortcode highligh
        wp_enqueue_script( 'codemirror-search-js',
          $plugin_dir . 'assets/codemirror/addon/search/search.js',
          array('cf7-codemirror-js'), $this->version, true
        );
        wp_enqueue_script( 'codemirror-jumptoline-js',
          $plugin_dir . 'assets/codemirror/addon/search/jump-to-line.js',
          array('cf7-codemirror-js'), $this->version, true
        );
        wp_enqueue_script( 'codemirror-matchesonscrollbar-js',
          $plugin_dir . 'assets/codemirror/addon/search/matchesonscrollbar.js',
          array('cf7-codemirror-js'), $this->version, true
        );
        wp_enqueue_script( 'codemirror-searchcursor-js',
          $plugin_dir . 'assets/codemirror/addon/search/searchcursor.js',
          array('cf7-codemirror-js'), $this->version, true
        );
        wp_enqueue_script( 'codemirror-annotatescrollbar-js',
          $plugin_dir . 'assets/codemirror/addon/scroll/annotatescrollbar.js',
          array('cf7-codemirror-js'), $this->version, true
        );
        wp_enqueue_script( 'codemirror-dialog-js',
          $plugin_dir . 'assets/codemirror/addon/dialog/dialog.js',
          array('cf7-codemirror-js'), $this->version, true
        );

        //js beautify
        wp_enqueue_script( 'beautify-js',
          $plugin_dir . 'assets/beautify/beautify.js',
          array('jquery'), $this->version, true
        );
        wp_enqueue_script( 'beautify-html-js',
          $plugin_dir . 'assets/beautify/beautify-html.js',
          array('beautify-js'), $this->version, true
        );
        wp_enqueue_script('jquery-select2', $plugin_dir . 'assets/select2/js/select2.min.js', array( 'jquery' ), $this->version, true );
        /** @since 4.0 enable dark theme */
        $cm_light = apply_filters('cf7sg_admin_editor_theme', '');
        $user_theme = get_user_meta(get_current_user_id(),'_cf7sg_cm_theme', true);
        if(!empty($cm_light) && file_exists(get_stylesheet_directory().'/'.$cm_light) ){
          wp_enqueue_style( "codemirror-theme-css", get_stylesheet_directory_uri().'/'.$cm_light, array(), $this->version, 'all' );
          $user_theme = basename($cm_light, '.css');
          update_user_meta(get_current_user_id(),'_cf7sg_cm_theme', '');
        }else{
          if(empty($user_theme)){
            $user_theme = 'paraiso-light';
            update_user_meta(get_current_user_id(),'_cf7sg_cm_theme', 'light');
          }else{
            $user_theme = ('light'==$user_theme ? 'paraiso-light' : 'material-ocean');
          }
        }
        $cm_js_light = apply_filters('cf7sg_admin_js_editor_theme', '');
        $user_js_theme = get_user_meta(get_current_user_id(),'_cf7sg_js_cm_theme', true);
        if(!empty($cm_js_light) && file_exists(get_stylesheet_directory().'/'.$cm_js_light) ){
          wp_enqueue_style( "codemirror-js-theme-css", get_stylesheet_directory_uri().'/'.$cm_js_light, array(), $this->version, 'all' );
          $user_js_theme = basename($cm_js_light, '.css');
          update_user_meta(get_current_user_id(),'_cf7sg_js_cm_theme', '');
        }else{
          if(empty($user_js_theme)){
            $user_js_theme = 'paraiso-light';
            update_user_meta(get_current_user_id(),'_cf7sg_js_cm_theme', 'light');
          }else{
            $user_js_theme = ('light'==$user_js_theme ? 'paraiso-light' : 'material-ocean');
          }
        }
        $cm_css_light = apply_filters('cf7sg_admin_css_editor_theme', '');
        $user_css_theme = get_user_meta(get_current_user_id(),'_cf7sg_css_cm_theme', true);
        if(!empty($cm_css_light) && file_exists(get_stylesheet_directory().'/'.$cm_css_light) ){
          wp_enqueue_style( "codemirror-css-theme-css", get_stylesheet_directory_uri().'/'.$cm_css_light, array(), $this->version, 'all' );
          $user_css_theme = basename($cm_css_light, '.css');
          update_user_meta(get_current_user_id(),'_cf7sg_css_cm_theme', '');
        }else{
          if(empty($user_css_theme)){
            $user_css_theme = 'paraiso-light';
            update_user_meta(get_current_user_id(),'_cf7sg_css_cm_theme', 'light');
          }else{
            $user_css_theme = ('light'==$user_css_theme ? 'paraiso-light' : 'material-ocean');
          }
        }
        //enqueue the cf7 scripts.
        wpcf7_admin_enqueue_scripts( 'wpcf7' );
        wp_enqueue_script('jquery-clibboard', $plugin_dir . 'assets/clipboard/clipboard.min.js', array('jquery'),$this->version,true);
        wp_enqueue_script( 'cf7-grid-codemirror-js', $plugin_dir . 'admin/js/cf7-grid-codemirror.js', array( 'jquery', 'jquery-ui-tabs', 'cf7-codemirror-js' ), $this->version, true );
        wp_localize_script(
          'cf7-grid-codemirror-js',
          'cf7sgeditor',
          array(
            'mode' =>  'shortcode',
            'theme' => array(
               'light'=>'paraiso-light',
               'dark'=>'material-ocean',
               'user'=>$user_theme
            ),
            'jstheme' => array(
               'light'=>'paraiso-light',
               'dark'=>'material-ocean',
               'user'=>$user_js_theme
            ),
            'csstheme' => array(
               'light'=>'paraiso-light',
               'dark'=>'material-ocean',
               'user'=>$user_css_theme
            ),
            'jserror'=>__('There is a <strong>Javascript error on the page</strong> which prevents the editor from loading properly.', 'cf7-grid-layout'),
            'fixhtmlform'=>__('The editor failed to load.  You may recover your form by copying it into a new form editor, do you wish to continue?', 'cf7-grid-layout'),
          )
        );
        global $post;

        wp_enqueue_script( $this->plugin_name, $plugin_dir . 'admin/js/cf7-grid-layout-admin.js', array('cf7-grid-codemirror-js', 'jquery-ui-sortable' ), $this->version, true );
        wp_localize_script(
          $this->plugin_name,
          'cf7grid',
          array(
            'preHTML' => apply_filters('cf7sg_pre_cf7_field_html', '<div class="field"><label></label>', $post->post_name),
						'postHTML' => apply_filters('cf7sg_post_cf7_field_html', '<p class="info-tip"></p></div>', $post->post_name),
						'requiredHTML' => apply_filters('cf7sg_required_cf7_field_html', '<em>*</em>', $post->post_name),
						'ui' => apply_filters('cf7sg_grid_ui', true, $post->post_name)
          )
        );
        wp_enqueue_script( 'cf7sg-dynamic-tag-js', $plugin_dir . 'admin/js/cf7sg-dynamic-tag.js', array('jquery','wpcf7-admin-taggenerator' ), $this->version, true );
        wp_enqueue_script( 'cf7-benchmark-tag-js', $plugin_dir . 'admin/js/cf7-benchmark-tag.js', array('jquery','wpcf7-admin-taggenerator' ), $this->version, true );
        /** @since 3.2.0 */
        wp_enqueue_script('cf7sg-mail-tag-js', $plugin_dir.'admin/js/mail-tag-helper.js', array('jquery','jquery-clibboard'));
        wp_localize_script('cf7sg-mail-tag-js','mailTagHelper',
          array(
            'msg'=>__('Click to copy!','cf7-grid-layout'),
            'filter'=> __('Filter mailTag %s', 'cf7-grid-layout')
          )
        );
        /** @since 3.3.0 enqueue ui-grid helpers js */
        wp_enqueue_script('ui-grid-helpers-js', $plugin_dir.'admin/js/ui-custom-helper.js', array('jquery'));
        do_action('cf7sg_enqueue_admin_editor_scripts');
        break;
      case 'edit':
        //wp_enqueue_script( $this->plugin_name.'-quick-edit', plugin_dir_url( __FILE__ ) . 'js/cf7-grid-layout-quick-edit.js', false, $this->version, true );
        /** @since 4.4.0 Enabled user script enqueue */
        do_action('cf7sg_enqueue_admin_table_scripts');
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
      __cf7sg( 'Edit Contact Form'),
      __cf7sg( 'Add New'),
      'wpcf7_edit_contact_forms',
      'post-new.php?post_type=wpcf7_contact_form'
    );
    //initial cf7 object when creating new form
    //add_action( 'load-' . $hook, array($this, 'setup_cf7_object'));
    //remove_submenu_page( $menu_slug, $submenu_slug );
    remove_submenu_page( 'wpcf7', 'wpcf7-new' );
    remove_meta_box('slugdiv', $this->cf7_post_type(), 'normal');
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
    if($post_type === $this->cf7_post_type()  ) {
      // debug_msg($args, 'pre-args');
      // $system_dropdowns = get_option('_cf7sg_dynamic_dropdown_system_taxonomy',array());
      /** @since 2.8.3 add wpcf7_type (registered in assets/cf7-table.php) taxonomy to cf7 post type */
      $system_taxonomy = array('wpcf7_type');
      // if(!empty($system_dropdowns)){
      //   foreach($system_dropdowns as $id=>$list){
      //     $system_taxonomy = array_merge($system_taxonomy, $list);
      //   }
      // }
      if(!empty($args['taxonomies'])){
        $system_taxonomy = array_merge($args['taxonomies'], $system_taxonomy);
        $system_taxonomy = array_unique($system_taxonomy);
      }
      $args['taxonomies'] = $system_taxonomy;

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
      /** @since 2.8.1  fix missing delete_posts notices*/
      // $args['capabilities']['delete_posts']= 'wpcf7_delete_posts';
      /** @since 3.0.0 enable better capability management */
      // $args['capability_type'] = 'page';

      $args['map_meta_cap']=true; //allow finer capability mapping.
      $args['capabilities']['edit_post'] = 'wpcf7_edit_contact_form';
      $args['capabilities']['read_post'] = 'wpcf7_read_contact_form';
      $args['capabilities']['delete_post'] = 'wpcf7_delete_contact_form';
   		$args['capabilities']['edit_posts'] = 'wpcf7_edit_contact_forms';
      $args['capabilities']['edit_others_posts']= 'wpcf7_edit_others_contact_forms';
      $args['capabilities']['edit_published_posts']= 'wpcf7_edit_published_contact_forms';
      $args['capabilities']['delete_posts']= 'wpcf7_delete_contact_forms';
      $args['capabilities']['delete_published_posts']= 'wpcf7_delete_published_contact_forms';
      $args['capabilities']['delete_others_posts']= 'wpcf7_delete_others_contact_forms';
      $args['capabilities']['publish_posts']= 'wpcf7_publish_contact_forms';
      $args['capabilities']['read_private_posts']= 'wpcf7_publish_contact_forms';

       // debug_msg($args);
    }
    return $args;
  }
  /**
   * Register custom taxonomy for dynamic dropdown lists
   * Hooked on 'init'
   * @since 1.0.0
   *
  **/
  public function register_dynamic_dropdown_taxonomy(){
    //register the dynamic dropdown taxonomies.
    $dropdowns = get_option('_cf7sg_dynamic_dropdown_taxonomy',array());
    //debug_msg($dropdowns);
    $created = array();
    foreach($dropdowns as $post_lists){
      foreach($post_lists as $slug=>$taxonomy){
        if(!isset($created[$slug])){
          if(is_array($taxonomy)){
            $this->register_dynamic_dropdown($taxonomy);
            $created[$slug] = $slug;
          }
        }
      }
    }
    /** @since 5.0 register dynamic_select list */
    do_action('cf7sg_register_dynamic_lists');
    // debug_msg('created dynamic select ');
  }
  /**
   * function to regsiter dyanmic dropdown taxonomies
   *
   * @since 1.0.0
   * @param      Object    $taxonomy_object     std object with parameters $taxonomy_object->slug, $taxonomy_object->singular, $taxonomy_object->plural, $taxonomy_object->hierarchical .
  **/
  protected function register_dynamic_dropdown($taxonomy_array){
    $slug = $taxonomy_array['slug'];
    $name = $taxonomy_array['singular'];
    $plural = $taxonomy_array['plural'];
    $is_hierarchical = $taxonomy_array['hierarchical'];

    $labels = array(
  		'name'                       =>  $plural,
  		'singular_name'              =>  $name,
  		'menu_name'                  =>  $plural,
  		'all_items'                  =>  'All '.$plural,
  		'parent_item'                =>  'Parent '.$name,
  		'parent_item_colon'          =>  'Parent '.$name.':',
  		'new_item_name'              =>  'New '.$name.' Name',
  		'add_new_item'               =>  'Add New '.$name,
  		'edit_item'                  =>  'Edit '.$name,
  		'update_item'                =>  'Update '.$name,
  		'view_item'                  =>  'View '.$name,
  		'separate_items_with_commas' =>  'Separate '.$plural.' with commas',
  		'add_or_remove_items'        =>  'Add or remove '.$plural,
  		'choose_from_most_used'      =>  'Choose from the most used',
  		'popular_items'              =>  'Popular '.$plural,
  		'search_items'               =>  'Search '.$plural,
  		'not_found'                  =>  'Not Found',
  		'no_terms'                   =>  'No '.$plural,
  		'items_list'                 =>  $plural.' list',
  		'items_list_navigation'      =>  $plural.' list navigation',
  	);
    //labels can be modified post registration
  	$args = array(
  		'labels'                     => $labels,
  		'hierarchical'               => $is_hierarchical,
  		'public'                     => false,
  		'show_ui'                    => true,
  		'show_admin_column'          => false,
  		'show_in_nav_menus'          => false,
  		'show_tagcloud'              => false,
      'show_in_quick_edit'         => false,
      'description'                => 'Contact Form 7 dynamic dropdown taxonomy list',
  	);

    register_taxonomy( $slug, $this->cf7_post_type(), $args );
  }
  /**
   * CF7 Form saved from backend, check if dynamic-select are used
   * Hooked on 'wpcf7_save_contact_form'
   * @since 1.0.0
   * @param  WPCF7_Contact_Form $cf7_form  cf7 form object
  */
  public function save_factory_metas($cf7_form){
    $cf7_post_id = $cf7_form->id();
    //get the tags used in this form
    $tags = $cf7_form->scan_form_tags(); //get your form tags
    $dynamic_lists = cf7sg_get_dynamic_lists();
    $dynamic_tags = array_keys($dynamic_lists);
    $dynamic_fields = array();
    $form_classes = array();
    foreach($tags as $tag){
      if(in_array($tag['basetype'],$dynamic_tags)){
        $dynamic_fields[$tag['basetype']]=$tag;
        $form_classes = array_merge($form_classes, $dynamic_lists[$tag['basetype']]->get_form_classes($tag, $cf7_post_id));
      }
    }
    if(!empty($form_classes)){
      $classes = get_post_meta($cf7_post_id, '_cf7sg_script_classes', true);
      if(empty($classes)){
        $classes = array();
      }
      $form_classes = array_diff(array_unique($form_classes), $classes); //any new classes?
      if(!empty($form_classes)) {
        update_post_meta($cf7_post_id, '_cf7sg_script_classes', array_merge( $form_classes, $classes));
      }
    }
    //check for newly created taxonomy lists.
    if( isset($_POST['cf72post_dynamic_select_taxonomies']) ){
      $created_taxonomies = array();
      $taxonomies = json_decode(str_replace('\"','"',$_POST['cf72post_dynamic_select_taxonomies']), true);
      if(empty($taxonomies)){
        $taxonomies = array();
      }
      foreach($taxonomies as $taxonomy){
        //sanitize fields before saving into the DB.
        foreach($taxonomy as $key=>$value){
          $key = sanitize_key($key);
          $taxonomy[$key] =  sanitize_text_field($value);
        }
        $created_taxonomies[$taxonomy['slug']] = $taxonomy;
      }
      //debug_msg($created_taxonomies);
      $post_lists = $saved_lists = $system_list = array();

      $dropdowns = get_option('_cf7sg_dynamic_dropdown_taxonomy',array());
      foreach($dropdowns as $id => $lists){
        $saved_lists = array_merge($saved_lists, $lists);
      }
      foreach($dynamic_fields as $tag){
        if(isset($tag['values'])){
          $slug='';
          foreach($tag['values'] as $values){
            if(0 == strpos($values, 'slug:') ){
              $slug = str_replace('slug:', '', $values);
              break;
            }
          }
          if(empty($slug)) continue; //not a taxonomy list.
          //is slug newly created?
          if(isset($created_taxonomies[$slug])){
            //store this taxonomy.
            $post_lists[$slug] = $created_taxonomies[$slug];
          }else if(isset($saved_lists[$slug])){
            //retain previously saved list if we are stil using it.
            $post_lists[$slug] = $saved_lists[$slug];
          }else{ //system taxonomy.
            $system_list[] = $slug;
          }
          //store the taxonomy slug in the cf7 metas.
          //$post_lists[$slug] = null;
        }
      }
      //list of taxonomy to register.
      //unset the old value.
      unset($dropdowns[$cf7_post_id]);
      //unshift new value to top of array.
      $dropdowns = array($cf7_post_id => $post_lists) + $dropdowns ;

      update_option('_cf7sg_dynamic_dropdown_taxonomy', $dropdowns);
      //list of system taxonomy to register
      $system_dropdowns = get_option('_cf7sg_dynamic_dropdown_system_taxonomy',array());
      $system_dropdowns[$cf7_post_id] = $system_list;
      update_option('_cf7sg_dynamic_dropdown_system_taxonomy', $system_dropdowns);
    }
  }
  /**
   * Print hiddend field on cf7 post submit box
   * Hooked on 'wpcf7_admin_misc_pub_section'
   * @since 1.0.0
   * @param      string    $post_id    cf7 form post id .
  **/
  public function dynamic_select_choices($post_id){
    echo '<input id="cf72post-dynamic-select" type="hidden" name="cf72post_dynamic_select_taxonomies" />';
  }

  /**
  * Hide the cf7 form editor page author metabox by default.
  * hooked to 'hidden_meta_boxes'.
  *@since 4.6.0
  *@param Array $hidden array of hidden metabox ids.
  *@param WP_Screen  $screen current amdin page screen object.
  *@param Boolean  $use_defaults wether default settings used.
  *@return Array array of hidden metabox ids.
  */
  public function hide_author_metabox($hidden, $screen, $use_defaults){
    if( 'wpcf7_contact_form' != $screen->id ) return $hidden;

    if(in_array('authordiv', $hidden)) return $hidden;

    if($use_defaults) $hidden[] = 'authordiv';
    else {
      $gs = get_option(self::CF7SG_OPTION, array());

      if(!isset($gs['hide_author'])){
        $gs['hide_author']=true; //flag it.
        $uo = get_user_option('metaboxhidden_wpcf7_contact_form');
        if(false === $uo) $uo = array();
        $uo = array_unique( array_merge( $uo + array( 'authordiv' ) ) );
        $hidden = array_unique( array_merge( $hidden + array( 'authordiv' ) ) );
        update_option(self::CF7SG_OPTION,$gs);
        update_user_option( get_current_user_id(), 'metaboxhidden_wpcf7_contact_form', $uo);
      }
    }
    return $hidden;
  }
  /**
  * Function to add the metabox to the cf7 post edit screen
  * This adds the main editor, hooked on 'add_meta_boxes'
  * @since 1.0.0
  */
  public function edit_page_metabox() {
    //add_meta_box( string $id, string $title, callable $callback, string $screen, string $context, string $priority, array $callback_args)
    if( post_type_exists( $this->cf7_post_type() ) ) {
      add_meta_box( 'meta-box-main-cf7-editor',
        __cf7sg( 'Edit Contact Form' ),
        array($this , 'main_editor_metabox_display'),
        $this->cf7_post_type(),
        'normal',
        'high'
      );
      add_meta_box( 'meta-box-cf7-info',
        __cf7sg( 'Information' ),
        array($this , 'info_metabox_display'),
        $this->cf7_post_type(),
        'side',
        'high'
      );
      /** @since 1.1.0 add helper metabox */
      add_meta_box( 'meta-box-cf7sg-helper',
        __( 'Actions & Filters', 'cf7-grid-layout' ),
        array($this , 'helper_metabox_display'),
        $this->cf7_post_type(),
        'side',
        'default'
      );
      /** @since 4.6.0 enable page redirect */
      add_meta_box(
          'cf7sg-redirect',                 // Unique ID
          'Redirect on Submission',      // Box title
          array($this, 'display_rediect_metabox'),  // Content callback, must be of type callable
          $this->cf7_post_type()                            // Post type
      );
    }
  }
  /**
  * Callback function to disolay the main editor meta box
  * @since 1.0.0
  */
  public function main_editor_metabox_display($post){
    if('auto-draft' !== $post->post_status){
      wpcf7_contact_form($post); //set the post
    }
    $post_id = $post->ID;
    $cf7_form = wpcf7_get_current_contact_form();
  	if ( ! $cf7_form ) {
      $args = apply_filters('cf7sg_new_cf7_form_template_arguments', array());
  		$cf7_form = WPCF7_ContactForm::get_template($args);
  	}
  	require_once WPCF7_PLUGIN_DIR . '/admin/includes/editor.php';
  	require_once plugin_dir_path( __FILE__ )  . 'partials/cf7-admin-editor-display.php';
  }

  /**
   * Re-introduces the wordpress 'wpcf7_admin_misc_pub_section' for plugins to add their fields for submission
   * Hooked to 'post_submitbox_misc_actions' which fires after post dat/time parameters are printed
   * @since 1.0.0
   * @param      WP_Post    $post    post object being edited/created.
  **/
  public function cf7_post_submit_action($post){
    if($this->cf7_post_type() == $post->post_type){
      do_action( 'wpcf7_admin_misc_pub_section', $post->ID );
    }
  }
  /**
  * Callback function to disolay the main editor meta box
  * @since 1.0.0
  */
  public function info_metabox_display($post){
  	require_once plugin_dir_path( __FILE__ )  . 'partials/cf7-info-metabox-display.php';
  }
  /**
  * Callback function to disolay the helper meta box
  * @since 1.0.0
  */
  public function helper_metabox_display($post){
    require_once plugin_dir_path( __FILE__ )  . '/partials/cf7-helper-metabox-display.php';
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
  *
  *
  *@since 4.6.0
  *@param string $param text_description
  *@return string text_description
  */
  public function display_rediect_metabox($post){
    require_once plugin_dir_path( __FILE__ )  . '/partials/cf7-redirect-metabox-display.php';
  }
  /**
  * Clean up post meta, delete sgcf7 corresponding view post.
  * Hooked to action 'before_delete_post'.
  *@since 4.3.0
  *@param int $post_id post ID.
  *@param WP_Post post object.
  */
  public function delete_post($post_id){
    if(get_post_type($post_id) != WPCF7_ContactForm::post_type) return;
    //remove viwing post.
    $preview_id = get_post_meta($post_id, '_cf7sg_form_page',true);
    if(!empty($preview_id)) wp_delete_post($preview_id);
  }
  /**
   * Save cf7 post using ajax
   * Hooked to 'save_post_wpcf7_contact_form'
   * @since 1.0.0
  **/
  public function save_post($post_id, $post, $update){
    //make sure this is the edit page and not a quick-edit.
    if( !isset($_POST['_wpcf7nonce']) ){
      return;
    }
    //validate the nonce.
    check_admin_referer( 'wpcf7-save-contact-form_' . $post_id, '_wpcf7nonce');

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
      return;
    }

    switch($post->post_status){
      case 'auto-draft':
      case 'trash':
        return;
      default:
        break;
    }

    // debug_msg($_POST, 'submitted ');
    $args = $_REQUEST;
  	$args['id'] = $post_id;

  	$args['title'] = isset( $_POST['post_title'] ) ? sanitize_text_field($_POST['post_title'], 'Contact Form', 'save') : null;
  	$args['locale'] = isset( $_POST['wpcf7-locale'] ) ? sanitize_text_field($_POST['wpcf7-locale']) : null;
  	$args['form'] = '';
    $allowed_tags = wp_kses_allowed_html( 'post' ); //filtered in function below.
    /** @since 4.8.1 alllow custom input html*/
    $allowed_tags['input']=array( //add additional input html elements
      'type'=>1,'name'=>1,'placeholder'=>1,'value'=>1,'maxlength'=>1,'minlength'=>1, //input fields.
      'spellcheck'=>1,'size'=>1,'readonly'=>1,'pattern'=>1,'list'=>1, //input fields.
      'class'=>1,
      'id'=>1,
      'data-*'=>1,
    );
    $allowed_tags['script']=array('type'=>1);
    $cf7_key = $post->post_name;
    $allowed_tags = apply_filters('cf7sg_kses_allowed_html',$allowed_tags, $cf7_key);
    if(isset( $_POST['wpcf7-form'] )){
      $args['form'] = wp_kses($_POST['wpcf7-form'], $allowed_tags);
    }
  	$args['mail'] = isset( $_POST['wpcf7-mail'] ) ? wpcf7_sanitize_mail( $_POST['wpcf7-mail'] ): array();
  	$args['mail_2'] = isset( $_POST['wpcf7-mail-2'] ) ? wpcf7_sanitize_mail( $_POST['wpcf7-mail-2'] ): array();
  	$args['messages'] = isset( $_POST['wpcf7-messages'] ) ? $_POST['wpcf7-messages'] : array();
	foreach($args['messages'] as $key=>$value){
		$args['messages'][$key] = sanitize_text_field($value);
	}
  	$args['additional_settings'] = isset( $_POST['wpcf7-additional-settings'] ) ? sanitize_textarea_field($_POST['wpcf7-additional-settings']) : '';

    //need to unhook this function so as not to loop infinitely
    //debug_msg($args, 'saving cf7 posts');
    remove_action('save_post_wpcf7_contact_form', array($this, 'save_post'), 10,3);
    // debug_msg($args['form'],'form ');
    $contact_form = wpcf7_save_contact_form( $args );
    add_action('save_post_wpcf7_contact_form', array($this, 'save_post'), 10,3);
    //flag as a grid form
    //update_post_meta($post_id, 'cf7_grid_form', true); removed since v2.3 as not used.
    //save sub-forms if any
    $sub_forms = json_decode(stripslashes($_POST['cf7sg-embeded-forms']));
    if(empty($sub_forms)) $sub_forms = array();
    if(!is_array($sub_forms)) $sub_forms = array($sub_forms);
    $sanitised_sub_forms = array();
  	foreach($sub_forms as $field){
      $sanitised_sub_forms[] = sanitize_text_field($field);
	  }
    update_post_meta($post_id, '_cf7sg_sub_forms', $sanitised_sub_forms);
    //save form fields which are in tabs or tables.
    $tt_fields = json_decode(stripslashes($_POST['cf7sg-table-fields']));
    if(empty($tt_fields)) $tt_fields = array();
    if(!is_array($tt_fields)) $tt_fields = array($tt_fields);
  	$sanitised_table_fields = array();
  	foreach($tt_fields as $table_fields){
      if(is_object($table_fields)){ /** @since 2.4.2 track fields in each tables. */
        $table_fields = get_object_vars($table_fields); /*convert to array*/
        $table = array_keys($table_fields);
        $table = sanitize_text_field($table[0]);
        $table_fields = $table_fields[$table];
        $sanitised_table_fields[$table]=array();
        foreach($table_fields as $field) $sanitised_table_fields[$table][] = sanitize_text_field($field);
      }else $sanitised_table_fields[] = sanitize_text_field($table_fields);
  	}
    update_post_meta($post_id, '_cf7sg_grid_table_names', $sanitised_table_fields);
    //tabs
    $tt_fields = json_decode(stripslashes($_POST['cf7sg-tabs-fields']));
    if(empty($tt_fields)) $tt_fields = array();
    if(!is_array($tt_fields)) $tt_fields = array($tt_fields);
  	$sanitised_tab_fields = array();
  	foreach($tt_fields as $tab_field){
      if(is_object($tab_field)){  /** @since 2.4.2 track fields in each tabs. */
        $tab_field = get_object_vars($tab_field); /*convert to array*/
        $tab = array_keys($tab_field);
        $tab = sanitize_text_field($tab[0]);
        $tab_field = $tab_field[$tab];
        $sanitised_tab_fields[$tab]=array();
        foreach($tab_field as $field) $sanitised_tab_fields[$tab][] = sanitize_text_field($field);
      }else $sanitised_tab_fields[] = sanitize_text_field($tab_field);
  	}
    update_post_meta($post_id, '_cf7sg_grid_tabs_names', $sanitised_tab_fields);
    /** track toggled fields.
    * @since 2.5*/
    $tt_fields = json_decode(stripslashes($_POST['cf7sg-toggle-fields']));
    if(empty($tt_fields)) $tt_fields = array();
    if(!is_array($tt_fields)) $tt_fields = array($tt_fields);
  	$sanitised_toggled_fields = array();
  	foreach($tt_fields as $tgg_field){
      if(is_object($tgg_field)){
        $tgg_field = get_object_vars($tgg_field); /*convert to array*/
        $tgg = array_keys($tgg_field);
        $tgg = sanitize_text_field($tgg[0]);
        $tgg_field = $tgg_field[$tgg];
        $sanitised_toggled_fields[$tgg]=array();
        foreach($tgg_field as $field) $sanitised_toggled_fields[$tgg][] = sanitize_text_field($field);
      }else $sanitised_toggled_fields[] = sanitize_text_field($tgg_field);
  	}
    update_post_meta($post_id, '_cf7sg_grid_toggled_names', $sanitised_toggled_fields);
    /** @since 4.0.0 track tabbed toggles */
    $ttgls = json_decode(stripslashes($_POST['cf7sg-tabbed-toggles']));
    if(empty($ttgls)) $ttgls = array();
    if(!is_array($ttgls)) $ttgls = array($ttgls);
    update_post_meta($post_id, '_cf7sg_grid_tabbed_toggles', $ttgls);
    /** @since 4.0.0 track grouped toggles */
    $ttgls = json_decode(stripslashes($_POST['cf7sg-grouped-toggles']));
    if(empty($ttgls)) $ttgls = array();
    if(is_object($ttgls)) $ttgls = get_object_vars($ttgls); //convert to array.
    else $ttgls = array();
    update_post_meta($post_id, '_cf7sg_grid_grouped_toggles', $ttgls);
    //flag tab & tables for more efficient front-end display.
    $has_tabs =  ( 'true' === $_POST['cf7sg-has-tabs']) ? true : false;
    update_post_meta($post_id, '_cf7sg_has_tabs', $has_tabs);
    $has_tables = ( 'true' === $_POST['cf7sg-has-tables']) ? true : false;
    update_post_meta($post_id, '_cf7sg_has_tables', $has_tables);
    $has_toggles = ( 'true' === $_POST['cf7sg-has-toggles']) ? true : false;
    update_post_meta($post_id, '_cf7sg_has_toggles', $has_toggles);
    /** @since 3.3.5 track toggles in tabs */
    // $toggle_in_tabs = json_decode(stripslashes($_POST['cf7sg-toggle-in-tabs']));
    // if(empty($toggle_in_tabs)) $toggle_in_tabs = array();
    // if(!is_array($toggle_in_tabs)) $toggle_in_tabs = array($toggle_in_tabs);
    // update_post_meta($post_id, '_cf7sg_grid_toggle_in_tabs', $toggle_in_tabs);
    /**
    * @since 1.2.3 disable cf7sg styling/js for non-cf7sg forms.
    */
    $is_cf7sg = ( 'true' === $_POST['is_cf7sg_form']) ? true : false;
    update_post_meta($post_id, '_cf7sg_managed_form', $is_cf7sg);
    update_post_meta($post_id, '_cf7sg_version', $this->version);
    /** @since 3.0.0 track script classes for loading for js/css in front-end */
    $classes = sanitize_text_field($_POST['cf7sg-script-classes']);
    $classes = explode(',', trim($classes, ','));
    // debug_msg($classes, 'script classes');
    update_post_meta($post_id, '_cf7sg_script_classes', $classes);
    /**
    *@since 2.3.0 the duplicate functionality has been instored and therefore any new meta fields added to this plugin needs to be added to the duplication properties too.
    */
    /** @since 4.0.0 save user theme pref */
    if(isset($_POST['cf7sg_codemirror_theme'])){
      update_user_meta(get_current_user_id(),'_cf7sg_cm_theme', $_POST['cf7sg_codemirror_theme']);
    }
    if(isset($_POST['cf7sg_js_codemirror_theme'])){
      update_user_meta(get_current_user_id(),'_cf7sg_js_cm_theme', $_POST['cf7sg_js_codemirror_theme']);
    }
    //save js file if used.
    $path = get_stylesheet_directory();
    if(!empty($_POST['cf7sg_js_file'])){
      //check if the file name is changed.
      if(!empty($_POST['cf7sg_prev_js_file']) && file_exists(ABSPATH. $_POST['cf7sg_prev_js_file'])){
        if( !unlink(ABSPATH.$_POST['cf7sg_prev_js_file']) ) debug_msg('CF7SG ADMIN: unable to delete file '.$_POST['cf7sg_prev_js_file']);
      }
      if( !is_dir("$path/js/") ) mkdir("$path/js/");
      file_put_contents( "$path/js/{$cf7_key}.js", stripslashes($_POST['cf7sg_js_file']) );
    }else if( isset($_POST['cf7sg_js_file']) && file_exists("$path/js/{$cf7_key}.js") ) { //delete file.
      if( !unlink("$path/js/{$cf7_key}.js") ) debug_msg("CF7SG ADMIN: unable to delete file $path/js/{$cf7_key}.js");
    }
    //save css file.
    if(!empty($_POST['cf7sg_css_file'])){
      //check if the file is changed.
      if(!empty($_POST['cf7sg_prev_css_file']) && file_exists(ABSPATH.$_POST['cf7sg_prev_css_file'])){
        if( !unlink(ABSPATH.$_POST['cf7sg_prev_css_file']) ) debug_msg('CF7SG ADMIN: unable to delete file '.$_POST['cf7sg_prev_css_file']);
      }
      if( !is_dir("$path/css/") ) mkdir("$path/css/");
      file_put_contents( $path."/css/{$cf7_key}.css", stripslashes($_POST['cf7sg_css_file']));
    }else if( isset($_POST['cf7sg_css_file']) && file_exists("$path/css/{$cf7_key}.css") ) { //delete file.
      if( !unlink("$path/css/{$cf7_key}.css") ) debug_msg("CF7SG ADMIN: unable to delete file $path/css/{$cf7_key}.css");
    }
    //jstags comments.
    if(empty($_POST['cf7sg_jstags_comments'])) update_post_meta($post->ID, '_cf7sg_disable_jstags_comments',1);
    else update_post_meta($post->ID, '_cf7sg_disable_jstags_comments',0);
    /** @since 4.3.0 create/update a preview form post for this form */
    $preview_id = get_post_meta($post->ID, '_cf7sg_form_page',true);
    $content = '[cf7form cf7key="'.$cf7_key.'"';
    $content .= isset($_POST['post_lang_choice']) ? ' lang="'.$_POST['post_lang_choice'].'"]':']';
    $prev_page = array(
      'post_title'=>sanitize_text_field($_POST['post_title']),
      'post_content'=>$content,
      'post_status'=>'draft',
      'post_type'=>'cf7sg_page',
      // 'post_name' => sanitize_title( $title ),
    );
    /** @since 4.6.0 redirect on submit */
    if(isset($_POST['cf7sg_page_redirect'])){
      $redirect_to = absint($_POST['cf7sg_page_redirect']);
      update_post_meta($post->ID, '_cf7sg_page_redirect',$redirect_to);
      if(isset($_POST['cache_cf7sg_submit']) and isset($_POST['cf7sg_cached_time']) and isset($_POST['cf7sg_cached_unit'])){
        $cache = floatval( $_POST['cf7sg_cached_time']);
        if($cache>0){
          $cache=array($cache, absint($_POST['cf7sg_cached_unit']));
          update_post_meta($post->ID, '_cf7sg_cache_redirect_data',$cache);
        }
      }else delete_post_meta($post->ID, '_cf7sg_cache_redirect_data');
    }
    if(empty($cf7_key)) $prev_page['post_content'] = '[contact-form-7 id="'.$post->ID.'"]';
    if( !empty($preview_id) ){
      $prev_page['ID'] = $preview_id;
    }
    $preview_id = wp_insert_post($prev_page, true);
    if(!is_wp_error($preview_id)){
      update_post_meta($post->ID, '_cf7sg_form_page',$preview_id);
    }
    /** @since 4.9.2 fire form saving action so as to prevent double save_post hook calls on other plugins */
    do_action('cf7sg_save_post',$post_id, $post, $update);
  }
  /**
  * Print default js template,
  * Hooked to 'cf7sg_default_custom_js_template'
  *@since 4.0.0
  *@param string $form_key form key.
  */
  public function print_default_js($form_key){
    $js_file = str_replace(ABSPATH, '', get_stylesheet_directory()."/js/{$form_key}.js");
    include_once 'partials/cf7-default-js.php';
  }
  /**
  *
  *
  *@since 2.3.0
  *@param string $new_form_id new form id to duplciate to.
  *@param string $form_id form id to duplciate.
  */
  public function duplicate_form_properties($new_form_id, $form_id){
    //these properties will be preceded with an '_' by the cf7 plugin before being duplicated.
    $properties = array('_cf7sg_sub_forms', '_cf7sg_grid_table_names', '_cf7sg_grid_tabs_names', '_cf7sg_has_tabs', '_cf7sg_has_tables', '_cf7sg_managed_form','_cf7sg_script_classes', '_cf7sg_version');
    $properties = apply_filters('cf7sg_duplicate_form_properties', $properties);
    foreach($properties as $field){
      $value = get_post_meta($form_id, $field, true);
      if(!empty($value)) update_post_meta($new_form_id,$field, $value);
    }
  }
  /**
  * Duplicate form.
  *
  *@since 2.3.0
  *@param string $param text_description
  *@return string text_description
  */
  public function duplicate_cf7_form(){
    global $pagenow;
    if($pagenow != 'post.php') return;
    if(isset($_GET['action']) && 'cf7copy' == $_GET['action']){
      $action = 'wpcf7-copy-contact-form_' . $_GET['post'];
      if( !wp_verify_nonce( $_GET['_wpnonce'], $action )){
        die( 'Security check error: Try to reload the page and try again.' );
      }
      if ( $form = wpcf7_contact_form( $_GET['post'] ) ) {
  			$new_form = $form->copy();
  			$new_form->save();
        $this->duplicate_form_properties($new_form->id(), $_GET['post']);
        wp_safe_redirect( admin_url( '/post.php?post='.$new_form->id().'&action=edit' ));
        exit;
  		}
    }
  }
  /**
  * Filtered allowed html tags & attributes, add data-button to div tags to ensure forms are saved properly.
  * Hooked on 'wp_kses_allowed_html'
  *@since 1.0.0
  *@param array $allowed array of allowed tags and attributes.
  *@param mixed $context  context in which content is filtered.
  *@return array  allowed tags and ttribtues.
  */
  public function custom_kses_rules($allowed, $context){
    if(is_array($context)){
      return $allowed;
    }
    if('post'===$context){
       $allowed['div']['data-button'] = true; //table buttons.
       $allowed['div']['data-form'] = true; //sub-forms.
       $allowed['div']['data-on'] = true; //toggles.
       $allowed['div']['data-off'] = true; //toggles.
       $allowed['div']['data-open'] = true; //accordion.
       $allowed['div']['data-group'] = true; //accordion.
       $allowed['div']['data-config-field'] = true; //cf7 plugin.
    }
    return $allowed;
  }
  /**
  * Filters the default form loaded when a new CF7 form is created
  * Hooked on 'wpcf7_default_template'
  * @since 1.0
  * @param string $template  the html string for the form tempalte
  * @param string $prop  the template property required.
  */
  public function default_cf7_form($template, $prop){
	  if($prop !== 'form') return $template;
    include( plugin_dir_path( __FILE__ ) . '/partials/cf7-default-form.php');
    return $template;
  }
  /**
   * Ajax function to return the content of a cf7 form
   * Hooked on 'wp_ajax_get_cf7_content'
   * @since 1.0.0
   * @return     String    cf7 form content.
  **/
  public function get_cf7_content(){
    if( !isset($_POST['nonce']) ){
      echo 'error, nonce failed, try to reload the page.';
      wp_die();
    }
    if(!wp_verify_nonce($_POST['nonce'], 'wpcf7-save-contact-form_' . $_POST['id'], '_wpcf7nonce')){
      echo 'error, nonce failed, try to reload the page.';
      wp_die();
    }
    $cf7_key = sanitize_text_field($_POST['cf7_key']);
    $sub_forms = get_posts(array(
      'post_type' => $this->cf7_post_type(),
      'post_name__in' => array($cf7_key)
    ));
    $form='';
    if(!empty($sub_forms)){
      $form = get_post_meta($sub_forms[0]->ID, '_form', true);
      echo $form;
    }else{
      echo 'unable to find form '.$cf7_key;
    }
    wp_die();
  }
  /**
   * Adds a [taxonomy] tag to cf7 forms
   * Hooked on 'wpcf7_admin_init'
   * @since 1.0.0
  **/
  public function cf7_shortcode_tags(){
    if ( class_exists( 'WPCF7_TagGenerator' ) ) {
      /** @since 4.10.0 enable extension of dynamic lists */
      $tag_generator = WPCF7_TagGenerator::get_instance();
      $tag_generator->add(
        'benchmark', //tag id
        __( 'benchmark', 'cf7_2_post' ), //tag button label
        array($this,'benchmark_tag_generator') //callback
      );
    }
    /** @since 4.10.0 abstract out dynamic lists */
    $lists = cf7sg_get_dynamic_lists();
    foreach($lists as $l) $l->register_cf7_tag();
  }

  /**
	 * Benchmark input screen displayt.
	 *
	 * This function is called by cf7 plugin, and is registered with a hooked function above
	 *
	 * @since 1.0.0
	 * @param WPCF7_ContactForm $contact_form the cf7 form object
	 * @param array $args arguments for this form.
	 */
	function benchmark_tag_generator( $contact_form, $args = '' ) {
    $args = wp_parse_args( $args, array() );
		include( plugin_dir_path( __FILE__ ) . '/partials/cf7-benchmark-tag.php');
	}

  /**
  * Deactivate this plugin if CF7 plugin is deactivated.
  * Hooks on action 'admin_init'
  * @since 2.1
  */
  //public function deactivate_cf7_polylang( $plugin, $network_deactivating ) {
  public function check_plugin_dependency() {
    //if either the polylang for the cf7 plugin is not active anymore, deactive this extension
    if( !is_plugin_active("contact-form-7/wp-contact-form-7.php") ){
        deactivate_plugins( "cf7-grid-layout/cf7-grid-layout.php" );
        debug_msg("Deactivating Smart Grid");

        $button = '<a href="'.network_admin_url('plugins.php').'">Return to Plugins</a></a>';
        wp_die( '<p><strong>CF7 smart Grid-layout Extension</strong> requires <strong>Contact Form 7</strong> plugin, and has therefore been deactivated!</p>'.$button );

        return false;
    }
    return true;
  }
  /**
  * Add disabled button message on hover to cf7 messages.
  * Hooked to 'wpcf7_messages', see file contact-form-7/includes/contact-form-template.php fn messages().
  *@since 2.6.0
  *@param array $messages array of messages to filter.
  *@return array array of cf7 messages.
  */
  public function disabled_message($messages){
    $messages['submit_disabled'] = array(
			'description'
				=> __( "Hover message for disabled submit/save button", 'cf7-grid-layout' ),
			'default'
				=> __( "Disabled!  To enable, check the acceptance field.", 'cf7-grid-layout' ),
		);
    /** @sicne 2.8.0 */
    $messages['max_table_rows'] = array(
			'description'
				=> __( "Message displayed when max tables rows reached.", 'cf7-grid-layout' ),
			'default'
				=> __( "You have reached the maximum number of rows.", 'cf7-grid-layout' ),
		);
    return $messages;
  }
  /**
  * Adds pretty pointers to help users.
  * Hooked on 'admin_enqueue_scripts'
  *@since 2.6.0
  *@param string $hook_suffix current page.
  */
  public function pretty_admin_pointers($hook_suffix){
    $screen = get_current_screen();
    if (!isset($screen) || $this->cf7_post_type() != $screen->post_type || in_array( $hook_suffix, array('post.php', 'post-new.php'), true)){
      return;
    }
    $enqueue_pointer = false;
    // Get array list of dismissed pointers for current user and convert it to array
    $user_id = get_current_user_id();
    $dismissed_pointers = explode( ',', get_user_meta( $user_id, 'dismissed_wp_pointers', true ) );
    $pointers = array();
    /**
    * Filter to add custom pointers for user iterface.
    * @var array $pointers an array of $pointer_id=>array($content, $arrow, $valign) key/value pairs to filter.  The key is the pointer id to identify which ones the user has dismissed.  The value is an array with the message $content, the position of the $arrow (left|right|top|bottom), the vertical alignment ($valign) of the box (center|top|bottom).
    * @return array an array of pointers, which will be checked agains the current user.
    */
    $filter_pointers = apply_filters('cf7sg_plugin_pointers-'.$screen->id, array());
    foreach($filter_pointers as $id=>$pointer){
    	// Check if our pointer is not among dismissed ones
    	if( !in_array( $id, $dismissed_pointers ) ) {
        $enqueue_pointer = true;
        $pointers[$id] = array($pointer[0], $pointer[1], $pointer[2], $pointer[3]);
      }
    }
  	// Enqueue pointer CSS and JS files, if needed
  	if( $enqueue_pointer ) {
  		wp_enqueue_style( 'wp-pointer' );
  		wp_enqueue_script( 'wp-pointer' );
      wp_enqueue_script('cf7sg-pointer-js', plugin_dir_url( __DIR__ ).'admin/js/cf7sg-pointers.js', array('jquery'), $this->version, true);
      wp_localize_script('cf7sg-pointer-js', 'cf7sg_pointers',
        array('pointers'=>$pointers, 'next'=>__('Next', 'cf7-grid-layout'))
      );
      // Add footer scripts using callback function
      //add_action( 'admin_print_footer_scripts', array($this, 'pretty_pointer_script') );
  	}
  }
  /**
  * CF7 Table pointer notices.
  * Hooked on 'cf7sg_plugin_pointers-edit-wpcf7_contact_form'
  *@since 2.6.0
  *@param array $pointers array of pointers to suplement
  *@return array array of pointers element-id=>hmtl to display key value pairs.
  */
  public function edit_pointers($pointers){
    ob_start();
    include_once 'partials/pointers/cf7sg-pointer-update-forms.php';
    $content =ob_get_contents();
    $pointers['update_forms_pointer'] = array($content, 'left', 'center','');
    /* tutorials */
    ob_clean();
    include_once 'partials/pointers/cf7sg-pointer-tutorials.php';
    $content =ob_get_contents();
    $pointers['tutorials_pointer'] = array($content, 'left', 'center','a[href="admin.php?page=cf7sg_help"]');
    /* new tutorials */
    ob_clean();
    include_once 'partials/pointers/cf7sg-pointer-tutorial-advance.php';
    $content =ob_get_contents();
    $pointers['new_tutorials_pointer'] = array($content, 'left', 'center','a[href="admin.php?page=cf7sg_help"]');
    /* shortcodes */
    ob_clean();
    include_once 'partials/pointers/cf7sg-pointer-shortcodes.php';
    $content = ob_get_clean();
    $pointers['cf7sg_shortcodes'] = array($content, 'top', 'top','');
    return $pointers;
  }
  /**
  * CF7 Edit pointer notices.
  * Hooked on 'cf7sg_plugin_pointers-wpcf7_contact_form'
  *@since 2.6.0
  *@param array $pointers array of pointers to suplement
  *@return array array of pointers element-id=>hmtl to display key value pairs.
  */
  public function post_pointers($pointers){
    ob_start();
    include_once 'partials/pointers/cf7sg-pointer-editor-full-screen.php';
    $content =ob_get_contents();
    //content / arrow [top,bottom,left,right] / box align [top,bottom,center] / css selector.
    $pointers['full_screen'] = array($content, 'left', 'center','#full-screen-cf7');
    /* shortcodes */
    ob_clean();
    include_once 'partials/pointers/cf7sg-pointer-editor-tabs.php';
    $content =ob_get_contents();
    $pointers['editor_tabs'] = array($content, 'right', 'center','#form-editor-tabs>ul>li.ui-tabs-active');
    /* shortcodes */
    ob_clean();
    include_once 'partials/pointers/cf7sg-pointer-editor-rows-control.php';
    $content = ob_get_contents();
    if(!empty($content)){
      $pointers['row_controls'] = array($content, 'right', 'center','#grid-form>.container>.row>.row-controls');
      ob_clean();
    }
    /* preview form */
    ob_clean();
    include_once 'partials/pointers/cf7sg-pointer-editor-preview-form.php';
    $content = ob_get_contents();
    if(!empty($content)){
      $pointers['preview_form'] = array($content, 'right', 'center','#preview-form-link');
      ob_clean();
    }
    include_once 'partials/pointers/cf7sg-pointer-editor-column-control.php';
    $content = ob_get_contents();
    if(!empty($content)){
      $pointers['column_controls'] = array($content, 'left', 'center','#grid-form > .container:first-child > .row > .columns:first-child > .grid-column > span.icon-code');
      ob_clean();
    }
    include_once 'partials/pointers/cf7sg-pointer-tag-dynamic-dropdown.php';
    $content = ob_get_contents();
    $pointers['dynamic_dropdown'] = array($content, 'left', 'center','#top-tags>#tag-generator-list > a[title*="dynamic-dropdown"]');
    ob_clean();
    /* #optional-editors */
    include_once 'partials/pointers/cf7sg-pointers-editor-optional-js-css.php';
    $content = ob_get_contents();
    $pointers['js_css_editors'] = array($content, 'left', 'center','#optional-editors');
    ob_clean();
    include_once 'partials/pointers/cf7sg-pointer-tag-benchmark.php';
    $content = ob_get_clean();
    $pointers['benchmark'] = array($content, 'left', 'center','#top-tags>#tag-generator-list > a[title*="benchmark"]');
    return $pointers;
  }
  /**
  * Rest meta_caps for better fine tuning of user caps.
  * hooked to 'wpcf7_map_meta_cap'.
  *@since 3.0.0
  *@param array $caps key->value pairs of capabilities.
  *@return array key->value pairs of capabilities.
  */
  public function reset_meta_cap($caps){
    return array(
      'wpcf7_read_contact_form'=>'read_post',
      'wpcf7_edit_contact_form' =>'edit_post',
      'wpcf7_edit_contact_forms' => 'edit_posts',
      'wpcf7_edit_others_contact_forms'=>'edit_others_posts',
      'wpcf7_edit_published_contact_forms'=>'edit_published_posts',
      'wpcf7_delete_contact_form'=>'delete_post',
      'wpcf7_delete_contact_forms'=>'delete_posts',
      'wpcf7_delete_published_contact_forms'=>'delete_published_posts',
      'wpcf7_delete_others_contact_forms'=>'delete_others_posts',
      'wpcf7_publish_contact_forms'=>'publish_posts',
      'wpcf7_publish_contact_forms'=>'read_private_posts',
      'wpcf7_submit'=>'read', /** @since 3.2.1 to fix subscribers_only. */
    );
  }
  /**
  * CF7 plugin by default sets form post status to 'publish' regardless of user capability.
  * This functions rectifies this.   Hooked to 'wp_insert_post_data'.
  *@since 3.0.0
  *@param array $data sanitised post data.
  *@return array post data.
  */
  public function pending_for_review($data, $post){
    if($this->cf7_post_type() != $data['post_type']) return $data;

    $post_type_object = get_post_type_object( $this->cf7_post_type() );
    //check if user can publish.
    if(isset($post['ID']) && !current_user_can($post_type_object->cap->publish_posts, $post['ID']) && $data['post_status']=='publish'){
      $data['post_status']='pending';
    }
    // debug_msg('post status '.$data['post_status']);
    return $data;
  }
  /**
  * Enable all cf7 capabilites for editors.
  * Hooked to action 'admin_init'.
  *@since 3.0.0
  */
  public function enable_cf7_editor_role(){
    global $wp_roles;
    if ( ! isset( $wp_roles ) ) $wp_roles = new WP_Roles();
    $caps=array('wpcf7_edit_contact_forms','wpcf7_edit_others_contact_forms','wpcf7_edit_published_contact_forms','wpcf7_read_contact_forms','wpcf7_publish_contact_forms','wpcf7_delete_contact_forms','wpcf7_delete_published_contact_forms','wpcf7_delete_others_contact_forms');
    $fe = $wp_roles->get_role('editor');
    if(!empty($fe)){ /** @since 3.0.3 in case editor role is deleted */
      foreach($caps as $cap) $fe->add_cap($cap);
    }
    $caps[]='wpcf7_manage_integration';
    $ad = $wp_roles->get_role('administrator');
    if(!empty($ad)){ /** @since 3.0.3 in case admin role is deleted */
      foreach($caps as $cap) $ad->add_cap($cap);
    }

    /** @since 3.1.3  redirect cf7 plugin pages*/
    global $pagenow;
    if ( $pagenow == 'admin.php' && isset( $_GET['page'] ) ) {
      $url = '';
      switch($_GET['page']){
        case 'wpcf7-new':
          $url = "post-new.php?post_type=wpcf7_contact_form" ;
          break;
        case 'wpcf7':
          if(isset($_GET['post'])) $url = "post.php?post={$_GET['post']}&action=edit&classic-editor";
          else $url = "edit.php?post_type=wpcf7_contact_form";
          break;
      }
      if(!empty($url)){
        wp_redirect( admin_url( $url ));
        exit;
      }
    }
  }
  /**
  * Add grid helper hooks for individual tags.
  * Hooked to action 'cf7sg_ui_grid_helper_hooks'.
  *@since 3.3.0
  */
  public function print_helper_hooks(){
    require_once plugin_dir_path( __FILE__ ) .'partials/helpers/cf7sg-form-fields.php';
  }
  /**
  * Setup mailtags or toggles.
  *
  *@since 4.0.0
  *@param string $param text_description
  *@return string text_description
  */
  public function setup_cf7_mailtags($mailtags = array()){
    // return $mailtags;
    $contact_form = WPCF7_ContactForm::get_current();
    $fields = get_post_meta($contact_form->id(), '_cf7sg_grid_toggled_names', true);
    //update_post_meta($post_id, '_cf7sg_grid_tabbed_toggles', $ttgls);
    if(!empty($fields)){
      $toggles = array_keys($fields);
      $groups = get_post_meta($contact_form->id(), '_cf7sg_grid_grouped_toggles', true);
      if(!empty($groups)){
        foreach($groups as $group=>$grp_toggles){
          $toggles = array_diff($toggles, $grp_toggles);
          array_push($toggles, $group);
        }
      }
      foreach($toggles as $tgl) $mailtags[]= 'cf7sg-toggle-'.$tgl;
    }
    //add a general form tag.
    $mailtags[]= 'cf7sg-form-'.get_cf7form_key($contact_form->id());
    return $mailtags;
  }
  /**
  * Funciton to to initialise admin notices.
  * hooked on 'admin_init'
  *@since 4.1.0
  */
  public function init_notices(){
    $grid_settings = get_option( self::CF7SG_OPTION, array());
    //if plugin settings exists and this is not an update, then no need to run.
    if( !empty($grid_settings) and !isset($grid_settings['update']) ) return;

    $warning = false;
    $notices = array();

    if(isset($grid_settings['update']) and CF7SG_VERSION_FORM_UPDATE==CF7_GRID_VERSION){
      $warning = true;
    }else if(isset($grid_settings['fv']) ) {
      if(version_compare($grid_settings['fv'], CF7SG_VERSION_FORM_UPDATE, '<') ) $warning = true;
    }else{ //check the forms directly
      global $wpdb;
      $post_type = $this->cf7_post_type();
      $result = $wpdb->get_col("SELECT pm.meta_value FROM {$wpdb->postmeta} as pm
        INNER JOIN {$wpdb->posts} as p on p.ID = pm.post_id
        WHERE p.post_type = '{$post_type}'
        AND pm.meta_key = '_cf7sg_version'
        ORDER BY pm.meta_key
      ");
      if(!empty($result) and version_compare($result[0], CF7SG_VERSION_FORM_UPDATE, '<') ) $warning = true;
    }

    if($warning){
      $link = admin_url('edit.php?post_type=wpcf7_contact_form');
      /* translators: %s is the url to the forms admin table */
      $msg = __('You need to <strong>update</strong> your <a href="%s">forms</a>','cf7-grid-layout');
      $notices['cf7sg_notice-'.CF7_GRID_VERSION] = array(
        'type'=>'notice-warning', //[notice-update|notice-error]
        'msg'=>sprintf($msg , $link),
        'pages'=>array('plugins.php','edit.php')
      );
    }
    /** @since 4.2.0 new sliders tutorial */
    if( isset($grid_settings['update']) ){
      $notices['cf7sg_sliders_tutorial'] = array(
        'type'=>'notice-update', //[notice-update|notice-error]
        /* translators: %s is the link to the tuorial page*/
        'msg'=>sprintf( __('There is a new tutorial for <a href="%s">multistep slider forms</a>','cf7-grid-layout'), admin_url('admin.php?page=cf7sg_help')),
        'html'=>'<div class="inline-top"><iframe width="230" height="130" src="https://www.youtube.com/embed/WiweQRhOr0g" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div><div class="inline-top"><strong>Learn how to create multi-step multi-slide CF7 forms using a slider construct functionality of the Smart Grid-layout extension plugin.</strong></div>',
        'pages'=>array('plugins.php','edit.php')
      );
      $notices['cf7sg_modular_tutorial'] = array(
        'type'=>'notice-update', //[notice-update|notice-error]
        /* translators: %s is the link to the tuorial page*/
        'msg'=>sprintf( __('There is a new tutorial for <a href="%s">modular form construct</a>','cf7-grid-layout'), admin_url('admin.php?page=cf7sg_help')),
        'html'=>'<div class="inline-top"><iframe width="230" height="130" src="https://www.youtube.com/embed/vPc3M5Emro4" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div><div class="inline-top"><strong>Learn how to use modular form construct functionality allowing complex forms to be assembled from other child forms.</strong></div>',
        'pages'=>array('plugins.php','edit.php')
      );
    }
    if(!empty($notices)) update_option('cf7sg-admin-notices', $notices);

    update_option(self::CF7SG_OPTION, array(
      'gv'=>CF7_GRID_VERSION,
      'fv'=>CF7SG_VERSION_FORM_UPDATE
    ));
  }
  /**
	* Display admin notices
	* Hooked on 'admin_notices'
	*@since 4.1.0
	*/
	public function admin_notices(){
		//check if we have any notices.
		global $pagenow;
		$notices = get_option('cf7sg-admin-notices', array());

		if(empty($notices)) return;

		if(!isset(self::$admin_notice_pages[$pagenow])) return;

    $notify=false;
    $rule = self::$admin_notice_pages[$pagenow];
    // debug_msg($rule, 'notice rules ');
    if($pagenow=='post.php'){
      $screen = get_current_screen();
      if($this->cf7_post_type() == $screen->post_type) $notify=true;
    }else if( !empty($rule) and is_array($rule) ){
      foreach($rule as $key=>$type){
        if( isset($_GET[$type]) and $_GET[$type]==$key ) $notify=true;
      }
    }else $notify = true; //default.

    if(!$notify) return; //rules don't match;

    //do we have any notices to display?
		foreach($notices as $id=>$notice){
      if( isset($notice['pages']) and !in_array($pagenow, $notice['pages'])) continue;
      $time = isset($notice['time']) ? $notice['time']: 'forever';
			$dismiss = "$id-$time";
			if ( ! PAnD::is_admin_notice_active( $dismiss ) ) {
				unset($notices[$id]);
				update_option('cf7sg-admin-notices', $notices);
				continue ; //continue foreach loop.
			}
      if(!isset($notice['html'])) $notice['html'] = '';
			?>
      <style>.notice .inline-top{display: inline-block;vertical-align: top;margin-right: 10px;max-width: 300px;}</style>
			<div data-dismissible="<?=$dismiss?>" class="notice <?=$notice['type']?> is-dismissible"><p><?=$notice['msg']?></p><?=$notice['html']?></div>
			<?php
      /** @since 4.7.1 dismiss notices once displayed */
      unset($notices[$id]);
      update_option('cf7sg-admin-notices', $notices);
		}
	}
  /**
	* ajax request to validate plugin update.
	* Hooked on 'wp_ajax_validate_cf7sg_version_update'
	* @since 4.1.0
  */
  public function validate_cf7sg_version_update(){
    if( !isset($_POST['nonce']) or !wp_verify_nonce($_POST['nonce'], 'cf7sg_udpate_plugin') ){
      echo 'error, nonce failed, try to reload the page.';
      wp_die();
    }
    $grid_settings = get_option(self::CF7SG_OPTION, array());
    $warning = false;
    if(isset($grid_settings['fv']) ) {
      if(version_compare($grid_settings['fv'], CF7SG_VERSION_FORM_UPDATE, '<') ) $warning = true;
    }
    $grid_settings['fv'] = CF7SG_VERSION_FORM_UPDATE;
    $grid_settings['gv'] = CF7_GRID_VERSION;
    $grid_settings['update'] = true;
    update_option(self::CF7SG_OPTION, $grid_settings);
    $update_msg = __('Version validated, thank you!','cf7-grid-layout');
    if($warning){
      $link = admin_url('edit.php?post_type=wpcf7_contact_form');
      /* translators: %s link to form table */
      $update_msg = __('You need to <strong>update</strong> your <a href="%s">forms</a>','cf7-grid-layout');
      $update_msg = sprintf( $update_msg, $link);
    }
    echo $update_msg;
    wp_die();
  }
  /**
  * Check if plugin is getting updated.
  * Hooked to 'upgrader_post_install'.
  *@since 4.1.0
  *@param array $param text_description
  *@param array $extras text_description
  *@param array $result text_description
  *@return array responses for the upgrade process
  */
  public function post_plugin_upgrade($response, $extras, $result){
    if( ( isset($response['destination_name']) and 'cf7-grid-layout' == $response['destination_name'] )
    or ( isset($extras['plugin']) and 'cf7-grid-layout/cf7-grid-layout.php' == $extras['plugin'] ) ){
      $grid_settings = get_option(self::CF7SG_OPTION, array());
      $grid_settings['update'] = true;
      update_option(self::CF7SG_OPTION, $grid_settings);
    }
    return $response;
  }
  /**
  * Preview form post type creation.
  * hooked on action 'init'.
  *@since 4.3.0
  *@param string $param text_description
  *@return string text_description
  */
  // Register Custom Post Type
  function register_form_preview_posttype() {

  	$labels = array(
  		'name'                  => _x( 'CF7 Forms', 'Post Type General Name', 'cf7-grid-layout' ),
  		'singular_name'         => _x( 'CF7 Form', 'Post Type Singular Name', 'cf7-grid-layout' ),
  		'menu_name'             => __( 'CF7 Forms', 'cf7-grid-layout' ),
  		'name_admin_bar'        => __( 'CF7 Form', 'cf7-grid-layout' ),
  		'parent_item_colon'     => __( 'Parent Item:', 'cf7-grid-layout' ),
  		'all_items'             => __( 'All Form Pages', 'cf7-grid-layout' ),
  		'add_new_item'          => __( 'Add New Form Page', 'cf7-grid-layout' ),
  		'add_new'               => __( 'Add New', 'cf7-grid-layout' ),
  		'new_item'              => __( 'New Form Page', 'cf7-grid-layout' ),
  		'edit_item'             => __( 'Edit Form Page', 'cf7-grid-layout' ),
  		'update_item'           => __( 'Update Form Page', 'cf7-grid-layout' ),
  		'view_item'             => __( 'View Form Page', 'cf7-grid-layout' ),
  		'view_items'            => __( 'View Form Pages', 'cf7-grid-layout' ),
  		'search_items'          => __( 'Search Form Page', 'cf7-grid-layout' ),
  		'not_found'             => __( 'Not found', 'cf7-grid-layout' ),
  		'not_found_in_trash'    => __( 'Not found in Trash', 'cf7-grid-layout' ),
  	);
  	$rewrite = array(
  		'slug'                  => 'sgform',
  		'with_front'            => true,
  		'pages'                 => true,
  		'feeds'                 => false,
  	);
    $args = array(
  		'label'                 => __( 'CF7 Form', 'cf7-grid-layout' ),
  		'description'           => __( 'Preview/View CF7 forms on the front-end', 'cf7-grid-layout' ),
  		'labels'                => $labels,
  		'public'                => true,
  		'show_ui'               => false,
  		'show_in_menu'          => false,
  		'show_in_admin_bar'     => false,
  		'show_in_nav_menus'     => false,
  		'can_export'            => false,
  		'has_archive'           => false,
  		'exclude_from_search'   => true,
  		'publicly_queryable'    => true,
  		// 'query_var'             => 'sgcf7',
  		'rewrite'               => $rewrite,
  	);
  	register_post_type( 'cf7sg_page', $args );

  }
  /**
  * Load translation files for the CF7 Polylang extension.
  * Hoooked to ''
  *@since 4.4.0
  *@param string $param text_description
  *@return string text_description
  */
  public function load_translation_files($trans){
    $trans[$this->plugin_name]=CF7SG_TRANSLATED_VERSION;
    return $trans;
  }
}
