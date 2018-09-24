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
}" href="javascript:void(0);">Filter</a> cf7 tag field pre-html.
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
}" href="javascript:void(0);">Filter</a> cf7 tag field post-html.
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
}" href="javascript:void(0);">Filter</a> cf7 tag required-html.
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
}" href="javascript:void(0);">Filter</a> the form wrapper css id.
</li>
<li>
  <a class="helper" data-cf72post="add_filter( 'cf7sg_set_max_tabs_limit','form_max_tabs',10,2);
function form_max_tabs($limit, $cf7_key){
  // $limit the limit of tabs users can created in tabbed section (see screenshot 13 in the plugin page, https://wordpress.org/plugins/cf7-grid-layout/#screenshots).
  //$cf7_key unique form key to identify your form, $cf7_id is its post_id.
  if('{$form_key}'==$cf7_key ){
    $limit =  5;
  }
  return $limit;
}" href="javascript:void(0);">Filter</a> the tabs limit (default 10).
</li>
