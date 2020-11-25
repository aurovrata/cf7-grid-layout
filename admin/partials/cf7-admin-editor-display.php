<?php

/**
 * Provide a admin area view for the plugin to edit contact form 7 through the visual editor
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://syllogic.in
 * @since      1.0.0
 *
 * @package    Cf7_Grid_Layout
 * @subpackage Cf7_Grid_Layout/admin/partials
 */
 $screen = get_current_screen();
?>
<?php do_action( 'wpcf7_admin_warnings', 'wpcf7', wpcf7_current_action(), null ); ?>
<?php do_action( 'wpcf7_admin_notices' , 'wpcf7', wpcf7_current_action(), null ); ?>
<?php
if('add' == $screen->action) do_action( 'load-contact_page_wpcf7-new' );
else do_action( 'load-toplevel_page_wpcf7' );
?>
<div id="cf7sg-editor">
    <div style="position:relative">
      <a id="full-screen-cf7" class="button" href="javascript:void(0)"><span><?= __('toggle full screen','cf7-grid-layout')?></span></a>
    </div>
  <input type="hidden" id="is-cf7sg-form" name="is_cf7sg_form" value="true" />
  <input type="hidden" id="wpcf7-locale" name="wpcf7-locale" value="<?php echo esc_attr( $cf7_form->locale() ); ?>" />
  <input type="hidden" id="active-tab" name="active-tab" value="<?php echo isset( $_GET['active-tab'] ) ? (int) $_GET['active-tab'] : '0'; ?>" />
  <?php wp_nonce_field( 'wpcf7-save-contact-form_' .  $post_id, '_wpcf7nonce' ); ?>

  <div id="contact-form-editor">
    <div class="loading-screen"><h2><?= __('Loading form editor ...','cf7-grid-layout')?><span class="spinner"></span></h2></div>
    <div class="keyboard-interaction"><?php echo sprintf( esc_html( __cf7sg( '%s keys switch panels') ), '<span class="dashicons dashicons-leftright"></span>' ); ?></div>

  <?php

  	$editor = new WPCF7_Editor( $cf7_form );
  	$panels = array();

  	if ( current_user_can( 'wpcf7_edit_contact_form', $post_id ) ) {
  		$panels = array(
  			'form-panel' => array(
  				'title' => __cf7sg( 'Form' ),
  				'callback' => array($this, 'grid_editor_panel') ),
  			'mail-panel' => array(
  				'title' => __cf7sg( 'Mail' ),
  				'callback' => 'wpcf7_editor_panel_mail' ),
  			'messages-panel' => array(
  				'title' => __cf7sg( 'Messages' ),
  				'callback' => 'wpcf7_editor_panel_messages' ) );

  		$additional_settings = trim( $cf7_form->prop( 'additional_settings' ) );
  		$additional_settings = explode( "\n", $additional_settings );
  		$additional_settings = array_filter( $additional_settings );
  		$additional_settings = count( $additional_settings );

  		$panels['additional-settings-panel'] = array(
  			'title' => $additional_settings
  				? sprintf(
  					__cf7sg( 'Additional Settings (%d)' ),
  					$additional_settings )
  				: __cf7sg( 'Additional Settings' ),
  			'callback' => 'wpcf7_editor_panel_additional_settings' );
  	}
    /**
    * filter to add/remove panels from the cf7 post editor
    * @param Array $panel array of panels presented as tabs in the editor, $id => array( 'title' => $panel_title, 'callback' => $callback_function).  The $callback_function must be a valid function to echo the panel html script.
    */
  	$panels = apply_filters( 'wpcf7_editor_panels', $panels );

  	foreach ( $panels as $id => $panel ) {
  		$editor->add_panel( $id, $panel['title'], $panel['callback'] );
  	}

  	$editor->display();
  ?>
  </div><!-- #contact-form-editor -->

  <form action="" class="dummy-form" data-id="dummy">
    <!-- DUMMY FORM to prevent some wp-core scripts from tempering with cf7 tags forms printed below-->
  </form>
  <?php

  	$tag_generator = WPCF7_TagGenerator::get_instance();

  	$tag_generator->print_panels( $cf7_form );

  	do_action( 'wpcf7_admin_footer', $cf7_form );

    $dropdowns = get_option('_cf7sg_dynamic_dropdown_taxonomy',array());
    $show_dropdown = array();
    if( isset($dropdowns[$post_id]) ){
      $show_dropdown = $dropdowns[$post_id];
    }
  ?>
  <script type="text/javascript">
  (function( $ ) {
  	'use strict';
    //hide the taxonomy metabox not used on this page.
    $(document).ready(function() {
      <?php
      $slugs = array();

      foreach($dropdowns as $id => $all_lists){
        //if($id == $post_id) continue;
        foreach($all_lists as $slug => $taxonomy){
          if(isset($slugs[$slug])){
            continue;
          }
          if( $taxonomy['hierarchical'] ){
            $hide_id = $slug.'div';
          }else{
            $hide_id = 'tagsdiv-'.$slug;
          }

          echo '$("#' . $hide_id . '").hide();'.PHP_EOL;
        }
      }
      ?>
    });
  })( jQuery );
  </script>
</div>
