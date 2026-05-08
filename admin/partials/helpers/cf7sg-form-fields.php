<?php
//helper snippets
/*
Available replacement varaibles:
{$form_key}  - unique form key.
{$form_key_slug}  - unique form key slug for function names.
($field_type) - tag field id, eg dynamic_select.
($field_name) - unique field name.
($field_name_slug) - unique field name slug for function names.
[dqt] - double quote for html attributes.
*/
$post_my_form_only = ' no-post-my-form';
if(is_plugin_active( 'post-my-contact-form-7/cf7-2-post.php' )){
  $post_my_form_only='';
}
?>
<li class="cf7sg-tag-dynamic_list-post">
  <a class="helper" data-cf72post="add_filter( 'cf7sg_dynamic_list_post_query','{$field_name_slug}_dynamic_list',10,3);
/**
* Filter post query for dynamic dropdown options.
* @param array $args an arra of query terms.
* @param WPCF7_FormTag $tag the field being populated.
* @param string $cf7_key  the form unique key.
* @return array an arra of query terms.
*/
function {$field_name_slug}_dynamic_list($query_args, $tag, $cf7_key){
  //if you have a dynamic dropdown field with posts as list source.
  //$query_args array to filter query arguments used to populate dynamic dropdown of posts.
  //these arguments are passed to the function get_posts($query_args). (codex: https://codex.wordpress.org/Template_Tags/get_posts)
  if('{$form_key}'!==$cf7_key || '{$field_name}' !== $tag->name){
    return $query_args;
  }
  //setup your custom query...
  return $query_args;
}" href="javascript:void(0);"><?=__('Filter','cf7-grid-layout')?></a> <?=__('query arguments to retrieve posts for dynamic dropdown.','cf7-grid-layout')?>
</li>
<li class="cf7sg-tag-dynamic_list-taxonomy">
  <a class="helper" data-cf72post="add_filter( 'cf7sg_{$field_type}_option_label','{$field_name_slug}_dynamic_option_label',10,4);
/**
* Filter dropdown options label for dynamic drodpwn list of taxonomy terms.
* @param string $label option label value.
* @param WP_Term $term the term object being used to populate this option.
* @param WPCF7_FormTag $tag the field being populated.
* @param string $cf7_key  the form unique key.
* @return string $label option label value.
*/
function {$field_name_slug}_dynamic_option_label($label, $term, $tag, $cf7_key){
  //these are the label users will see when the dropdown opens.
  if('{$form_key}'!==$cf7_key || '{$field_name}' !== $tag->name){
    return $label;
  }
  return $label;
}" href="javascript:void(0);"><?=__('Filter','cf7-grid-layout')?></a> <?=__('the option label.','cf7-grid-layout')?>
</li>
<li class="cf7sg-tag-dynamic_list-taxonomy">
  <a class="helper" data-cf72post="add_filter( 'cf7sg_{$field_type}_taxonomy_query','{$field_name_slug}_taxonomy_query',10,4);
/**
* Filter dropdown taxonomy query parameter.
* (see https://developer.wordpress.org/reference/classes/wp_term_query/__construct/)
* @param array $args array of taxonomy query attributes.
* @param WPCF7_FormTag $tag the field being populated.
* @param string $cf7_key  the form unique key.
* @param array $branch  an array of term IDs representing the current taxonomy branch being queried, the last term is the query parent value.
* @return array of query attributes.
*/
function {$field_name_slug}_taxonomy_query($args, $tag, $cf7_key, $branch){
  //these are the label users will see when the dropdown opens.
  if('{$form_key}'!==$cf7_key || '{$field_name}' !== $tag->name){
    return $args;
  }
  //use only the child terms of a parent.
  if($args['parent']!=0) $args=null;
  // or if you want to list only the 2nd level of your tree,
  if(count($args)>2) $args=null;
  return $args;
}" href="javascript:void(0);"><?=__('Filter','cf7-grid-layout')?></a> <?=__('the taxonomy query.','cf7-grid-layout')?>
</li>
<li class="cf7sg-tag-dynamic_list-taxonomy cf7sg_filter_taxonomy_images">
  <a class="helper" data-cf72post="add_filter( 'cf7sg_{$field_type}_options_attributes','{$field_name_slug}_dynamic_option_attributes',10,4);
