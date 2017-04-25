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
 $class = wpcf7_form_controls_class( $tag->type, 'cf7sf-dynamic-dropdown' );
 if ( $validation_error ) {
     $class .= ' wpcf7-not-valid';
 }
$class = $tag->get_class_option( $class );
$id = $tag->get_id_option();

$options = array();
 if(!empty($tag->values)){
   $source = array();
   foreach($tag->values as $values){
     if(0 == strpos($values, 'slug:') ){
       $source['source'] = "taxonomy";
       $source['taxonomy'] = str_replace('slug:', '', $values);
     }
     if(0 == strpos($values, 'source:post')){
       $source['source'] = "post";
       $source['post'] = str_replace('source:post:', '', $values);
     }
     if(0 == strpos($values, 'taxonomy:')){
       if(empty($source['taxonomy'])){
         $source['taxonomy'] = array();
       }
       $values = str_replace('taxonomy:', '', $values);
       $exp = explode(":", $values);
       if(!empty($exp) && is_array($exp)){
         $source['taxonomy'][$exp[1]] = $exp[0];
       }
     }
     if(0 == strpos($values, 'source:filter')){
       $source['source'] = "filter";
     }
   }
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
       debug_msg($terms, 'Unable to retried taxonomy <em>'.$source['taxonomy'].'</em> terms');
       $terms = array();
     }else{
       foreach($terms as $term){
         $options[$term->slug] = $term->name;
       }
     }
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
     $args = apply_filters('cf7sg_dynamic_dropdown_post_query', $args, $tag->name);
     $posts = get_posts($args);
     if(!empty($posts)){
       foreach($posts as $post){
         $options[$post->post_name] = $post->post_title;
       }
     }
   }else if('filter' == $source['source']){
     $options = apply_filters('cf7sg_dynamic_dropdown_custom_options', $options, $tag->name);
   }
 }
?>
<select id="<?php echo $id?>" name="<?php echo $tag->name ?>" class="<?php echo $class?>">
<?php
$default_value = apply_filters('cf7sg_dynamic_dropdown_default_value', null, $source, $tag->name);
if(!is_null($default_value)):
?>
  <option value=""><?php echo $default_value ?></option>
<?php
endif;
foreach($options as $value=>$name){
  echo '<option value="'.$value.'">'.$name.'</option>';
}
?>
</select>
