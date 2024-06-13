<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://syllogic.in
 * @since      1.0.0
 *
 * @package    Cf7_Grid_Layout
 * @subpackage Cf7_Grid_Layout/public/partials
 */

$validation_error = wpcf7_get_validation_error( $tag->name );
$class            = wpcf7_form_controls_class( $tag->type, 'cf7sg-benchmark' );
if ( $validation_error ) {
	$class .= ' wpcf7-not-valid';
}
$class      = $tag->get_class_option( $class );
$css_id     = $tag->get_id_option();
$input_type = 'number';

$tag_name = sanitize_html_class( $tag->name );
?>
<span class="wpcf7-form-control-wrap <?php echo esc_attr( $tag_name ); ?>" data-name="<?php echo esc_attr( $tag_name ); ?>">
	<input id="<?php echo esc_attr( $css_id ); ?>" name="<?php echo esc_attr( $tag->name ); ?>" class="<?php echo esc_attr( $class ); ?>" 
	<?php
	if ( ! empty( $tag->values ) ) {
		foreach ( $tag->values as $values ) {
			if ( 0 === strpos( $values, 'above:' ) ) {
				echo ' data-cf7sg-benchmark="above" data-cf7sg-benchmark-limit="' . esc_attr( str_replace( 'above:', '', $values ) ) . '"';
			}
			if ( 0 === strpos( $values, 'below:' ) ) {
				echo ' data-cf7sg-benchmark="below" data-cf7sg-benchmark-limit="' . esc_attr( str_replace( 'below:', '', $values ) ) . '"';
			}
			if ( 0 === strpos( $values, 'between:' ) ) {
				$range = explode( ':', str_replace( 'between:', '', $values ) );
				echo ' data-cf7sg-benchmark="range" data-cf7sg-benchmark-min="' . esc_attr( $range[0] ) . '" data-cf7sg-benchmark-max="' . esc_attr( $range[1] ) . '"';
			}
			if ( 0 === strpos( $values, 'warn:' ) ) {
				echo ' data-cf7sg-benchmark-msg="' . esc_attr( str_replace( 'warn:', '', $values ) ) . '"';
			}
			if ( 0 === strpos( $values, 'hidden:' ) ) {
				$input_type = ( 'true' === str_replace( 'hidden:', '', $values ) ) ? 'hidden' : 'number';
			}
		}
		echo ' '; // make sure we have an extra space at the end.
	}
	?>
	type="<?php echo esc_attr( $input_type ); ?>" />
</span>
