<?php
$slug='';
if(!empty($post)){
  $slug =  $post->post_name;
}
?>
<label for="post_name">Form key</label>
<input name="post_name" size="13" id="post_name" value="<?php echo $slug?>" type="text" />
<p>
  <?php echo wpcf7_link( __( 'https://contactform7.com/docs/', 'contact-form-7' ), __( 'Docs', 'contact-form-7' ) ); ?>&nbsp;
  <?php echo wpcf7_link( __( 'https://contactform7.com/faq/', 'contact-form-7' ), __( 'FAQ', 'contact-form-7' ) ); ?>&nbsp;
  <?php echo wpcf7_link( __( 'https://contactform7.com/support/', 'contact-form-7' ), __( 'Support', 'contact-form-7' ) ); ?>
</p>

<div class="clear"></div>
