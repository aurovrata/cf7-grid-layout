<?php
//column control.
global $post;
$is_form = get_post_meta($post->ID, '_cf7sg_managed_form', true);
if(empty($is_form) || !$is_form){ //check if new form  debug_msg($post);
  $is_form = false;
  if('auto-draft'==$post->post_status) $is_form=true;
}
if($is_form):
 ?>
<h3 class="cf7sg-pointer"><?=__('Column controls', 'cf7-grid-layout')?></h3>
<p><?=__('Use the column controls to split a row into multiple columns (<span class="dashicons dashicons-plus"></span>), delete a column (<span class="dashicons dashicons-trash"></span>), or move (<span class="dashicons dashicons-move"></span>) an existing column by dragging it within the same row.  <br />Use this link (<span class="icon-code"></span>) to switch to the line of code in the text editor corresponsding to this column.<br />You can also edit a column (<span class="dashicons dashicons-edit"></span>) and <b>convert it into a grid section</b>.  Column grid sections can have multiple rows and mulitple columns.<br />You can also convert a column into a form by inserting an existing sub-form.  This is useful to design and maintain multiple large forms that have common fields.','cf7-grid-layout')?></p>
<?php endif; ?>
