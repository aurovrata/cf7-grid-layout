<?php
global $post, $pagenow;
$cf7_key = $post->post_name;
$cf7_conditional='';
if(is_plugin_active('cf7-conditional-fields/contact-form-7-conditional-fields.php')) $cf7_conditional='cf7-conditional-group';
 ?>
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
      <div class="form-controls">
        <span class="form-switch">
          <span class="grid control-label active"><?= __('Single form', 'cf7-grid-layout');?></span>
          <div class="cf7sg-toggle-button style-r">
            <input type="checkbox" class="form-switch-checkbox checkbox"><div class="knobs"></div><div class="layer"></div>
          </div>
          <span class="multistep control-label"><?= __('Multistep form', 'cf7-grid-layout');?></span>
        </span>
        <span class="dashicons dashicons-edit form-control grid-control"></span>
        <span class="dashicons dashicons-no-alt form-control grid-control display-none"></span>
        <div class="grid-controls display-none">
          <label class="slider-form-label unique-mod">
            <?=__('Multistep slider form','cf7-grid-layout')?>
            <input type="checkbox" class="slider-form wrap-control" />
          </label>
          <a class="button template-form" href="javascript:void(0);"><?= __('Load form template', 'cf7-grid-layout');?></a>
          <a class="button clear-form" href="javascript:void(0);"><?= __('Clear the form', 'cf7-grid-layout');?></a>
        </div>
      </div>
      <div id="grid-form" class="<?=$cf7_conditional?>"></div>
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
        <?= esc_textarea( $form_obj->prop( 'form' ) );
        /** @since 2.8.3 rename codemirror textarea#wpcf7-form and initially popullate with form.  */?>
      </textarea>
    </div>
  </div>
</div>
<textarea id="wpcf7-form-hidden" name="wpcf7-form" class="hidden" data-config-field="form.body"><?= esc_textarea( $form_obj->prop( 'form' ) );?></textarea>
<!-- cf7sg - track embeded sub-forms -->
<input type="hidden" value="" id="cf7sg-embeded-forms" name="cf7sg-embeded-forms" />
<input type="hidden" value="" id="cf7sg-tabs-fields" name="cf7sg-tabs-fields" />
<input type="hidden" value="" id="cf7sg-table-fields" name="cf7sg-table-fields" />
<input type="hidden" value="" id="cf7sg-toggle-fields" name="cf7sg-toggle-fields" />
<input type="hidden" value="" id="cf7sg-tabbed-toggles" name="cf7sg-tabbed-toggles" />
<input type="hidden" value="" id="cf7sg-grouped-toggles" name="cf7sg-grouped-toggles" />
<!-- CF7 SG UI editor templates -->
<template id="grid-row">
  <div class="cf7sg-container">
    <div class="cf7sg-row">
      <div class="row-controls">
        <span class="dashicons dashicons-move row-control grid-control"></span>
        <span class="control-label">
          <span class="row-label display-none"><?= __('Row', 'cf7-grid-layout');?></span>
          <span class="table-label display-none"><?= __('Table', 'cf7-grid-layout');?></span>
          <span class="collapsible-label display-none"><?= __('Collapsible section', 'cf7-grid-layout');?></span>
        </span>
        <span class="dashicons dashicons-edit row-control grid-control"></span>
        <span class="dashicons dashicons-no-alt row-control grid-control"></span>
        <span class="dashicons dashicons-trash row-control grid-control"></span>
        <span class="dashicons dashicons-menu-alt row-control grid-control"></span>
        <span class="dashicons dashicons-editor-table row-control grid-control"></span>
        <span class="dashicons php-icon row-control display-none" data-field="" data-tag="" data-search=""></span>
        <div class="grid-controls">
          <label class="collapsible-row-label unique-mod">
            <?=__('Row collapsible','cf7-grid-layout')?>
            <input type="checkbox" class="collapsible-row wrap-control" />
          </label>
          <label class="tabs-row-label unique-mod">
            <?=__('Tabbed section','cf7-grid-layout')?>
            <input type="checkbox" class="tabs-row wrap-control" />
          </label>
          <label class="cf7-sg-hidden table-row-button table-control">
            <?=__('Button label','cf7-grid-layout')?>
            <input type="text" value="<?= __('Add Row','cf7-grid-layout')?>"/>
          </label>
          <label class="cf7-sg-hidden footer-row-label table-control unique-mod">
            <?=__('Row table footer','cf7-grid-layout')?>
            <input type="checkbox" class="footer-row" />
          </label>
        </div>
      </div>
      <div class="cf7sg-col full">
        <template class="inner-template">#grid-col</template>
      </div>
    </div>
  </div>
  <div class="add-item-button add-row-button">
    <span class="button add-row">
      <span class="dashicons dashicons-plus"></span>
      <span><?= __('Row', 'cf7-grid-layout');?></span>
    </span>
    <span class="button add-tab">
      <span class="dashicons dashicons-plus"></span>
      <span><?= __('Tab', 'cf7-grid-layout');?></span>
    </span>
    <span class="button add-table">
      <span class="dashicons dashicons-plus"></span>
      <span><?= __('Table', 'cf7-grid-layout');?></span>
    </span>
    <span class="button add-collapsible">
      <span class="dashicons dashicons-plus"></span>
      <span><?= __('Collapsible', 'cf7-grid-layout');?></span>
    </span>
    <span class="button add-slide display-none">
      <span class="dashicons dashicons-plus"></span>
      <span><?= __('Slide', 'cf7-grid-layout');?></span>
    </span>
  </div>
