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
   * Shortcode handler for multi-forms [multi-cf7-form]
   * Hooked to 'add_shortcode'
   * @since 1.0.0
   * @param      Array    $atts     attributes from the shortcode.
   * @param      String    $content     shortcode content.
   * @return     String    shorcode rendered content.
  **/
  public function multi_form_shortcode($atts, $content){
    //encapsulate the forms in a form element,
    //and make sure we had a submit button if non-provided in the content
    //parse all shild form  shotcodes
    $shortcode = '<div id="cf7sg-multi-form">';

    $shortcode .= do_shortcode($content);

    $shortcode .='</div>';
    //extract main form
    //$start = strpos($shortcode, '<!-- MULTI-CF7 MAIN FORM START -->');
    //$end = strpos($shortcode, '<!-- MULTI-CF7 MAIN FORM END -->');

    //$main_form_html = substr($shortcode, $start, $end - $start);
    //Create a new DOM document
    require_once plugin_dir_path( dirname( __DIR__ ) ) . 'assets/php-query/phpQuery.php';

    phpQuery::newDocument($main_form_html);
    $inner_main_form = pq('#cf7sg-multi-form-main')->find('form.wpcf-form')->contents()->remove();
    $form_wrap = pq('#cf7sg-multi-form-main')->contents()->remove();
    //reinsert main form
    pq('#cf7sg-multi-form-main').after($inner_main_form);
    //remove the main form wrapper
    pq('#cf7sg-multi-form')->find('#cf7sg-multi-form-main')->remove();
    $form_wrap->find(('form.wpcf-form'))->append(pq('#cf7sg-multi-form')->html());

    return $form_wrap->htmlOuter();


    //Parse the HTML. The @ is used to suppress any parsing errors
    //that will be thrown if the $html string isn't valid XHTML.
    /*
    @$dom->loadHTML($main_form_html);
    $node = $dom->getElementsByTagName('form')->item(0);
    //$outerHTML = $node->ownerDocument->saveHTML($node);
    $innerHTML = '';
    if( $a('main')){
      $innerHTML = '<!-- MULTI-CF7 MAIN FORM START -->';
    }
    foreach ($node->childNodes as $childNode){
      if( !$a('main')){
        //ignore hidden fields div as well as display message
        if('div' == $childNode->tagName &&
          $childNode.hasAttribute('style') &&
          !$childNode.hasAttribute('class') &&
          !$childNode.hasAttribute('id')){
            if( 1 == preg_match('/^display:\s?none$/g', $childNode.getAttribute('style')) ) continue;
        } //hidden fields
        if('div' == $childNode->tagName && $childNode.hasAttribute('class') !$childNode.hasAttribute('id') ){
          if( 1 == preg_match('/wpcf7-response-output/g', $childNode.getAttribute('class')) ) continue;
        }//response output
        //''
      }
      $innerHTML .= $childNode->ownerDocument->saveHTML($childNode);
    }
    */

    //TODO: enable a filter for cf7 form submission as well as post my cf7 form
  }
  /**
   * Shortcode handler for multi-forms to include a child form [child-cf7-form]
   * Hooked to 'add_shortcode'
   * @since 1.0.0
   * @param      Array    $atts     attributes from the shortcode.
   * @return     String    shorcode rendered content.
  **/
  public function child_form_shortcode($atts){
    $a = shortcode_atts( array(
      'main' => false,
      'cf7key' => ''
    ), $atts );
    if(!$a('main')){
      $cf7_posts = get_posts(array(
        'post_type' => 'wpcf7_contact_form',
        'name' => $a['cf7key']
      ));
      wp_reset_postdata();
      if(!empty($cf7_posts)){
        $cf7_id = $cf7_posts[0]->ID;
        $cf7_form = wpcf7_contact_form($cf7_id);
        $shortcode =  $cf7_form->form_elements();
        if(1 == preg_match('/type="submit"/g', $html)){
          $shortcode .=  $cf7_form->form_response_output();
        }
        return $shortcode;
      }else{
        return '<em>' . _('[child-cf7-form] shortcode missing cf7key attribute','cf7-admin-table') . '</em>';
      }
    }
    /* Assuming this is the main cf7 form */
    $shortcode = '<div id="cf7sg-multi-form-main">';

    //get the cf7 form
    $shortcode .= do_shortcode('[cf7-form cf7key="'.$a['cf7key'].'"]');

    $shortcode .= '</div>';

    return $shortcode;
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
}
