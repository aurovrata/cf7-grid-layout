<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://syllogic.in
 * @since      1.0.0
 *
 * @package    Cf7_Grid_Layout
 * @subpackage Cf7_Grid_Layout/public/partials
 */
 $validation_error = wpcf7_get_validation_error( $tag->name );
 $class = wpcf7_form_controls_class( $tag->type, 'cf7sg-dynamic-dropdown' );
 if ( $validation_error ) {
     $class .= ' wpcf7-not-valid';
 }
$class = $tag->get_class_option( $class );

$id = $tag->get_id_option();
$options = array();
$cf7_form = wpcf7_get_current_contact_form();
$cf7_key = Cf7_WP_Post_Table::form_key($cf7_form->id());
$filter_options = false;
if(!empty($tag->values)){
  if('taxonomy' == $source['source']){
    $taxonomy_query= array('hide_empty' => false);
    //check the WP version
    global $wp_version;
    if ( $wp_version >= 4.5 ) {
     $taxonomy_query['taxonomy'] = $source['taxonomy'];
     $terms = get_terms($taxonomy_query); //WP>= 4.5 the get_terms does not take a taxonomy slug field
    }else{
     $terms = get_terms($source['taxonomy'], $taxonomy_query);
    }
    if( is_wp_error( $terms )) {
     debug_msg($terms, 'Unable to retrieve taxonomy <em>'.$source['taxonomy'].'</em> terms');
     $terms = array();
    }else{
      $option_attributes = array();
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
       * @param WP_Term $term the term object being used to populate this option.
       * @param string $name the field name being populated.
       * @param string $cf7_key  the form unique key.
       * @return array array of $value=>$name pairs which will be used for populating select options attributes.
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
           $option_attributes[$term->slug] = ' '.$attribute.'="'.$avalue.'"';
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
      $option_attributes = array();
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
        * @param array $attributes an array of <attribute>=>$value pairs which will be used for populating select options, instead of a string $value, an array of values can be passed such as classes.
        * @param WP_Post $post the post object being used to populate this option.
        * @param string $name the field name being populated.
        * @param string $cf7_key  the form unique key.
        * @return array array of $value=>$name pairs which will be used for populating select options attributes.
        * @since 2.0.0
        */
        $attributes = apply_filters('cf7sg_dynamic_dropdown_option_attributes', array(), $post, $tag->name, $cf7_key);
        if(!empty($attributes)){
          foreach($attributes as $attribute => $avalue){
            if(is_array($avalue)){
              $separator = ' ';
              if('style' === $attribute ) $separator = ';';
              $avalue = implode( $separator, $avalue);
            }
            $option_attributes[$term->slug] = ' '.$attribute.'="'.$avalue.'"';
          }
        }
      }
    }
    $filter_options = true;
  }else if('filter' == $source['source']){
     $options = apply_filters('cf7sg_dynamic_dropdown_custom_options', $options, $tag->name, $cf7_key);
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
?>
<span class="wpcf7-form-control-wrap <?= $tag_name ?>">
<select id="<?= $id?>" name="<?= $tag->name ?>" class="<?= $class?>">
<?php
/**
* @since 2.2 allows custom filtered $options to be an html string.
*/
if(is_array( $options )){
  /**
  * Filter dynamic dropdown default empty label.
  * @param string $label the label for the default value, this is null by default and not shown.
  * @param string $name the field name being populated.
  * @param string $cf7_key  the form unique key.
  * @return string the label for the default value, returning a non-null value with display this as the first option.
  */
  $default_value = apply_filters('cf7sg_dynamic_dropdown_default_value', null, $source, $tag->name, $cf7_key);
  if(!is_null($default_value)):
  ?>
    <option value=""><?= $default_value ?></option>
  <?php
  endif;
  foreach($options as $value=>$name){
    $attribute = '';
    if(isset($option_attributes[$value])) $attribute = $option_attributes[$value];
    echo '<option value="'.$value.'"'.$attribute.'>'.$name.'</option>';
  }
}else{
  echo $options;
}
?>
</select>
</span>
<?php
 /* Bookeeping, set up tagged select2 fields to filter newly added options in case Post My CF& Form plugin is running */
 $form_classes = array();
 if(strpos($class, 'select2')){
   $form_classes[] = 'has-select2';
   //track this field if user sets custom options.
   if(strpos($class, 'tags')){
     $tagged_fields = get_post_meta($cf7_form->id(), '_cf7sg_select2_tagged_fields', true);
     if(empty($tagged_fields)){
       $tagged_fields = array();
     }
     if( !isset($tagged_fields[$tag->name]) ){
       $tagged_fields[$tag->name] = $source;
       update_post_meta($cf7_form->id(), '_cf7sg_select2_tagged_fields',$tagged_fields);
     }
   }
 }
 if(false != strpos($class, 'nice-select') || false !=strpos($class, 'ui-select')){
   $form_classes[] = 'has-nice-select';
 }
 if(!empty($form_classes)) $this->update_form_classes($form_classes, $cf7_form->id());
