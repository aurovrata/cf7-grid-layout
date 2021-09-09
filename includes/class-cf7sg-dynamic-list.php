<?php

/**
* Abstract class for building dynamic lists.
*
* @since 4.10.0
*/

class CF7SG_Dynamic_list{
  /**
  * @since    4.10.0
  * @access   protected
  * @var String $tag_id tag field unique id.
  */
  public $tag_id;
  /**
  * @since    4.10.0
  * @access   protected
  * @var String $label tag button label.
  */
  public $label;
  /**
  * @since    4.11.0
  * @access   protected
  * @var Array $styles array of style fields available in the tag manager form.
  */
  protected $styles = array();
  /**
  * @since    4.11.0
  * @access   protected
  * @var Array $style_extras array of extra fields appended to each style field for further customisation, can either be style_id => array( $field_value => $field_label) (defaults to a checkbox) or,
  * style_id => array( $field_value => array(
  *   'type'=>'text|number|checkbox' default is checkbox.
  *   'label'=> label text or html string.
  *   'attrs'=> string og attributes to be added to the field.
  * ))
  * for other fields.
  */
  protected $style_extras = array();
  /**
  * @since    4.11.0
  * @access   protected
  * @var Array $other_extras array of extra fields added to the 'Other attributes' fieldset, can either be array( $field_value => $field_label) (defaults to a checkbox) or,
  * array( $field_value => array(
  *   'type'=>'text|number|checkbox' default is checkbox.
  *   'label'=> label text or html string.
  *   'attrs'=> string og attributes to be added to the field.
  * ))
  * for other fields.
  */
  protected $other_extras = array();
  /**
  * @since 4.11.0
  * @access   protected
  * @var String default checkbox, can be set to radio isntead for extra configuration fields using hte set_others_extras_radio();
  */
  protected $other_extras_type = 'checkbox';
  /**
  * flag that determines if field can accepted nested lists such as hierarchical taxonomies.
  * @since 4.11.0
  * @access   protected
  * @var String default false, can be changed with allow_nesting() method;
  */
  protected $nesting = false;
  /**
  * @since    4.10.0
  * @access   protected
  * @var Array $instances array of tag_id=>CF7SG_Dynamic_list objects to keep track of instances.
  */
  protected static $instances;