</template>
<template id="grid-cf7-forms">
  <div class="cf7sg-external-form" data-form="">
    <div class="ext-form-controls">
      <div class="row-controls">
        <span class="dashicons dashicons-move ext-form-control"></span>
        <span class="dashicons dashicons-trash ext-form-control"></span>
      </div>
      <select class="form-select">
        <option value=""><?=__('Select contact form 7','cf7-grid-layout')?></option>
        <?php
          $cf7_forms = get_posts(array(
            'post_type' => 'wpcf7_contact_form',
            'post_status'=> 'publish',
            'posts_per_page' => -1,
            'post__not_in' => array($form_obj->id())
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
    </div>
    <div class="cf7sg-external-form-content"></div>
  </div>
</template>
<template id="grid-collapsible">
  <div class="cf7sg-container cf7sg-collapsible">
    <div class="cf7sg-collapsible-title"><span class="cf7sg-title"></span></div>
    <div class="cf7sg-row">
      <div class="row-controls">
        <span class="dashicons dashicons-move row-control grid-control"></span>
        <span class="control-label"><?= __('Collapsible section', 'cf7-grid-layout');?></span>
        <span class="dashicons dashicons-edit row-control grid-control"></span>
        <span class="dashicons dashicons-no-alt row-control grid-control"></span>
        <span class="dashicons dashicons-trash row-control grid-control"></span>
        <div class="grid-controls collapsible">
          <label class="collapsible-row-label unique-mod">
            <?=__('Row collapsible','cf7-grid-layout')?>
            <input type="checkbox" checked="checked" class="collapsible-row wrap-control" />
          </label>
          <label class="collapsible-row-title unique-mod">
            <?=__('Section title','cf7-grid-layout')?>
            <input type="text" value=""/>
          </label>
          <label class="collapsible-row-toggle unique-mod">
            <?=__('Toggled section','cf7-grid-layout')?>
            <input type="checkbox" class="collapsible-toggle wrap-control" />
          </label>
        </div>
      </div>
      <div class="cf7sg-col full">
        <template class="inner-template">#grid-row</template>
      </div>
    </div>
  </div>
</template>
<template id="grid-collapsible-with-toggle">
  <div class="cf7sg-toggle-button style-r" data-on="<?=_x('Yes','toggle label','cf7-grid-layout')?>" data-off="<?=_x('No','toggle label','cf7-grid-layout')?>">
    <input type="checkbox" class="checkbox"><div class="knobs"></div><div class="layer"></div>
  </div>
</template>
<template id="grid-tabs">
  <div class="cf7-sg-tabs cf7sg-container" id="">
    <div class="cf7sg-row">
      <div class="row-controls">
        <span class="dashicons dashicons-move row-control grid-control"></span>
        <span class="control-label"><?= __('Tabbed section', 'cf7-grid-layout');?></span>
        <span class="dashicons dashicons-edit row-control grid-control"></span>
        <span class="dashicons dashicons-no-alt row-control grid-control"></span>
        <span class="dashicons dashicons-trash row-control grid-control"></span>
        <div class="grid-controls collapsible">
          <label class="tab-row-title unique-mod">
            <?=__('Tab label','cf7-grid-layout')?>
            <input type="text" value=""/>
          </label>
        </div>
      </div>
      <div class="cf7sg-col full">
        <input type="radio" class="display-none" id=""/>
        <div class="cf7-sg-panel">
          <label class="cf7sg-tab-title" for=""></label>
          <template class="inner-template">#grid-row</template>
        </div>
      </div>
    </div>
  </div>
</template>
<template id="grid-table-footer-row">
  <div class="cf7sg-row cf7-sg-table-footer-row">
    <div class="cf7sg-col full">
      <div class="grid-column-tip">
        <div class="cf7-field-tip cf7-field-inner">
          <p class="content" style=""><?= __('describe your table here','cf7-grid-layout')?></p>
          <input type="text" placeholder="<?= __('describe your table here','cf7-grid-layout')?>" style="display: none;" id="">
          <span class="dashicons dashicons-no-alt field-control" style="display: none;"></span>
        </div>
        <textarea class="grid-input cf7-sg-hidden table-footer-tip"></textarea>
      </div>
    </div>
  </div>
</template>
<template id="grid-multistep-container">
  <div class="cf7sg-container cf7sg-slider">
    <div class="cf7sg-row">
      <div class="cf7sg-col full cf7sg-slider-section" data-next="" data-prev="" data-submit="<?= __('Submit','cf7-grid-layout');?>" data-dots="false">
        <div class="cf7sg-container cf7sg-slide" id="">
          <div class="cf7sg-slide-title"><span class="cf7sg-title"></span></div>
          <div class="cf7sg-row">
            <div class="row-controls">
              <span class="dashicons dashicons-move row-control grid-control"></span>
              <span class="control-label">
                <span class="slide-label"><?= __('Slide', 'cf7-grid-layout');?></span>
              </span>
              <span class="dashicons dashicons-edit row-control grid-control"></span>
              <span class="dashicons php-icon row-control display-none" data-field="" data-tag="" data-search=""></span>
              <span class="dashicons dashicons-no-alt row-control grid-control"></span>
              <span class="dashicons dashicons-trash row-control grid-control"></span>
              <div class="grid-controls">
                <label class="cf7-sg-hidden table-row-button table-control">
                  <?=__('Slide title','cf7-grid-layout')?>
                  <input type="text" value=""/>
                </label>
              </div>
            </div>
            <div class="cf7sg-col full">
              <template class="inner-template">#grid-row</template>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<template id="grid-helper">
  <span class="dashicons dashicons-no-alt"></span>
  <span class="copy-helper"><?=__('Click to copy!','cf7-grid-layout')?></span>
  <p><?=__('Click-to-copy &amp; paste in your <em>functions.php</em> file.','cf7-grid-layout')?></p>
  <ul class="cf7sg-helper-list"></ul>
</template>
<template id="grid-js-helper">
  <span class="dashicons dashicons-no-alt"></span>
  <span class="copy-helper"><?=__('Click to copy!','cf7-grid-layout')?></span>
  <p class="js-help"><?= sprintf(__('Click-to-copy &amp; paste in<br/><em>&lt;theme folder&gt;/js/%s.js</em> file.','cf7-grid-layout'), $cf7_key);?></p>
  <ul class="cf7sg-helper-list"></ul>
</template>
<template id="grid-col">
  <div class="grid-column">
    <span class="dashicons dashicons-move column-control grid-control"></span>
    <span class="column-label column-control">
      <div class="column-offset centred-menu column-setting unset" style="--cf7sg-cm-val:0">
        <div class="cm-list">
          <div class="cm-item" data-cmi="0" data-cmv="">[.]</div>
          <div class="cm-item" data-cmi="1" data-cmv="offset-one">[1/12]</div>
          <div class="cm-item" data-cmi="2" data-cmv="offset-two">[1/6]</div>
          <div class="cm-item" data-cmi="3" data-cmv="offset-three">[1/4]</div>
          <div class="cm-item" data-cmi="4" data-cmv="offset-four">[1/3]</div>
          <div class="cm-item" data-cmi="5" data-cmv="offset-five">[5/12]</div>
          <div class="cm-item" data-cmi="6" data-cmv="offset-six">[1/2]</div>
          <div class="cm-item" data-cmi="7" data-cmv="offset-seven">[7/12]</div>
          <div class="cm-item" data-cmi="8" data-cmv="offset-eight">[2/3]</div>
          <div class="cm-item" data-cmi="9" data-cmv="offset-nine">[3/4]</div>
          <div class="cm-item" data-cmi="10" data-cmv="offset-ten">[5/6]</div>
          <div class="cm-item" data-cmi="11" data-cmv="offset-eleven">[11/12]</div>
        </div>
      </div>
      <span class="popup-helper  display-none"><?= __('Column offset','cf7-grid-layout')?></span>
      <div class="column-size centred-menu column-setting" style="--cf7sg-cm-val:11">
        <div class="cm-list">
          <div class="cm-item" data-cmi="0" data-cmv="one">1/12</div>
          <div class="cm-item" data-cmi="1" data-cmv="two">1/6</div>
          <div class="cm-item" data-cmi="2" data-cmv="one-fourth">1/4</div>
          <div class="cm-item" data-cmi="3" data-cmv="one-third">1/3</div>
          <div class="cm-item" data-cmi="4" data-cmv="five">5/12</div>
          <div class="cm-item" data-cmi="5" data-cmv="one-half">1/2</div>
          <div class="cm-item" data-cmi="6" data-cmv="seven">7/12</div>
          <div class="cm-item" data-cmi="7" data-cmv="two-thirds">2/3</div>
          <div class="cm-item" data-cmi="8" data-cmv="nine">3/4</div>
          <div class="cm-item" data-cmi="9" data-cmv="ten">5/6</div>
          <div class="cm-item" data-cmi="10" data-cmv="eleven">11/12</div>
          <div class="cm-item" data-cmi="11" data-cmv="full">Full</div>
        </div>
      </div>
      <span class="popup-helper display-none"><?= __('Column size','cf7-grid-layout')?></span>
      <?=__('Col','cf7-grid-layout')?>
    </span>
    <!-- <span class="dashicons dashicons-edit column-control grid-control"></span>
    <span class="dashicons dashicons-no-alt column-control grid-control"></span> -->
    <span class="dashicons dashicons-trash column-control grid-control"></span>
    <span class="dashicons php-icon column-control" data-field="" data-tag="" data-search="" style="display:none;"></span>
    <span class="js-icon column-control grid-control" style="display:none;"></span>
    <span class="dashicons dashicons-editor-code column-control grid-control"></span>
    <span class="dashicons dashicons-visibility column-control grid-control"></span>
    <span class="display-none cf7-conditional-group">
      <label for="cf7cfg"><?= __('Group', 'cf7-grid-layout');?></label><input type="text" id="cf7cfg"/>
      <span class="dashicons dashicons-no-alt"></span>
    </span>
    <div class="grid-controls">
      <!-- <a id="new-row" class="button make-grid column-control" href="javascript:void(0);"><?= __('Make grid', 'cf7-grid-layout');?></a>
      <a class="button external-form" href="javascript:void(0);"><?= __('Insert form', 'cf7-grid-layout');?></a>
      <label class="display-none accordion-label grouping-option"><input type="checkbox" name="grouping-option" class="accordion-rows column-control" /><?=__('Enable accordion','cf7-smart-grid')?></label><span class="popup display-none"><?= __('Group collapsible rows as jQuery accordion','cf7-smart-grid')?></span>
      <label class="display-none slider-label grouping-option"><input type="checkbox" name="grouping-option" class="slider-rows column-control" /><?=__('Enable slider','cf7-smart-grid')?></label><span class="popup display-none"><?= __('Convert collapsible rows into sides','cf7-smart-grid')?></span> -->
    </div>
    <div class="cf7-field-label cf7-field-inner">
      <p class="content"><?=__('Field label','cf7-grid-layout')?></p>
      <input type="text" placeholder="<?=__('Field label','cf7-grid-layout')?>"/>
    </div>
    <div class="cf7-field-type cf7-field-inner">
      <p class="content"><?=__('[select a field]','cf7-grid-layout')?></p>
      <textarea class="field-entry" placeholder="<?=__('select a field','cf7-grid-layout')?>"></textarea>
    </div>
    <div class="cf7-field-tip cf7-field-inner">
      <p class="content"><?=__('describe your field','cf7-grid-layout')?></p>
      <input type="text" placeholder="<?=__('describe your field here','cf7-grid-layout')?>" />
    </div>
    <textarea class="grid-input display-none"></textarea>
  </div>
  <div class="add-item-button add-field-button">
    <span class=button>
      <span class="dashicons dashicons-plus"></span>
      <span class="field-label display-none"><?= __('Add Field', 'cf7-grid-layout');?></span>
      <span class="row-label display-none"><?= __('Add Row', 'cf7-grid-layout');?></span>
    </span>
  </div>
</template>
<?php
