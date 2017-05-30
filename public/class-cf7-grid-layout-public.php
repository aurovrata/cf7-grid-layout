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
    $class = array();

    wp_enqueue_script('contact-form-7');
    wp_enqueue_script($this->plugin_name);
    wp_localize_script( $this->plugin_name, 'cf7sg_ajaxData', array('url' => admin_url( 'admin-ajax.php' )));

    $class[]='has-validation';
    wp_enqueue_script('jquery-select2');
    wp_enqueue_style('select2-style');
    $class[]='has-select2';
    wp_enqueue_script('jquery-ui-accordion');
    $class[]='has-accordion';
    //load tabs
    $has_tabs = false;
    if(get_post_meta($cf7_id, '_cf7sg_has_tabs', true)){
      wp_enqueue_script('jquery-ui-tabs');
      $class[]='has-tabs';
      $has_tabs = true;
    }
    //load tables
    $has_tables = false;
    if(get_post_meta($cf7_id, '_cf7sg_has_tables', true)){
      $class[]='has-table';
      $has_tables = true;
    }
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
    $cf7post = get_post($cf7_id);
    $cf7_key = $cf7post->post_name;
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
        //check if sub-forms needs tabs|table
        if(!$has_tables && get_post_meta($post_obj->ID, '_cf7sg_has_tables', true)){
          $class[]='has-table';
          $has_tables = true;
        }
        if(!$has_tabs && get_post_meta($post_obj->ID, '_cf7sg_has_tabs', true)){
          wp_enqueue_script('jquery-ui-tabs');
          $class[]='has-tabs';
          $has_tabs = true;
        }
      }
      if(!empty($cf7_form)){ //redraw the form.
        wpcf7_save_contact_form(array('id'=>$cf7_id, 'form'=>$form_raw));
        debug_msg('Updated embeded forms in cf7 form: '.$cf7_key);
        //reload the form
        $cf7_form = wpcf7_contact_form($cf7_id);
        $output = $cf7_form->form_html($attr);
        $class[]='has-update';
      }
    }
    //$cf7_key = get_post_meta($cf7_id, '_smart_grid_cf7_form_key', true);
    //allow custom script print
    do_action('smart_grid_enqueue_scripts', $cf7_key, $attr);
    //form id
    $css_id = apply_filters('cf7_smart_grid_form_id', $cf7_key, $attr);
    $classes = implode(' ', $class);
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
   * Funciton to load cusomt js script for Post My CF7 Form loading of form field values
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
  public function register_dynamic_taxonomy_shortcode() {
    if( function_exists('wpcf7_add_form_tag') ) {
      //dynamic select
      wpcf7_add_form_tag(
        array( 'dynamic_select', 'dynamic_select*' ),
        array($this,'cf7_taxonomy_shortcode'),
        true //has name
      );
    }
  }
  /**
	 * Register a [taxonomy] shortcode with CF7.
	 * called by funciton above
	 * This function registers a callback function to expand the shortcode for the googleMap form fields.
	 * @since 1.0.0
   * @param strng $tag the tag name designated in the tag help screen
   * @return string a set of html fields to capture the googleMap information
	 */

  public function cf7_taxonomy_shortcode($tag){
    $tag = new WPCF7_FormTag( $tag );
    ob_start();
    include( plugin_dir_path( __FILE__ ) . '/partials/cf7-taxonomy-tag-display.php');
    $html = ob_get_contents ();
    ob_end_clean();
    return $html;
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
    $grid_fields = get_post_meta($cf7_id , '_cf7sg_grid_tabs_names', true);
    $grid_fields += get_post_meta($cf7_id , '_cf7sg_grid_table_names', true);

    $tags = $cf7form->scan_form_tags();
    foreach($tags as $tag){
      if(in_array($tag['name'], $grid_fields)){
        //setup wpcf7 validation filters for arrays prior to cf7 default filters so as not to get array conversion errors.
        add_filter("wpcf7_validate_{$tag['type']}", array($this, 'validate_array_values'), 5,2);
      }
    }

    return $data;
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
        if('thermal-fuel-qty' == $name){
          debug_msg($results, 'thermal-fuel-qty:'.$tag_obj->type.' ');
        }
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
}
