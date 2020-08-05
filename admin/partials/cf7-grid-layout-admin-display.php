<?php
global $post;
$cf7_key = $post->post_name;
 ?>
<h2><?php echo esc_html( __cf7sg( 'Form' ) ); ?></h2>
<div id="top-tags">
<?php
  $tag_generator = WPCF7_TagGenerator::get_instance();
  $tag_generator->print_buttons();
?>
</div>
<ul id="js-tags" class="display-none">
  <li id="form-events"><?=__('Form','cf7-grid-layout')?><span>&gt;</span>
    <ul>
      <li>
        <a class="helper" data-cf72post="$('#cf7sg-form-{$cf7_key} form.wpcf7-form').on( 'cf7SmartGridReady', function(e){
/* event fired once the form has been initialised, click to insert helper code into your js file. */
let $form = $(this); //$jquery form object.
});" href="javascript:void(0);"><?=__('On form ready','cf7-grid-layout')?></a>
      </li>
    </ul>
  </li>
  <li id="table-events" class="display-none"><?=__('Tables','cf7-grid-layout')?><span>&gt;</span>
    <ul>
      <li>
        <a class="helper" data-cf72post="$('#cf7sg-form-{$cf7_key} form.wpcf7-form').on( 'sgTableReady', '.container.cf7-sg-table', function(e){
/* event fired once a table has been initialised, click to insert helper code into your js file. */
let $form = $(this), $table = $(e.target);
});" href="javascript:void(0);"><?=__('On table ready','cf7-grid-layout')?></a>
      </li>
      <li>
        <a class="helper" data-cf72post="$('#cf7sg-form-{$cf7_key} form.wpcf7-form').on( 'sgRowAdded', '.container.cf7-sg-table', function(e){
/* event fired when a table row has been added, click to insert helper code into your js file. */
let $form = $(this), $table = $(e.target);
});" href="javascript:void(0);"><?=__('On row add','cf7-grid-layout')?></a>
      </li>
      <li>
        <a class="helper" data-cf72post="$('#cf7sg-form-{$cf7_key} form.wpcf7-form').on( 'sgRowDeleted', '.container.cf7-sg-table', function(e){
/* event fired when a table row has been deleted, click to insert helper code into your js file. */
let $form = $(this), $table = $(e.target);
});" href="javascript:void(0);"><?=__('On row remove','cf7-grid-layout')?></a>
      </li>
    </ul>
  </li>
  <li id="tab-events" class="display-none"><?=__('Tabs','cf7-grid-layout')?><span>&gt;</span>
    <ul>
      <li>
        <a class="helper" data-cf72post="$('#cf7sg-form-{$cf7_key} form.wpcf7-form').on( 'sgTabsReady', function(e){
/* event fired once the tabs has been initialised, click to insert helper code into your js file. */
let $form = $(this), $tabs = $(e.target);
});" href="javascript:void(0);"><?=__('On tabs ready','cf7-grid-layout')?></a>
      </li>
      <li>
        <a class="helper" data-cf72post="$('#cf7sg-form-{$cf7_key} form.wpcf7-form').on( 'sgTabAdded', '.cf7-sg-tabs-panel', function(e){
/* event fired when a new tab is added, click to insert helper code into your js file. */
let $form = $(this), $tabs = $(e.target);
});" href="javascript:void(0);"><?=__('On tab add','cf7-grid-layout')?></a>
      </li>
      <li>
        <a class="helper" data-cf72post="$('#cf7sg-form-{$cf7_key} form.wpcf7-form').on( 'sgTabRemoved', '.cf7-sg-tabs', function(e){
/* event fired when a new tab is removed, click to insert helper code into your js file. */
let $form = $(this), $tabs = $(e.target);
});" href="javascript:void(0);"><?=__('On tab remove','cf7-grid-layout')?></a>
      </li>
    </ul>
  </li>
  <li id="collapsible-events" class="display-none"><?=__('Collapsible rows','cf7-grid-layout')?><span>&gt;</span>
    <ul>
      <li>
        <a class="helper" data-cf72post="$('#cf7sg-form-{$cf7_key} form.wpcf7-form').on( 'sgCollapsibleRowsReady', function(e){
