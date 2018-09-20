<h2><?php echo esc_html( __( 'Form', 'contact-form-7' ) ); ?></h2>
<?php
  $tag_generator = WPCF7_TagGenerator::get_instance();
  $tag_generator->print_buttons();
?>
<div id="form-editor-tabs">
  <ul>
    <li><a href="#cf7-editor-grid">Grid</a></li>
    <li><a href="#cf7-codemirror">Text</a></li>
  </ul>
  <div id="cf7-editor-grid">
    <div id="grid-form"></div>
  </div>
  <div id="cf7-codemirror">
    <textarea class="cf7-sg-hidden codemirror-cf7-update"></textarea>
  </div>
</div>
<textarea id="wpcf7-form-hidden" name="wpcf7-form" class="hidden large-text code" data-config-field="form.body">
<?php
//if( metadata_exists( 'post', $form_post->id(), 'cf7_grid_form' )){ //grid form
  //echo esc_textarea( $form_post->prop( 'form' ) );
//}else{
  echo esc_textarea( $form_post->prop( 'form' ) );
//}
?>
</textarea>
<!-- cf7sg - track embeded sub-forms -->
<input type="hidden" value="" id="cf7sg-embeded-forms" name="cf7sg-embeded-forms" />
<input type="hidden" value="" id="cf7sg-tabs-fields" name="cf7sg-tabs-fields" />
<input type="hidden" value="" id="cf7sg-table-fields" name="cf7sg-table-fields" />
<?php
  $tag_generator = WPCF7_TagGenerator::get_instance();
  $tag_generator->print_buttons();
?>
<div id="grid-row">
  <div class="container">
    <div class="row">
      <div class="columns full"></div>
      <div class="row-controls">
        <span class="dashicons dashicons-edit row-control"></span>
        <span class="dashicons dashicons-no-alt row-control"></span>
        <span class="dashicons dashicons-move row-control"></span>
        <span class="dashicons dashicons-plus row-control"></span>
        <span class="dashicons dashicons-trash row-control"></span>
        <div class="grid-controls">
          <label class="collapsible-row-label unique-mod">
            Row collapsible
            <input type="checkbox" class="collapsible-row" />
          </label>
          <label class="cf7-sg-hidden table-row-label unique-mod">
            Row table input
            <input type="checkbox" class="table-row" />
          </label>
          <label class="table-row-button">
            Button label
            <input type="text" value="Add Row"/>
          </label>
          <label class="cf7-sg-hidden footer-row-label unique-mod">
            Row table footer
            <input type="checkbox" class="footer-row" />
          </label>
          <label class="cf7-sg-hidden tabs-row-label unique-mod">
            Tabbed section
            <input type="checkbox" class="tabs-row" />
          </label>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="grid-cf7-forms">
  <div class="cf7sg-external-form" data-form="">
    <div class="form-controls">
      <select class="form-select">
        <option value="">Select contact form 7</option>
        <?php
          $cf7_forms = get_posts(array(
            'post_type' => 'wpcf7_contact_form',
            'post_status'=> 'publish',
            'posts_per_page' => -1,
            'post__not_in' => array($form_post->id())
          ));
          if(!empty($cf7_forms)):
            foreach($cf7_forms as $cf7_form):
        ?>
        <option value="<?php echo $cf7_form->post_name ?>"><?php echo $cf7_form->post_title ?></option>
    <?php   endforeach;
            wp_reset_postdata();
          endif;
      ?>
      </select>
      <div class="row-controls">
        <span class="dashicons dashicons-move form-control"></span>
        <span class="dashicons dashicons-plus form-control"></span>
        <span class="dashicons dashicons-trash form-control"></span>
      </div>
    </div>
    <div class="cf7sg-external-form-content"></div>
  </div>
</div>
<div id="grid-collapsible">
  <div class="cf7sg-collapsible-title"><label>Section title <input type="text" /><input type="hidden" /><input type="checkbox" /><span>toggled</span></label></div>