/**
* Filter dropdown options  attributes.
* @param array $attributes an array of <attribute>=>$value pairs which will be used for populating select options, instead of a string $value, an array of values can be passed such as classes.
* @param WP_Term $term the term object being used to populate this option.
* @param WPCF7_FormTag $tag the field tag object.
* @param string $cf7_key  the form unique key.
* @return array array of $value=>$name pairs which will be used for populating select options attributes.
*/
function {$field_name_slug}_dynamic_option_attributes($attributes, $term, $tag, $cf7_key){
  //these are the optional attributes you can add to your dynamic list option when populating from taxonomy terms.
  //this is especially useful in the context of select2 fields, as much richer options can be built.
  if('{$form_key}'!==$cf7_key || '{$field_name}' !== $tag->name){
    return $attributes;
  }
  //example: $attributes['class'] = array($term->slug);
  //if this is a dynamic_checkbox with imagegrid option selected, then you will will need to provide image sources for each term,
  //$attributes['data-thumbnail'] = <url of image>

  return $attributes;
}" href="javascript:void(0);"><?=__('Filter','cf7-grid-layout')?></a> <?=__('options attributes.','cf7-grid-layout')?>
</li>
<li class="cf7sg-tag-dynamic_list-post">
  <a class="helper" data-cf72post="add_filter( 'cf7sg_{$field_type}_option_label','{$field_name_slug}_dynamic_option_label',10,4);
/**
* Filter dropdown options label for dynamic drodpwn list of existing posts.
* @param string $label option label value.
* @param WP_Post $post the post object being used to populate this option.
* @param WPCF7_FormTag $tag the field being populated.
* @param string $cf7_key  the form unique key.
* @return string $label option label value.
*/
function {$field_name_slug}_dynamic_option_label($label, $post, $tag, $cf7_key){
  //these are the label users will see when the dropdown opens.
  if('{$form_key}'!==$cf7_key || '{$field_name}' !== $tag->name){
    return $label;
  }
  //setup a custom label
  return $label;
}" href="javascript:void(0);"><?=__('Filter','cf7-grid-layout')?></a> <?=__('the option label.','cf7-grid-layout')?>
</li>
<li class="cf7sg-tag-dynamic_list-post">
  <a class="helper" data-cf72post="add_filter( 'cf7sg_dynamic_list_options_attributes','{$field_name_slug}_dynamic_option_attributes',10,4);
/**
* Filter dropdown options  attributes.
* @param array $attributes an array of <attribute>=>$value pairs which will be used for populating select options, instead of a string $value, an array of values can be passed such as classes.
* @param WP_Post $post the post object being used to populate this option.
* @param WPCF7_FormTag $tag the field tag object.
* @param string $cf7_key  the form unique key.
* @return array array of $value=>$name pairs which will be used for populating select options attributes.
*/
function {$field_name_slug}_dynamic_option_attributes($attributes, $post, $tag, $cf7_key){
  //these are the optional attributes you can add to your dynamic list option when populating from existing posts.
  //this is especially useful in the conctext of select2 fields, as much richer options can be built.
  if('{$form_key}'!==$cf7_key || '{$field_name}' !==$tag->name){
    return $attributes;
  }
  //example: $attributes['class'] = array('author-'.$post->post_author);
  //if you selected the Image Grid option on dynamic_checkbox field, the plugin inserts the post featured image as,
  //$attributes['data-thumbnail'] = <url of 'thumbnail' sized image> which you can change if you want.
  return $attributes;
}" href="javascript:void(0);"><?=__('Filter','cf7-grid-layout')?></a> <?=__('the option attributes.','cf7-grid-layout')?>
</li>
<li class="cf7sg-tag-dynamic_list">
  <a class="helper" data-cf72post="add_filter( 'cf7sg_{$field_type}_default_value','{$field_name_slug}_dynamic_default_option',10,3);
