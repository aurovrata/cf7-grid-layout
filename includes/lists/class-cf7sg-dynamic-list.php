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
  /**
  * function returns an array of dynamic field styles value=>label pairs for the admin tag generator.
  * these styles will be radio fields on the tag generator form.  The selected style for a given field
  * will be parametrised as a class on the dynamic field HTML element.
  * so for example the select2 jquery dropdown style would have a select2 class added to the select field.
  * @return Array value=>label pairs.
  */
  abstract public function admin_generator_tag_styles();
  /**
  * Function to get classes to be added to the form wrapper.
  * these classes will be passed in the resource enqueue action, allowing for specific js/css resources
  * to be queued up and loaded on the page where the form is being displayed.
  * @param WPCF7_FormTag $tag cf7 tag object for the form field.
  * @param int $form_id cf7 fomr post ID..
  * @return Array an array of classes to be added to the form to which this tag belonggs to.
  */
  abstract public function get_form_classes($tag, $form_id);

  /**
	 * Register a [dynamic_display] shortcode with CF7.
	 * called by funciton above
	 * This function registers a callback function to expand the shortcode for the googleMap form fields.
	 * @since 1.0.0
   * @param Array $attrs array of attribute key=>value pairs to be included in the html element tag.
   * @param Array $options array of value=>label pairs  of options.
   * @param Array $option_attrs array of value=>attribute pairs  for each options, such as permalinks for post sources..
   * @param Boolean $is_multiselect if the field has multiple selection enabled..
   * @param String $selected default selected value.
   * @return String an html string representing the input field to a=be added to the field wrapper and into the form.
   */
  abstract public function get_dynamic_html_field($attrs, $options, $option_attrs, $is_multiselect, $selected);

  /**
  * Function called by the public class method when the Post My CF7 Form plugin maps a form submission
  * to a post.
  * @since 4.10.0
  * @param String $post_id  the id of the post to which this submission was mapped
  * @param String post ID of form being submitted
  * @param String $cf7_key  the unique form key to identity the form being submitted
  * @param Array $post_fields form fields mapped to post fields, form-field-name => post-field-name key value pairs
  * @param Array $post_meta_fields form fields mapped to post meta fields,  form-field-name => post-meta-field-name key value pairs
  * @param Array $submitted_data data submited in the form, form-field-name => submitted-value key value pairs
  *@return string text_description
  */
  abstract public function save_form_2_post($post_id, $form_id, $cf7_key, $post_fields, $post_meta_fields, $submitted_data);

  /**
  * Called to register stylesheet resources on the frontend.
  * IMPORTANT: use wp_register_style()
  *@since 4.10.0
  * @param Boolean $airplane if true load only local resources.
  */
  abstract public function register_styles($airplane);

  /**
  * Called to register stylesheet resources on the frontend.
  * IMPORTANT: use wp_register_script()
  *@since 4.10.0
  * @param Boolean $airplane if true load only local resources.
  */
  abstract public function register_scripts($airplane);

  /**
  * function to register and track newly created dynamic list.
  */
  protected function register(){
    if(!isset(self::$instances)) self::$instances = array();
    self::$instances[$this->tag_id] = $this;
    // debug_msg(self::$instances, 'registration ');
  }

  static public function get_instances($tag_id=null){
    if(!isset(self::$instances)) self::$instances = array();
    $instance = self::$instances;
    if(isset($tag_id)){
      $instance = isset(self::$instances[$tag_id]) ? self::$instances[$tag_id]:false;
    }
    // debug_msg(self::$instances, 'get isntances ');
    // debug_msg($instance, 'get isntance '.$tag_id);
    return $instance;
  }
  /**
  * Function to register the admin tag.
  * called by the admin/class-cf7-grid-layout-admin.php cf7_shortcode_tags() method which is hooked to
  * 'wpcf7_admin_init'.
  */
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

  /**
   * Register the form front-end shortcode.
   * Called by public/class-cf7-grid-layout-public.php register_cf7_shortcode() methid
   * which is hooked  on 'wpcf7_init'.
   * This function registers a callback function to expand the shortcode for the save button field.
   * @since 2.0.0
   */
  public function register_cf7_shortcode() {
    if( function_exists('wpcf7_add_form_tag') ) {
      //dynamic select
      wpcf7_add_form_tag(
        array( $this->tag_id, $this->tag_id.'*' ),
        array($this,'get_shortcode_html'),
        true //has name
      );
    }
  }

  /**
	 * Function to retrieve dynamic-dropdown attributes
	 *
	 * @since 1.0.0
	 * @param      WPCF7_FormTag    $tag     cf7 tag object of basetype dynamic_select.
	 * @return     array    an array of attributes with 'source'=>[post|taxonomy|filter],     .
	**/
	public function get_dynamic_attributes($tag){
	  if(is_array($tag) && isset($tag['basetype']) && 'dynamic_select' === $tag['basetype']){
			$tag = new WPCF7_FormTag($tag);
		}
		if( !($tag instanceof WPCF7_FormTag) || 'dynamic_select' !== $tag['basetype']){
			return false;
		}
		$source = array();
    if(empty($tag->values)) debug_msg($tag, "CF7SG ERROR: malformed dynamic dropdown tag, unable to retrieve values");
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
  * function to display the html field in the form.
  * @since 4.10.0
  * @param String $field_name the tag field name designated in the tag help screen
  * @return String a set of html fields to capture the googleMap information
  */
  public function get_shortcode_html($field_name){
    $tag = new WPCF7_FormTag( $field_name );
		$source = $this->get_dynamic_attributes($tag);

    $validation_error = wpcf7_get_validation_error( $tag->name );
    $class = wpcf7_form_controls_class( $tag->type, 'cf7sg-dynamic-dropdown' );
    if ( $validation_error ) {
        $class .= ' wpcf7-not-valid';
    }
    $class = $tag->get_class_option( $class );

    $id = $tag->get_id_option();
    // $default = $tag->get_default_option();
    $has_permalinks = false;
    /** @since 3.3.0 allow multiple */
    $select_attributes = '';
    $option_attrs = array();
    // $option_attributes[$default] = ' ';
    // $name_suffix='';
    foreach($tag->options as $tag_option){
      switch($tag_option){
        case 'multiple':
          $is_multiple = true;
          break;
        case 'permalinks': /** @since 4.0 */
          $has_permalinks = true;
          break;
      }
    }
    $options = array();
    $cf7_form = wpcf7_get_current_contact_form();
    $cf7_key = get_cf7form_key($cf7_form->id());
    $filter_options = false;

    if(!empty($tag->values)){
      if('taxonomy' == $source['source']){
        $taxonomy_query= array('hide_empty' => false, 'taxonomy'=>$source['taxonomy']);
        $taxonomy_query = apply_filters('cf7sg_dynamic_dropdown_taxonomy_query', $taxonomy_query, $tag->name, $cf7_key);
        //check the WP version
        global $wp_version;
        if ( $wp_version >= 4.5 ) {
        $terms = get_terms($taxonomy_query); //WP>= 4.5 the get_terms does not take a taxonomy slug field
        }else{
        $terms = get_terms($source['taxonomy'], $taxonomy_query);
        }
        if( is_wp_error( $terms )) {
          debug_msg($terms, 'Unable to retrieve taxonomy <em>'.$source['taxonomy'].'</em> terms');
          $terms = array();
        }else{
          if(!empty($terms)) $selected = $terms[0]->slug;
          foreach($terms as $term){
            /**
            * Filter dropdown options labels.
            * @param string $label option label value.
            * @param mixed $term the term object being used to populate this option.
            * @param string $name the field name being populated.
            * @param string $cf7_key  the form unique key.
            * @return string $label option label value.
            * @since 2.0.0
            */
            $label = apply_filters('cf7sg_dynamic_dropdown_option_label', $term->name, $term, $tag->name, $cf7_key);
            $options[$term->slug] = $label;

            /**
            * Filter dropdown options  attributes.
            * @param array $attributes an array of <attribute>=>$value pairs which will be used for populating select options, instead of a string $value, an array of values can be passed such as classes.
            * @param mixed either WP_Post or WP_Term object being used to populate this option.
            * @param String $name the field name being populated.
            * @param String $cf7_key  the form unique key.
            * @return Array array of $value=>$name pairs which will be used for populating select options attributes.
            * @since 2.0.0
            */
            $attributes = apply_filters('cf7sg_dynamic_dropdown_option_attributes', array(), $term, $tag->name, $cf7_key);
            if(!empty($attributes)){
             foreach($attributes as $attribute => $avalue){
               if(is_array($avalue)){
                 $separator = ' ';
                 if('style' === $attribute ) $separator = ';';
                 $avalue = implode( $separator, $avalue);
               }
               $option_attrs[$term->slug] = ' '.$attribute.'="'.$avalue.'"';
              }
            }
          }
        }
        $filter_options = true;
      }else if('post' == $source['source']){
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
        /**
        * Filter post query for dynamic dropdown options.
        * @param array $args an arra of query terms.
        * @param string $name the field name being populated.
        * @param string $cf7_key  the form unique key.
        * @return array an arra of query terms.
        */
        $args = apply_filters('cf7sg_dynamic_dropdown_post_query', $args, $tag->name, $cf7_key);
        $posts = get_posts($args);
        if(!empty($posts)){
          $selected = $posts[0]->post_name;

          foreach($posts as $post){
            /**
             * Filter dropdown options labels.
             * @param string $label option label value.
             * @param mixed $post the post object being used to populate this option.
             * @param string $name the field name being populated.
             * @param string $cf7_key  the form unique key.
             * @return string $label option label value.
             * @since 2.0.0
            */
            $label = apply_filters('cf7sg_dynamic_dropdown_option_label', $post->post_title, $post, $tag->name, $cf7_key);
            $options[$post->post_name] = $label;
            /**
             * Filter dropdown options  attributes.
             * @param Array $attributes an array of <attribute>=>$value pairs which will be used for populating select options, instead of a string $value, an array of values can be passed such as classes.
             * @param mixed either WP_Post or WP_Term object being used to populate this option.
             * @param String $name the field name being populated.
             * @param String $cf7_key  the form unique key.
             * @return Array array of $value=>$name pairs which will be used for populating select options attributes.
             * @since 2.0.0
            */
            $attributes = array();
            if($has_permalinks){
              $attributes['data-permalink'] = get_permalink($post);
            }
            $attributes = apply_filters('cf7sg_dynamic_dropdown_option_attributes', $attributes, $post, $tag->name, $cf7_key);
            if(!empty($attributes)){
              foreach($attributes as $attribute => $avalue){
                if(is_array($avalue)){
                  $separator = ' ';
                  if('style' === $attribute ) $separator = ';';
                  $avalue = implode( $separator, $avalue);
                }
                $option_attrs[$post->post_name] = ' '.$attribute.'="'.$avalue.'"';
              }
            }
          }
        }
        $filter_options = true;
      }else if('filter' == $source['source']){
        /**
         * Get custom options from filter source.
         * @param Array $options an array of value=>label pairs of options. Alternatively 2 arrays can be filtered, see return parameter description.
         * @param String $field_name name of field being populated.
         * @param String $cf7_key form unique key.
         * @return Array either an array of value=>label pairs, or 2 arrays, 'values'=>array(value=>label), 'attributes'=>array(value=>attribute string), in order to add a set of attributes to be added to each option html element tag.
         */
         $custom_options = apply_filters('cf7sg_dynamic_dropdown_custom_options', array(), $tag->name, $cf7_key);
         if(isset($custom_options['values']) && is_array($custom_options['values'])){
           $options = $custom_options['values'];
         }else $options = $custom_options;
         $custom_options = apply_filters('cf7sg_dynamic_dropdown_custom_options', array(), $tag->name, $cf7_key);
         if(isset($custom_options['attributes']) && is_array($custom_options['attributes'])){
           $option_attrs = $custom_options['attributes'];
         }
       }
     }
     if($filter_options){ //true if either taxonomy or post dropdpwn;
       /**
       * Allow filtering of options populated by posts or taxonomies.
       * @param array $options an array of $value=>$name pairs which will be used for populating select options.
       * @param string $name the field name being populated.
       * @param string $cf7_key  the form unique key.
       * @return array array of $value=>$name pairs which will be used for populating select options.
       * @since 1.4.0
       */
       $options = apply_filters('cf7sg_dynamic_dropdown_filter_options', $options, $tag->name, $cf7_key);
     }

     $tag_name = sanitize_html_class( $tag->name );
     /** @since 3.3.0 enable custom attributes on select element*/
     $attributes = apply_filters('cf7sg_dynamic_dropdown_attributes', array(), $tag->name, $cf7_key);
     $attributes['id']=$id;
     $attributes['class']=$class;
     $attributes['name']=$tag->name;

     /** @since 4.0 */
     if($has_permalinks) $class.=' cf7sg-permalinks';

     /**
     * @since 2.2 allows custom filtered $options to be an html string.
     */
     if(!is_array( $options )) $options = array();
     /**
     * Filter dynamic dropdown default empty value label.
     * @param string $label the label for the default value, this is null by default and not shown.
     * @param string $name the field name being populated.
     * @param string $cf7_key  the form unique key.
     * @return string the label for the default value, returning a non-null value with display this as the first option.
     */
     $default_value = apply_filters('cf7sg_dynamic_dropdown_default_value', null, $tag->name, $cf7_key);
     if(!is_null($default_value)){
       $options['']=$default_value;
       $selected='';
     }

     $html = '<span class="wpcf7-form-control-wrap '.$tag_name.'">'.PHP_EOL;
     $html .= $this->get_dynamic_html_field($attributes, $options, $option_attrs, $is_multiple,$selected).PHP_EOL;
     $html .='</span>'.PHP_EOL;

     return $html;
  }
  /**
   *
   *
   * @since 5.0.0
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
}

if(!function_exists('cf7sg_get_dynamic_lists')){
  function cf7sg_get_dynamic_lists($tag_id=null){
    return CF7SG_Dynamic_list::get_instances($tag_id);
  }
}
