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

<div class="control-box cf7-grid-layout">
  <fieldset>
    <legend>Smart Grid Layout</legend>
    <table id="grid-tag-generator" class="form-table">
      <tbody>
        <tr id="number_of_rows_tr">
          <th><label for="number_of_rows">Rows</label></th>
          <td>
            <input type="number" readonly name="number_of_rows" id="number_of_rows" class="regular-text" value="3" /><br/>
          </td>
        </tr>
        <tr id="number_of_columns_1_tr">
          <th><label for="number_of_columns_1">Columns (row 1)</label></th>
          <td>
            <input type="number" readonly name="number_of_columns_1" id="number_of_columns_1" class="regular-text" value="3" /><br/>
          </td>
        </tr>
        <tr id="number_of_columns_2_tr">
          <th><label for="number_of_columns_1">Columns (row 2)</label></th>
          <td>
            <input type="number" readonly name="number_of_columns_2" id="number_of_columns_2" class="regular-text" value="3" /><br/>
          </td>
        </tr>
        <tr id="number_of_columns_3_tr">
          <th><label for="number_of_columns_1">Columns (row 3)</label></th>
          <td>
            <input type="number" readonly name="number_of_columns_3" id="number_of_columns_3" class="regular-text" value="3" /><br/>
          </td>
        </tr>
      </tbody>
    </table>
  </fieldset>
</div>
<div class="insert-box">
  <input type="hidden" name="values" value="" />
  <input type="text" name="smart-grid" class="tag code" readonly="readonly" onfocus="this.select()" />

  <div class="submitbox">
      <input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
  </div>

  <br class="clear" />

  <p class="description mail-tag"><label><?php echo esc_html( __( "This field should not be used on the Mail tab.", 'contact-form-7' ) ); ?></label>
  </p>
</div>
