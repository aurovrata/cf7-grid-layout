<?php
/*
List of helpers for hooks fired prior to the form loading.
*/
/*
Available replacement varaibles:
{$form_key}  - unique form key.
{$form_key_slug}  - unique form key slug for function names.
($field_name) - unique field name.
($field_name_slug) - unique field name slug for function names.
[dqt] - double quote for html attributes.
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
<li>
  <a class="helper" data-cf72post="add_filter('cf7sg_include_hidden_form_fields', 'insert_hidden_cf7sg_{$form_key_slug}',10,2);
function prefill_cf7sg_{$form_key_slug}($hidden, $cf7_key){
  if('{$form_key}' != $cf7_key) return $values;
  //return an array of field-name=>value pairs.
  $hidden_values = array(
    'my-secret-field' => 'default value',
  );
  //you may retrieve a hidden field in the client side using javascript:
  // $('input[name=[dqt]my-secret-field[dqt]]');
  return array_merge($hidden, $hidden_values);
}" href="javascript:void(0);"><?=__('Insert','cf7-grid-layout')?></a> <?=__('hidden fields.','cf7-grid-layout')?>
</li>
<li>
  <a class="helper" data-cf72post="add_filter('cf7sg_prefill_form_fields', 'prefill_cf7sg_{$form_key_slug}',10,2);
function prefill_cf7sg_{$form_key_slug}($values, $cf7_key){
  if('{$form_key}' != $cf7_key) return $values;
  //return an array of field-name=>value pairs.
  //fields with multiple selections such as checkboxes and dropdown menu can take an array as a value.
  $custom_values = array(
    'your-name' => 'test name',
    'select-type' => array('House', 'Office'),
  );
  return array_merge($values, $custom_values);
}" href="javascript:void(0);"><?=__('Prefill','cf7-grid-layout')?></a> <?=__('form fields.','cf7-grid-layout')?>
</li>
<li>
  <a class="helper" data-cf72post="add_action('cf7sg_enqueue_custom_script-{{$form_key}', 'localize_{$form_key_slug}',10,2);
function localize_{$form_key_slug}($script_id){
  //when you use a custom JavaScript file for your form, this action if fired when the plugin loads it.
  //this allows you to localize your script should you need to.
  wp_localize_script($script_id, 'customVar', array('data'=>array(1,2,3,4)));
}" href="javascript:void(0);"><?=__('Localize','cf7-grid-layout')?></a> <?=__('custom js script.','cf7-grid-layout')?>
</li>
