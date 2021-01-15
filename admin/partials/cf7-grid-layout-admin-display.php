<?php
global $post, $pagenow;
$cf7_key = $post->post_name;
 ?>
<h2><?php echo esc_html( __cf7sg( 'Form' ) ); ?></h2>
<div id="top-tags">
<?php
  $tag_generator = WPCF7_TagGenerator::get_instance();
  $tag_generator->print_buttons();
?>
</div>
<?php require_once plugin_dir_path( __FILE__ ) .'helpers/cf7sg-js-events.php'; ?>
<div id="editors">
  <?php
  $js_file = str_replace(ABSPATH, '', get_stylesheet_directory()."/js/{$cf7_key}.js");
  $js_file_exists = file_exists(ABSPATH.$js_file);
  $jscm_required = $js_file_exists ? ' required':'';
  $editor_disable = ('post-new.php'==$pagenow) ? ' disabled':'';
  $css_file = str_replace(ABSPATH, '', get_stylesheet_directory()."/css/{$cf7_key}.css");
  $css_file_exists = file_exists(ABSPATH.$css_file);
  $csscm_required = $css_file_exists ? ' required':'';
  ?>

  <div id="optional-editors">
    <a class="button jstab cf7sg-cmtab<?=$jscm_required.$editor_disable?>" href="javascript:void(0);"><?=__('Add custom JS','cf7-grid-layout')?></a>
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
    <a class="button csstab cf7sg-cmtab<?=$csscm_required.$editor_disable?>" href=""><?=__('Add custom CSS','cf7-grid-layout')?></a>
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
<textarea id="wpcf7-form-hidden" name="wpcf7-form" class="hidden" data-config-field="form.body"><?= esc_textarea( $form_post->prop( 'form' ) );?></textarea>
<!-- cf7sg - track embeded sub-forms -->
<input type="hidden" value="" id="cf7sg-embeded-forms" name="cf7sg-embeded-forms" />
<input type="hidden" value="" id="cf7sg-tabs-fields" name="cf7sg-tabs-fields" />
<input type="hidden" value="" id="cf7sg-table-fields" name="cf7sg-table-fields" />
<input type="hidden" value="" id="cf7sg-toggle-fields" name="cf7sg-toggle-fields" />
<input type="hidden" value="" id="cf7sg-tabbed-toggles" name="cf7sg-tabbed-toggles" />
<input type="hidden" value="" id="cf7sg-grouped-toggles" name="cf7sg-grouped-toggles" />
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
        <option value="full" selected><?=__('full width','cf7-grid-layout')?></option>
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
