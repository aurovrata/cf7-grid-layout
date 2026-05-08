<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://syllogic.in
 * @since      1.5.0
 *
 * @package    Cf7_2_Post
 * @subpackage Cf7_2_Post/admin/partials
 */
 //TODO: add a check box to include or not address fields
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div id="benchmark-tag-generator" class="control-box cf7-benchmark">
  <fieldset>
    <legend>Benchmark field</legend>
    <table  class="form-table">
      <tbody>
        <tr>
      	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __cf7sg( 'Name' ) ); ?></label></th>
      	<td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
      	</tr>
        <tr>
        	<th scope="row">Field type</th>
        	<td><input name="required" type="checkbox"> Required field<br /></td>
      	</tr>
        <tr>
        	<th scope="row">Hidden field</th>
        	<td>
            <input name="hidden" type="checkbox">Make this a hidden field<br />
            <p>
              A hidden field will need to be populated with your own custom javascript, but the msg and limits will be automatically checked when the field is populated.  In case the limits are breached, an event 'cf7sg-benchmark-<span id="event-name"></span>' will be fired on the field itself.  Your custom javascript can further take action on the event.
            </p>
          </td>
      	</tr>
        <tr>
          <th>
            <label for="tag-generator-panel-number-id">Id attribute</label>
          </th>
          <td>
            <input name="id" class="idvalue oneline option" id="tag-generator-panel-benchmark-id" type="text">
          </td>
        </tr>
        <tr>
          <th>
            <label for="tag-generator-panel-number-class">Class attribute</label>
          </th>
          <td>
            <input name="class" class="classvalue oneline option" id="tag-generator-panel-benchmark-class" type="text">
          </td>
        </tr>
      </tbody>
    </table>
    <div id="benchmark-sources" class="tabordion">
      <section id="above-source">
        <input type="radio" name="bsections" id="above-tab" checked>
        <label for="above-tab">Above</label>
        <article>
          <h4>Above</h4>
          <label>limit <input type="number" id="benchmark-above" /></label><br />
          <label class="warning-msg">Warning <input type="text" id="warning-above"  value="The value is too high" /></label>
        </article>
      </section>
      <section id="below-source">
        <input type="radio" name="bsections" id="below-tab">
        <label for="below-tab">Below</label>
        <article class="">
          <h4>Below</h4>
          <label>limit <input type="number" id="benchmark-below" /></label><br />
          <label class="warning-msg">Warning <input type="text" id="warning-below"  value="The value is too low" /></label>
        </article>
      </section>
      <section id="between-source">
        <input type="radio" name="bsections" id="between-tab">
        <label for="between-tab">Between</label>
        <article>
          <h4>Between</h4>
          <label>min <input type="number" id="benchmark-min" /></label>
          <label>max <input type="number" id="benchmark-max" /></label><br />
          <label>Warning <input type="text" id="warning-between" value="The value is out of bound" /></label>
        </article>
      </section>
    </div> <!-- end-tabs-->

  </fieldset>
</div>
<div class="insert-box">
  <input type="hidden" name="values" value="" />
  <input type="text" name="benchmark" class="tag code" readonly="readonly" onfocus="this.select()" />

  <div class="submitbox">
      <input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __cf7sg( 'Insert Tag' ) ); ?>" />
  </div>

  <br class="clear" />
</div>