/* event fired once the collapsible rows (accordion/toggle/sections) have been initialised */
let $form = $(this), $row = $(e.target);
});" href="javascript:void(0);"><?=__('On section ready','cf7-grid-layout')?></a>
      </li>
      <li>
        <a class="helper" data-cf72post="$('#cf7sg-form-{$cf7_key} form.wpcf7-form').on( 'accordionactivate', function(e, ui){
  /* event fired when a collapsible row (accordion/toggle/section) is activated, click to insert helper code into your js file. */
  //this event is fired if the section is either closed or opened.
  //for single collapsed sections,
  //  when openned, ui.oldPanel is empty and ui.newPanel is the current opened panel.
  //  when closed, ui.oldPanel is the currently closed panel and ui.newPanel is empty.
  //for accordion with multile rows,
  //  ui.oldPanel is the previous panel (else empty if first actiation), ui.newPanel is the current opened panel.
  //see https://api.jqueryui.com/accordion/#event-activate for more details.
  let $form = $(this), $row = $(e.target);
  switch(true){
    case $row.is('.with-toggle'):
      //this is a toggled section, identify it by its CSS id $this.attr('id').
      break;
    case $row.is('.cf7sg-accordion-rows'):
      //this is an accordion with multiple collapsible sections, identify it by its CSS id $this.attr('id')
      break;
    default: //this is a single collapsible section.
      //identify it by its CSS id $this.attr('id')
      break;
    }
});" href="javascript:void(0);"><?=__('On section activated','cf7-grid-layout')?></a>
      </li>
    </ul>
  </li>
  <li id="slider-events" class="display-none"><?=__('Slides','cf7-grid-layout')?><span>&gt;</span>
    <ul>
      <li>
        <a class="helper" data-cf72post="$('#cf7sg-form-{$cf7_key} form.wpcf7-form').on( 'sgSliderReady','.cf7sg-slider-section', function(e){
/* event fired once the slider has been initialised, click to insert helper code into your js file. */
let $form = $(this), $slider = $(e.target);
});" href="javascript:void(0);"><?=__('On slider ready','cf7-grid-layout')?></a>
      </li>
      <li>
        <a class="helper" data-cf72post="$('#cf7sg-form-{$cf7_key} form.wpcf7-form').on( 'sgNextSlide','.cf7sg-collapsible', function(e){
  /* event fired when the next slide is activated, click to insert helper code into your js file. */
  //e.detail.prev holds the index to the previous slide, empty on the first slide.
  //e.detail.current holds the index of the current slide
  //e.detail.last holds the index of the last slide.
  //you can add a CSS id to each div.container.cf7sg-collapsible elements
  //  whithin the div.cf7sg-slider-section element to uniquely identify each slide $(this).attr('id');
  let $form = $(this), $slide = $(e.target);
});" href="javascript:void(0);"><?=__('Next slide activated','cf7-grid-layout')?></a>
      </li>
      <li>
        <a class="helper" data-cf72post="$('#cf7sg-form-{$cf7_key} form.wpcf7-form').on( 'sgPrevSlide','.cf7sg-collapsible', function(e){
  /* event fired when the previous slide is activated, click to insert helper code into your js file. */
  //e.detail.prev holds the index to the previous slide, empty on the first slide.
  //e.detail.current holds the index of the current slide
  //e.detail.last holds the index of the last slide.
  //you can add a CSS id to each div.container.cf7sg-collapsible elements
  //  whithin the div.cf7sg-slider-section element to uniquely identify each slide $(this).attr('id');
  let $form = $(this), $slide = $(e.target);
});" href="javascript:void(0);"><?=__('Previous slide activated','cf7-grid-layout')?></a>
      </li>
    </ul>
  </li>
  <li id="field-events"><?=__('Form fields','cf7-grid-layout')?><span>&gt;</span>
    <ul>
      <li>
        <a class="helper all-fields" data-cf72post="$('#cf7sg-form-{$cf7_key} form.wpcf7-form').on( 'change',':input', function(e){
  /* event fired when a field changes value, click to insert helper code into your js file. */
  let $form = $(e.delegateTarget), $field=$(this), fieldName = $field.attr('name');
  {$array_field_extraction}
  // $form is the form jquery object.
  // $field is the input field jquery object.
  //
  switch(fieldName){{$list_of_fields}}
});" href="javascript:void(0);"><?=__('Value change','cf7-grid-layout')?></a>
      </li>
    </ul>
  </li>
  <li id="other-events" class="display-none"><?=__('Others','cf7-grid-layout')?><span>&gt;</span>
    <ul>
    </ul>
  </li>
