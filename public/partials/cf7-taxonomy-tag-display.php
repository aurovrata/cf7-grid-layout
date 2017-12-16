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
       foreach($terms as $term){
         $options[$term->slug] = $term->name;
       }
     }
     /**
     * Allow filtering of options populated by posts or taxonomies.
     * @param array $options an array of $value=>$name pairs which will be used for populating select options.
     * @param string $name the field name being populated.
     * @param string $cf7_key  the form unique key.
     * @return array array of $value=>$name pairs which will be used for populating select options.
     * @since 1.4.0
     */
     $options = apply_filters('cf7sg_dynamic_dropdown_filter_options', $options, $tag->name, $cf7_key);
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
     $args = apply_filters('cf7sg_dynamic_dropdown_post_query', $args, $tag->name, $cf7_key);
     $posts = get_posts($args);
     if(!empty($posts)){
       foreach($posts as $post){
         $options[$post->post_name] = $post->post_title;
       }
     }
     /**
     * Allow filtering of options populated by posts or taxonomies.
     * @param array $options an array of $value=>$name pairs which will be used for populating select options.
     * @param string $name the field name being populated.
     * @param string $cf7_key  the form unique key.
     * @return array array of $value=>$name pairs which will be used for populating select options.
     * @since 1.4.0
     */
     $options = apply_filters('cf7sg_dynamic_dropdown_filter_options', $options, $tag->name, $cf7_key);
   }else if('filter' == $source['source']){
     $options = apply_filters('cf7sg_dynamic_dropdown_custom_options', $options, $tag->name, $cf7_key);
   }
 }
 $tag_name = sanitize_html_class( $tag->name );
?>
<span class="wpcf7-form-control-wrap <?= $tag_name ?>">
<select id="<?= $id?>" name="<?= $tag->name ?>" class="<?= $class?>">
<?php
$default_value = apply_filters('cf7sg_dynamic_dropdown_default_value', null, $source, $tag->name, $cf7_key);
if(!is_null($default_value)):
?>
  <option value=""><?= $default_value ?></option>
<?php
endif;
foreach($options as $value=>$name){
  echo '<option value="'.$value.'">'.$name.'</option>';
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
