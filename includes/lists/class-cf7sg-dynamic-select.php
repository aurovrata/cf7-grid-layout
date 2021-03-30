<?php

/**
*
*
* @since 4.10.0
*/
require_once plugin_dir_path( __DIR__ ) . 'lists/class-cf7sg-dynamic-list.php';

class CF7SG_Dynamic_Select extends CF7SG_Dynamic_list{

  public function __construct(){
    parent::__construct('dynamic_select',__( 'dynamic-dropdown', 'cf7_2_post' ));
  }
  /**
  * define the style optoins for the dynamic list construct.
  * the stule unique slug will be inserted as class in the cf7 tag object, allowing the field styling.
  * @return Array an array of style-slug => style label.
  */
  public function admin_generator_tag_styles(){
    add_action('cf7sg_'.$this->tag_id.'_admin_tag_style-select2', array($this,'custom_select2'));
    return array(
      'select' => __('HTML Select field','cf7-grid-layout'),
      'select2' => '<a target="_blank" href="https://select2.org/getting-started/basic-usage">'.__('jQuery Select2','cf7-grid-layout').'</a>'
    );
  }
  /**
  * custom html + js script for select2 option.
  */
  public function custom_select2(){
    /*
      the class $tag_id along with the style slug is used to buitl the input id attribute,
      so this input field is: $this->tag_id.'-'.'select2';
      Here an optional 'tags' class will be aded to the CF7 tag object when select2 style is chosen.
    */
    ?>
    <span class="display-none">
      <input id="select2-tags" type="checkbox" disabled value="tags"/>
      <a target="_blank" href="https://select2.org/tagging"><?=__('Enable user options','cf7-grid-layout')?></a>
    </span>
    <script type="text/javascript">
    (function($){
      let $tags = $('#select2-tags'), $select2 = $('#<?=$this->tag_id?>-select2');
      $tags.change(function(e){
        if($tags.is(':checked')){
          $select2.val('select2 tags').change();
        }
      });
      $('.list-style.<?=$this->tag_id?>').change(function(e){
        if($select2.is(':checked')){
          $tags.prop('disabled', false);
          $tags.parent().show();
        }else{
          $tags.prop('checked', false);
          $tags.prop('disabled', true);
          $tags.parent().hide();
          $select2.val('select2');
        }
      });
    })(jQuery);
    </script>
    <?php
  }
  /**
	 * Register a [dynamic_display] shortcode with CF7.
	 * called by funciton above
	 * This function registers a callback function to expand the shortcode for the googleMap form fields.
	 * @since 1.0.0
   * @param Array $attrs array of attribute key=>value pairs to be included in the html element tag.
   * @param Array $options array of value=>label pairs  of options.
   * @param Array $option_attrs array of value=>attribute pairs  for each options, such as permalinks for post sources..
   * @param Boolean $is_multiselect if the field has multiple selection enabled..
   * @return String an html string representing the input field to a=be added to the field wrapper and into the form.
   */

  public function get_dynamic_html_field( $attrs, $options, $option_attrs, $is_multiselect, $selected){
    $attributes ='';
    foreach($attrs as $key=>$value){
      if('name'==$key && $is_multiselect) $value.='[]';
      $attributes .= ' '.$key.'="'.$value.'"';
    }
    $html = '<select value="'.$selected.'"'.$attributes.'>'.PHP_EOL;
    foreach($options as $value=>$label){
      $attributes ='';
      if(isset($option_attrs[$value])) $attributes = ' '.$option_attrs[$value];
      if($value==$selected) $attributes .=' selected="selected"';
      $html .= '<option value="'.$value.'"'.$attributes.'>'.$label.'</option>'.PHP_EOL;
    }
    $html .='</select>'.PHP_EOL;
    return $html;
  }

