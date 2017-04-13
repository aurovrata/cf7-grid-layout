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

$terms = array();
 if(!empty($tag->values)){
   foreach($tag->values as $values){
     if(0 == strpos($values, 'slug:') ){
       $taxonomy = str_replace('slug:', '', $values);
     }
   }
   $taxonomy_query= array('hide_empty' => false);
   //check the WP version
   global $wp_version;
   if ( $wp_version >= 4.5 ) {
     $taxonomy_query['taxonomy'] = $taxonomy;
     $terms = get_terms($taxonomy_query); //WP>= 4.5 the get_terms does not take a taxonomy slug field
   }else{
     $terms = get_terms($taxonomy, $taxonomy_query);
   }
   if( is_wp_error( $terms )) {
     debusg_msg($terms, 'Unable to retried taxonomy <em>'.$taxonomy.'</em> terms');
     $terms = array();
   }
 }
?>
<select id="<?php echo $id?>" name="<?php echo $tag->name ?>" class="<?php echo $class?>">
<?php
$value = apply_filters('cf7sg_dynamic_dropdown_default_value', null, $taxonomy, $tag->name);
if(!is_null($value)):
?>
  <option value=""><?php echo $value ?></option>
<?php
endif;
foreach($terms as $term){
  echo '<option value="'.$term->slug.'">'.$term->name.'</option>';
}
?>
</select>
