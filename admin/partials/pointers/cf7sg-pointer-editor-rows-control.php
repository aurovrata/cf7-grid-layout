<?php
//Row controls.
global $post;
$is_form = get_post_meta($post->ID, '_cf7sg_managed_form', true);
if(empty($is_form) || !$is_form){ //check if new form  debug_msg($post);
  $is_form = false;
  if('auto-draft'==$post->post_status) $is_form=true;
}
if($is_form):
 ?>
<h3 class="cf7sg-pointer"><?=__('Row controls', 'cf7-grid-layout')?></h3>
<p><?=__('The row controls are in a dark grey box.  You can add new rows (<span class="dashicons dashicons-plus"></span>), delete a row (<span class="dashicons dashicons-trash"></span>), or move (<span class=" dashicons dashicons-move"></span>) an existing row by dragging it.  You can also edit a row (<span class=" dashicons dashicons-edit"></span>) and convert it into a collapsible section.  Collapsible sections are great to break down a large form.','cf7-grid-layout')?></p>
<?php endif; ?>
