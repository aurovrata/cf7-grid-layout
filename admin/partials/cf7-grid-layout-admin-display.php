<h2><?php echo esc_html( __( 'Form', 'contact-form-7' ) ); ?></h2>
<?php
  $tag_generator = WPCF7_TagGenerator::get_instance();
  $tag_generator->print_buttons();
?>
<div id="form-editor-tabs">
  <ul>
    <li><a href="#cf7-editor-grid"><?=__('Grid','cf7-grid-layout')?></a></li>
    <li><a href="#cf7-codemirror"><?=__('Text','cf7-grid-layout')?></a></li>
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
<input type="hidden" value="" id="cf7sg-toggle-fields" name="cf7sg-toggle-fields" />
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
            <?=__('Row collapsible','cf7-grid-layout')?>
            <input type="checkbox" class="collapsible-row" />
          </label>
          <label class="cf7-sg-hidden table-row-label unique-mod">
            <?=__('Row table input','cf7-grid-layout')?>
            <input type="checkbox" class="table-row" />
          </label>
          <label class="table-row-button">
            <?=__('Button label','cf7-grid-layout')?>
            <input type="text" value="Add Row"/>
          </label>
          <label class="cf7-sg-hidden footer-row-label unique-mod">
            <?=__('Row table footer','cf7-grid-layout')?>
            <input type="checkbox" class="footer-row" />
          </label>
          <label class="cf7-sg-hidden tabs-row-label unique-mod">
            <?=__('Tabbed section','cf7-grid-layout')?>
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
        <option value=""><?=__('Select contact form 7','cf7-grid-layout')?></option>
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
  <div class="cf7sg-collapsible-title"><label><?=__('Section title','cf7-grid-layout')?> <input type="text" /><input type="hidden" /><input type="checkbox" /><span><?=__('toggled','cf7-grid-layout')?></span></label></div>
</div>
<div id="grid-collapsible-with-toggle">
  <div class="toggle toggle-light" data-on="Yes" data-off="No"></div>
</div>
<div id="grid-tabs">
  <ul class="cf7-sg-tabs-list">
    <li><a href="" class="cf7-sg-hidden"></a><label><?=__('Tab label','cf7-grid-layout')?><input type="text" /></label></li>
  </ul>
</div>
<div id="grid-helper">
  <span class="dashicons dashicons-no-alt"></span>
  <span class="copy-helper"><?=__('Click to copy!','cf7-grid-layout')?></span>
  <p><?=__('Click-to-copy &amp; paste in your <em>functions.php</em> file.','cf7-grid-layout')?></p>
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
      <?=__('Column offset:','cf7-grid-layout')?><br />
      <select class="column-offset select2 column-setting">
        <option value="" selected><?=__('no offset','cf7-grid-layout')?></option>
        <option value="offset-one"><?=__('one (1/12<sup>th</sup>)','cf7-grid-layout')?></option>
        <option value="offset-two"><?=__('two (1/6<sup>th</sup>)','cf7-grid-layout')?></option>
        <option value="offset-three"><?=__('three (1/4<sup>th</sup>)','cf7-grid-layout')?></option>
        <option value="offset-four"><?=__('four (1/3<sup>rd</sup>)','cf7-grid-layout')?></option>
        <option value="offset-five"><?=__('five (5/12<sup>ths</sup>)','cf7-grid-layout')?></option>
        <option value="offset-six"><?=__('half','cf7-grid-layout')?></option>
        <option value="offset-seven"><?=__('seven (7/12<sup>ths</sup>)','cf7-grid-layout')?></option>
        <option value="offset-eight"><?=__('eight (2/3<sup>rds</sup>)','cf7-grid-layout')?></option>
        <option value="offset-nine"><?=__('nine (3/4<sup>ths</sup>)','cf7-grid-layout')?></option>
        <option value="offset-ten"><?=__('ten (5/6<sup>ths</sup>)','cf7-grid-layout')?></option>
        <option value="offset-eleven"><?=__('eleven (11/12<sup>ths</sup>)','cf7-grid-layout')?></option>
      </select>
      <?=__('Column size:','cf7-grid-layout')?><br />
      <select class="column-size select2 column-setting">
        <option value="one"><?=__('one (1/12<sup>th</sup>)','cf7-grid-layout')?></option>
        <option value="two"><?=__('two (1/6<sup>th</sup>)','cf7-grid-layout')?></option>
        <option value="one-fourth"><?=__('three (1/4<sup>th</sup>)','cf7-grid-layout')?></option>
        <option value="one-third"><?=__('four (1/3<sup>rd</sup>)','cf7-grid-layout')?></option>
        <option value="five"><?=__('five (5/12<sup>ths</sup>)','cf7-grid-layout')?></option>
        <option value="one-half"><?=__('half width','cf7-grid-layout')?></option>
        <option value="seven"><?=__('seven (7/12<sup>ths</sup>)','cf7-grid-layout')?></option>
        <option value="two-thirds"><?=__('eight (2/3<sup>rds</sup>)','cf7-grid-layout')?></option>
        <option value="nine"><?=__('nine (3/4<sup>ths</sup>)','cf7-grid-layout')?></option>
        <option value="ten"><?=__('ten (5/6<sup>ths</sup>)','cf7-grid-layout')?></option>
        <option value="eleven"><?=__('eleven (11/12<sup>ths</sup>)','cf7-grid-layout')?></option>
        <option value="full" selected><?=__('full wifth','cf7-grid-layout')?></option>
      </select>
      <a id="new-row" class="button make-grid" href="javascript:void(0);"><?php _e('Make grid', 'cf7-grid-layout');?></a>
      <a class="button external-form" href="javascript:void(0);"><?php _e('Insert form', 'cf7-grid-layout');?></a>
    </div>
    <div class="cf7-field-label cf7-field-inner">
      <p class="content"><?=__('Field label','cf7-grid-layout')?></p>
      <input type="text" placeholder="<?=__('Field Label','cf7-grid-layout')?>"/>
      <span class="dashicons dashicons-no-alt field-control"></span>
    </div>
    <div class="cf7-field-type cf7-field-inner">
      <p class="content"><?=__('[select a field]','cf7-grid-layout')?></p>
      <textarea placeholder="<?=__('select a field','cf7-grid-layout')?>"></textarea>
      <span class="dashicons dashicons-no-alt field-control"></span>
    </div>
    <div class="cf7-field-tip cf7-field-inner">
      <p class="content"><?=__('describe your field','cf7-grid-layout')?></p>
      <input type="text" placeholder="<?=__('describe your field here','cf7-grid-layout')?>" />
      <span class="dashicons dashicons-no-alt field-control"></span>
    </div>
    <textarea class="grid-input"></textarea>
  </div>
</div>
<?php