</ul>
<div id="editors">
  <?php
  $js_file = str_replace(ABSPATH, '', get_stylesheet_directory()."/js/{$cf7_key}.js");
  $js_file_exists = file_exists(ABSPATH.$js_file);
  $jscm_required = $js_file_exists ? ' required':'';
  $css_file = str_replace(ABSPATH, '', get_stylesheet_directory()."/css/{$cf7_key}.css");
  $css_file_exists = file_exists(ABSPATH.$css_file);
  $csscm_required = $css_file_exists ? ' required':'';
  ?>

  <div id="optional-editors">
    <a class="button jstab cf7sg-cmtab<?=$jscm_required?>" href="javascript:void(0);"><?=__('Add custom JS','cf7-grid-layout')?></a>
    <div id="cf7-js-codemirror" class="display-none">
      <div  class="codemirror-theme"><?=__('Editor theme:','cf7-grid-layout')?>
        <?php $user_js_theme = get_user_meta(get_current_user_id(),'_cf7sg_js_cm_theme', true); ?>
        <label>
          <input type="radio" value="light" name="cf7sg_js_codemirror_theme"<?=('light'==$user_js_theme ? ' checked' : '')?>/><?=__('Light','cf7-grid-layout')?>
        </label>
        <label>
          <input type="radio" value="dark" name="cf7sg_js_codemirror_theme" <?=('dark'==$user_js_theme ? ' checked' : '')?>/><?=__('Dark','cf7-grid-layout')?>
        </label>
        <input type="hidden" name="cf7sg_prev_js_file" class="prev-file" />
      </div>
      <textarea id="cf7-form-js" class="cf7-sg-hidden" data-file="<?=__('File','cf7-grid-layout')?>&nbsp;&gt;&gt;&nbsp;/<?=$js_file?>" name="cf7sg_js_file" data-form="">
        <?php
        if($js_file_exists){
          $file = file_get_contents(ABSPATH.$js_file);
          echo esc_textarea( $file );
        }else{
          do_action('cf7sg_default_custom_js_template', $cf7_key);
        }
        ?>
      </textarea>
    </div>
    <a class="button csstab cf7sg-cmtab<?=$csscm_required?>" href=""><?=__('Add custom CSS','cf7-grid-layout')?></a>
    <div id="cf7-css-codemirror" class="display-none">
      <div  class="codemirror-theme"><?=__('Editor theme:','cf7-grid-layout')?>
        <?php $user_css_theme = get_user_meta(get_current_user_id(),'_cf7sg_css_cm_theme', true); ?>
        <label>
          <input type="radio" value="light" name="cf7sg_css_codemirror_theme"<?=('light'==$user_css_theme ? ' checked' : '')?>/><?=__('Light','cf7-grid-layout')?>
        </label>
        <label>
          <input type="radio" value="dark" name="cf7sg_css_codemirror_theme" <?=('dark'==$user_css_theme ? ' checked' : '')?>/><?=__('Dark','cf7-grid-layout')?>
        </label>
        <input type="hidden" name="cf7sg_prev_css_file" class="prev-file" />
      </div>
      <textarea id="cf7-form-css" class="cf7-sg-hidden" data-file="<?=__('File','cf7-grid-layout')?>&nbsp;&gt;&gt;&nbsp;/<?=$css_file?>" name="cf7sg_css_file" data-form="">
        <?php
        if($css_file_exists){
          $file = file_get_contents(ABSPATH.$css_file);
          echo esc_textarea( $file );
        }else{
          do_action('cf7sg_default_custom_css_template', $cf7_key);
        }
        ?>
      </textarea>
    </div>
  </div>

  <div id="form-editor-tabs">
    <ul>
      <li><a class="button" href="#cf7-editor-grid"><?=__('Grid','cf7-grid-layout')?></a></li>
      <li><a class="button" href="#cf7-codemirror">&lt;HTML/&gt;</a></li>
    </ul>
    <div id="cf7-editor-grid">
      <div id="grid-form"></div>
    </div>
    <div id="cf7-codemirror">
      <div  class="codemirror-theme"><?=__('Editor theme:','cf7-grid-layout')?>
        <?php $user_theme = get_user_meta(get_current_user_id(),'_cf7sg_cm_theme', true);?>
        <label>
          <input type="radio" value="light" name="cf7sg_codemirror_theme"<?=('light'==$user_theme ? ' checked' : '')?>/><?=__('Light','cf7-grid-layout')?>
        </label>
        <label>
          <input type="radio" value="dark" name="cf7sg_codemirror_theme" <?=('dark'==$user_theme ? ' checked' : '')?>/><?=__('Dark','cf7-grid-layout')?>
        </label>
      </div>
      <textarea id="wpcf7-form" class="cf7-sg-hidden codemirror-cf7-update">
        <?= esc_textarea( $form_post->prop( 'form' ) );
        /** @since 2.8.3 rename codemirror textarea##wpcf7-form adn initially popullate with form.  */?>
      </textarea>
    </div>
  </div>