  /**
  * Function to get classes to be added to the form wrapper.
  * these classes will be passed in the resource enqueue action, allowing for specific js/css resources
  * to be queued up and loaded on the page where the form is being displayed.
  * @param WPCF7_FormTag cf7 tag object for the form field.
  * @param int $form_id cf7 fomr post ID..
  * @return Array an array of classes to be added to the form to which this tag belonggs to.
  */
  public function get_form_classes($tag, $form_id){
    /* Bookeeping, set up tagged select2 fields to filter newly added options in case Post My CF7 Form plugin is running */
    $class = $tag->get_class_option('');

    $form_classes = array();
    if(strpos($class, 'select2')){
      $form_classes[] = 'has-select2';
      //track this field if user sets custom options.
      if(strpos($class, 'tags')){
        $tagged_fields = get_post_meta($form_id, '_cf7sg_select2_tagged_fields', true);
        if(empty($tagged_fields)){
          $tagged_fields = array();
        }
        if( !isset($tagged_fields[$tag->name]) ){
          $tagged_fields[$tag->name] = $source;
          update_post_meta($form_id, '_cf7sg_select2_tagged_fields',$tagged_fields);
        }
      }
    }
    if(false != strpos($class, 'nice-select') || false !=strpos($class, 'ui-select')){
      $form_classes[] = 'has-nice-select';
    }
    return $form_classes;
  }
  /**
  * Save new options for select2 with tags optoin enabled.
  */
  public function save_form_2_post($post_id, $form_id, $cf7_key, $post_fields, $post_meta_fields, $submitted_data){
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
              * @param string $cf7_key  the form unique key.
              * @return string the new term name to insert.
              * @since 2.0.0
              */
              $term = apply_filters('cf7sg_dynamic_dropdown_new_term', $term, $field_name, $taxonomy, $submitted_data, $cf7_key);
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
             * @param  array  $submitted_data  array of other submitted $field=>$value pairs.
             * @param string $cf7_key  the form unique key.
             */
             $value[$idx] = apply_filters('cf7sg_dynamic_dropdown_new_post', $post_name, $field_name, $title, $post_type, $args, $submitted_data, $cf7_key);
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
          * @param string $cf7_key  the form unique key.
          */
          $value = apply_filters('cf7sg_dynamic_dropdown_filter_select2_submission', $values, $field_name, $submitted_data, $cf7_key);
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
  public function register_styles($airplane){
    $ff = '';
    if(!defined('WP_DEBUG') || !WP_DEBUG){
      $ff = '.min';
    }
    $plugin_dir = plugin_dir_url( __DIR__ );
    wp_register_style('jquery-nice-select-css',  "$plugin_dir../assets/jquery-nice-select/css/nice-select{$ff}.css", array(), '1.1.0', 'all' );
    /** @since 3.2.1 use cloudflare for live sites */
    if( $airplane || (defined('WP_DEBUG') && WP_DEBUG) ){
      wp_register_style('select2-style', "$plugin_dir../assets/select2/css/select2.min.css", array(), '4.0.13', 'all' );
      debug_msg($plugin_dir);
    }else{
      wp_register_style('select2-style', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css', array(), '4.0.13','all');
    }
  }
  public function register_scripts($airplane){
    /** @since 3.2.1 use cloudflare for live sites */
    $plugin_dir = plugin_dir_url( __DIR__ );
    if( $airplane || (defined('WP_DEBUG') && WP_DEBUG) ){
      wp_register_script('jquery-select2',  "$plugin_dir../assets/select2/js/select2.min.js", array( 'jquery' ), '4.0.13', true );
    }else{
      wp_register_script('jquery-select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array( 'jquery' ), '4.0.13', true );
    }
    wp_register_script('jquery-nice-select', "$plugin_dir../assets/jquery-nice-select/js/jquery.nice-select.min.js", array( 'jquery' ), '1.1.0', true );

    //listen for script enqueue action.
    add_action('smart_grid_enqueue_scripts', function($cf7_key, $atts, $classes){
      //check for classes set in get_form_classes()method above.
      if(in_array('has-select2', $classes)){
        wp_enqueue_style('select2-style');
        wp_enqueue_script('jquery-select2');
      }
      if(in_array('has-nice-select', $classes)){
        wp_enqueue_style('jquery-nice-select-css');
        wp_enqueue_script('jquery-nice-select');
      }
    },10,3);
  }
}

if( !function_exists('cf7sg_create_dynamic_select_tag') ){
  function cf7sg_create_dynamic_select_tag(){
    //check if there is an existing instance in memory.
    $new_instance = CF7SG_Dynamic_Select::get_instances('dynamic_select');
    if(false === $new_instance) $new_instance =   new CF7SG_Dynamic_Select();
    return $new_instance;
  }
}
add_action('cf7sg_register_dynamic_lists', 'cf7sg_create_dynamic_select_tag');
