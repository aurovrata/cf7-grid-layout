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
  private $registered = false;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

  /**
   * The version of this plugin.
   *
   * @since    2.6.0
   * @access   private
   * @var      string    $form_id    The current form id.
   */
  private $form_id='';

  /**
   * The cf7 array fields.
   *
   * @since    1.0.0
   * @access   private
   * @var      Array    $array_grid_fields    The form fields which were converted to arrays by the plugin.
   */
  static private $array_grid_fields = array();

  /**
  * The cf7 array fields.
  *
  * @since    2.5.0
  * @access   private
  * @var      Array    $array_toggle_fields    The form fields which are within toggle sections.
  */
  static private $array_toggle_fields = array();

  /**
   * The cf7 array fields.
   *
   * @since    2.5.0
   * @access   private
   * @var      Array    $array_toggled_panels    The form toggled sections used.
   */
  static private $array_toggled_panels = array();
  /**
   * The cf7 array fields.
   *
   * @since    2.6.0
   * @access   private
   * @var      Array    $localised_data    Localised data parameters.
   */
  private $localised_data = array();
  
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
  *
  *
  * @since 1.0.0
  * @param string $field field name to check
  * @param string $form_id form id for which to check the
  * @return string 'singular' for non-grid fields, 'tab' for tabbed fields, 'table' for tablled fields, 'both' for fields in tables in tabs.
  */
  static public function field_type($field, $form_id){
    if(!isset(self::$array_grid_fields[$form_id])){
      //try to load the fields.
			self::get_grid_fields($form_id);
    }
    $type = 'singular';
    if(isset(self::$array_grid_fields[$form_id][$field])){
      $type = self::$array_grid_fields[$form_id][$field];
      switch(true){ /** @since 2.4.2 tables have unique id with time in milliseconds since 1/1/70 */
        case preg_match('/^cf7-sg-table-[0-9]{3,13}$/', $type):
          $type = 'table';
          break;
        case preg_match('/^cf7-sg-tab-[0-9]{3,13}$/', $type):
          $type = 'tab';
          break;
        default:
          $type = 'both';
          break;
      }
    }
    return $type;
  }

  /**
  * Check if a field is in a toggled section.
  *
  *@since 2.5.0
  *@param string $field field nav_menu_link_attributes.
  *@return boolean check if the field is in a toggle section.
  */
  protected function get_toggle($field){
    return isset(self::$array_toggle_fields[$this->form_id][$field]) ? self::$array_toggle_fields[$this->form_id][$field]: null;
  }
  /**
  * Check if a toggled section was used and its fields submitted.
  *
  *@since 2.5.0
  *@param string $toggle toogle id
  *@return boolean was the toggle section submitted.
  */
  protected function is_submitted($toggle){
    return isset(self::$array_toggled_panels[$this->form_id][$toggle]);
  }
	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function register_styles() {
    $plugin_dir = plugin_dir_url( __DIR__ );
    //default style for cf7 grid forms (row buttons and tables mainly).
    wp_register_style( $this->plugin_name, $plugin_dir . 'public/css/cf7-grid-layout-public.css', array(), $this->version, 'all' );
    wp_register_style( 'cf7-benchmark-css', $plugin_dir . 'public/css/cf7-benchmark.css', array(), $this->version, 'all' );
    //others
    // get registered script object for jquery-ui
    global $wp_scripts;
    $ui = $wp_scripts->query('jquery-ui-core');

    // tell WordPress to load the Smoothness theme from Google CDN
    $protocol = is_ssl() ? 'https' : 'http';
    $url_path = "$protocol://cdnjs.cloudflare.com/ajax/libs/jqueryui/{$ui->ver}/";
    wp_register_style('cf7-jquery-ui', $url_path . 'themes/smoothness/jquery-ui.min.css', array(), $ui->ver , 'all');

    wp_register_style( 'cf7-jquery-ui-theme', $url_path . 'jquery-ui.theme.min.css', array(), $ui->ver, 'all');
    wp_register_style( 'cf7-jquery-ui-structure', $url_path . 'jquery-ui.structure.min.css', array(), $ui->ver, 'all');
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
    $plugin_dir = plugin_dir_url( __DIR__ );
		wp_register_script( $this->plugin_name, $plugin_dir . 'public/js/cf7-grid-layout-public.js', array( 'jquery','contact-form-7' ), $this->version, true );
    wp_register_script('jquery-select2', $plugin_dir . 'assets/select2/js/select2.min.js', array( 'jquery' ), $this->version, true );
    wp_register_script('jquery-nice-select', $plugin_dir . 'assets/jquery-nice-select/js/jquery.nice-select.min.js', array( 'jquery' ), $this->version, true );
    wp_register_script('jquery-toggles', $plugin_dir . 'assets/jquery-toggles/toggles.min.js', array( 'jquery' ), $this->version, true );
    wp_register_script('js-cf7sg-benchmarking', $plugin_dir . 'public/js/cf7-benchmark.js', array( 'jquery' ), $this->version, true );
    //allow custom script registration
    do_action('smart_grid_register_scripts');
	}
  /**
   * Dequeue script 'contact-form-7'
   * hooked on 'wp_print_scripts', this canbe re-enqeued on specific cf7 shortcode calls
   * @since 1.0.0
  **/
  public function dequeue_cf7_scripts(){
    wp_dequeue_script('contact-form-7');
  }
  /**
   * Dequeue script 'contact-form-7'
   * hooked on 'wp_print_style', this canbe re-enqeued on specific cf7 shortcode calls
   * @since 1.0.0
  **/
  public function dequeue_cf7_styles(){
    wp_dequeue_style('contact-form-7');
  }
  /**
   * Add the cf7 key to the hidden fields so as not to have to load it after each submission.
   * Hooked to 'wpcf7_form_hidden_fields'
   * @since 1.0.0
   * @param      Array    $hidden     hidden fields to add to cf7 form.
   * @return      Array     hidden fields to add to cf7 form.
  **/
  public function add_hidden_fields($hidden){
    $form = wpcf7_get_current_contact_form();
    $post = get_post($form->id());
    $hidden['_wpcf7_key'] = $post->post_name;
    $hidden['_cf7sg_toggles'] = '';
    $hidden['_cf7sg_version'] = $this->version;
    return $hidden;
  }
  /**
   * Enqueue scripts requried for cf7 shortcode
   * hooked on 'do_shortcode_tag',
   * @since 1.0.0
  **/
  public function cf7_shortcode_request($output, $tag, $attr){
    //if not a cf7 shortcode, then exit.
    if('contact-form-7' !== $tag){
      return $output;
    }
    //wp_enqueue_script('contact-form-7'); //default cf7 plugin script.
    wp_enqueue_script('contact-form-7'); //default cf7 plugin script.
    // global $wp_scripts;

    //wp_print_scripts(array('contact-form-7'));
    $cf7_id = $attr['id'];
    //get the key
    $cf7post = get_post($cf7_id);
    $cf7_key = $cf7post->post_name;
    //load custom css/js script from theme css folder.
    $themepath = get_stylesheet_directory();
    $themeuri = get_stylesheet_directory_uri();
    if( file_exists($themepath.'/css/'.$cf7_key.'.css') ){
      wp_enqueue_style( $cf7_key.'-css' , $themeuri.'/css/'.$cf7_key.'.css', array($this->plugin_name), null, 'all');
    }
    if( file_exists($themepath.'/js/'.$cf7_key.'.js') ){
      wp_enqueue_script( $cf7_key.'-js' , $themeuri.'/js/'.$cf7_key.'.js', array($this->plugin_name), null, true);
      do_action('smart_grid_register_custom_script', $cf7_key);
    }
    /**
    * @since 1.2.3 disable cf7sg styling/js for non-cf7sg forms.
    */
    $is_form = get_post_meta($cf7_id, '_cf7sg_managed_form', true);
    if(''===$is_form || !$is_form){
      wp_enqueue_style('contact-form-7'); //default cf7 plugin css.
      return $output;
    }
    $class = get_post_meta($cf7_id, '_cf7sg_classes', true);
    if(empty($class)){
      $class = array();
    }
    wp_enqueue_script($this->plugin_name);
    /** @since 2.6.0 disabled button message*/
    $messages = wpcf7_messages();
    $this->localised_data = array(
      'url' => admin_url( 'admin-ajax.php' ),
      'submit_disabled'=> isset($messages['submit_disabled']) ? $messages['submit_disabled']['default']: __( "Disabled!  To enable, check the acceptance field.", 'cf7-grid-layout' )
    );
    wp_localize_script( $this->plugin_name, 'cf7sg', $this->localise_script() );

    $class['has-validation']=true;

    $class['has-select2'] = true;
    //if(isset($class['has-select2'])){
      wp_enqueue_script('jquery-select2');
      wp_enqueue_style('select2-style');
    //}
    $class['has-accordion']=true;
    wp_enqueue_script('jquery-ui-accordion');

    //load tabs
    $has_tabs = false;
    if(get_post_meta($cf7_id, '_cf7sg_has_tabs', true)){
      $class['has-tabs']=true;
      $has_tabs = true;
    }
    //load tables
    $has_tables = false;
    if(get_post_meta($cf7_id, '_cf7sg_has_tables', true)){
      $class['has-table']=true;
      $has_tables = true;
    }
    $class['has-effects']=true;
    wp_enqueue_script('jquery-effects-core');
		$class['has-nice-select'] = true;
    //if(isset($class['has-nice-select'])){
    wp_enqueue_script('jquery-nice-select');
    wp_enqueue_style('jquery-nice-select-css');
    //}
    $has_toggles = false;
    if(get_post_meta($cf7_id, '_cf7sg_has_toggles', true)){
      $class['has-toggles']=true;
      $has_toggles = true;
    }

    //styles
    wp_enqueue_style('contact-form-7');
    wp_enqueue_style($this->plugin_name);
    wp_enqueue_style('smart-grid');
    wp_enqueue_style('dashicons');
    $class['has-grid']=true;
    wp_enqueue_style('cf7-jquery-ui-theme');
    wp_enqueue_style('cf7-jquery-ui-structure');
    wp_enqueue_style('cf7-jquery-ui');
    if(isset($class['has-benchmark'])){
      wp_enqueue_script('js-cf7sg-benchmarking');
    }

    $class['has-date']=true;
    wp_enqueue_script('jquery-ui-datepicker');

    $form_time = strtotime($cf7post->post_modified);
    //check if there are any recently updated sub-forms.
    $sub_form_keys = get_post_meta($cf7_id, '_cf7sg_sub_forms', true);
    if(!empty($sub_form_keys)){
      $args = array(
        'post_type' => 'wpcf7_contact_form',
        'post_name__in' => $sub_form_keys
      );
      $sub_forms = get_posts($args);
      $cf7_form = $form_raw = '';
      foreach($sub_forms as $post_obj){
        //check form saved date
        //debug_msg()
        if(strtotime($post_obj->post_modified ) > $form_time){
          if(empty($cf7_form)){
            $cf7_form = wpcf7_contact_form($cf7_id);
            $form_raw = $cf7_form->prop( 'form' );
          }
          $form_raw = $this->update_sub_form($form_raw, $post_obj);
          //debug_msg($form_raw, 'new form---------------- ');
        }
        //check if sub-forms needs tabs|tablevalidate_beanchmark
        if(!$has_tables && get_post_meta($post_obj->ID, '_cf7sg_has_tables', true)){
          $class['has-table']=true;
          $has_tables = true;
        }
        if(!$has_tabs && get_post_meta($post_obj->ID, '_cf7sg_has_tabs', true)){
          $class['has-tabs']=true;
          $has_tabs = true;
        }
        if(!$has_toggles && get_post_meta($post_obj->ID, '_cf7sg_has_toggles', true)){
          $class['has-toggles']=true;
          $has_toggles=true;
        }
      }
      if(!empty($cf7_form)){ //redraw the form.
        $cf7_form = wpcf7_save_contact_form(array('id'=>$cf7_id, 'form'=>$form_raw));
        //debug_msg('Updated embeded forms in cf7 form: '.$cf7_key);
        //reload the form
        //$cf7_form = wpcf7_contact_form($cf7_id);
        $output = $cf7_form->form_html($attr);
        $class['has-update']=true;
      }
    }
    if($has_tabs) wp_enqueue_script('jquery-ui-tabs');
    if($has_toggles){
      wp_enqueue_script('jquery-toggles');
      wp_enqueue_style('jquery-toggles-css');
      wp_enqueue_style('jquery-toggles-light-css');
    }
    //$cf7_key = get_post_meta($cf7_id, '_smart_grid_cf7_form_key', true);
    //allow custom script print
    do_action('smart_grid_enqueue_scripts', $cf7_key, $attr);
    //form id
    $css_id = apply_filters('cf7_smart_grid_form_id', 'cf7sg-form-'.$cf7_key, $attr);
    $classes = implode(' ', array_keys($class)) .' key_'.$cf7_key;

    $output = '<div class="cf7sg-container"><div id="' . $css_id . '" class="cf7-smart-grid ' . $classes . '">' . $output . '</div></div>';
    return $output;
  }
  /**
  * Function hooked on 'cf7_2_post_form_values' to load toggle status for saed submissions
  *
  *@since 1.1.0
  *@param string $post_id saved submission post ID
  */
  public function load_saved_toggled_status($field_values){
    if(!isset($field_values['map_post_id']) || empty($field_values['map_post_id'])){
      return $field_values;
    }
    $post_id = $field_values['map_post_id'];
    $toggles = get_post_meta($post_id, 'cf7sg_toggles_status', true);
    //debug_msg($toggles, $post_id.' status ');
    if(empty($toggles)) $toggles = array();
    wp_enqueue_script($this->plugin_name);
    wp_localize_script( $this->plugin_name, 'cf7sg', $this->localise_script( array('toggles_status' => $toggles)) );
    return $field_values;
  }
  /**
  *  Single source for localised script.
  *
  *@since 2.6.0
  *@param array $params additional parameter to send.
  *@return array data array to localise
  */
  private function localise_script($params=array()){
    $this->localised_data += $params;
    return $this->localised_data;
  }
  /**
   * Update sub-forms in cf7 forms
   * Hooked to 'do_shortcode'
   * @since 1.0.0
   * @param      Array    $atts     attributes from the shortcode.
   * @param      String    $content     shortcode content.
   * @return     String    shorcode rendered content.
  **/
  public function update_sub_form($form_raw, $sub_form_post){
    //Create a new DOM document
    $cf7_key = $sub_form_post->post_name;
    $sub_form_raw = get_post_meta($sub_form_post->ID, '_form', true);
    //debug_msg($sub_form_raw, 'new subform');
    //PHP DOM plugin.
    require_once plugin_dir_path(  __DIR__  ) . 'assets/php-query/phpQuery.php';
    $doc = phpQuery::newDocument($form_raw);
    //$form = pq('#cf7sg-form-'.$key)->find('form.wpcf-form')->contents()->remove();
    //remove old form content.
    pq('#cf7sg-form-'.$cf7_key)->contents()->remove();
    //$form_wrap = pq('#cf7sg-multi-form-main')->contents()->remove();
    //$form_wrap->find(('form.wpcf-form'))->append(pq('#cf7sg-multi-form')->html());
    //add updated form
    pq('#cf7sg-form-'.$cf7_key)->append($sub_form_raw);
    return $doc->htmlOuter();
  }
  /**
   * Function to load custom js script for Post My CF7 Form loading of form field values
   * Hooked to 'cf7_2_post_echo_field_mapping_script'
   * @since 1.0.0
   * @param boolean  $default_script  whether to use the default script or not, default is true.
   * @param string  $field  cf7 form field name
   * @param string  $type   field type (number, text, select...)
   * @param string  $json_value  the json value loaded for this field in the form.
   * @param string  $$js_form  the javascript variable in which the form is loaded.
   * @return boolean  false to print a custom script from the called function, true for the default script printed by this plugin.
  **/
  public function load_tabs_table_field($default_script, $post_id,  $field, $type, $json_value, $js_form){
    $grid = self::field_type($field, $post_id);
    switch($grid){
      case 'tab':
      case 'table':
      case 'both':
        include( plugin_dir_path( __FILE__ ) . '/partials/cf7sg-field-load-script.php');
        $default_script = false;
        break;
      default:
        break;
    }
    return $default_script;
  }
  /**
   * Register a [save] shortcode with CF7.
   * Hooked  on 'wpcf7_init'
   * This function registers a callback function to expand the shortcode for the save button field.
   * @since 2.0.0
   */
  public function register_cf7_shortcode() {
    if( function_exists('wpcf7_add_form_tag') ) {
      //dynamic select
      wpcf7_add_form_tag(
        array( 'dynamic_select', 'dynamic_select*' ),
        array($this,'cf7_taxonomy_shortcode'),
        true //has name
      );
      //benchmark
      wpcf7_add_form_tag(
        array( 'benchmark', 'benchmark*' ),
        array($this,'cf7_benchmark_shortcode'),
      true //has name
    );
    }
  }
  /**
	 * Register a [benchmark] shortcode with CF7.
	 * called by funciton above
	 * This function registers a callback function to expand the shortcode for the googleMap form fields.
	 * @since 1.0.0
   * @param strng $tag the tag name designated in the tag help screen
   * @return string a set of html fields to capture the googleMap information
	 */

  public function cf7_benchmark_shortcode($tag){
    $tag = new WPCF7_FormTag( $tag );
    wp_enqueue_script('js-cf7sg-benchmarking');
    wp_enqueue_style( 'cf7-benchmark-css' );
    ob_start();
    include( plugin_dir_path( __FILE__ ) . '/partials/cf7-benchmark-tag.php');
    $html = ob_get_contents ();
    ob_end_clean();
    $this->update_form_classes('has-benchmark');
    return $html;
  }
  /**
	 * Register a [dynamic_display] shortcode with CF7.
	 * called by funciton above
	 * This function registers a callback function to expand the shortcode for the googleMap form fields.
	 * @since 1.0.0
   * @param strng $tag the tag name designated in the tag help screen
   * @return string a set of html fields to capture the googleMap information
	 */

  public function cf7_taxonomy_shortcode($tag){
    $tag = new WPCF7_FormTag( $tag );
		$source = self::get_dynamic_drodown_attributes($tag);
    ob_start();
    include( plugin_dir_path( __FILE__ ) . '/partials/cf7-taxonomy-tag-display.php');
    $html = ob_get_contents ();
    ob_end_clean();
    return $html;
  }
  /**
   *
   *
   * @since 1.0.0
   * @param      mixed    $class    array of classes or string to set class for form and dependency loading .
   * @param      Object    $cf7_form    cf7 post id against which set the c$lass .
  **/
  public function update_form_classes($class, $cf7_id=null){
    if(empty($cf7_id)){
      //get latest form
      if(function_exists('wpcf7_get_current_contact_form')){
        $cf7_form = wpcf7_get_current_contact_form();
        if(!empty($cf7_form)) $cf7_id = $cf7_form->id();
      }
    }
    if(empty($cf7_id)){
      debug_msg('Unable to get Contact Form 7 ID to set class: '.$class);
      return;
    }
    $classes = get_post_meta($cf7_id, '_cf7sg_classes', true);
    if(empty($classes)){
      $classes = array();
    }
    if(is_array($class)){
      foreach($class as $class_name){
        $classes[$class_name] = true;
      }
    }else{
      $classes[$class] = true;
    }
    update_post_meta($cf7_id, '_cf7sg_classes', $classes);
    return;
  }
  /**
   * Function to save ajax submitted grid fields, grid fields are any input/select fields used in the table/tabs constructs
   * hooked on wp_ajax_nopriv_save_grid_fields and wp_ajax_save_grid_fields.  The ajax is only fired in case a sub-form has been updated.
   * @since 1.0.0
  **/
  public function save_grid_fields(){
    if( !isset($_POST['nonce']) ){
      wp_send_json_error(array('message'=>'nonce failed'));
      wp_die();
    }
    $cf7_id = sanitize_text_field($_POST['id']);

    if(!wpcf7_verify_nonce($_POST['nonce'], $cf7_id)){
      wp_send_json_error(array('message'=>'nonce failed'));
      wp_die();
    }

    if(isset($_POST['tabs_fields']) && isset($_POST['table_fields'])){
      $tabs_fields =  json_decode(stripslashes($_POST['tabs_fields']));
      if(empty($tabs_fields)) $tabs_fields = array();
      if(!is_array($tabs_fields)) $tabs_fields = array($tabs_fields);
			//validate each field name as text, sanitize.
			$sanitised_tab_fields = array();
			foreach($tabs_fields as $field){
				$sanitised_tab_fields[] = sanitize_text_field($field);
			}
      $table_fields =  json_decode(stripslashes($_POST['table_fields']));
      if(empty($table_fields)) $table_fields = array();
      if(!is_array($table_fields)) $table_fields = array($table_fields);
			$sanitised_table_fields = array();
			foreach($table_fields as $field){
				$sanitised_table_fields[] = sanitize_text_field($field);
			}
      //debug_msg($grid_fields, $cf7_id);
      update_post_meta($cf7_id, '_cf7sg_grid_tabs_names', $sanitised_tab_fields);
      update_post_meta($cf7_id, '_cf7sg_grid_table_names', $sanitised_table_fields);
      wp_send_json_success(array('message'=>'saved fields'));
    }else{
      wp_send_json_error(array('message'=>'no data received'));
    }
    wp_die();
  }
  /**
   *  This function filters submitted data and consolidates grid field values into arrays.
   * Function hooked on 'wpcf7_posted_data'
   * @since 1.0.0
   * @param   Array    $data    unvalidated submitted data.
   * @return  Array    filtered submitted data.
  **/
  public function setup_grid_values($data){
    $cf7form = WPCF7_ContactForm::get_current();
    if(empty($cf7form) ){
      if(isset($data['_wpcf7']) ){
        $cf7_id = $data['_wpcf7'];
        $cf7form = WPCF7_ContactForm::get_instance($cf7_id);
        if(empty($cf7form) ){
          debug_msg("CF7SG ERROR: fn setup_grid_values() is unable to load submitted form");
          return $data;
        }
      }
    }
    $cf7_id = $cf7form->id();
    $this->form_id = $cf7_id;
		//debug_msg($grid_fields, 'grid fields...');
    $tags = $cf7form->scan_form_tags();
    foreach($tags as $tag){
      $field_name = $tag['name'];
      $field_type = self::field_type($field_name, $cf7_id);
      switch($field_type){
        case 'tab':
        case 'table':
        case 'both':
          // the $data array is passed by reference and will be consolidated.
          /** @since 2.5.0 */
          if( isset($_POST['_cf7sg_version']) && version_compare($_POST['_cf7sg_version'],'2.5.0','>=') ){
            $this->consolidate_grid_submissions_v2($tag, $field_type, $data);
          }else{
            $this->consolidate_grid_submissions($field_name, $field_type, $data);
          }
          break;
        default:
          $toggle = $this->get_toggle($field_name);
          if('unit-contact' ==$field_name) debug_msg($toggle, 'unit contact toggle ');
          if(!empty($toggle)) $data[$field_name] = $this->is_submitted($toggle) ? $this->get_field_value($field_name, $data,$field_type) : null;
          else $data[$field_name]= $this->get_field_value($field_name, $data, $tag['basetype']);
          break;
      }
    }
    return $data;
  }
  /**
  * new function to consolidate submitted data for improved handling of optional toggled fields as well as special fields such as files and checkboxes in tabbed sectoins.
  *
  *@since 2.5.0
  *@param array $field_tag a cf7 form field tag which is part of tabs or table grid section.
  *@param array $type field type, should be 'tab'/'table'/'both'.
  *@param array $data cf7 form submissed data passed by reference as consolidated grid values will be removed and the original field value replaced with an array.
  *@return array  a filtered array of $index_suffix=>$value pairs for tabs or rows fields, The index suffix is '.row-<index>' for tables and '.tab-<index>' for tabs. This method returns a 2 dimensional array for fields which are both within rowns and tabs.  The 2 dimensional array will list [<tab_suffix>][<row_suffix>]=>$value.  The original field name that was submitted can be reconstructed as $field_name.$index_suffix.  The first field will have an empty string as its $index_suffix.
  */
  private function consolidate_grid_submissions_v2($field_tag, $type, &$data){
    //get_post_meta($post_id, '_cf7sg_has_tables', true);
    if(isset($data['_wpcf7']) ) $cf7_id = $data['_wpcf7'];
    else{
      debug_msg("CF7SG ERROR: fn consolidate_grid_submissions_v2() is unable to load submitted form");
    }
    $field_name = $field_tag['name'];
    $field_type = $field_tag['basetype'];
    $origin = self::$array_grid_fields[$cf7_id][$field_name];
    $values = array();
    $regex = '';
    $submitted_fields=array();
    $max_fields =0;
    $purge_fields=array();
    $toggle = $this->get_toggle($field_name);
    switch($type){
      case 'tab':
      case 'table':
        $index_suffix = ('table'==$type)?'_row-':'_tab-';
        //find the number of rows submitted.
        $max_fields = isset($_POST[$origin]) ? $_POST[$origin]:0;
        if(!empty($toggle)){ //check if submitted.
          $values[''] = $this->is_submitted($toggle) ? $this->get_field_value($field_name, $data,$field_type) : null;
        }else $values['']= $this->get_field_value($field_name, $data,$field_type);

        for($idx=1;$idx<$max_fields;$idx++){
          $fid=$index_suffix.$idx;
          if(!empty($toggle)){ //check if submitted.
            $values[$fid] = $this->is_submitted($toggle.$fid) ? $this->get_field_value($field_name.$fid, $data,$field_type) : null;
          }else $values[$fid] = $this->get_field_value($field_name.$fid, $data,$field_type);
          $purge_fields[$field_name.$fid] = true;
        }
        break;
      case 'both':
        $origins = explode(':', $origin);
        $table_origin = $origins[0];
        $tab_origin = $origins[1];
        $max_tabs = isset($_POST[$tab_origin])?$_POST[$tab_origin]:0;
        $values[''] = array(); //init multi-dimensional array
        if(!empty($toggle)){ //check if submitted, tab<-toggle<-table.
          $values[''][''] = $this->is_submitted($toggle) ? $this->get_field_value($field_name, $data,$field_type) : null;
        }else $values[''][''] = $this->get_field_value($field_name, $data,$field_type);

        for($idx=0;$idx<$max_tabs;$idx++){ //tables in other tabs.
          $max_fields = ($idx>0) ? $_POST[$table_origin.'_tab-'.$idx]:$_POST[$table_origin];
          $tab_suffix= ($idx>0)? '_tab-'.$idx:'';
          for($jdx=0;$jdx<$max_fields;$jdx++){
            $row_suffix= ($jdx>0)?'_row-'.$jdx:'';
            $fid = $tab_suffix.$row_suffix;
            if(!empty($toggle)){ //check if submitted, tab<-toggle<-table.
              $values[$tab_suffix][$row_suffix] = $this->is_submitted($toggle.$tab_suffix) ? $this->get_field_value( $field_name.$fid, $data, $field_type):null;
            }else $values[$tab_suffix][$row_suffix] = $this->get_field_value($field_name.$fid, $data,$field_type);
            $purge_fields[$field_name.$fid] = true; //remove from the origina $data.
          }
        }
        break;
    }
    //debug_msg($purge_fields, 'purging ');
    //debug_msg($data);
    $data = array_udiff_uassoc($data, $purge_fields, function($a, $b){return 0;}, function($a, $b){
      if ($a === $b) return 0;
      return ($a > $b)? 1:-1;
    }); //this will remove all the surplus fields
    $data[$field_name] = $values;
    return $values;
  }
  /**
  * Extract either a file name or a field value
  *
  *@since 2.5.0
  *@param array $data submitted data.
  *@return string text_description
  */
  private function get_field_value($field_name, $data, $field_type){
    $value = '';
    if('file' == $field_type) {
      $value = isset($_FILES[$field_name]) ? $_FILES[$field_name]['name']:'';
    }else{
      $value = isset($data[$field_name]) ? $data[$field_name]:'';
    }
    return $value;
  }
	/**
	 * function returns an array of fields as keys and value which are eitehr 'tab' or 'table', or 'both'.
	 *
	 * @since 1.0.0
	 * @param      string    $form_id     form id for which to return fields.
	 * @return     array    empty array if no fields found..
	**/
	static public function get_grid_fields($form_id){
    if(isset(self::$array_grid_fields[$form_id])){
      return self::$array_grid_fields[$form_id];
    }
		$grid_fields = array();
		//tables
    $fields = get_post_meta($form_id , '_cf7sg_grid_table_names', true);
		if(!empty($fields)){
      if( is_array( $fields[key($fields)] ) ){
        foreach($fields as $table=>$table_fields) $grid_fields += array_fill_keys($table_fields, $table);
      }else $grid_fields += array_fill_keys($fields, 'cf7-sg-table-000');
		}
    //tabs
		$fields = get_post_meta($form_id , '_cf7sg_grid_tabs_names', true);
		if(!empty($fields)){
      if(is_array( $fields[key( $fields)] ) ){ /** @since 2.4.2 */
        foreach($fields as $tab=>$tab_fields){
          foreach($tab_fields as $field){
            if(isset($grid_fields[$field])) $grid_fields[$field] = $grid_fields[$field].':'.$tab;
            else $grid_fields[$field] = $tab;
          }
        }
      }else{
        foreach($fields as $field){
          if(isset($grid_fields[$field])) $grid_fields[$field] = $grid_fields[$field].':'.$tab;
          else $grid_fields[$field] = 'cf7-sg-tab-000';
        }
      }
		}
		$subform_keys = get_post_meta($form_id, '_cf7sg_sub_forms', true);
    if(!empty($subform_keys)){
  		foreach($subform_keys as $cf7Key){
  			$post_id = Cf7_WP_Post_Table::form_id($cf7Key);
  			$grid_fields += self::get_grid_fields($post_id);
  		}
    }
    self::$array_grid_fields[$form_id]=$grid_fields;
    //load panel fields.
    if( isset($_POST['_cf7sg_version']) && version_compare($_POST['_cf7sg_version'],'2.5.0','>=') ){
      self::$array_toggled_panels[$form_id]=array();
      $toggled_panels = array();
      if(isset($_POST['_cf7sg_toggles'])){
        $toggled_panels = json_decode( stripslashes($_POST['_cf7sg_toggles']));
        if(!empty($toggled_panels)) $toggled_panels = get_object_vars($toggled_panels);
        else $toggled_panels = array();
      }
      self::$array_toggled_panels[$form_id]=$toggled_panels;

      $fields = get_post_meta($form_id, '_cf7sg_grid_toggled_names', true);
      self::$array_toggle_fields[$form_id]=array();
      if(!empty($fields)){
        foreach($fields as $panel=>$pfields){
          foreach($pfields as $field) self::$array_toggle_fields[$form_id][$field]=$panel;
        }
      }
    }
		return $grid_fields;
	}
  /**
  * Consolidates submitted data from table and tab fields into arrays.
  *
  *@since 1.0.0
  *@param string $field_name a cf7 form field name which is part of tabs or table grid section.
  *@param array $type field type, should be 'tab'/'table'/'both'.
  *@param array $data cf7 form submissed data passed by reference as consolidated grid values will be removed and the original field value replaced with an array.
  *@return array  a filtered array of $index_suffix=>$value pairs for tabs or rows fields, The index suffix is '.row-<index>' for tables and '.tab-<index>' for tabs. This method returns a 2 dimensional array for fields which are both within rowns and tabs.  The 2 dimensional array will list [<tab_suffix>][<row_suffix>]=>$value.  The original field name that was submitted can be reconstructed as $field_name.$index_suffix.  The first field will have an empty string as its $index_suffix.
  */
  private function consolidate_grid_submissions($field_name, $type, &$data){
    $values = array();
    $regex = '';
    $submitted_fields=array();
    $max_fields =0;
    $purge_fields=array();
    switch($type){
      case 'table':
        $index_suffix = '_row-';
        $regex = '/^'.preg_quote($field_name).'_row-[0-9]+$/';
        $values['']=$data[$field_name];
        //extract all relevant fields
        $submitted_fields = preg_grep($regex, array_keys($data));
        $max_fields = sizeof($submitted_fields);
        break;
      case 'tab':
        $index_suffix = '_tab-';
        $regex = '/^'.preg_quote($field_name).'_tab-[0-9]+$/';
        $values['']=$data[$field_name];
        //extract all relevant fields
        $submitted_fields = preg_grep($regex, array_keys($data));
        foreach($submitted_fields as $field){
          $idx = 1.0 * str_replace($field_name.'_tab-', '', $field);
          if($max_fields < $idx)  $max_fields = $idx;
        }
        break;
      case 'both':
        $regex = '/^'.preg_quote($field_name);
        $values[''] = array(); //init multi-dimensional array
        if(isset($data[$field_name])){
          $values[''][''] = $data[$field_name];
        }else{
          $values[''][''] = null;
        }
        //extract all relevant fields
        $submitted_fields = preg_grep($regex.'_tab-[0-9]+_row-[0-9]+$/', array_keys($data));
        //we also need the rows with tab index=0
        $submitted_fields += preg_grep($regex.'_row-[0-9]+$/', array_keys($data));
        //.. and tabs with row index=0
        $submitted_fields += preg_grep($regex.'_tab-[0-9]+$/', array_keys($data));
        //if('other-rm-qty'==$field_name){
          // debug_msg($submitted_fields, 'Found fields '.$field_name);
        //}
        $max_fields = sizeof($submitted_fields);
        break;
    }

    $row_idx=1; //[0][0] already set above is this is tab table field
    $tab_idx=0;
    $error_loop=false;
    $loop_counter_limit = apply_filters('cf7sg_set_max_tabs_limit', 10, $data['_wpcf7_key'], $data['_wpcf7']);
    for($idx=1; ($idx <= $max_fields && !$error_loop); $idx++){
      switch($type){
        case 'table':
        case 'tab':
          if(!isset($data[$field_name.$index_suffix.$idx])){
            /**
            *assuming this field was not submitted as it was disabled, hence set to null.
            * @since 1.0.0
            */
            $values[$index_suffix.$idx] = null;
          }else{
            $values[$index_suffix.$idx] = $data[$field_name.$index_suffix.$idx];
            $purge_fields[$field_name.$index_suffix.$idx] = true;
          }
          break;
        case 'both':
          //first attempt to look for the frist tab rows
          $row_suffix = ($row_idx>0)?'_row-'.$row_idx:'';
          $tab_suffix = ($tab_idx>0)?'_tab-'.$tab_idx:'';
          //if('other-rm-qty'==$field_name){
            // debug_msg($idx.'Looking for: '.$field_name.$tab_suffix.$row_suffix);
          //}
          if(isset($data[$field_name.$tab_suffix.$row_suffix])){
            $values[$tab_suffix][$row_suffix] = $data[$field_name.$tab_suffix.$row_suffix];
            $purge_fields[$field_name.$tab_suffix.$row_suffix] = true;
            //next loop, look for the next row within the same tab,
            $row_idx +=1;
          }else{

            $loop_counter=$tab_idx;
						$loop = true;
            do{//move to next tab, and reset the row counter
              // debug_msg('loop:'.$loop_counter.', max: '.$loop_counter_limit);
              $loop_counter +=1;
              if($loop_counter > $loop_counter_limit) $error_loop=true;
              $tab_idx +=1;
              $row_idx = 0;
              $row_suffix = '';
              $tab_suffix = '_tab-'.$tab_idx;
              //init new tab row values array
              $values[$tab_suffix] = array();
              //keep looking for next tab if we don't find a value;
              /**
              *if the frist row is not submitted then this may be in a toggled section which has been disabled.
              * but we still need to keep looking for values in other tabs.
              *@since 1.1.0
              */
              $values[$tab_suffix][$row_suffix] = null;
							$loop = !isset($data[$field_name.$tab_suffix.$row_suffix]) && !$error_loop;
            }while($loop);
            if(!$error_loop) {
              $values[$tab_suffix][$row_suffix] = $data[$field_name.$tab_suffix.$row_suffix];
              $purge_fields[$field_name.$tab_suffix.$row_suffix] = true;
              $row_idx +=1; //next loop, keep searching in thsi tab.
            }else{
              debug_msg($submitted_fields, 'CF7SG ERROR: Reached max tabs search loop for table field '.$field_name.' (cannot find any new rows above tab '.$tab_idx.' therefor abandoning search for remaining '.sizeof($submitted_fields)-$idx.' regex match in preg_grep result array listed below)');
              //for loop will end here
            }
          }
          break;
      }//end switch.
    }//end for loop preg_grep array.
    if(!$error_loop){
      //debug_msg($purge_fields, 'purging ');
      //debug_msg($data);
      $data = array_udiff_uassoc($data, $purge_fields, function($a, $b){return 0;}, function($a, $b){
        if ($a === $b) return 0;
        return ($a > $b)? 1:-1;
      }); //this will remove all the surplus fields
      $data[$field_name] = $values;
    }
    return $values;
  }
  /**
   * Validates required benchmark and dynamic_select tags
   *
   * @since 1.0.0
   * @param WPCF7_Validation $result   validation object
   * @param Array $tag   an array of cf7 tag attributes and values
   * @return WPCF7_Validation  validation result
  **/
  public function validate_required($result, $tag){
    if(!isset( $_POST[$tag->name] ) ){
      return $result; //not submitted hence disabled.
    }
    $tag = new WPCF7_FormTag( $tag );

    $name = $tag->name;
  	$value = isset( $_POST[$name] )
  		? trim( strtr( (string) sanitize_text_field($_POST[$name]), "\n", " " ) ): '';

  	if ( $tag->is_required() && '' == $value ) {
  		$result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
    }
    return $result;
  }

  /**
   * Final validation with all values submitted for inter dependent validation
   * Hooked to filter 'wpcf7_validate', sets up the final $result object
   * @since 1.0.0
   * @param WPCF7_Validation $result   validation object
   * @param Array $tags   an array of cf7 tag used in this form
   * @return WPCF7_Validation  validation result
  **/
  public function filter_wpcf7_validate($result, $tags){
    /**
    *@since 1.1.0
    *reset the validation result.
    * this is required to dynamically disable required form fields & stop their validation.
    * Disabled fields are not submitted but CF7 forces their values to empty and therefore flags requried fields as invalid at submission even if they are disabled.
    * this bug was reported in the cf7 support forum:
    * https://wordpress.org/support/topic/bug-javascript-disabled-fields-flagged-as-invalid/
    **/
    //debug_msg($result);
    $result = new WPCF7_Validation();
    //rebuild the default vaidation result.
    $cf7form = WPCF7_ContactForm::get_current();
    $tags = $cf7form->scan_form_tags();
    $submitted = WPCF7_Submission::get_instance();
    $data = $submitted->get_posted_data();
    if(isset($data['unit-contact'])) debug_msg($data['unit-contact'], 'unit-contact ');
    if(isset($_POST['unit-contact'])) debug_msg($_POST['unit-contact'], 'posted unit-contact');
    $tag_types = array(); //store all form tags, including cloned tags for array fields.

    /**
    *@since 2.1.0 fix issue with Conditional Field plugin.
    */
    $data = $this->remove_hidden_fields_from_conditional_plugin($data);
    $toggle_status = '';
    if(isset($_POST['_cf7sg_toggles'])){
      $toggle_status = json_decode( stripslashes($_POST['_cf7sg_toggles']));
    }
    /** @since 2.5.0  new data consolidation enables simpler validation */
    $deprecated = true;
    if( isset($_POST['_cf7sg_version']) && version_compare($_POST['_cf7sg_version'],'2.5.0','>=') ){
      $deprecated = false;
    }
		foreach ( $tags as $tag ) {
      //debug_msg($data);
      if($deprecated){
        /**
        * @since 2.1.5 fix validation of non-toggled checkox/radio.  Toggled fields are now tracked in the tag itself as a class.
        */
        if(!isset($_POST[$tag['name']])){
          $isRequired = false;
          switch($tag['type']){
            case 'checkbox*':
            case 'radio':
            case 'file*': /** @since 2.3.1 fix as file field has no values in $_POST.*/
              $isRequired = true;
              $tag_options = $tag->options;
              if(empty($tag_options)) break; //break from switch, not toggled, we need to validate.
              $toggle='';
              foreach($tag_options as $option){
                $match = array();
                preg_match('/class:cf7sg-toggle-(.[^\s]+)/i',$option, $match);
                if(!empty($match)){
                  $toggle=$match[1];
                  break; //break fromeach loop.
                }
              }
              if(empty($toggle)) break; //break from switch, not toggled, we need to validate.
              //check if the toggle is open. only open toggles are registered.
              if(!empty($toggle_status) && property_exists($toggle_status, $toggle)) break; //break from switch, is toggled and in use, we need to validate.
              //if we reached here, then the checkbox/radio is toggled and not in use, so do not validate.
              $isRequired = false;
              break;
            }
            if(!$isRequired) continue;//not submitted==disabled, or not used.
        }
      }
      /**
      *@since 1.9.0 fix issue with Conditional Field plugin.
      */
      if(!isset($data[$tag['name']])) continue; //it was removed by some plugin.
			$type = $tag['type'];
		  //check to see if this field is an array (table or tab or both).
      $tag_types[$tag['name']] = $tag['type'];
      $field_type = self::field_type($tag['name'], $cf7form->id());
      switch($field_type){
        case 'tab':
        case 'table':
        case 'both':
          // the $data array is passed by reference and will be consolidated.
          $values =  $data[$tag['name']];
          foreach($values as $index=>$value){
            if(is_array($value) && 'both'==$field_type){
              foreach($value as $row=>$row_value){
                if($deprecated && !isset($_POST[$tag['name'].$index.$row])) continue;
                elseif(!isset($row_value)) continue;
                $sg_field_tag = clone $tag;
                $sg_field_tag['name'] = $tag['name'].$index.$row;
                $result = apply_filters("wpcf7_validate_{$tag['type']}", $result, $sg_field_tag);
              }
            }else{
              if($deprecated && !isset($_POST[$tag['name'].$index])) continue;
              elseif(!isset($value)) continue;
              $sg_field_tag = clone $tag;
              $sg_field_tag['name'] = $tag['name'].$index;
              $result = apply_filters("wpcf7_validate_{$tag['type']}", $result, $sg_field_tag);
            }
          }
          // debug_msg($values, 'values '.$tag['name'].'....');
          // debug_msg($result, 'validation '.$tag['name'].'....');
          if($deprecated){
            /** @since 2.3.1 fix the files tag validation for files in tabs/tables*/
            switch(true){
              case 'file':
              case 'file*':
              // Search for $_FILES where name match a tabbed file field
              $regex = '/^'.preg_quote($tag['name']);
              $submitted_fields = preg_grep($regex.'$/', array_keys($_FILES));
              //extract all relevant fields
              $submitted_fields += preg_grep($regex.'_tab-[0-9]+_row-[0-9]+$/', array_keys($_FILES));
              //we also need the rows with tab index=0
              $submitted_fields += preg_grep($regex.'_row-[0-9]+$/', array_keys($_FILES));
              //.. and tabs with row index=0
              $submitted_fields += preg_grep($regex.'_tab-[0-9]+$/', array_keys($_FILES));
              foreach($submitted_fields as $index => $field){
                // For each tabbed file, call the filter validate to add uploaded_files
                $sg_field_tag = clone $tag;
                $sg_field_tag['name'] = $field;
                $result = apply_filters("wpcf7_validate_{$sg_field_tag['type']}", $result, $sg_field_tag);
              }
            }
          }
          break;
        default:
          if(!isset($data[$tag['name']])) continue; /*likely toggled and unused*/
          $result = apply_filters( "wpcf7_validate_{$type}", $result, $tag );
          break;
      }
    }
    $validation = array();
    $form_key = '';
    if(isset($data['_wpcf7_key'])){
      $form_key = $data['_wpcf7_key'];
    }
    /**
    * filter to validate the entire submission and check interdependency of fields
    * @since 1.0.0
    * @param Array  $validation  intially an empty array
    * @param Array  $data   submitted data, $field_name => $value pairs ($value can be an array especially for fields in tables and tabs) null values are fields which have not been disabled and not submitted such as those in toggled sections.
    * @param String  $form_key  unique form key to identify current form.
    * @param int  $form_id  cf7 form post ID.
    * @return Array  an array of errors messages, $field_name => $error_msg for single values, and $field_name => [0=>$error_msg, 1=>$error_msg,...] for array values
    */
    $validation = apply_filters('cf7sg_validate_submission', $validation, $data, $form_key, $data['_wpcf7']);
    if(!empty($validation)){
      foreach($validation as $name=>$msg){
        if(is_array($data[$name]) ) {
          if( is_array($msg) ){
            for($idx=0, $sfx=''; $idx < sizeof($msg); $idx++){
              if(empty($msg[$idx])) continue;
              //setup the error message to return to the form.
              $tag = new WPCF7_FormTag( array('name'=>$name.$sfx, 'type'=>$tag_types[$name]) );
              $result->invalidate( $tag, $msg[$idx] );
            }
          }else{
            debug_msg('Filtered cf7sg_validate_submission validation ERROR, expecting array for field '.$name);
          }
        }elseif( !empty($msg) ){
          $tag = new WPCF7_FormTag( array('name'=>$name, 'type'=>$tag_types[$name]) );
          $result->invalidate( $tag, $msg);
        }
      }
    }
    return $result;
  }
  /**
  * Function to save toggled collapsible sections status to open them up again for draft forms.
  * Hooks action 'cf7_2_post_form_posted'
  * @since 1.0.0
  * @param      string    $key     form unique key.
  * @param     Array    $submitted_data    array of field-name=>value pairs submitted in form
  *@since 1.1.0
  *@param string $param text_description
  *@return string text_description
  */
  public function save_toggle_status($post_id, $key, $post_fields, $post_meta_fields, $submitted_data){
    if(isset($submitted_data['_cf7sg_toggles'])){
      $toggles = json_decode( stripslashes( $submitted_data['_cf7sg_toggles'] ) );
      $toggles_array = array();
      if(!empty($toggles)){
        foreach($toggles as $key=>$value){
          $toggles_array[$key] = sanitize_text_field($value);
        }
      }
      //debug_msg($toggles_array, 'toggles status saved, ');
      update_post_meta($post_id, 'cf7sg_toggles_status', $toggles_array);
    }
  }
  /**
   * Function to save custom options values added to tagged select2 fields.
   * Hooks action 'cf7_2_post_form_posted'
   * @since 1.0.0
   * @param      string    $key     form unique key.
   * @param     Array    $submitted_data    array of field-name=>value pairs submitted in form
  **/
  public function save_select2_custom_options($post_id, $key, $post_fields, $post_meta_fields, $submitted_data){
    $form_id = Cf7_WP_Post_Table::form_id($key);
    $tagged_fields = get_post_meta($form_id, '_cf7sg_select2_tagged_fields', true);
    if(empty($tagged_fields)){
      return;
    }

    foreach($tagged_fields as $field_name=>$source){
      $value = $submitted_data[$field_name];
      $is_array = true;
      if(!is_array($value)){
        $value = array($value);
        $is_array = false;
      }
      switch($source['source']){
        case 'taxonomy':
          $taxonomy = $source['taxonomy'];
          $idx=0;
          foreach($value as $term){
            if(!term_exists($term, $taxonomy)){
              /**
              * Filter custom options from tag enabled select2 dynamic-dropdown fields
              * where the source of options come from taxonomy terms.  Filter is fired when a new value is submitted. The pluign inserts a new term by default as per submitted value.
              * @param  string  $term the new term submitted by the user.
              * @param  string  $field_name the name of the form field.
              * @param  string $taxonomy  the taxonomy to which this is added.
              * @param  array  $submitted_data  array of other submitted $field=>$value pairs.
              * @param string $key  the form unique key.
              * @return string the new term name to insert.
              * @since 2.0.0
              */
              $term = apply_filters('cf7sg_dynamic_dropdown_new_term', $term, $field_name, $taxonomy, $submitted_data, $key);
              $new_term = wp_insert_term($term, $taxonomy);
              if(!is_wp_error($new_term)){
                $new_term = get_term($new_term['term_id'], $taxonomy);
                $value[$idx] = $new_term->slug;
              }
            }
            $idx++;
          }
          break;
        case 'post':
          $args = array(
           'post_type' => $source['post'],
           'post_status' => 'publish',
           'posts_per_page'   => -1
          );
          if(!empty($source['taxonomy'])){
            $tax = array();
            if(sizeof($source['taxonomy']) > 1){
              $tax['relation'] = 'AND';
            }
            foreach($source['taxonomy'] as $term => $taxonomy){
              $tax[]=array(
                'taxonomy' => $taxonomy,
                'field'    => 'slug',
                'terms'    => $term
              );
            }
            $args['tax_query'] = $tax;
         }
         $args = apply_filters('cf7sg_dynamic_dropdown_post_query', $args, $tag->name, $key);
         $posts = get_posts($args);
         $options = array();
         if(!empty($posts)){
           foreach($posts as $post){
             $options[$post->post_name] = $post->post_title;
           }
         }
         $idx=0;
         foreach($value as $slug){
           if(!isset($options[$slug])){
             $args = $source;
             $post_type = $source['post'];
             $title = $slug;
             $post_name = '';
             /**
             * Filter custom options from tag enabled select2 dynamic-dropdown fields
             * where the source of options come from post titles.  Filter is fired when a new value is submitted.
             * This plugin does not take any further action, ie no post of $post_type will be created. It is upto you to do so and return the slug of the newly created post.
             * @param  string  $post_name the new post slug.
             * @param  string  $field_name the name of the form field.
             * @param  string  $title  new value being submitted for a new post title.
             * @param  string $post_type  the post type from which this dropdown was built
             * @param  array  $args  an array of additional parameters that was set in the tag, for example the taxonomy and terms from which to filter the posts for the dynamic list.
             * @param  array  $submitted_data  array of other submitted $field=>$value pairs.
             * @param string $key  the form unique key.
             */
             $value[$idx] = apply_filters('cf7sg_dynamic_dropdown_new_post', $post_name, $field_name, $title, $post_type, $args, $submitted_data, $key);
           }
           $idx++;
         }
         break;
        case 'filter':
          $values = $value;
          /**
          * Filter custom otions from tag enabled select2 dynamic-dropdown fields
          * where the source of options come from a filter.  Filter is fired when values are submitted.
          * Return updated values if any are custom values so that saved/draft submissions will reflect the correct value saved in the DB,
          * @param  array  $values  an array submitted values (several values can be submitted in the case of a tabbed/table input field).
          * @param  string  $field_name the name of the form field.
          * @param  array  $submitted_data  array of other submitted $field=>$value pairs.
          * @param string $key  the form unique key.
          */
          $value = apply_filters('cf7sg_dynamic_dropdown_filter_select2_submission', $values, $field_name, $submitted_data, $key);
          break;
      }
      //Save the modified value, find which post field the field is mapped to
      if(!$is_array){
        $value = $value[0];
      }
      if( isset($post_fields[$field_name]) ){
        $post_key = $post_fields[$field_name];
        switch($post_key){
          case 'title':
          case 'author':
          case 'excerpt':
            $post_key = 'post_'.$post_key;
            break;
          case 'editor':
            $post_key ='post_content';
            break;
          case 'slug':
            $post_key ='post_name';
            break;
        }
        wp_update_post(array(
          'ID' => $post_id,
          $post_key => $value
        ));
      }else if( isset($post_meta_fields[$field_name]) ){
        update_post_meta($post_id, $post_meta_fields[$field_name], $value);
      }
    }
  }
	/**
	 * Function to retrieve dynamic-dropdown attributes
	 *
	 * @since 1.0.0
	 * @param      WPCF7_FormTag    $tag     cf7 tag object of basetype dynamic_select.
	 * @return     array    an array of attributes with 'source'=>[post|taxonomy|filter],     .
	**/
	static public function get_dynamic_drodown_attributes($tag){
	  if(is_array($tag) && isset($tag['basetype']) && 'dynamic_select' === $tag['basetype']){
			$tag = new WPCF7_FormTag($tag);
		}
		if( !($tag instanceof WPCF7_FormTag) || 'dynamic_select' !== $tag['basetype']){
			return false;
		}
		$source = array();
    foreach($tag->values as $values){
      if(0 === strpos($values, 'slug:') ){
        $source['source'] = "taxonomy";
        $source['taxonomy'] = str_replace('slug:', '', $values);
      }
      if(0 === strpos($values, 'source:post')){
        $source['source'] = "post";
        $source['post'] = str_replace('source:post:', '', $values);
      }
      if(0 === strpos($values, 'taxonomy:')){
        if(empty($source['taxonomy'])){
          $source['taxonomy'] = array();
        }
        $values = str_replace('taxonomy:', '', $values);
        $exp = explode(":", $values);
        if(!empty($exp) && is_array($exp)){
          $source['taxonomy'][$exp[1]] = $exp[0];
        }
      }
      if(0 === strpos($values, 'source:filter')){
        $source['source'] = "filter";
      }
    }
		return $source;
	}
  /**
  * Function to temporarily fix the conditional fields plugin issue.
  * this is is called by the vlidation function 'filter_wpcf7_validate' above.
  *@since 2.1
  *@param array $posted_data submitted data
  *@return array submitted data without fields that remained hidden.
  */
  private function remove_hidden_fields_from_conditional_plugin($posted_data){
    /*code taken from cf7cf.php file in cf7-conditional-fields*/
    if(!isset($posted_data['_wpcf7cf_hidden_group_fields'])){
      return $posted_data;
    }
    $hidden_fields = json_decode(stripslashes($posted_data['_wpcf7cf_hidden_group_fields']));
    $conditonal_fields = array();
    if (is_array($hidden_fields) && count($hidden_fields) > 0) {
      foreach ($hidden_fields as $field) {
        if (wpcf7cf_endswith($field, '[]')) {
          $field = substr($field,0,strlen($field)-2);
        }
        unset( $posted_data[$field] );
      }
    }
    return $posted_data;
  }
  /**
  * Filter mail tags which are table or tab fields.
  * Hooked on 'wpcf7_mail_tag_replaced'.
  *@since 2.1
  *@param string $replaced mail tag string to replace.
  *@param mixed $submitted value of submitted field from cf7 posted data..
  *@param boolea $html mail if mail body is using html.
  *@param WPCF7_MailTag $mail_tag mail tag object of field being replaced.
  *@return string repalcement string.
  */
  public function filter_table_tab_mail_tag($replaced, $submitted, $html, $mail_tag ){
    $cf7form = WPCF7_ContactForm::get_current();
    $field_type = self::field_type($mail_tag->field_name(), $cf7form->id());
    $label = '';
    $build = false;
    switch($field_type){
      case 'tab':
      case 'table':
        if($html){
          $filtered = apply_filters('cf7sg_mailtag_grid_fields', '', $mail_tag->field_name(), $submitted, Cf7_WP_Post_Table::form_key($cf7form->id()));
          if(!empty($filtered)){
            $replaced = $filtered;
            break;
          }
        }
        $label=('table'==$field_type)?'row':$field_type;
        if($html && is_array($submitted)) {
          $idx=0;
          $replaced='';
          foreach($submitted as $index=>$value){
            $idx++;
            if(is_array($value)) $value = implode(' | ', $value);
            $replaced .= '<div><label>'.$label.'('.$idx.'):</label><span>'.$value.'</span></div>';
          }
        }
        break;
      case 'both':
        if($html){
          $filtered = apply_filters('cf7sg_mailtag_grid_fields', '', $mail_tag->field_name(), $submitted, Cf7_WP_Post_Table::form_key($cf7form->id()));
          if(!empty($filtered)){
            $replaced = $filtered;
            break;
          }
        }
        // build table.
        $replaced = '';
        if( is_array($submitted)) {
          $tab = 0;
          foreach($submitted as $index=>$value){
            $tab++;
            $idx=0;
            if($html) $replaced .= '<div><span>tab('.$tab.')</span>&nbsp;';
            else  $replaced .= 'tab('.$tab.') = ';
            if(is_array($value)){
              foreach($value as $row=>$row_value){
                $idx++;
                if(is_array($row_value)) $row_value = implode('|', $row_value);
                if($html) $replaced .='<div><label>row('.$idx.'):</label><span>' . $row_value . '</span></div>';
                else  $replaced .= $row_value.',';
              }
            }
            if($html) $replaced .='</div>';
            else  $replaced .= PHP_EOL;
          }
        }
        break;
    }

    return $replaced;
  }
  /**
  * We need to add the missing attachments just before the mail is sent.
  * Hooked to filter 'wpcf7_mail_components', sets up the final $components object
  * @since 2.4.1
  * @param Array $components   an array of mail parts
  * @param WPCF7_ContactForm $cf7form   cf7 form object
  * @param WPCF7_Mail $cf7mail   cf7 mail object
  * @return Array  an array of mail parts
  */
  public function wpcf7_mail_components($components, $cf7form, $cf7mail) {
    //$tags = $cf7form->scan_form_tags();
    $submission = WPCF7_Submission::get_instance();
    $uploaded_files = $submission->uploaded_files();
    $cf7_mail = WPCF7_Mail::get_current();
    /** @since 2.5.6 fix issue with non-attached files */
    $template = $cf7_mail->get( 'attachments' );
    $row = $tab = null;
    $idx = 0;
    $attachments = array();
    foreach ( (array) $uploaded_files as $name => $path ) {
      if ( false === strpos( $template, "[${name}]" ) )  continue; //not attached.
      $field_type = self::field_type($name, $cf7form->id());
      $data = $submission->get_posted_data($name);
      switch($field_type){
        case 'tab':
        case 'table':
          foreach($data as $field_idx=>$file_name){
            if(empty($file_name)) continue;
            $idx++;
            $row = ('table'==$field_type)? str_replace('_row-', '', $field_idx):null;
            $row = (isset($row) && empty($row))? 1:1+(int)$row;
            $tab = ('tab'==$field_type)?  str_replace('_tab-', '', $field_idx):null;
            $tab = (isset($tab) && empty($tab))? 1:1+(int)$tab;
            $file = $uploaded_files[$name.$field_idx];
            $attachments[] = $file;
            $file = explode('/',$file);
            $filename = $file[count($file)-1];
            $components['body'].= apply_filters('cf7sg_annotate_mail_attach_grid_files', '', $name, $row, $tab, $idx,$filename, $_POST['_wpcf7_key']);
          }
          break;
        case 'both':
          $row = $tab = 1;
          foreach($data as $field_tab=>$tab_files){
            $tab = str_replace('_tab-', '', $field_tab);
            $tab = empty($tab)?1:1+(int) $tab;
            foreach($tab_files as $field_row=>$file_name){
              if(empty($file_name)) continue;
              $idx++;
              $row = str_replace('_row-', '', $field_row);
              $file = $uploaded_files[$name.$field_tab.$field_row];
              $attachments[] = $file;
              $file = explode('/',$file);
              $filename = $file[count($file)-1];
              $components['body'].= apply_filters('cf7sg_annotate_mail_attach_grid_files','', $name, $row, $tab, $idx,$filename, $_POST['_wpcf7_key']);
            }
          }
          break;
        default: //singular fields.
          if(empty($path)) continue;
          $idx++;
          $attachments[] = $path;
          $path = explode('/',$path);
          $filename = $path[count($path)-1];
          $components['body'].= apply_filters('cf7sg_annotate_mail_attach_grid_files','', $name, null, null, $idx,$filename, $_POST['_wpcf7_key']);
          break;
      }
      //     break;
      // }
    }
    $components['attachments'] = $attachments;
    return $components;
  }
}
