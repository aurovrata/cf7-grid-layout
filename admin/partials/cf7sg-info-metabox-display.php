<?php
/**
 * Info metabox.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://syllogic.in
 * @since      4.6.0
 *
 * @package    Cf7_Grid_Layout
 * @subpackage Cf7_Grid_Layout/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$slug = '';
if ( ! empty( $post ) ) {
	$slug = $post->post_name;
}
$ver = get_post_meta( $post->ID, '_cf7sg_version', true );
if ( empty( $ver ) ) {
	$ver = $this->version;
}
$locale_str    = get_post_meta( $post->ID, '_locale', true );
$locale_class  = '';
$server_locale = get_locale();
if ( empty( $locale_str ) ) {
	$locale_str = '<em>unset</em>';
} else {
	if ( $server_locale !== $locale_str ) {
		$locale_class = 'cf7sg-locale-warning';
	}
}
?>
<label for="post_name"><?php esc_html_e( 'Form key', 'cf7-grid-layout' ); ?></label>
<input name="post_name" size="13" id="post_name" value="<?php echo esc_attr( $slug ); ?>" type="text" />
<p>
	<span><?php esc_html_e( 'Created using plugin: v.', 'cf7-grid-layout' ); ?></span><strong><?php echo esc_attr( $ver ); ?></strong>
	<input type="hidden" id="cf7sg-version" name="cf7sg-version" value="<?php echo esc_attr( $ver ); ?>"/>
</p>
<p id="cf7sg-locale-info" class="<?php echo esc_attr( $locale_class ); ?>">
	<span><?php esc_html_e( 'locale:', 'cf7-grid-layout' ); ?></span>&nbsp;<strong><?php echo esc_attr( $locale_str ); ?></strong>
<?php if ( ! empty( $locale_class ) ) : ?>
	<a href="javascript:void(0);" class="button" onclick="cf7sgResetLocale('<?php echo esc_attr( $server_locale ); ?>')"><?php esc_html_e( 'Reset locale', 'cf7-grid-layout' ); ?></a>
	<br/>
	<em class="info-tip">
	<?php
	echo esc_html(
		sprintf(
			/* translators: %1 post locale, %2 server locale */
			__( 'Your form locale (%1$s) is different from the server locale (%2$s), this may cause issues if you are mixing locales on your pages.', 'cf7-grid-layout' ),
			$locale_str,
			$server_locale
		)
	);
	?>
	</em>
	<script type="text/javascript">
	function cf7sgResetLocale(loc){
		let e=document.querySelector('input#wpcf7-locale');
		e.value = loc;
		e=document.querySelector('#cf7sg-locale-info');
		e.classList.remove('<?php echo esc_attr( $locale_class ); ?>');
		e.querySelector('strong').innerText = loc;
		e.querySelector('.button').remove();
		e.querySelector('.info-tip').innerText = "<?php esc_html_e( 'Update your form to save the changes', 'cf7-grid-layout' ); ?>";
	}
	</script>
<?php endif; ?>
</p>
<p>
	<a href="https://contactform7.com/docs/"><?php esc_html_e( 'Docs', 'cf7-grid-layout' ); ?></a>&nbsp;
	<a href="https://contactform7.com/faq/"><?php esc_html_e( 'FAQ', 'cf7-grid-layout' ); ?></a>&nbsp;
	<a href="https://contactform7.com/support/"><?php esc_html_e( 'Support', 'cf7-grid-layout' ); ?></a>
</p>
<?php

$dropdowns     = get_option( '_cf7sg_dynamic_dropdown_taxonomy', array() );
$show_dropdown = array();
if ( isset( $dropdowns[ $post->ID ] ) ) :
	$show_dropdown = $dropdowns[ $post->ID ];
	?>
<strong> <?php esc_html_e( 'Manage dynamic lists', 'cf7-grid-layout' ); ?></strong>
<ul>
	<?php foreach ( $show_dropdown as $slug => $tx ) : ?>
	<li>
		<?php echo esc_html( $tx['plural'] ); ?> (<a target="_blank" href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=' . $slug . '&post_type=wpcf7_contact_form' ) ); ?>"><?php esc_html_e( 'Edit', 'cf7-smart-grid' ); ?></a>)
	</li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
<div class="clear"></div>
<div id="preview-form-link">
	<?php
	/** NB @since 4.3.0 preview link */
	$preview_id   = get_post_meta( $post->ID, '_cf7sg_form_page', true );
	$preview_link = '';
	if ( ! empty( $preview_id ) ) {
		$preview_link = get_preview_post_link( $preview_id );
		if ( strpos( $preview_link, 'wp-admin' ) > 0 ) {
			echo sprintf( '<strong><em>%s</em></strong>', esc_html__( 'Your server does not permit previews', 'cf7-grid-layout' ) );
		} else {
			echo sprintf(
				'<a href="%1s" class="button">%2s</a>',
				esc_url( $preview_link ),
				/*translators: link to preview page with form*/
				esc_html__( 'Preview form', 'cf7-grid-layout' )
			);
		}
	} else {
		echo sprintf( '<strong><em>%s</em></strong>', esc_html__( 'Publish your form to preview', 'cf7-grid-layout' ) );
	}
	?>
</div>
