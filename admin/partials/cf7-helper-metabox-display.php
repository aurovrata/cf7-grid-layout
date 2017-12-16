<?php
//helper snippets
$post_my_form_only = ' no-post-my-form';
if(is_plugin_active( 'post-my-contact-form-7/cf7-2-post.php' )){
  $post_my_form_only='';
}
?>
<div id="helperdiv" class="postbox">
  <button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Helper</span><span class="toggle-indicator" aria-hidden="true"></span></button>
  <h2 class="hndle ui-sortable-handle"><span>Actions &amp; Filters</span></h2>
  <div class="inside">
    <p>Click on a link to copy the helper snippet code and paste it in your <em>functions.php</em> file.</p>
    <ul class="helper-list">
      <li class="<?=$post_my_form_only?>">
        <a class="helper" data-cf72post="add_action( 'cf7_2_post_form_mapped_to_{$post_type}','new_{$post_type}_mapped',10,3);
function new_{$post_type}_mapped($post_id, $cf7_form_data, $cf7form_key){
  //$post_id is the ID of the post to which the form values are being mapped to
  // $form_data is the submitted form data as an array of field-name=>value pairs
  //$cf7form_key unique form key to identify your form.

}" href="javascript:void(0);">Action</a> after submission is saved to mapped post.
      </li>
      <li>
        <a class="helper" data-cf72post="add_filter( 'cf7sg_pre_cf7_field_html', 'filter_pre_html', 10, 2);
