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
	 * The cf7 submitted data.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      Array    $submitted_data    The cf7 submitted data.
	 */
	private $submitted_data;

  /**
   * The cf7 array fields.
   *
   * @since    1.0.0
   * @access   private
   * @var      Array    $array_tabs_fields    The form fields which were converted to arrays by the plugin.
   */
  private $array_tabs_fields;

  /**
   * The cf7 array fields.
   *
   * @since    1.0.0
   * @access   private
   * @var      Array    $array_table_fields    The form fields which were converted to arrays by the plugin.
   */
  private $array_table_fields;

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
    $this->submitted_data = array();
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
    wp_register_style( 'cf7-benchmark-css', $plugin_dir . 'public/css/cf7-benchmark.css', array('dashicons'), $this->version, 'all' );
    //others
    wp_register_style( 'cf7-jquery-ui', $plugin_dir . 'assets/jquery-ui/jquery-ui.min.css', array(), '1.12.1', 'all');
    wp_register_style( 'cf7-jquery-ui-theme', $plugin_dir . 'assets/jquery-ui/jquery-ui.theme.min.css', array(), '1.12.1', 'all');
    wp_register_style( 'cf7-jquery-ui-structure', $plugin_dir . 'assets/jquery-ui/jquery-ui.structure.min.css', array(), '1.12.1', 'all');
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

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cf7-grid-layout-public.js', array( 'jquery' ), $this->version, true );
    wp_register_script('jquery-select2', plugin_dir_url( __DIR__ ) . 'assets/select2/js/select2.min.js', array( 'jquery' ), $this->version, true );
    wp_register_script('jquery-nice-select', plugin_dir_url( __DIR__ ) . 'assets/jquery-nice-select/js/jquery.nice-select.min.js', array( 'jquery' ), $this->version, true );
    wp_register_script('jquery-toggles', plugin_dir_url( __DIR__ ) . 'assets/jquery-toggles/toggles.min.js', array( 'jquery' ), $this->version, true );
    wp_register_script('js-cf7sg-benchmarking', plugin_dir_url( __FILE__ ) . 'js/cf7-benchmark.js', array( 'jquery' ), $this->version, true );
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
   * Add the cf7 key to the hiddend fields so as not to have to load it after each submission.
   * Hooked to
   * @since 1.0.0
   * @param      Array    $hidden     hidden fields to add to cf7 form.
   * @return      Array    $hidden     hidden fields to add to cf7 form.
  **/
  public function set_hidden_key($hidden){
    $form = wpcf7_get_current_contact_form();
    $post = get_post($form->id());
    $hidden['_wpcf7_key'] = $post->post_name;
    return $hidden;
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
    $cf7_id = $attr['id'];
    $class = get_post_meta($cf7_id, '_cf7sg_classes', true);
    if(empty($class)){
      $class = array();
    }

    wp_enqueue_script('contact-form-7');
    wp_enqueue_script($this->plugin_name);
    wp_localize_script( $this->plugin_name, 'cf7sg_ajaxData', array('url' => admin_url( 'admin-ajax.php' )));

    $class['has-validation']=true;

    if(isset($class['has-select2'])){
      wp_enqueue_script('jquery-select2');
      wp_enqueue_style('select2-style');
    }
    $class['has-accordion']=true;
    wp_enqueue_script('jquery-ui-accordion');

    //load tabs
    $has_tabs = false;
    if(get_post_meta($cf7_id, '_cf7sg_has_tabs', true)){
      wp_enqueue_script('jquery-ui-tabs');
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

    if(isset($class['has-nice-select'])){
      wp_enqueue_script('jquery-nice-select');
      wp_enqueue_style('jquery-nice-select-css');
    }
    $class['has-toggles']=true;
    wp_enqueue_script('jquery-toggles');
    wp_enqueue_style('jquery-toggles-css');
    wp_enqueue_style('jquery-toggles-light-css');


    //styles
    wp_enqueue_style('contact-form-7');
    wp_enqueue_style($this->plugin_name);
    wp_enqueue_style('smart-grid');
    $class['has-grid']=true;
    wp_enqueue_style('cf7-jquery-ui-theme');
    wp_enqueue_style('cf7-jquery-ui-structure');
    wp_enqueue_style('cf7-jquery-ui');
    if(isset($class['has-benchmark'])){
      wp_enqueue_script('js-cf7sg-benchmarking');
    }

    //get the key
    $cf7post = get_post($cf7_id);
    $cf7_key = $cf7post->post_name;
    //load custom css/js script from theme css folder
    $themepath = get_stylesheet_directory();
    $themeuri = get_stylesheet_directory_uri();
    if( file_exists($themepath.'/css/'.$cf7_key.'.css') ){
      wp_enqueue_style( $cf7_key , $themeuri.'/css/'.$cf7_key.'.css', array($this->plugin_name), null, 'all');
    }
    if( file_exists($themepath.'/js/'.$cf7_key.'.js') ){
      wp_enqueue_script( $cf7_key , $themeuri.'/js/'.$cf7_key.'.js', array($this->plugin_name), null, true);
    }

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
        }
        //check if sub-forms needs tabs|tablevalidate_beanchmark
        if(!$has_tables && get_post_meta($post_obj->ID, '_cf7sg_has_tables', true)){
          $class['has-table']=true;
          $has_tables = true;
        }
        if(!$has_tabs && get_post_meta($post_obj->ID, '_cf7sg_has_tabs', true)){
          wp_enqueue_script('jquery-ui-tabs');
          $class['has-tabs']=true;;
          $has_tabs = true;
        }
      }
      if(!empty($cf7_form)){ //redraw the form.
        wpcf7_save_contact_form(array('id'=>$cf7_id, 'form'=>$form_raw));
        debug_msg('Updated embeded forms in cf7 form: '.$cf7_key);
        //reload the form
        $cf7_form = wpcf7_contact_form($cf7_id);
        $output = $cf7_form->form_html($attr);
        $class['has-update']=true;
      }
    }
    //$cf7_key = get_post_meta($cf7_id, '_smart_grid_cf7_form_key', true);
    //allow custom script print
    do_action('smart_grid_enqueue_scripts', $cf7_key, $attr);
    //form id
    $css_id = apply_filters('cf7_smart_grid_form_id', $cf7_key, $attr);
    $classes = implode(' ', array_keys($class));
    $output = '<div id="cf7sg-form-' . $css_id . '" class="cf7-smart-grid ' . $classes . '">' . $output . '</div>';
    return $output;
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
    if(!isset($this->array_tabs_fields)){
      $this->array_tabs_fields = get_post_meta($post_id, '_cf7sg_grid_tabs_names', true);
      $this->array_table_fields = get_post_meta($post_id, '_cf7sg_grid_table_names', true);
      $sub_forms = get_post_meta($post_id, '_cf7sg_sub_forms', true);
      foreach($sub_forms as $form_key){
        $form_id = Cf7_WP_Post_Table::form_id($form_key);
        $this->array_table_fields += get_post_meta($form_id, '_cf7sg_grid_table_names', true);
        $this->array_tabs_fields += get_post_meta($form_id, '_cf7sg_grid_tabs_names', true);
      }
    }
    $grid='';
    if(false === array_search($field, $this->array_table_fields )){
      if(false === array_search($field, $this->array_tabs_fields )){
        return $default_script;
      }else{
        $grid = 'tabs';
      }
    }else{
      $grid = 'table';
    }
    include( plugin_dir_path( __FILE__ ) . '/partials/cf7sg-field-load-script.php');
    return false;
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
   * @return     string    $p2     .
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
    $cf7_id = $_POST['id'];

    if(!wpcf7_verify_nonce($_POST['nonce'], $cf7_id)){
      wp_send_json_error(array('message'=>'nonce failed'));
      wp_die();
    }

    if(isset($_POST['tabs_fields'])){
      $tabs_fields =  json_decode(stripslashes($_POST['tabs_fields']));
      $table_fields =  json_decode(stripslashes($_POST['table_fields']));

      //debug_msg($grid_fields, $cf7_id);
      update_post_meta($cf7_id, '_cf7sg_grid_tabs_names', $tabs_fields);
      update_post_meta($cf7_id, '_cf7sg_grid_table_names', $table_fields);
      wp_send_json_success(array('message'=>'saved fields'));
    }else{
      wp_send_json_error(array('message'=>'no data received'));
    }
    wp_die();
  }
  /**
   *  Use this function to setup validations filters for the submitted form.
   * Funciton hooked on 'wpcf7_posted_data'
   * @since 1.0.0
   * @param   Array    $data    unvalidated submitted data.
   * @return  Array    filtered submitted data.
  **/
  public function setup_tag_filters($data){
    //TODO: validate with sfgrid form nonce.
    $cf7form = WPCF7_ContactForm::get_current();
    if(empty($cf7form) ){
      debug_msg("Unable to load submitted form");
      return $data;
    }else if(isset($data['_wpcf7']) ){
      $cf7_id = $data['_wpcf7'];
      if( $cf7_id != $cf7form->id() ){
        $cf7form = WPCF7_ContactForm::get_instance($cf7_id);
      }
    }
		$grid_fields = self::get_grid_fields($cf7_id);

		//debug_msg($grid_fields, 'grid fields...');
    $tags = $cf7form->scan_form_tags();
    foreach($tags as $tag){
      if( isset($grid_fields[$tag['name']]) ){
        //setup wpcf7 validation filters for arrays prior to cf7 default filters so as not to get array conversion errors.
        add_filter("wpcf7_validate_{$tag['type']}", array($this, 'validate_array_values'), 5,2);
      }
    }

    return $data;
  }
	/**
	 * function returns an array of fields which are eitehr tabs or tables
	 *
	 * @since 1.0.0
	 * @param      string    $form_id     form id for which to return fields.
	 * @return     array    empty array if no fields found..
	**/
	static function get_grid_fields($form_id){
		$grid_fields = array();
		//tabs
		$fields = get_post_meta($form_id , '_cf7sg_grid_tabs_names', true);
		if(!empty($fields)){
			$grid_fields += array_fill_keys($fields, 'tab');
		}
		//tables
    $fields = get_post_meta($form_id , '_cf7sg_grid_table_names', true);
		if(!empty($fields)){
			$grid_fields += array_fill_keys($fields, 'table');
		}
		$subform_keys = get_post_meta($form_id, '_cf7sg_sub_forms', true);

		if(empty($subform_keys)) return $grid_fields;

		foreach($subform_keys as $cf7Key){
			$post_id = Cf7_WP_Post_Table::form_id($cf7Key);
			$grid_fields += self::get_grid_fields($post_id);
		}

		return $grid_fields;
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
    $tag = new WPCF7_FormTag( $tag );

    $name = $tag->name;
  	$value = isset( $_POST[$name] )
  		? trim( strtr( (string) $_POST[$name], "\n", " " ) )
  		: '';


  	if ( $tag->is_required() && '' == $value ) {
  		$result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
    }
    return $result;
  }
  /**
	 * Filter the validation results of cf7 plugin. Resets the results for array fields
	 * @since 1.0.0
   * @param WPCF7_Validation $results   validation object
   * @param Array $tags   an array of cf7 tag used in this form
   * @return WPCF7_Validation  validation result
	 */
  public function validate_array_values($results, $tag){
    /*
    TODO: see if $resutls[name] can be replaced from field bame to <field-name>-<index> so that error msg insertion can take place accurately on the front end.
    This woudl also require that the js file that builds array fields (tabs/tables) also replaces the class in teh outer span with an indexed one,
    span.wpcf7-form-control-wrap.<field-name> to span.wpcf7-form-control-wrap.<field-name>-<index>
    */
    $tag_obj = new WPCF7_FormTag( $tag );

  	$name = $tag_obj->name;
    //reset the $_POST data as cf7 expect single value
    if( isset($_POST[$name]) && is_array($_POST[$name]) ){
      $values = $_POST[$name];
      $_POST[$name] = $values[0];
      //temporarily remove this filter
      remove_filter("wpcf7_validate_{$tag_obj->type}", array($this, 'validate_array_values'), 5,2);
      for($idx=1; $idx<sizeof($values); $idx++){
        $_POST[$name.'_'.$idx] =$values[$idx];
        $tag['name'] = $name.'_'.$idx;
        apply_filters("wpcf7_validate_{$tag_obj->type}", $results, $tag);
      }
      //reapply this filter
      add_filter("wpcf7_validate_{$tag_obj->type}", array($this, 'validate_array_values'), 5,2);
    }
    return $results;
  }
  /**
   * Final validation with all values submitted for inter dependent validation
   * Hooked to filter 'wpcf7_validate', sets up the final $results object
   * @since 1.0.0
   * @param WPCF7_Validation $results   validation object
   * @param Array $tags   an array of cf7 tag used in this form
   * @return WPCF7_Validation  validation result
  **/
  public function filter_wpcf7_validate($results, $tags){
    //TODO: validate with sfgrid form nonce.
    //get the submitted values
    $submitted = WPCF7_Submission::get_instance();
    $data = $submitted->get_posted_data();
    $tag_types = array();
    foreach($tags as $tag){
      $tag_types[$tag['name']] = $tag['type'];
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
    * @param Array  $data   submitted data, $field_name => $value pairs ($value can be an array).
    * @param String  $form_key  unique form key to identify current form.
    * @return Array  an array of errors messages, $field_name => $error_msg for single values, and $field_name => [0=>$error_msg, 1=>$error_msg,...] for array values
    */
    $validation = apply_filters('cf7sg_validate_submission', $validation, $data, $form_key);
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
    //debug_msg
    return $results;
  }
  /**
   * Function to save custom options values added to tagged select2 fields.
   * Hooked 'cf7_2_post_form_posted'
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
         $args = apply_filters('cf7sg_dynamic_dropdown_post_query', $args, $tag->name, $cf7_key);
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
             */
             $value[$idx] = apply_filters('cf7sg_dynamic_dropdown_new_post', $post_name, $field_name, $title, $post_type, $args);
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
          */
          $value = apply_filters('cf7sg_dynamic_dropdown_filter_select2_submission', $values, $field_name);
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
	 * Function to retrive dynamic-dropdown attributes
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
}
