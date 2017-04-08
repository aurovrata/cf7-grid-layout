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
?>
<?php do_action( 'wpcf7_admin_warnings' ); ?>
<?php do_action( 'wpcf7_admin_notices' ); ?>
<input type="hidden" id="wpcf7-locale" name="wpcf7-locale" value="<?php echo esc_attr( $post->locale() ); ?>" />
<input type="hidden" id="active-tab" name="active-tab" value="<?php echo isset( $_GET['active-tab'] ) ? (int) $_GET['active-tab'] : '0'; ?>" />
<?php $nonce = wp_create_nonce( 'wpcf7-save-contact-form_' .  $post->ID );?>

<div id="contact-form-editor">
<div class="keyboard-interaction"><?php echo sprintf( esc_html( __( '%s keys switch panels', 'contact-form-7' ) ), '<span class="dashicons dashicons-leftright"></span>' ); ?></div>

<?php

	$editor = new WPCF7_Editor( $post );
	$panels = array();

	if ( current_user_can( 'wpcf7_edit_contact_form', $post_id ) ) {
		$panels = array(
			'form-panel' => array(
				'title' => __( 'Form', 'contact-form-7' ),
				'callback' => array($this, 'grid_editor_panel') ),
			'mail-panel' => array(
				'title' => __( 'Mail', 'contact-form-7' ),
				'callback' => 'wpcf7_editor_panel_mail' ),
			'messages-panel' => array(
				'title' => __( 'Messages', 'contact-form-7' ),
				'callback' => 'wpcf7_editor_panel_messages' ) );

		$additional_settings = trim( $post->prop( 'additional_settings' ) );
		$additional_settings = explode( "\n", $additional_settings );
		$additional_settings = array_filter( $additional_settings );
		$additional_settings = count( $additional_settings );

		$panels['additional-settings-panel'] = array(
			'title' => $additional_settings
				? sprintf(
					__( 'Additional Settings (%d)', 'contact-form-7' ),
					$additional_settings )
				: __( 'Additional Settings', 'contact-form-7' ),
			'callback' => 'wpcf7_editor_panel_additional_settings' );
	}

	$panels = apply_filters( 'wpcf7_editor_panels', $panels );

	foreach ( $panels as $id => $panel ) {
		$editor->add_panel( $id, $panel['title'], $panel['callback'] );
	}

	$editor->display();
?>
</div><!-- #contact-form-editor -->



<?php

	$tag_generator = WPCF7_TagGenerator::get_instance();
	$tag_generator->print_panels( $post );

	do_action( 'wpcf7_admin_footer', $post );
