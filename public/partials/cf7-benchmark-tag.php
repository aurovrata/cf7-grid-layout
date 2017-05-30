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
 $class = wpcf7_form_controls_class( $tag->type, 'cf7sg-benchmark' );
 if ( $validation_error ) {
     $class .= ' wpcf7-not-valid';
 }
$class = $tag->get_class_option( $class );
$id = $tag->get_id_option();
$data_limit = '';
$data_warn = '';

 if(!empty($tag->values)){
   foreach($tag->values as $values){
     if(0 === strpos($values, 'above:') ){
       $data_limit = 'data-type="above" data-limit="'.str_replace('above:', '', $values);
     }
     if(0 === strpos($values, 'below:')){
       $data_limit = 'data-type="below" data-limit="'.str_replace('below:', '', $values);
     }
     if(0 === strpos($values, 'between:')){
       $range = explode(":",  str_replace('between:', '', $values));
       $data_limit = 'data-type="range" data-min="'.$range[0].'" data-max="'.$range[1].'"';
     }
     if(0 === strpos($values, 'warn:')){
       $data_warn = 'data-msg="'.str_replace('warn:', '', $values).'"';
     }
   }

 }
?>
<span class="wpcf7-form-control-wrap <? echo sanitize_html_class( $tag->name ) ?>">
  <input data-limit="<?= $limit['type']?>" type="number" id="<?php echo $id?>" name="<?php echo $tag->name ?>" class="<?php echo $class?>" />
</span>
