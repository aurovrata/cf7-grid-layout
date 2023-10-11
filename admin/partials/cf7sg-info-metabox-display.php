<?php
$slug='';
if(!empty($post)){
  $slug =  $post->post_name;
}
$ver = get_post_meta($post->ID, '_cf7sg_version',true);
if(empty($ver)) $ver = $this->version;
$locale = get_post_meta($post->ID, '_locale',true);
$locale_class='';
$server_locale = get_locale();
if(empty($locale)) $locale = '<em>unset</em>';
else{
  if($server_locale !=  $locale) $locale_class='cf7sg-locale-warning';
}
?>
<label for="post_name"><?=__('Form key','cf7-grid-layout')?></label>
<input name="post_name" size="13" id="post_name" value="<?php echo $slug?>" type="text" />
<p>
  <span><?=__('Created using plugin: v.','cf7-grid-layout')?></span><strong><?=$ver?></strong>
  <input type="hidden" id="cf7sg-version" name="cf7sg-version" value="<?=$ver?>"/>
</p>
<p id="cf7sg-locale-info" class="<?=$locale_class?>">
  <span><?=__('locale:','cf7-grid-layout')?></span>&nbsp;<strong><?=$locale?></strong>
<?php if(!empty($locale_class)): ?>
  <a href="javascript:void(0);" class="button" onclick="cf7sgResetLocale('<?=$server_locale?>')"><?=__('Reset locale','cf7-grid-layout')?></a>
  <br/>
  <em class="info-tip">
  <?=
  sprintf( __('Your form locale (%1$s) is different from the server locale (%2$s), this may cause issues if you are mixing locales on your pages.','cf7-grid-layout'),
    $locale,
    $server_locale
  );
  ?>
  </em>
  <script type="text/javascript">
    function cf7sgResetLocale(loc){
      let e=document.querySelector('input#wpcf7-locale');
      e.value = loc;
      e=document.querySelector('#cf7sg-locale-info');
      e.classList.remove('<?=$locale_class?>');
      e.querySelector('strong').innerText = loc;
      e.querySelector('.button').remove();
      e.querySelector('.info-tip').innerText = "<?=__('Update your form to save the changes','cf7-grid-layout')?>";
    }
  </script>
<?php endif;?>
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
  $preview = get_preview_post_link($preview_id);
  if(strpos($preview,'wp-admin')>0){
    $preview = sprintf('<strong><em>%s</em></strong>',__('Your server does not permit previews','cf7-grid-layout'));
  }else{
    /*translators: link to preview page with form*/
    $preview = sprintf('<a href="%1s" class="button">%2s</a>', $preview,__('Preview form','cf7-grid-layout'));
  }
}else{
  $preview = sprintf('<strong><em>%s</em></strong>',__('Publish your form to preview','cf7-grid-layout'));
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
