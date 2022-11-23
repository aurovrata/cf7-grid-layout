<?php ob_start(); ?>
<div class="container">
  <div class="row">
    <div class="columns full">
    </div>
  </div>
</div>
<?php
$template = ob_get_clean();
// $template = sprintf( $template,
//   __( 'Your Name', 'contact-form-7' ),
//   __( 'Enter your full name', 'cf7-grid-layout' ),
//   __( 'Your Email', 'contact-form-7' ),
//   __( 'Enter a valid email', 'cf7-grid-layout' ),
//   __( 'Send', 'contact-form-7' ),
//   __( 'Subject', 'contact-form-7' ),
// 	__( 'the topic of your message', 'cf7-grid-layout' ),
//   __( 'Your Message', 'contact-form-7' ),
// 	__( 'Enter a brief message', 'cf7-grid-layout' ));
