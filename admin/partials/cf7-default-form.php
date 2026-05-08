<?php ob_start(); ?>
<div class="container">
  <div class="row">
    <div class="columns one-half">
      <div class="container">
        <div class="row">
          <div class="columns full">
            <div class="field"><label>%1$s<em>*</em></label>[text* your-name]
              <p class="info-tip">%2$s</p>
            </div>
          </div>
        </div>
      </div>
      <div class="container">
        <div class="row">
          <div class="columns full">
            <div class="field"><label>%3$s<em>*</em></label>[email* your-email]
              <p class="info-tip">%4$s</p>
            </div>
          </div>
        </div>
      </div>
      <div class="container">
        <div class="row">
          <div class="columns full">
            <div class="field"><label>%6$s<em>*</em></label>[text* your-subject]
              <p class="info-tip">%7$s</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="columns one-half">
      <div class="container">
        <div class="row">
          <div class="columns full">
            <div class="field"><label>%8$s<em>*</em></label>[textarea* your-message x5]
              <p class="info-tip">%9$s</p>
            </div>
          </div>
        </div>
      </div>
      <div class="container">
        <div class="row">
          <div class="columns full">
            <div class="field"><label></label>[submit "%5$s"]
              <p class="info-tip"></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
$template = ob_get_clean();
$template = sprintf( $template,
  __( 'Your Name', 'contact-form-7' ),
  __( 'Enter your full name', 'cf7-grid-layout' ),
  __( 'Your Email', 'contact-form-7' ),
  __( 'Enter a valid email', 'cf7-grid-layout' ),
  __( 'Send', 'contact-form-7' ),
  __( 'Subject', 'contact-form-7' ),
	__( 'the topic of your message', 'cf7-grid-layout' ),
  __( 'Your Message', 'contact-form-7' ),
	__( 'Enter a brief message', 'cf7-grid-layout' ));
