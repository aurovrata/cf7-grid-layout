<?php

/**
* Abstract class for building dynamic lists.
*
* @since 4.10.0
*/

abstract class CF7SG_Dynamic_list{
  protected $tag_id;
  protected $label;
  protected static $instances;

  public function __construct($tag_id, $label){
    $this->tag_id = $tag_id;
    $this->label = $label;
    $this->register();
  }

  protected function register(){
    if(!isset(self::$instances)) self::$instances = array();
    self::$instances[$this->tag_id] = $this;
  }

  static public function get_instances($tag_id=null){
    if(!isset(self::$instances)) self::$instances = array();
    $instance = self::$instances;
    if(isset($tag_id)){
      $instance = isset(self::$instances[$tag_id]) ? self::$instances[$tag_id]:false;
    }
    return $instance;
  }

  public function register_cf7_tag(){
    if ( class_exists( 'WPCF7_TagGenerator' ) ) {
      $tag_generator = WPCF7_TagGenerator::get_instance();
      $tag_generator->add(
        $this->tag_id, //tag id
        $this->label, //tag button label
        array($this,'admin_tag_generator') //callback
      );
    }
  }
  /**
   * Dynamic select screen displayt.
   *
   * This function is called by cf7 plugin, and is registered with a hooked function above
   *
   * @since 1.0.0
   * @param WPCF7_ContactForm $contact_form the cf7 form object
   * @param array $args arguments for this form.
   */
  public function admin_tag_generator( $contact_form, $args = ''){
    $args = wp_parse_args( $args, array() );
    add_action('cf7sg_', array($this, 'admin_generator_tag_styles'));
		include_once plugin_dir_path( __FILE__ ) . '/admin/cf7-dynamic-tag-display.php';
  }

  abstract public function admin_generator_tag_styles();

}

if(!function_exists('cf7sg_get_dynamic_lists')){
  function cf7sg_get_dynamic_lists(){
    return CF7SG_Dynamic_list::get_instances();
  }
}
