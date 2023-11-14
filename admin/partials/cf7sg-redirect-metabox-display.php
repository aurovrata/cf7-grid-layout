<?php
/**
 * Display metabox for redirect.
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

$redirect = get_post_meta( $post->ID, '_cf7sg_page_redirect', true );
$cache    = get_post_meta( $post->ID, '_cf7sg_cache_redirect_data', true );
$unit     = MINUTE_IN_SECONDS;
$time     = 5;
$disabled = ' disabled';
$checked  = '';
if ( ! empty( $cache ) && is_array( $cache ) ) {
	$checked  = 'checked';
	$time     = $cache[0];
	$unit     = $cache[1];
	$disabled = '';
}
?>
<div>
	<label for="cf7-page-redirect"><?php esc_html_e( 'Redirect on successful form submission to', 'cf7-grid-layout' ); ?>:
<?php
wp_dropdown_pages(
	array(
		'id'               => 'cf7-page-redirect',
		'name'             => 'cf7sg_page_redirect',
		'selected'         => esc_attr( $redirect ),
		'show_option_none' => esc_html__( 'Select a page', 'cf7-grid-layout' ),
	)
);
?>
	</label>
	<div>
	<label for="is-cf7sg-cached">
		<input type="checkbox" id="is-cf7sg-cached" name="cache_cf7sg_submit" <?php echo esc_attr( $checked ); ?>/>
		<?php esc_html_e( 'Cache the submitted form data for', 'cf7-grid-layout' ); ?>
	</label>
	<fieldset id="cf7sg-cache-limit" <?php echo esc_attr( $disabled ); ?>>
		<label for="cf7sg-cached-time">
		<input type="number" value="<?php echo esc_attr( $time ); ?>" id="cf7sg-cached-time" name="cf7sg_cached_time"/>
		</label>
		<label for="cf7sg-cached-unit">
		<select id="cf7sg-cached-unit" name="cf7sg_cached_unit">
			<option value="<?php echo esc_attr( MINUTE_IN_SECONDS ); ?>" <?php echo ( MINUTE_IN_SECONDS === $unit ) ? 'selected' : ''; ?>>
			<?php esc_html_e( 'minute', 'cf7-grid-layout' ); ?>
			</option>
			<option value="<?php echo esc_attr( HOUR_IN_SECONDS ); ?>" <?php echo ( HOUR_IN_SECONDS === $unit ) ? 'selected' : ''; ?>>
			<?php esc_html_e( 'hour', 'cf7-grid-layout' ); ?>
			</option>
			<option value="<?php echo esc_attr( DAY_IN_SECONDS ); ?>" <?php echo ( DAY_IN_SECONDS === $unit ) ? 'selected' : ''; ?>>
			<?php esc_html_e( 'day', 'cf7-grid-layout' ); ?>
			</option>
		</select>
		</label>
	</fieldset>
	<p>
		<?php esc_html_e( 'If you need access to the submitted data on the redirected page, then check this option.  It will cache the submitted form fields and files as a <a href="https://developer.wordpress.org/apis/handbook/transients/">transient</a>, allowing you to retrieve it on the redirected page with the following id:', 'cf7-grid-layout' ); ?><code>'_cf7sg_'.$_GET['cf7sg']</code>
	</p>
	<script type="text/javascript">
	(function($){
		'use strict';
		$('#is-cf7sg-cached').change(function(){
			if(this.checked) $('#cf7sg-cache-limit').prop('disabled', false);
			else $('#cf7sg-cache-limit').prop('disabled', true);
		})
	})(jQuery)
	</script>
	</div>
</div>