</div>
<div id="grid-collapsible-with-toggle">
  <div class="toggle toggle-light" data-on="Yes" data-off="No"></div>
</div>
<div id="grid-tabs">
  <ul class="cf7-sg-tabs-list">
    <li><a href="" class="cf7-sg-hidden"></a><label>Tab label<input type="text" /></label></li>
  </ul>
</div>
<div id="grid-helper">
  <span class="dashicons dashicons-no-alt"></span>
  <span class="copy-helper">Click to copy!</span>
  <p>Click-to-copy &amp; paste in your <em>functions.php</em> file.</p>
  <ul class="cf7sg-helper-list"></ul>
</div>
<div id="grid-col">
  <div class="grid-column">
    <span class="dashicons dashicons-edit column-control"></span>
    <span class="dashicons dashicons-no-alt column-control"></span>
    <span class="dashicons dashicons-plus column-control"></span>
    <span class="dashicons dashicons-controls-repeat column-control" style="display:none;"></span>
    <span class="dashicons dashicons-trash column-control"></span>
    <span class="dashicons dashicons-move column-control"></span>
    <span class="icon-code column-control"></span>
    <div class="grid-controls">
      Column offset:<br />
      <select class="column-offset select2 column-setting">
        <option value="" selected>no offset</option>
        <option value="offset-one">one (1/12<sup>th</sup>)</option>
        <option value="offset-two">two (1/6<sup>th</sup>)</option>
        <option value="offset-three">three (1/4<sup>th</sup>)</option>
        <option value="offset-four">four (1/3<sup>rd</sup>)</option>
        <option value="offset-five">five (5/12<sup>ths</sup>)</option>
        <option value="offset-six">half</option>
        <option value="offset-seven">seven (7/12<sup>ths</sup>)</option>
        <option value="offset-eight">eight (2/3<sup>rds</sup>)</option>
        <option value="offset-nine">nine (3/4<sup>ths</sup>)</option>
        <option value="offset-ten">ten (5/6<sup>ths</sup>)</option>
        <option value="offset-eleven">eleven (11/12<sup>ths</sup>)</option>
      </select>
      Column size:<br />
      <select class="column-size select2 column-setting">
        <option value="one">one (1/12<sup>th</sup>)</option>
        <option value="two">two (1/6<sup>th</sup>)</option>
        <option value="one-fourth">three (1/4<sup>th</sup>)</option>
        <option value="one-third">four (1/3<sup>rd</sup>)</option>
        <option value="five">five (5/12<sup>ths</sup>)</option>
        <option value="one-half">half width</option>
        <option value="seven">seven (7/12<sup>ths</sup>)</option>
        <option value="two-thirds">eight (2/3<sup>rds</sup>)</option>
        <option value="nine">nine (3/4<sup>ths</sup>)</option>
        <option value="ten">ten (5/6<sup>ths</sup>)</option>
        <option value="eleven">eleven (11/12<sup>ths</sup>)</option>
        <option value="full" selected>full wifth</option>
      </select>
      <a id="new-row" class="button make-grid" href="javascript:void(0);"><?php _e('Make grid', 'cf7-grid-layout');?></a>
      <a class="button external-form" href="javascript:void(0);"><?php _e('Insert form', 'cf7-grid-layout');?></a>
    </div>
    <div class="cf7-field-label cf7-field-inner">
      <p class="content">Field label</p>
      <input type="text" placeholder="Field Label"/>
      <span class="dashicons dashicons-no-alt field-control"></span>
    </div>
    <div class="cf7-field-type cf7-field-inner">
      <p class="content">[select a field]</p>
      <textarea placeholder="select a field"></textarea>
      <span class="dashicons dashicons-no-alt field-control"></span>
    </div>
    <div class="cf7-field-tip cf7-field-inner">
      <p class="content">describe your field</p>
      <input type="text" placeholder="describe your field here" />
      <span class="dashicons dashicons-no-alt field-control"></span>
    </div>
    <textarea class="grid-input"></textarea>
  </div>
</div>
<?php