function filter_pre_html($html, $cf7_key){
  //the $html string to change
  //the $cf7_key is a unique string key to identify your form, which you can find in your form table i nthe dashboard.
  if('{$form_key}'!==$cf7_key ){
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
  if('{$form_key}'!==$cf7_key ){
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
  if('{$form_key}'!==$cf7_key ){
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
  if('{$form_key}'!==$cf7_key ){
    $limit =  5;
  }
  return $limit;
}" href="javascript:void(0);">Filter</a> the tabs limit (default 10).
      </li>
      <li>
        <a class="helper" data-cf72post="add_filter( 'cf7sg_validate_submission','validate_field_submission',10,3);
function validate_field_submission($validation_errors, $submission, $cf7_key){
  /* $submission an array of <field-name>=>$value pairs one for each submitted field.
   tabbed/tabled sections fields have arrays as $values.
   tables within tabs have aray of array as values.
   if a value is not valid, return a <field-name>=><error message string> pair in the $validation_errors array. The sbumission process will be cancelled and teh user required to correct the field before re-submitting.
   $cf7_key unique form key to identify your form, $cf7_id is its post_id.
  */
  if('{$form_key}'!==$cf7_key ){
    if($submission['location-city'] === 'Chennai'){
      $validation_errors['location-city'] = 'location cannot be Chennai!';
    }
  }
  return $validation_errors;
}" href="javascript:void(0);">Filter</a> custom form submission validation of any field.
      </li>
      <li>
        <a class="helper" data-cf72post="add_filter( 'cf7sg_dynamic_dropdown_post_query','dynamic_dropdown_posts',10,3);
function dynamic_dropdown_posts($query_args, $field, $cf7_key){
  //if you have a dynamic dropdown field with posts as list source.
  //$query_args array to filter query arguments used to populate dynamic dropdown of posts.
  //these arguments are passed to the function get_posts($query_args). (codex: https://codex.wordpress.org/Template_Tags/get_posts)
  // $field name to filter dynamic dropdown posts list query
  //$cf7_key unique form key to identify your form, $cf7_id is its post_id.
  if('{$form_key}'!==$cf7_key ){
    if($field === 'author-posts'){
      //lets say we want to show only the posts of the current logged in user.
      $user = wp_get_current_user();
      $query_args['author'] = $user->ID;
    }
  }
  return $validation_errors;
}" href="javascript:void(0);">Filter</a> query arguments to retrieve posts for dynamic dropdown.
      </li>
      <li>
        <a class="helper" data-cf72post="add_filter( 'cf7sg_dynamic_dropdown_filter_options', 'filter_dropdown_options', 10,3);
/**
* Allow filtering of options populated by posts or taxonomies. This is useful when you are using hooks to register saved posts with taxonomy terms.  It allows you to build more custom option lists which you can then interpret in your form submission hoooks. 
* @param array $options an array of $value=>$name pairs which will be used for populating select options.
* @param string $name the field name being populated.
* @param string $cf7_key  the form unique key.
* @return array array of $value=>$name pairs which will be used for populating select options.
*/
function filter_dropdown_options($options, $field, $cf7_key){
  if('{$form_key}'!==$cf7_key ){
    if($field === 'my-custom-options'){
      $filtered = array();
      foreach($options as $value=>$label){
        $filtered[$value.'|<some-additional-data>'] = $label;
      }
      $options = $filtered;
    }
  }
  return $options;
}" href="javascript:void(0);">Filter</a> the taxonomy/post dynamic dropdown options.
      </li>
      <li>
        <a class="helper" data-cf72post="add_filter( 'cf7sg_dynamic_dropdown_custom_options','dynamic_dropdown_filter',10,3);
function dynamic_dropdown_filter($options, $field, $cf7_key){
  //if you have a dynamic dropdown field with custom filter as list source.
  //$options array of $value=>$label pairs to populate dropdown option list.
  // $field name of dynamic dropdown to populate
  //$cf7_key unique form key to identify your form, $cf7_id is its post_id.
  if('{$form_key}'!==$cf7_key ){
    if($field === 'my-custom-options'){
      $options['optA']='Option A';
      $options['optB']='Option B';
      $options['optC']='Option C';
    }
  }
  return $options;
}" href="javascript:void(0);">Filter</a> the custom option list of dynamic dropdown.
      </li>
      <li>
        <a class="helper" data-cf72post="add_filter( 'cf7sg_dynamic_dropdown_default_value','dynamic_dropdown_default',10,3);
function dynamic_dropdown_filter($default, $field, $cf7_key){
  //set the dynamic dropdown default unselected value.
  //$default label of default value.
  // $field name of dynamic dropdown to populate
  //$cf7_key unique form key to identify your form, $cf7_id is its post_id.
  if('{$form_key}'!==$cf7_key ){
    if($field === 'my-custom-options'){
      $default = 'Select an option';
    }
  }
  return $default;
}" href="javascript:void(0);">Filter</a> the default value of a dynamic dropdown field.
      </li>
      <li class="<?=$post_my_form_only?>">
        <a class="helper" data-cf72post="add_filter( 'cf7sg_dynamic_dropdown_new_post','dynamic_select2_newpost',10,7);
function dynamic_select2_newpost($post_name, $field, $title, $post_type, $args, $cf7_key, $submitted_data){
  /*
  Filter active when saving forms using Post My CF7 Form plugin.
  select2 dynamic dropdowns can have an option for custom user values to be selected/inserted.
  If a user searches an option which is not available they can simply insert it.  You need to enable the tagging functionality (https://select2.org/tagging) by adding a 'tags' class to your cf7 tag.

  When this option is enabled for a dynamic dropdown with posts as its source of option list, then you need to hook this filter which is fired when a the user has submitted a new custom post title in the select2 field.  This plugin takes not action to create the new post.  This is your reponsibility.
  $post_name new post slug to set.
  $field name of dynamic dropdown.
  $title string submitted by the user.
  $post_type the post type which was used to build the original dropdown list.
  $args an array of any additional arguments set to limit the original dropdown list, such as taxonomy terms.
  $cf7_key unique form key to identify your form, $cf7_id is its post_id.
  $submitted_data array of other submitted $field=>$value pairs.
  */
  if('{$form_key}'!==$cf7_key ){
    if($field === 'author-posts'){
      //create your new post using wp_insert_post();
      $post_name = ;// set the new post slug here, which will be the value stored in the submission form mapped post for this field.
    }
    return $post_name;
  }
  return $default;
}" href="javascript:void(0);">Filter</a> user added option of a dynamic dropdown post field.
      </li>
      <li class="<?=$post_my_form_only?>">
        <a class="helper" data-cf72post="add_filter( 'cf7sg_dynamic_dropdown_filter_select2_submission','dynamic_select2_filter_values',10,3);
function dynamic_select2_filter_values($values, $field, $cf7_key, $submitted_data){
  /*
  Filter active when saving forms using Post My CF7 Form plugin.
  For a dynamic dropdown with custom option list set by a filter, you need to hook this filter which is fired when a the user has submitted select2 field value(s). You can return the filtered selected value(s) which will be stored in the mapped post.
  This filter is especially useful when the select2 tags have been enabled (https://select2.org/tagging) and your user may have submitted new values which your original filtered list did not contain.
  $values selected by user.
  $field name of dynamic dropdown.
  $cf7_key unique form key to identify your form, $cf7_id is its post_id.
  $submitted_data array of other submitted $field=>$value pairs.
  */
  if('{$form_key}'!==$cf7_key ){
    if($field === 'my-custom-option'){
      //do something...
    }
    return $values;
  }
  return $default;
}" href="javascript:void(0);">Filter</a> user added option of a dynamic select2 dropdown with custom options.
      </li>
    </ul>
  </div>
</div>
<script type="text/javascript">
(function($){
	$(document).ready( function(){
    $('#helperdiv .helper-list li a').each(function(){
      new Clipboard($(this)[0], {
        text: function(trigger) {
          var $target = $(trigger);
          var text = $target.data('cf72post');
          //get postType
          var key = $('#post_name').val();
          return text.replace(/\{\$form_key\}/gi, key);
        }
      });
    });
  });
})(jQuery)
</script>
<style>
.helper-list li{
  position: relative;
}
.helper-list li .helper::before {
    content: 'Click to copy!';
    display: none;
    position: absolute;
    top: -22px;
    left: 10px;
    background: #323232;
    color: white;
    padding: 2px 5px;
    border-radius: 3px;
    font-weight: bold;
}
.helper-list li .helper:hover::before {
    display: inline-block;
}
.helper-list li.no-post-my-form{
  display: none;
}
</style>
