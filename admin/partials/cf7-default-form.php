<?php ob_start(); ?>
<div class="container" style="">
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
            <div class="field"><label></label>[submit "%5$s"]
              <p class="info-tip"></p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="columns one-half">
      <div class="container">
        <div class="row">
          <div class="columns full">
            <div class="field"><label>%6$s<em>*</em></label>[text* your-subject]
              <p class="info-tip">%7$s</p>
            </div>
          </div>
        </div>
      </div>
      <div class="container">
        <div class="row">
          <div class="columns full">
            <div class="field"><label>%8$s<em>*</em></label>[textarea* your-message x5]
              <p class="info-tip">%9$s</p>
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
__( 'Name', 'cf7-grid-layout' ),
  __( 'Enter your full name', 'cf7-grid-layout' ),
  __( 'Email', 'cf7-grid-layout' ),
  __( 'Enter a valid email', 'cf7-grid-layout' ),
	__( 'Send', 'cf7-grid-layout' ),
   __( 'Subject', 'cf7-grid-layout' ),
	__( 'the topic of your message', 'cf7-grid-layout' ),
  __( 'Message', 'cf7-grid-layout' ),
	__( 'Enter a brief message', 'cf7-grid-layout' ));
