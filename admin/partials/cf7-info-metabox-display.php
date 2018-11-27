<?php
$slug='';
if(!empty($post)){
  $slug =  $post->post_name;
}
?>
<label for="post_name"><?=__('Form key','cf7-grid-layout')?></label>
<input name="post_name" size="13" id="post_name" value="<?php echo $slug?>" type="text" />
<p>
  <?php echo wpcf7_link( __( 'https://contactform7.com/docs/', 'contact-form-7' ), __( 'Docs', 'contact-form-7' ) ); ?>&nbsp;
  <?php echo wpcf7_link( __( 'https://contactform7.com/faq/', 'contact-form-7' ), __( 'FAQ', 'contact-form-7' ) ); ?>&nbsp;
  <?php echo wpcf7_link( __( 'https://contactform7.com/support/', 'contact-form-7' ), __( 'Support', 'contact-form-7' ) ); ?>
</p>
<?php
$dropdowns = get_option('_cf7sg_dynamic_dropdown_taxonomy',array());
$show_dropdown = array();
if( isset($dropdowns[$post->ID]) ):
  $show_dropdown = $dropdowns[$post->ID];
?>
<strong> <?=__('Manage dynamic lists','cf7-grid-layout')?></strong>
<ul>
<?php foreach($show_dropdown as $slug=>$taxonomy): ?>
  <li>
    <?php echo $taxonomy['plural']?> (<a target="_blank" href="<?php echo admin_url('edit-tags.php?taxonomy='.$slug.'&post_type=wpcf7_contact_form')?>"><?php _e( 'Edit', 'cf7-smart-grid' );?></a>)
  </li>
<?php endforeach;?>
</ul>
<?php endif;?>
<div class="clear"></div>