</div>
<textarea id="wpcf7-form-hidden" name="wpcf7-form" class="hidden large-text code" data-config-field="form.body"><?= esc_textarea( $form_post->prop( 'form' ) );?></textarea>
<!-- cf7sg - track embeded sub-forms -->
<input type="hidden" value="" id="cf7sg-embeded-forms" name="cf7sg-embeded-forms" />
<input type="hidden" value="" id="cf7sg-tabs-fields" name="cf7sg-tabs-fields" />
<input type="hidden" value="" id="cf7sg-table-fields" name="cf7sg-table-fields" />
<input type="hidden" value="" id="cf7sg-toggle-fields" name="cf7sg-toggle-fields" />
<div id="bottom-tags">
<?php
  $tag_generator = WPCF7_TagGenerator::get_instance();
  $tag_generator->print_buttons();
?>
</div>
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
            <input type="text" value="<?= __('Add Row','cf7-grid-layout')?>"/>
          </label>
          <label class="cf7-sg-hidden footer-row-label unique-mod">
            <?=__('Row table footer','cf7-grid-layout')?>
            <input type="checkbox" class="footer-row" />
          </label>
          <label class="cf7-sg-hidden tabs-row-label unique-mod">
            <?=__('Tabbed section','cf7-grid-layout')?>
            <input type="checkbox" class="tabs-row" />
          </label>
          <a class="display-none button make-grid row-control" href="javascript:void(0);"><?= __('Make grid', 'cf7-grid-layout');?></a>
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
<div id="grid-js-helper">
  <span class="dashicons dashicons-no-alt"></span>
  <span class="copy-helper"><?=__('Click to copy!','cf7-grid-layout')?></span>
  <p class="js-help"><?= sprintf(__('Click-to-copy &amp; paste in<br/><em>&lt;theme folder&gt;/js/%s.js</em> file.','cf7-grid-layout'), $cf7_key);?></p>
  <ul class="cf7sg-helper-list"></ul>
</div>
<div id="grid-col">
  <div class="grid-column">
    <span class="dashicons dashicons-edit column-control"></span>
    <span class="dashicons dashicons-no-alt column-control"></span>
    <span class="dashicons dashicons-plus column-control"></span>
    <span class="dashicons php-icon column-control" style="display:none;"></span>
    <span class="js-icon column-control" style="display:none;"></span>
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
      <a id="new-row" class="button make-grid column-control" href="javascript:void(0);"><?= __('Make grid', 'cf7-grid-layout');?></a>
      <a class="button external-form" href="javascript:void(0);"><?= __('Insert form', 'cf7-grid-layout');?></a>
      <label class="display-none accordion-label grouping-option"><input type="checkbox" name="grouping-option" class="accordion-rows column-control" /><?=__('Enable accordion','cf7-smart-grid')?></label><span class="popup display-none"><?= __('Group collapsible rows as jQuery accordion','cf7-smart-grid')?></span>
      <label class="display-none slider-label grouping-option"><input type="checkbox" name="grouping-option" class="slider-rows column-control" /><?=__('Enable slider','cf7-smart-grid')?></label><span class="popup display-none"><?= __('Convert collapsible rows into sides','cf7-smart-grid')?></span>
    </div>
    <div class="cf7-field-label cf7-field-inner">
      <p class="content"><?=__('Field label','cf7-grid-layout')?></p>
      <input type="text" placeholder="<?=__('Field label','cf7-grid-layout')?>"/>
      <span class="dashicons dashicons-no-alt field-control"></span>
    </div>
    <div class="cf7-field-type cf7-field-inner">
      <p class="content"><?=__('[select a field]','cf7-grid-layout')?></p>
      <textarea class="field-entry" placeholder="<?=__('select a field','cf7-grid-layout')?>"></textarea>
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