/**
* Filter dynamic dropdown default empty label.
* @param string $label the label for the default value, this is null by default and not shown.
* @param WPCF7_FormTag $tag the field being populated.
* @param string $cf7_key  the form unique key.
* @return string the label for the default value, returning a non-null value with display this as the first option.
*/
function {$field_name_slug}_dynamic_default_option($default_label, $tag, $cf7_key){
  if('{$form_key}'!==$cf7_key || '{$field_name}' !== $tag->name){
    return $default_label;
  }
  $default_label = 'Please select an option...';
  return $default_label;
}" href="javascript:void(0);"><?=__('Filter','cf7-grid-layout')?></a> <?=__('the default option label.','cf7-grid-layout')?>
</li>
<li class="cf7sg-tag-dynamic_select-post-tags <?=$post_my_form_only?>">
  <a class="helper" data-cf72post="add_filter( 'cf7sg_dynamic_dropdown_new_post','{$field_name_slug}_select2_newpost',10,7);
/**
* Filter custom options from tag enabled select2 dynamic-dropdown fields
* where the source of options come from post titles.  Filter is fired when a new value is submitted.
* This plugin does not take any further action, ie no post of $post_type will be created. It is upto you to do so and return the slug of the newly created post.
* @param  String  $post_name the new post slug.
* @param String $field name of the form field.
* @param  String  $title  new value being submitted for a new post title.
* @param  String $post_type  the post type from which this dropdown was built
* @param  Array  $args  an array of additional parameters that was set in the tag, for example the taxonomy and terms from which to filter the posts for the dynamic list.
* @param  Array  $submitted_data  array of other submitted $field=>$value pairs.
* @param String $key  the form unique key.
* @return String value to be stored for this field, the post slug.
*/
function {$field_name_slug}_select2_newpost($post_name, $field, $title, $post_type, $args, $submitted_data, $cf7_key){
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
  if('{$form_key}'!==$cf7_key || '{$field_name}' !== $field){
    return $default;
  }
  //create your new post using wp_insert_post();
  $post_name = 'new_submision';
  return $post_name;
}" href="javascript:void(0);"><?=__('Insert','cf7-grid-layout')?></a> <?=__('user added post.','cf7-grid-layout')?>
</li>
<li class="cf7sg-tag-dynamic_select-post-tags <?=$post_my_form_only?>">
  <a class="helper" data-cf72post="add_filter( 'cf7sg_dynamic_dropdown_new_term','{$field_name_slug}_select2_newterm',10,7);
/**
* Filter custom options from tag enabled select2 dynamic-select fields
* where the source of options come from taxonomy terms.  Filter is fired when a new value is submitted.
* This plugin will attempt to insert the new term, whether filtered or not,
* however you can return an empty string to stop the term being inserted.
* @param  String  $term_name the new term name.
* @param String $field name of the form field.
* @param  String  $taxonomy  the taxonomy for which the new term is to be added to.
* @param  Array  $submitted_data  array of other submitted $field=>$value pairs.
* @param String $key  the form unique key.
* @return String name of the new term, empty value will not be inserted.
*/
function {$field_name_slug}_select2_newpost($term_name, $field, $taxonomy, $submitted_data, $cf7_key){
  if('{$form_key}'!==$cf7_key || '{$field_name}' !== $field){
    return $default;
  }
  //change the name if need be.
  return $term_name;
}" href="javascript:void(0);"><?=__('Filter','cf7-grid-layout')?></a> <?=__('user added term.','cf7-grid-layout')?>
</li>
<li class="cf7sg-tag-dynamic_select-filter <?=$post_my_form_only?>">
  <a class="helper" data-cf72post="add_filter( 'cf7sg_dynamic_dropdown_filter_select2_submission','{$field_name_slug}_select2_filter_values',10,4);
