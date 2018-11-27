<?php
/*
List of helpers for hooks fired prior to the form loading.
*/
?>
<li>
  <a class="helper" data-cf72post="add_filter( 'cf7sg_pre_cf7_field_html', 'filter_pre_html', 10, 2);
function filter_pre_html($html, $cf7_key){
//the $html string to change
//the $cf7_key is a unique string key to identify your form, which you can find in your form table i nthe dashboard.
if('{$form_key}'==$cf7_key ){
  $html =  '<label></label>';
}
return $html;
}" href="javascript:void(0);"><?=__('Filter','cf7-grid-layout')?></a> <?=__('cf7 tag field pre-html.','cf7-grid-layout')?>
</li>
<li>
  <a class="helper" data-cf72post="add_filter( 'cf7sg_post_cf7_field_html', 'filter_post_html', 10, 2);
function filter_post_html($html, $cf7_key){
  //the $html string to change
  //the $cf7_key is a unique string key to identify your form, which you can find in your form table i nthe dashboard.
  if('{$form_key}'==$cf7_key ){
    $html =  '';
  }
  return $html;
}" href="javascript:void(0);"><?=__('Filter','cf7-grid-layout')?></a> <?=__('cf7 tag field post-html.','cf7-grid-layout')?>
</li>
<li>
  <a class="helper" data-cf72post="add_filter( 'cf7sg_required_cf7_field_html', 'filter_required_html', 10, 2);
function filter_required_html($html, $cf7_key){
  //the $html string to change
  //the $cf7_key is a unique string key to identify your form, which you can find in your form table i nthe dashboard.
  if('{$form_key}'==$cf7_key ){
    $html =  '<span>(required)</span>';
  }
  return $html;
}" href="javascript:void(0);"><?=__('Filter','cf7-grid-layout')?></a> <?=__('cf7 tag required-html.','cf7-grid-layout')?>
</li>
<li>
  <a class="helper" data-cf72post="add_filter( 'cf7_smart_grid_form_id','form_wrapper_id',10,2);
function form_wrapper_id($css_id, $cf7_key){
  // $css_id the css id for the html form wrapper.
  //$cf7_key unique form key to identify your form, $cf7_id is its post_id.
  if('{$form_key}'!==$cf7_key ){
    $css_id =  'my-form';
  }
  return $css_id;
}" href="javascript:void(0);"><?=__('Filter','cf7-grid-layout')?></a> <?=__('the form wrapper css id.','cf7-grid-layout')?>
</li>