  public function __construct($tag_id, $label){
    $this->tag_id = $tag_id;
    $this->label = $label;
    $this->register();
  }
  /**
  * set the stles for the tag manager.
  * @since 4.11.0
  * @param Array $styles array of style fields available in the tag manager form.
  * @param Array $style_extras array of extra fields appended to each style field for further customisation, can either be style_id => array( $field_value => $field_label) (defaults to a checkbox) or,
  * style_id => array( $field_value => array(
  *   'type'=>'text|number|checkbox' default is checkbox.
  *   'label'=> label text or html string.
  *   'attrs'=> string og attributes to be added to the field.
  * ))
  * for other fields.
  */
  public function set_styles($styles, $style_extras){
    $this->styles = $styles;
    $this->style_extras = $style_extras;
  }
  /**
  * Set extra configuratino fields in the 'Other Attributes' fieldset of the tag manager form.
  * @since 4.11.0
  * @param Array $extras array of extra fields added to the 'Other attributes' fieldset, can either be array( $field_value => $field_label) (defaults to a checkbox) or,
  * array( $field_value => array(
  *   'type'=>'text|number|checkbox' default is checkbox.
  *   'label'=> label text or html string.
  *   'attrs'=> string of attributes to be added to the field.
  * ))
  * for other fields.
  */
  public function set_others_extras($extras){
    $this->other_extras = $extras;
  }
  /**
  * Set the extra configuration fields to be radio fields instead of the default checkbox.
  * @since 4.11.0
  */
  public function set_others_extras_radio(){
    $this->other_extras_type = 'radio';
  }
  /**
  * This field can handle nesting of values (hierarchical lists)
  *
  *@since 4.11.0
  */
  public function allow_nesting(){
    $this->nesting = true;
  }
  /**
  * Return the nesting boolean to determine if the list can display nested lists.
  *@since 4.11.0
  *@return Boolean false or true.
  */
  public function has_nesting(){
    return $this->nesting;
  }
  /**
  * function returns an array of dynamic field styles value=>label pairs for the admin tag generator.
  * these styles will be radio fields on the tag generator form.  The selected style for a given field
  * will be parametrised as a class on the dynamic field HTML element.
  * so for example the select2 jquery dropdown style would have a select2 class added to the select field.
  * @return Array value=>label pairs.
  */
  public function get_tag_generator_styles(){
    return $this->styles;
  }
  /**
  * get extra fields associated with a given style field to display in the tag manager form.
  * @since 4.11.0
  * @param String  $style style id
  */
  public function get_style_extras($style){
    $extras = array();
    if(isset($this->style_extras[$style])) $extras = $this->style_extras[$style];
    return $extras;
  }
  /**
  * get extra fields associated with a given style field to display in the tag manager form.
  * @since 4.11.0
  * @param String  $style style id
  */
  public function get_other_extras(){
    return $this->other_extras;
  }
  /**
  * get the extra fields type
  *
  *@since 4.11.0
  *@return String checkbox | radio
  */
  public function get_other_extras_type(){
    return $this->other_extras_type;
  }
  /**
  * Function to get classes to be added to the form wrapper.
  * these classes will be passed in the resource enqueue action, allowing for specific js/css resources
  * to be queued up and loaded on the page where the form is being displayed.
  * @param WPCF7_FormTag $tag cf7 tag object for the form field.
  * @param int $form_id cf7 fomr post ID..
  * @return Array an array of classes to be added to the form to which this tag belonggs to.
  */
  public function get_form_classes($tag, $form_id){
     return apply_filters('cf7sg_save_dynamic_list_form_classes', array(), $tag, $form_id);
  }

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
   * @since 4.10.0
   * @param WPCF7_ContactForm $contact_form the cf7 form object
   * @param array $args arguments for this form.
   */
  public function admin_tag_generator( $contact_form, $args = ''){
    $args = wp_parse_args( $args, array() );
    do_action('cf7sg_display_dynamic_list_tag_manager', $this->tag_id, $this, $contact_form, $args);
  }
  /**
  * function to display taxonomy dropdown list for posts in tag generator */
  public function cf7sg_terms_to_options($taxonomy, $is_hierarchical, $parent=0, $level=1){
    $args = array('hide_empty' => 0);
    if($is_hierarchical){
      $args['parent'] = $parent;
    }
    //check the WP version
    global $wp_version;
    if ( $wp_version >= 4.5 ) {
      $args['taxonomy'] = $taxonomy;
      $terms = get_terms($args); //WP>= 4.5 the get_terms does not take a taxonomy slug field
    }else{
      $terms = get_terms($taxonomy, $args);
    }
    if( is_wp_error( $terms ) ){
      debug_msg('Taxonomy '.$taxonomy.' does not exist');
      return '';
    }else if( empty($terms) ){
      return'';
    }
    if(0==$parent) $class = 'parent';
    else $class = 'child';
    $script = '';
    foreach($terms as $term){
      $script .='<option class="level-'.$level.'" value="taxonomy:' . $taxonomy . ':' . $term->slug . '" >' . $term->name . '</option>' . PHP_EOL;
      if($is_hierarchical){
        $script .= $this->cf7sg_terms_to_options($taxonomy, $is_hierarchical, $term->term_id, $level+1);
      }
    }
    return $script;
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
	 * Function to retrieve dynamic-dropdown data attributes
	 *
	 * @since 4.11.0
	 * @param      WPCF7_FormTag    $tag     cf7 tag object of basetype dynamic list.
	 * @return     Array    an array of data attributes      .
	**/
	public function get_data_attributes($tag){
	  if(is_array($tag) && isset($tag['basetype']) && $this->tag_id === $tag['basetype']){
			$tag = new WPCF7_FormTag($tag);
		}
		if( !($tag instanceof WPCF7_FormTag) || $this->tag_id !== $tag['basetype']){
			return false;
		}
		$attrs = array();
    if(empty($tag->values)) debug_msg($tag, "CF7SG ERROR: malformed {$this->tag_id} tag, unable to retrieve values");
    foreach($tag->values as $values){
      if(false === stripos($values, 'data-')) continue;
      $a = explode(':',$values);
      if(isset($a[1])) $attrs[$a[0]] = $a[1];
    }
    return $attrs;
  }
  /**
	 * Function to retrieve dynamic-dropdown attributes
	 *
	 * @since 1.0.0
	 * @param      WPCF7_FormTag    $tag     cf7 tag object of basetype dynamic_select.
	 * @return     array    an array of attributes with 'source'=>[post|taxonomy|filter],     .
	**/
	public function get_dynamic_attributes($tag){
	  if(is_array($tag) && isset($tag['basetype']) && $this->tag_id === $tag['basetype']){
			$tag = new WPCF7_FormTag($tag);
		}
		if( !($tag instanceof WPCF7_FormTag) || $this->tag_id !== $tag['basetype']){
			return false;
		}
		$source = array();
    if(empty($tag->values)) debug_msg($tag, "CF7SG ERROR: malformed {$this->tag_id} tag, unable to retrieve values");
    foreach($tag->values as $values){
      if(0 === strpos($values, 'slug:') ){
        $source['source'] = "taxonomy";
        $s = explode(':',$values);
        $source['taxonomy'] = $s[1];
        $source['tree'] = isset($s[2]) && 'tree'==$s[2]; /** @since 4.11 */
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
    // debug_msg($tag, 'tag object ');
		$source = $this->get_dynamic_attributes($tag);
    /** @since 4.11.0 enable data attributes */
    $data_attrs = $this->get_data_attributes($tag);

    $validation_error = wpcf7_get_validation_error( $tag->name );
    $class = wpcf7_form_controls_class( $tag->type, 'cf7sg-dynamic-list cf7sg-'.$this->tag_id );
    if ( $validation_error ) {
        $class .= ' wpcf7-not-valid';
    }
    $class = $tag->get_class_option( $class );

    $id = $tag->get_id_option();
    //attributes to be added to the dropdown select field
    $select_attributes = '';
    //capture any attributes to be added to individual options in dropdpwn list.
    $option_attrs = array();
    //other attrbutes captures in the tag manager.
    $other_attrs = array();
    foreach($tag->options as $tag_option){
      if(false !== stripos($tag_option, ':')) continue;
      $other_attrs[$tag_option] = true;
    }
    $options = array();
    $cf7_form = wpcf7_get_current_contact_form();
    $cf7_key = get_cf7form_key($cf7_form->id());
    $filter_options = false;
    $selected='';
    if(!empty($tag->values)){
      if('taxonomy' == $source['source']){
        $taxonomy_query= array('hide_empty' => false, 'taxonomy'=>$source['taxonomy']);
        $taxonomy_query = apply_filters_deprecated('cf7sg_dynamic_dropdown_taxonomy_query',
          array(
            $taxonomy_query,
            $tag->name,
            $cf7_key
          ), '4.11.0', 'cf7sg_dynamic_list_taxonomy_query' );
        $taxonomy_query = apply_filters("cf7sg_{$this->tag_id}_taxonomy_query", $taxonomy_query, $tag, $cf7_key);
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
          //need ability to build hierarchy
          if($this->nesting && is_taxonomy_hierarchical($source['taxonomy']) && $source['tree']){
             //apply_filters("cf7sg_{$this->tag_id}_{$tag}_{$cf7_key}_include_children ", false)
          }
          if(!empty($terms)) $selected = $terms[0]->slug;
          foreach($terms as $term){
            /**
            * Filter dropdown options labels.
            * @param String $label option label value.
            * @param mixed $term the term object being used to populate this option.
            * @param WPCF7_FormTag $tag the field name being populated.
            * @param String $cf7_key  the form unique key.
            * @return String $label option label value.
            * @since 2.0.0
            */
            $label = $term->name;
            $label = apply_filters_deprecated('cf7sg_dynamic_dropdown_option_label',
              array(
                $label,
                $term,
                $tag->name,
                $cf7_key
              ), '4.11.0', "cf7sg_{$this->tag_id}_option_label" );
            $label = apply_filters("cf7sg_{$this->tag_id}_option_label", $label, $term, $tag, $cf7_key);
            $options[$term->slug] = $label;

            /**
            * Filter dropdown options  attributes.
            * @param array $attributes an array of <attribute>=>$value pairs which will be used for populating select options, instead of a string $value, an array of values can be passed such as classes.
            * @param mixed either WP_Post or WP_Term object being used to populate this option.
            * @param WPCF7_FormTag $tag the field name being populated.
            * @param String $cf7_key  the form unique key.
            * @return Array array of $value=>$name pairs which will be used for populating select options attributes.
            * @since 2.0.0
            */
            $attributes = apply_filters_deprecated( 'cf7sg_dynamic_dropdown_option_attributes',
              array(
                array(),
                $term,
                $tag->name,
                $cf7_key ), '4.11.0', "cf7sg_{$this->tag_id}_options_attributes" );
            /** @since 4.11.0 more versatile to allow plugins to customise the option attributes */
            $attributes = apply_filters("cf7sg_{$this->tag_id}_options_attributes", array(), $term, $tag, $cf7_key);
            if(is_array($attributes)){
              if(isset($attributes['class'])){
                if(!is_array($attributes['class'])) $attributes['class'] = array($attributes['class']);
                $attributes['class'][]='cf7sg-dl';
              }else $attributes['class']='cf7sg-dl';
            }else $attributes = array('class'=>'cf7sg-dl');

            $option_attrs[$term->slug] = $attributes;
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
          if(count($tax)>1) $tax['relation']='AND';

          $args['tax_query'] = $tax;
        }
        /**
        * Filter post query for dynamic dropdown options.
        * @param array $args an arra of query terms.
        * @param WPCF7_FormTag $tag the field name being populated.
        * @param String $cf7_key  the form unique key.
        * @return array an arra of query terms.
        */
        $args = apply_filters_deprecated('cf7sg_dynamic_dropdown_post_query',
          array(
            $args,
            $tag->name,
            $cf7_key
          ), '4.11.0', 'cf7sg_dynamic_list_post_query' );
        $args = apply_filters('cf7sg_dynamic_list_post_query', $args, $tag, $cf7_key);

        $posts = get_posts($args);
        // debug_msg($posts, 'post query ');
        if(!empty($posts)){
          $post_taxonomies = get_object_taxonomies($source['post']);

          $selected = $posts[0]->post_name;

          foreach($posts as $post){
            /**
             * Filter dropdown options labels.
             * @param String $label option label value.
             * @param mixed $post the post object being used to populate this option.
             * @param WPCF7_FormTag $tag the field name being populated.
             * @param String $cf7_key  the form unique key.
             * @return String $label option label value.
             * @since 2.0.0
            */
            $label = $post->post_title;
            $label = apply_filters_deprecated('cf7sg_dynamic_dropdown_option_label',
              array(
                $label,
                $post,
                $tag->name,
                $cf7_key
              ), '4.11.0', "cf7sg_{$this->tag_id}_option_label" );
            $label = apply_filters("cf7sg_{$this->tag_id}_option_label", $label, $post, $tag, $cf7_key);
            $options[$post->post_name] = $label;
            $attributes = array();
            if( isset($other_attrs['permalinks']) ){
              $attributes['data-permalink'] = get_permalink($post);
            }
            if( isset($other_attrs['thumbnails']) ){
              $size = apply_filters("cf7sg_{$this->tag_id}_image_size", 'thumbnail', $post, $tag, $cf7_key);
              $attributes['data-thumbnail'] = get_the_post_thumbnail_url($post, $size);
            }
            $filter_attributes = apply_filters_deprecated( 'cf7sg_dynamic_dropdown_option_attributes',
              array(
                array(),
                $post,
                $tag->name,
                $cf7_key ), '4.11.0', "cf7sg_{$this->tag_id}_options_attributes" );
            /**
             * Filter dropdown options  attributes.
             * @param Array $attributes an array of <attribute>=>$value pairs which will be used for populating select options, instead of a string $value, an array of values can be passed such as classes.
             * @param mixed either WP_Post or WP_Term object being used to populate this option.
             * @param WPCF7_FormTag $tag the field name being populated.
             * @param String $cf7_key  the form unique key.
             * @return Array array of $value=>$name pairs which will be used for populating select options attributes.
             * @since 4.11.0
            */
            $filter_attributes = apply_filters("cf7sg_{$this->tag_id}_options_attributes", array(), $post, $tag, $cf7_key);
            if(is_array($filter_attributes)){
              if(isset($filter_attributes['class'])){
                if(!is_array($filter_attributes['class'])) $filter_attributes['class'] = array($filter_attributes['class']);
                $filter_attributes['class'][]='cf7sg-dl';
              }else $filter_attributes['class']=array('cf7sg-dl');
            }else $filter_attributes = array('class'=>array('cf7sg-dl'));
            //setup classes for existing post terms.
            if(apply_filters("cf7sg_{$this->tag_id}_include_post_terms_as_class", false, $tag, $cf7_key)){
              foreach($post_taxonomies as $tx){
                $ts = get_the_terms($post, $tx);
                if(is_array($ts)) foreach($ts as $t) $filter_attributes['class'][]="$tx-$ts->slug";
              }
            }

            $option_attrs[$post->post_name] = $attributes;
            if(is_array($filter_attributes)){
              $option_attrs[$post->post_name] = array_merge($attributes, $filter_attributes);
            }
          }
        }
        $filter_options = true;
      }else if('filter' == $source['source']){
        $options = apply_filters_deprecated( 'cf7sg_dynamic_dropdown_custom_options',
          array(
            '',
            $tag->name,
            $cf7_key
          ), '4.11.0', "cf7sg_custom_{$this->tag_id}" );

        /** @since 4.11.0 more versatile to allow plugins to customise the option attributes */
        $custom_options = apply_filters("cf7sg_custom_{$this->tag_id}", array(), $tag, $cf7_key);
        if(isset($custom_options['values']) && is_array($custom_options['values'])){
         $options = $custom_options['values'];
       }else if(!empty($custom_options)) $options = $custom_options;
        // $custom_options = apply_filters('cf7sg_dynamic_dropdown_custom_options', array(), $tag->name, $cf7_key);
        if(isset($custom_options['attributes']) && is_array($custom_options['attributes'])){
          $option_attrs = $custom_options['attributes'];
        }
      }
    }
    if($filter_options){ //true if either taxonomy or post dropdpwn;
      /**
      * Allow filtering of options populated by posts or taxonomies.
      * @param array $options an array of $value=>$name pairs which will be used for populating select options.
      * @param WPCF7_FormTag $tag the field name being populated.
      * @param String $cf7_key  the form unique key.
      * @return array array of $value=>$name pairs which will be used for populating select options.
      * @since 1.4.0
      */
      $options = apply_filters_deprecated('cf7sg_dynamic_dropdown_filter_options', array( $options, $tag->name, $cf7_key), '4.11.0');
    }

    $tag_name = sanitize_html_class( $tag->name );
    /** @since 3.3.0 enable custom attributes on select element*/
    $attributes = apply_filters_deprecated('cf7sg_dynamic_dropdown_attributes',
     array(array(), $tag->name, $cf7_key),
     '4.11.0',"cf7sg_{$this->tag_id}_attributes");
    $attributes = apply_filters("cf7sg_{$this->tag_id}_attributes", array(), $tag, $cf7_key);
    if(!empty($id)) $attributes['id']=$id;
    $attributes['class']=$class;
    $attributes['name']=$tag->name;

    /** @since 4.0 */
    if(isset($other_attrs['permalinks'])) $class.=' cf7sg-permalinks';

    /**
    * @since 2.2 allows custom filtered $options to be an html string.
    */
    if(!is_array($options) && !is_string($options)) $options = array();
    /**
    * Filter dynamic dropdown default empty value label.
    * @param String $label the label for the default value, this is null by default and not shown.
    * @param WPCF7_FormTag $tag the field name being populated.
    * @param String $cf7_key  the form unique key.
    * @return String the label for the default value, returning a non-null value with display this as the first option.
    */
    $default_value = apply_filters_deprecated('cf7sg_dynamic_dropdown_default_value',
      array(
        null,
        $tag->name,
        $cf7_key
      ),'4.11.0', "cf7sg_{$this->tag_id}_default_value");
    $default_value = apply_filters("cf7sg_{$this->tag_id}_default_value", null, $tag, $cf7_key);

    if(!is_null($default_value)){
     $options['']=$default_value;
     $selected='';
    }
    $other_classes='';
    if(!empty($other_attrs)) $other_classes = ' cf7sg-'.implode(' cf7sg-',array_keys($other_attrs));
    $html = '<span class="wpcf7-form-control-wrap cf7sg-dl-'. $source['source'] .' '. $tag_name . $other_classes . '">' . PHP_EOL;
    /**
    * Register a [dynamic_display] shortcode with CF7.
    * @since 4.11.0
    * @param Array $attrs array of attribute key=>value pairs to be included in the html element tag.
    * @param Array $options array of value=>label pairs  of options.
    * @param Array $option_attrs array of value=>attribute pairs  for each options, such as permalinks for post sources..
    * @param Boolean $is_multiselect if the field has multiple selection enabled..
    * @param String $selected default selected value.
    * @param Array $children array of parentID=>array(slug,childID) to reconstruct hierrarchical lists.
    * @return String an html string representing the input field to a=be added to the field wrapper and into the form.
    */
    $html .= apply_filters("cf7sg_{$this->tag_id}_html_field", '', $attributes, $options, $option_attrs, $other_attrs, $selected, $children).PHP_EOL;
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
/* build a dynamic select dropdown */
if( !function_exists('cf7sg_create_dynamic_select_tag') ){
  function cf7sg_create_dynamic_select_tag(){
    //check if there is an existing instance in memory.
    $dl = CF7SG_Dynamic_List::get_instances('dynamic_select');
    if(false === $dl){
      $dl = new CF7SG_Dynamic_List('dynamic_select',__( 'dynamic-dropdown', 'cf7-grid-layout' ));
      $dl->set_styles(array(
        'select' => __('HTML Select field','cf7-grid-layout'),
        'select2' => '<a target="_blank" href="https://select2.org/getting-started/basic-usage">'.__('jQuery Select2','cf7-grid-layout').'</a>'
      ),array(
        'select2'=>array(
          'tags'=>array(
            'label'=> '<a target="_blank" href="https://select2.org/tagging">'.__('Enable user options','cf7-grid-layout').'</a>',
            'attrs'=>'disabled'
          )
        )
      ));
      $dl->set_others_extras(array(
        'multiple'=> '<a target="_blank" href="https://www.w3schools.com/tags/att_select_multiple.asp">'.__('Enable multiple selection','cf7-grid-layout').'</a>'
      ));
    }
    return $dl;
  }
}
add_action('cf7sg_register_dynamic_lists', 'cf7sg_create_dynamic_select_tag');
/**
* Dynamic checkbox
* @since 4.11.0
*/
if( !function_exists('cf7sg_create_dynamic_checkbox_tag') ){
  function cf7sg_create_dynamic_checkbox_tag(){
    //check if there is an existing instance in memory.
    $dl = CF7SG_Dynamic_List::get_instances('dynamic_checkbox');
    if(false === $dl){
      $dl = new CF7SG_Dynamic_List('dynamic_checkbox',__( 'dynamic-checkbox', 'cf7-grid-layout' ));
      $dl->set_styles(array(
        'checkbox' => __('Checkbox fields','cf7-grid-layout'),
        'radio' => __('Radio fields','cf7-grid-layout'),
      ),array(
        'checkbox'=>array(
          'maxcheck'=>array(
            'label'=> __('Limit selections','cf7-grid-layout'),
            'attrs'=>'class="limit-check"',
            'html'=>'<input type="number" min="1" value="3" class="max-selection"/>
            <input type="hidden" value="" class="data-attribute" />'
          )
        )
      ));
      $dl->set_others_extras_radio(); //default is checkbox.
      $dl->set_others_extras(array(
        ''=> __('List','cf7-grid-layout'),
        'hybriddd'=> '<a href="https://aurovrata.github.io/hybrid-html-dropdown/">Hybrid Dropdown</a>',
        'treeview'=> sprintf(__('<a href="%s">Treeview dropdown</a>','cf7-grid-layout'),'https://aurovrata.github.io/hybrid-html-dropdown/examples/#hybrid-dropdown-with-treeview-selection'),
        'imagehdd'=> sprintf(__('<a href="%s">Image dropdown</a>','cf7-grid-layout'),'https://aurovrata.github.io/hybrid-html-dropdown/examples/#dropdown-list-with-with-custom-labels-with-images'),
        'imagegrid'=> __('Image grid','cf7-grid-layout'),
      ));
      $dl->allow_nesting(); //flag as able to handle hierarchical lists.
    }
    return $dl;
  }
}
add_action('cf7sg_register_dynamic_lists', 'cf7sg_create_dynamic_checkbox_tag');