/**
* Filter custom otions from tag enabled select2 dynamic-dropdown fields
* where the source of options come from a filter.  Filter is fired when values are submitted.
* Return updated values if any are custom values so that saved/draft submissions will reflect the correct value saved in the DB,
* @param  array  $values  an array submitted values (several values can be submitted in the case of a tabbed/table input field).
* @param  string  $field_name the name of the form field.
* @param  array  $submitted_data  array of other submitted $field=>$value pairs.
* @param string $key  the form unique key.
* @return string value to be saved for this field.
*/
function {$field_name_slug}_select2_filter_values($values, $field, $submitted_data, $cf7_key){
  /*
  Filter active when saving forms using Post My CF7 Form plugin.
  For a dynamic dropdown with custom option list set by a filter, you need to hook this filter which is fired when a user has submitted select2 field value(s). You can return the filtered selected value(s) which will be stored in the mapped post.
  This filter is especially useful when the select2 tags have been enabled (https://select2.org/tagging) and your user may have submitted new values which your original filtered list did not contain.
  $values selected by user.
  $field name of dynamic dropdown.
  $cf7_key unique form key to identify your form, $cf7_id is its post_id.
  $submitted_data array of other submitted $field=>$value pairs.
  */
  if('{$form_key}'!==$cf7_key || '{$field_name}' !== $field){
    return $values;
  }
  //do something...
  return $values;
}" href="javascript:void(0);"><?=__('Filter','cf7-grid-layout')?></a> <?=__('user added option.','cf7-grid-layout')?>
</li>
<li class="cf7sg-tag-dynamic_list-filter cf7sg_filter_source">
  <a class="helper" data-cf72post="add_filter( 'cf7sg_custom_{$field_type}','{$field_name_slug}_dynamic_options',10,3);
/**
* Filter dropdown options for dynamic drodpwn list of taxonomy terms.
* @param Array $options the option to filter.
* @param WPCF7_FormTag $tag field tag object.
* @param string $cf7_key  the form unique key.
* @return Array $options return either an array of <option value>=><option label> pairs or 2 arrays, one for values and another for attributes.
*/
function {$field_name_slug}_dynamic_options($options, $tag, $cf7_key){
  if('{$form_key}'!==$cf7_key || '{$field_name}' !== $tag->name){
    return $options;
  }
  //these are the label users will see when the dropdown opens.
  //you can group your options if need be. Let's assume you have an array of arrays of data to display in groups.
  $data = ... //fetch your data, either from the database or some other source.
  foreach($data as $value=>$label){
    $options[$value]=$label;
    //if you are displaying more complex select2 fields, or imagegrid for dynamic checkbox, then add extra parameters into a 2nd array of attributes,
    $options['values'][$value]=$label;
    $options['attributes'][$values]= array('data-thumbnail'=>'<image url>');
  }
  return $options;
}" href="javascript:void(0);"><?=__('Filter','cf7-grid-layout')?></a> <?=__('custom options .','cf7-grid-layout')?>
</li>
<li class="cf7sg-tag-dynamic_list cf7sg-tag-dynamic_list cf7sg-tag-radio cf7sg-tag-checkbox cf7sg-tag-select cf7sg-tag-acceptance">
  <a class="helper" data-cf72post="add_filter( 'cf7sg_mail_tag_{$field_type}','{$field_name_slug}_mail_tag_value',10,3);
/**
* Filter the value inserted in the mail tag.
* @since 2.9.0.
* @param string $replaced value to filter.
* @param string $name name of the field being inserted in the mail.
* @param string $cf7_key unique form key identifier.
* @return string a value to replace.
*/
function {$field_name_slug}_mail_tag_value($replaced, $name, $cf7_key){
  //check to make sure you have the right field in the right form.
  if( '{$field_name_slug}' !== $name || '{$form_key}'!==$cf7_key) return $replaced;
  //$replaced is the value inserted in the mail if you have used this field as a mail tag.
  $replaced; //this is currently set to the selected value and is a slug, you need to programmatically fetch your value and set it up.
  return $replaced;
}" href="javascript:void(0);"><?=__('Filter','cf7-grid-layout')?></a> <?=__('the mail tag value.','cf7-grid-layout')?>
</li>
<li class="cf7sg-slider">
  <a class="helper" data-cf72post="add_filter( 'cf7sg_slider_auto_scroll','{$form_key}_slider_auto_scroll',10,2);
/**
* Disable the auto scroll back to the top of the slider form.
* @since 4.13.0
* @param boolean $scroll boolean flag, default true.
* @param string $cf7_key unique form key identifier.
* @return boolean a boolean value.
*/
function {$form_key}_slider_auto_scroll($scroll, $cf7_key){
  //check to make sure you have the right field in the right form.
  if('{$form_key}'!==$cf7_key) return $scroll;
  $scroll = false; //disable auto scroll.
  return $scroll;
}" href="javascript:void(0);"><?=__('Disable','cf7-grid-layout')?></a> <?=__('the auto scroll on slide change.','cf7-grid-layout')?>
</li>
