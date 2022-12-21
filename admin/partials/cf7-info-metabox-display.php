<?php
$slug='';
if(!empty($post)){
  $slug =  $post->post_name;
}
$ver = get_post_meta($post->ID, '_cf7sg_version',true);

?>
<label for="post_name"><?=__('Form key','cf7-grid-layout')?></label>
<input name="post_name" size="13" id="post_name" value="<?php echo $slug?>" type="text" />
<p>
  <span><?=__('Plugin version:','cf7-grid-layout')?></span>&nbsp;<strong><?=$ver?></strong> 
  <input type="hidden" id="cf7sg-version" name="cf7sg-version" value="<?=$ver?>"/>
</p>
<p>
  <a href="<?= __cf7sg( 'https://contactform7.com/docs/' )?>"><?= __cf7sg( 'Docs' )?></a>&nbsp;
  <a href="<?= __cf7sg( 'https://contactform7.com/faq/' )?>"><?= __cf7sg( 'FAQ' )?></a>&nbsp;
  <a href="<?= __cf7sg( 'https://contactform7.com/support/' )?>"><?= __cf7sg( 'Support' )?></a>
</p>
<?php /** @since 4.3.0 preview link */
$preview_id = get_post_meta($post->ID, '_cf7sg_form_page',true);
$preview = '';
if( !empty($preview_id) ){
  /*translators: link to preview page with form*/
  $preview = sprintf('<a href="%1s" class="button">%2s</a>', get_preview_post_link($preview_id),__('Preview form','cf7-grid-layout'));
}else{
  $preview = sprintf('<strong><em>%s</em></strong>',__('Update your form to preview','cf7-grid-layout'));
}
$dropdowns = get_option('_cf7sg_dynamic_dropdown_taxonomy',array());
$show_dropdown = array();
if( isset($dropdowns[$post->ID]) ):
  $show_dropdown = $dropdowns[$post->ID];
?>
<strong> <?=__('Manage dynamic lists','cf7-grid-layout')?></strong>
<ul>
<?php foreach($show_dropdown as $slug=>$taxonomy): ?>
  <li>
    <?php echo $taxonomy['plural']?> (<a target="_blank" href="<?php echo admin_url('edit-tags.php?taxonomy='.$slug.'&post_type=wpcf7_contact_form')?>"><?= __( 'Edit', 'cf7-smart-grid' );?></a>)
  </li>
<?php endforeach;?>
</ul>
<?php endif;?>
<div class="clear"></div>
<div id="preview-form-link"><?= $preview?></div>
