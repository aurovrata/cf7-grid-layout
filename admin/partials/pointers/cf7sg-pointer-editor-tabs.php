<?php
//pointer for editor tabs
global $post;
$is_form = get_post_meta($post->ID, '_cf7sg_managed_form', true);
if(empty($is_form) || !$is_form){ //check if new form  debug_msg($post);
  $is_form = false;
  if('auto-draft'==$post->post_status) $is_form=true;
}
if($is_form):
 ?>
<h3 class="cf7sg-pointer"><?=__('Switch Editors', 'cf7-grid-layout')?></h3>
<p><?=__('CF7 Smart Grid extension replaces the Contact Form 7 textarea editor with a <b>UI Grid editor</b> and a colour markup text editor, use this tab to switch between the two.<br />  <b>NOTE</b> that previous forms designed using the CF7 plugin editor will be editable in the text editor only.<br /><b>Clear this form</b> by switching to the text mode, selecting everything and deleting the code. Switch back to the grid editor to start with a blank form.','cf7-grid-layout')?></p>
<?php else:?>
  <h3 class="cf7sg-pointer"><?=__('Switch Editors', 'cf7-grid-layout')?></h3>
  <p><?=__("<b>Grid editor disabled!</b>  This form's HTML markup is not compatible.  The Smart Grid encodes forms with a HTML markup that allows rich responsive layouts.  Create a new form to experience the grid editor.",'cf7-grid-layout')?></p>
<?php endif; ?>
