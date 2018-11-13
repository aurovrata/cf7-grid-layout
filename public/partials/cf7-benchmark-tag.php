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
if(!empty($id)) $id = 'id="'.$id.'"';
$data_limit = '';
$data_warn = '';
$input_type = 'number';
 if(!empty($tag->values)){
   foreach($tag->values as $values){
     if(0 === strpos($values, 'above:') ){
       $data_limit = 'data-cf7sg-benchmark="above" data-cf7sg-benchmark-limit="'.str_replace('above:', '', $values).'"';
     }
     if(0 === strpos($values, 'below:')){
       $data_limit = 'data-cf7sg-benchmark="below" data-cf7sg-benchmark-limit="'.str_replace('below:', '', $values).'"';
     }
     if(0 === strpos($values, 'between:')){
       $range = explode(":",  str_replace('between:', '', $values));
       $data_limit = 'data-cf7sg-benchmark="range" data-cf7sg-benchmark-min="'.$range[0].'" data-cf7sg-benchmark-max="'.$range[1].'"';
     }
     if(0 === strpos($values, 'warn:')){
       $data_warn = 'data-cf7sg-benchmark-msg="'.str_replace('warn:', '', $values).'"';
     }
     if(0 === strpos($values, 'hidden:')){
       $input_type = ("true" === str_replace('hidden:', '', $values))?'hidden':'number';
     }
   }

 }
 //debug_msg($tag->name." input:".'<input '.$id.' name="'. $tag->name .'" class="'. $class.'" '. $data_limit.' '. $data_warn.' type="'. $input_type .'" />');
 $tag_name = sanitize_html_class( $tag->name );
?>
<span class="wpcf7-form-control-wrap <?= $tag_name ?>">
  <input <?= $id?> name="<?= $tag->name ?>" class="<?= $class?>" <?= $data_limit?> <?= $data_warn?> type="<?= $input_type ?>" />
</span>
