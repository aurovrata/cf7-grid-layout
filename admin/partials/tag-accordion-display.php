<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://syllogic.in
 * @since      1.0.0
 *
 * @package    Cf7_GoogleMap
 * @subpackage Cf7_GoogleMap/admin/partials
 */
 //TODO: add a check box to include or not address fields
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="control-box cf7-googleMap">
  <fieldset>
    <legend>Google Map field for contact form 7</legend>
    <table id="googleMap-tag-generator" class="form-table">
      <tbody>
        <tr>
      	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'contact-form-7' ) ); ?></label></th>
      	<td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
      	</tr>
        <tr>
      	<th scope="row">Field type</th>
      	<td><input name="required" type="checkbox"> Required field<br /></td>
      	</tr>
          <tr>
            <th>
              <p><?php esc_html_e( 'Map Zoom', 'cf7-google-map' ); ?>: </p>
              <input type="text" readonly name="cf7_zoom" id="cf7_zoom" class="regular-text cf7-googleMap-values" value="3" /><br/>
              <label for="cf7_centre_lat" ><?php esc_html_e( 'Map Centre', 'cf7-google-map' ); ?>: <br />
              <input type="text" readonly name="cf7_centre_lat" id="cf7_centre_lat" class="regular-text cf7-googleMap-values" value="0" /><br />
              <input type="text" readonly name="cf7_centre_lng" id="cf7_centre_lng" class="regular-text cf7-googleMap-values" value="79.810600" />
            </th>
            <td>
              <div id="cf7_admin_map"></div>
            </td>
          </tr>
          <tr>
            <th>
              <?php esc_html_e( 'Marker location', 'cf7-google-map' ); ?>:
            </th>
            <td>
              <div class="listings">
                <input type="text" name="cf7_listing_lat" id="cf7_listing_lat" class="regular-text cf7-googleMap-values" value="12.007089" />,<br />
                <small><?php esc_html_e( 'e.g.', 'cf7-google-map' ); ?> <code>12.007089</code></small>
              </div>
              <div class="listings">
                <input type="text" name="cf7_listing_lng" id="cf7_listing_lng" class="regular-text cf7-googleMap-values" value="79.810600" /><br />
                <small><?php esc_html_e( 'e.g.', 'cf7-google-map' ); ?> <code>79.810600</code></small>
              </div>
            </td>
          </tr>
      </tbody>
    </table>
  </fieldset>
</div>
<div class="insert-box">
  <input type="hidden" name="values" value="" />
  <input type="text" name="map" class="tag code" readonly="readonly" onfocus="this.select()" />

  <div class="submitbox">
      <input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
  </div>

  <br class="clear" />
</div>
