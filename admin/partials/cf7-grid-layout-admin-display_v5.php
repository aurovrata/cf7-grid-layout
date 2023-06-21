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
    <a class="button jstab cf7sg-cmtab<?php esc_attr_e($jscm_required.$editor_disable)?>" href="javascript:void(0);"><?php _e('Add custom JS','cf7-grid-layout')?></a>
    <div id="cf7-js-codemirror" class="display-none">
      <div  class="codemirror-theme"><?php _e('Editor theme:','cf7-grid-layout')?>
        <?php $user_js_theme = get_user_meta(get_current_user_id(),'_cf7sg_js_cm_theme', true); ?>
        <label>
          <input type="radio" value="light" name="cf7sg_js_codemirror_theme"<?php echo ('light'==$user_js_theme ? ' checked' : '')?>/><?php _e('Light','cf7-grid-layout')?>
        </label>
        <label>
          <input type="radio" value="dark" name="cf7sg_js_codemirror_theme" <?php echo ('dark'==$user_js_theme ? ' checked' : '')?>/><?php _e('Dark','cf7-grid-layout')?>
        </label>
        <input type="hidden" name="cf7sg_prev_js_file" class="prev-file" />
      </div>
      <textarea id="cf7-form-js" class="cf7-sg-hidden" data-file="<?php _e('File','cf7-grid-layout')?>&nbsp;&gt;&gt;&nbsp;/<?php esc_html_e($js_file)?>" name="cf7sg_js_file" data-form="">
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
    <a class="button csstab cf7sg-cmtab <?php esc_attr_e($csscm_required.$editor_disable)?>" href=""><?php _e('Add custom CSS','cf7-grid-layout')?></a>
    <div id="cf7-css-codemirror" class="display-none">
      <div  class="codemirror-theme"><?php _e('Editor theme:','cf7-grid-layout')?>
        <?php $user_css_theme = get_user_meta(get_current_user_id(),'_cf7sg_css_cm_theme', true); ?>
        <label>
          <input type="radio" value="light" name="cf7sg_css_codemirror_theme" <?php echo ('light'==$user_css_theme ? ' checked' : '')?>/><?php _e('Light','cf7-grid-layout')?>
        </label>
        <label>
          <input type="radio" value="dark" name="cf7sg_css_codemirror_theme" <?php echo ('dark'==$user_css_theme ? ' checked' : '')?>/><?php _e('Dark','cf7-grid-layout')?>
        </label>
        <input type="hidden" name="cf7sg_prev_css_file" class="prev-file" />
      </div>
      <textarea id="cf7-form-css" class="cf7-sg-hidden" data-file="<?php _e('File','cf7-grid-layout')?>&nbsp;&gt;&gt;&nbsp;/<?php esc_html_e($css_file)?>" name="cf7sg_css_file" data-form="">
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
      <li><a class="button" href="#cf7-editor-grid"><?php _e('Grid','cf7-grid-layout')?></a></li>
      <li><a class="button" href="#cf7-codemirror">&lt;HTML/&gt;</a></li>
    </ul>
    <div id="cf7-editor-grid" style="--cf7sg-col-label:'<?php _e('Col','cf7-grid-layout')?>'">
      <div class="cf7sg-hide-grid display-none">
        <p class="full-ui"><?php _e('Please toggle to full screen mode to edit this form','cf7-grid-layout')?></p>
        <p class="no-ui display-none"><?php _e('Your device screen is too small to edit this form, use the HTML editor instead.','cf7-grid-layout')?></p>
      </div >
      <div class="cf7sg-form-ctrls">
        <h3 class="cf7sg-form-label single"><?php _e('Single form','cf7-grid-layout')?></h2>
        <h3 class="cf7sg-form-label multiple display-none"><?php _e('Multistep slider form','cf7-grid-layout')?></h2>
        <span class="dashicons dashicons-admin-generic form-control grid-control"></span>
        <span class="dashicons dashicons-no-alt form-control grid-control display-none"></span>
        <div class="grid-controls display-none">
          <label class="slider-form-label unique-mod">
            <?php _e('Multistep slider form','cf7-grid-layout')?>
            <input type="checkbox" class="slider-form wrap-control" />
          </label>
          <a class="button cf7sg-template-form" href="javascript:void(0);"><?php _e('Load form template', 'cf7-grid-layout');?></a>
          <a class="button cf7sg-clear-form" href="javascript:void(0);"><?php _e('Clear the form', 'cf7-grid-layout');?></a>
        </div>
      </div>
      <div id="grid-form" class="<?php esc_attr_e($cf7_conditional)?>"></div>
    </div>
    <div id="cf7-codemirror">
      <div  class="codemirror-theme"><?php _e('Editor theme:','cf7-grid-layout')?>
        <?php $user_theme = get_user_meta(get_current_user_id(),'_cf7sg_cm_theme', true);?>
        <label>
          <input type="radio" value="light" name="cf7sg_codemirror_theme"<?php echo ('light'==$user_theme ? ' checked' : '')?>/><?php _e('Light','cf7-grid-layout')?>
        </label>
        <label>
          <input type="radio" value="dark" name="cf7sg_codemirror_theme" <?php echo ('dark'==$user_theme ? ' checked' : '')?>/><?php _e('Dark','cf7-grid-layout')?>
        </label>
      </div>
      <textarea id="wpcf7-form" class="cf7-sg-hidden codemirror-cf7-update">
        <?php echo esc_textarea( $form_obj->prop( 'form' ) );
        /** @since 2.8.3 rename codemirror textarea#wpcf7-form and initially popullate with form.  */?>
      </textarea>
    </div>
  </div>
</div>
<div id="cf7sg-grid-modal" class="display-none"></div>
<template id="cf7sg-grid-modal-tpl">
	<section class="grid-ctrls">
		<h3 class="cf7sg-uirs"><?php _e('Row Settings','cf7-grid-layout')?></h3>
		<h3 class="display-none cf7sg-uics"><?php _e('Column Settings','cf7-grid-layout')?></h3>
		<h3 class="display-none cf7sg-uiss"><?php _e('Slide Settings','cf7-grid-layout')?></h3>
		<h3 class="display-none cf7sg-uifs"><?php _e('Form Settings','cf7-grid-layout')?></h3>
    <div class="grid-controls">
			<div class="cf7sg-uirs">
					<input id="cf7sg-uirs-g" type="radio" name="cf7sg-uirst" checked="checked" class="cf7sg-uirs-tab"/>
					<label for="cf7sg-uirs-g"><span><?php _e('General','cf7-grid-layout')?></span></label>
					<div class="cf7sg-settab">
						<div class="cf7sg-uirs-label"><?php _e('Row type','cf7-grid-layout');?></div>
						<div class="cf7sg-row-type cf7sg-uirs-ctrl">
							<p><?php _e('Transform this row','cf7-grid-layout');?></p>
							<div class="cf7sg-switch-vertical">
								<input id="svrow" type="radio" name="cf7sg-row-switch" checked="checked" class="cf7sg-uirs-rowtype"/>
								<label for="svrow"><?php _e('Grid row','cf7-grid-layout')?></label>
								<input id="svtable" type="radio" name="cf7sg-row-switch" class="cf7sg-uirs-rowtype"/>
								<label for="svtable"><?php _e('Table row','cf7-grid-layout')?></label>
								<input id="svcoll" type="radio" name="cf7sg-row-switch" class="cf7sg-uirs-rowtype"/>
								<label for="svcoll"><?php _e('Collapsible row','cf7-grid-layout')?></label>
								<input id="svtabs" type="radio" name="cf7sg-row-switch"class="cf7sg-uirs-rowtype"/>
								<label for="svtabs"><?php _e('Tabbed section','cf7-grid-layout')?></label>
								<span class="cf7sg-toggle-outside">
									<span class="cf7sg-toggle-inside"></span>
								</span>
							</div>
						</div>
					</div>
			</div>
			<div class="cf7sg-uirs cf7sg-uirs-coll">
				<input id="cf7sg-uirs-coll" type="radio" name="cf7sg-uirst" class="cf7sg-uirs-tab"/>
				<label for="cf7sg-uirs-coll"><span><?php _e('Collapsible Row','cf7-grid-layout')?></span></label>
				<div class="cf7sg-settab">
					<div class="cf7sg-uirs-label"><?php _e('Section title','cf7-grid-layout')?></div>
					<div class="cf7sg-uirs-ctrl"><input type="text" value="" id="cf7sg-coll-title"/></div>
					<div class="cf7sg-uirs-label"><?php _e('Toggled','cf7-grid-layout')?></div>
					<div class="cf7sg-uirs-ctrl">
						<input id="cf7sg-uirs-coll-tgl" type="checkbox" class="collapsible-toggle" /><?php _e('enable togle switch','cf7-grid-layout')?>
						<p><em><?php _e('Fields within a toggled and collapsed section are disabled and not submitted.','cf7-grid-layout')?></em></p>
						<div class="cf7sg-uirs-toggled">
							<label><?php _e('Toggle labels','cf7-grid-layout')?>: <?php _e('On','cf7-grid-layout')?>-</label>
							<input size="6" type="text" value="Yes" id="cf7sg-is-toggled"/> | 
							<label><?php _e('off','cf7-grid-layout')?>-</label><input size="6" type="text" value="No" id="cf7sg-isnt-toggled"/>
						</div>
					</div>
				</div>
			</div>
			<div class="cf7sg-uirs cf7sg-uirs-table">
				<input id="cf7sg-uirs-table" type="radio" name="cf7sg-uirst" class="cf7sg-uirs-tab"/>
				<label for="cf7sg-uirs-table"><span><?php _e('Table Row','cf7-grid-layout')?></span></label>
				<div class="cf7sg-settab">
					<div class="cf7sg-uirs-label"><?php _e('Button label','cf7-grid-layout')?></div>
					<div class="cf7sg-uirs-ctrl"><input type="text" id="cf7sg-uirs-table-button" value="<?php _e('Add Row','cf7-grid-layout')?>"/></div>
					<div class="cf7sg-uirs-label"><?php _e('Table footer row','cf7-grid-layout')?></div>
					<div class="cf7sg-uirs-ctrl">
						<input type="checkbox" id="cf7sg-uirs-table-footer" /><?php _e('enable footer row','cf7-grid-layout')?>
						<p><em><?php _e('Adds an addtional row below the table and above the control button for additional content such as helper text.','cf7-grid-layout')?></em></p>
					</div>
				</div>
	    </div>
			<div class="cf7sg-uirs cf7sg-uirs-tabs">
				<input id="cf7sg-uirs-tabs"  type="radio" name="cf7sg-uirst" class="cf7sg-uirs-tab"/>
				<label for="cf7sg-uirs-tabs"><span><?php _e('Tabbed Section','cf7-grid-layout')?></span></label>
				<div class="cf7sg-settab">
					<div class="cf7sg-uirs-label"><?php _e('Tab label','cf7-grid-layout')?></div>
					<div class="cf7sg-uirs-ctrl"><input type="text" id="cf7sg-uirs-tab-label" value=""/></div>
				</div>
			</div>
			<div class="cf7sg-uis-col cf7sg-uirs display-none">
				<input id="cf7sg-uirs-col"  type="radio" name="cf7sg-uirst" class="cf7sg-uirs-tab"/>
				<label for="cf7sg-uirs-col"><span><?php _e('General','cf7-grid-layout')?></span></label>
				<div class="cf7sg-settab">
					<div class="cf7sg-uirs-label"><?php _e('Column offset','cf7-grid-layout');?></div>
					<div class="cf7sg-uirs-ctrl cf7sg-uics-ctrl">
						<select id="cf7sg-uisc-off">
							<option value=""><?php _e('no offset','cf7-grid-layout')?></option>
							<option value="offset-one">1/12</option>
							<option value="offset-two">1/6</option>
							<option value="offset-three"><?php _e('one quarter','cf7-grid-layout')?></option>
							<option value="offset-four"><?php _e('one third','cf7-grid-layout')?></option>
							<option value="offset-five">5/12</option>
							<option value="offset-six"><?php _e('one half','cf7-grid-layout')?></option>
							<option value="offset-seven">7/12</option>
							<option value="offset-eight"><?php _e('two thirds','cf7-grid-layout')?></option>
							<option value="offset-nine"><?php _e('three quarters','cf7-grid-layout')?></option>
							<option value="offset-ten">5/6</option>
							<option value="offset-eleven">11/12</option>
						</select>
					</div>
					<div class="cf7sg-uirs-label"><?php _e('Column width','cf7-grid-layout');?></div>
					<div class="cf7sg-uirs-ctrl cf7sg-uics-ctrl">
						<select id="cf7sg-uisc-size">
							<option value="one">1/12</option>
							<option value="two">1/6</option>
							<option value="one-fourth"><?php _e('one quarter','cf7-grid-layout')?></option>
							<option value="one-third"><?php _e('one third','cf7-grid-layout')?></option>
							<option value="five">5/12</option>
							<option value="one-half"><?php _e('one half','cf7-grid-layout')?></option>
							<option value="seven">7/12</option>
							<option value="two-thirds"><?php _e('two thirds','cf7-grid-layout')?></option>
							<option value="nine"><?php _e('three quarters','cf7-grid-layout')?></option>
							<option value="ten">5/6</option>
							<option value="eleven">11/12</option>
							<option value="full"><?php _e('full','cf7-grid-layout')?></option>
						</select>
					</div>
					<div class="cf7sg-uirs-label"><?php _e('Column layout','cf7-grid-layout');?></div>
					<div class="cf7sg-uirs-ctrl cf7sg-uics-ctrl">
						<p><?php _e('Transform this column','cf7-grid-layout');?></p>
						<div class="cf7sg-switch-vertical">
							<input id="svcfield" type="radio" name="cf7sg-col-switch" checked="checked" class="cf7sg-uirs-coltype"/>
							<label for="svcfield"><?php _e('Field cell','cf7-grid-layout')?></label>
							<input id="svcgrid" type="radio" name="cf7sg-col-switch" class="cf7sg-uirs-coltype"/>
							<label for="svcgrid"><?php _e('Inner grid','cf7-grid-layout')?></label>
							<input id="svcform" type="radio" name="cf7sg-col-switch" class="cf7sg-uirs-coltype"/>
							<label for="svcform"><?php _e('Modular form','cf7-grid-layout')?></label>
							<span class="cf7sg-toggle-outside">
								<span class="cf7sg-toggle-inside"></span>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="cf7sg-uirs cf7sg-uirs-condition">
				<input id="cf7sg-uirs-condition"  type="radio" name="cf7sg-uirst" class="cf7sg-uirs-tab"/>
				<label for="cf7sg-uirs-condition">
					<span class="cf7sg-uirs"><?php _e('Conditional Row','cf7-grid-layout')?></span>
					<span class="display-none cf7sg-uics"><?php _e('Conditional Column','cf7-grid-layout')?></span>
				</label>
				<div class="cf7sg-settab">
					<div class="cf7sg-uirs-label"><?php _e('Conditional','cf7-grid-layout');?></div>
					<div class="cf7sg-uirs-ctrl">
						<input type="checkbox" id="conditional-grp"/>
						<label for="conditional-grp">
							<span class="cf7sg-uirs"><?php _e('Make this row conditional','cf7-grid-layout');?></span>
							<span class="display-none cf7sg-uics"><?php _e('Make this column conditional','cf7-grid-layout');?></span>
						</label>
						<div class="cf7sg-uirs-cg">
							<label for="conditional-grp-name"><?php _e('Group name','cf7-grid-layout');?></label>
							<input type="text" id="conditional-grp-name"/>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="cf7sg-form-ctrls">
	<h3 class="cf7sg-uirs"><?php _e('Form Settings','cf7-grid-layout')?></h3>
    <div class="grid-controls">
			<div class="cf7sg-uirs cf7sg-uifs">
				<input id="cf7sg-uifs-g" type="radio" name="cf7sg-uifst" checked="true" class="cf7sg-uirs-tab"/>
				<label for="cf7sg-uifs-g"><span><?php _e('General','cf7-grid-layout')?></span></label>
				<div class="cf7sg-settab">
					<div class="cf7sg-uirs-label"><?php _e('Form type','cf7-grid-layout');?></div>
					<div class="cf7sg-uirs-ctrl">
						<div class="cf7sg-switch-vertical">
							<input id="svfsingle" type="radio" name="cf7sg-form-switch" checked="checked" class="cf7sg-uirs-formtype"/>
							<label for="svfsingle"><?php _e('Single form','cf7-grid-layout')?></label>
							<input id="svfmulti" type="radio" name="cf7sg-form-switch" class="cf7sg-uirs-formtype"/>
							<label for="svfmulti"><?php _e('Multistep slider form','cf7-grid-layout')?></label>
							<span class="cf7sg-toggle-outside">
								<span class="cf7sg-toggle-inside"></span>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="cf7sg-uirs cf7sg-multi-form display-none">
				<input id="cf7sg-uifs-m" type="radio" name="cf7sg-uifst" class="cf7sg-uirs-tab"/>
				<label for="cf7sg-uifs-m"><span><?php _e('Multistep','cf7-grid-layout')?></span></label>
				<div class="cf7sg-settab">
					<div class="cf7sg-uirs-label "><?php _e('Slide indicator','cf7-grid-layout');?></div>
					<div class="cf7sg-uirs-ctrl">
						<input type="checkbox" required="true" id="cf7sg-uifs-dots" checked="true"/>
						<em><?php _e('Enable the slide progress indicator','cf7-grid-layout');?></em>
					</div>
					<div class="cf7sg-uirs-label "><?php _e('Next slide button','cf7-grid-layout');?></div>
					<div class="cf7sg-uirs-ctrl">
						<input type="text" id="cf7sg-uifs-next" size="12"/>
						<p><em><?php _e('If left empty, defaults to an arrow icon','cf7-grid-layout');?></em></p>
					</div>
					<div class="cf7sg-uirs-label "><?php _e('Previous slide button','cf7-grid-layout');?></div>
					<div class="cf7sg-uirs-ctrl">
						<input type="text" id="cf7sg-uifs-prev" size="12"/>
						<p><em><?php _e('If left empty, defaults to an arrow icon','cf7-grid-layout');?></em></p>
					</div>
					<div class="cf7sg-uirs-label "><?php _e('Submit button','cf7-grid-layout');?></div>
					<div class="cf7sg-uirs-ctrl">
						<input type="text" required="true" id="cf7sg-uifs-submit" value="<?php _e('Submit','cf7-grid-layout');?>" size="12"/>
						<p><em><?php _e('On the last slide, the <em>next</em> slide button is replaced with the form submit button','cf7-grid-layout');?></em></p>
					</div>
				</div>
			</div>
		</div>
  </section>
	<section class="cf7sg-slide-ctrls">
		<h3 class="cf7sg-uirs"><?php _e('Slide Settings','cf7-grid-layout')?></h3>
    <div class="grid-controls">
		<div class="cf7sg-uirs cf7sg-uiss">
					<input id="cf7sg-uiss-g" type="radio" name="cf7sg-uisst" class="cf7sg-uirs-tab" checked="true"/>
					<label for="cf7sg-uiss-g"><span><?php _e('General','cf7-grid-layout')?></span></label>
					<div class="cf7sg-settab">
						<div class="cf7sg-uirs-label"><?php _e('Slide title','cf7-grid-layout');?></div>
						<div class="cf7sg-uirs-ctrl">
							<input type="text" id="cf7sg-slide-title"/>
							<p><em><?php _e("Optional, leave empty if you don't want a title displayed at the top of the slide.",'cf7-grid-layout');?></em></p>
						</div>
					</div>
			</div>
		</div>
  </section>
</template>
<textarea id="wpcf7-form-hidden" name="wpcf7-form" class="hidden" data-config-field="form.body"><?php echo esc_textarea( $form_obj->prop( 'form' ) );?></textarea>
<!-- cf7sg - track embeded sub-forms -->
<input type="hidden" value="" id="cf7sg-embeded-forms" name="cf7sg-embeded-forms" />
<input type="hidden" value="" id="cf7sg-tabs-fields" name="cf7sg-tabs-fields" />
<input type="hidden" value="" id="cf7sg-table-fields" name="cf7sg-table-fields" />
<input type="hidden" value="" id="cf7sg-toggle-fields" name="cf7sg-toggle-fields" />
<input type="hidden" value="" id="cf7sg-tabbed-toggles" name="cf7sg-tabbed-toggles" />
<input type="hidden" value="" id="cf7sg-grouped-toggles" name="cf7sg-grouped-toggles" />
<!-- CF7 SG UI editor templates -->
<template id="grid-row" data-table-button="<?php /*translators: public table button label */ _e('Add Row', 'cf7-grid-layout');?>">
  <div class="cf7sg-container">
    <div class="cf7sg-row">
      <div class="ui-grid-ctrls grid-ctrls cf7sg-ui-row">
        <span class="dashicons dashicons-move row-control grid-control"></span>
        <span class="control-label">
          <span class="row-label display-none"><?php _e('Row', 'cf7-grid-layout');?></span>
          <span class="table-label display-none"><?php _e('Table', 'cf7-grid-layout');?></span>
          <span class="collapsible-label display-none"><?php _e('Collapsible section', 'cf7-grid-layout');?></span>
        </span>
        <span class="dashicons dashicons-admin-generic row-control grid-control"></span>
        <span class="dashicons dashicons-trash row-control grid-control"></span>
        <span class="dashicons dashicons-plus row-control grid-control"></span>
        <span class="dashicons php-icon row-control display-none" data-field="" data-tag="" data-search=""></span>
      </div>
      <div class="cf7sg-col full">
        <template class="inner-template">#grid-col</template>
      </div>
    </div>
  </div>
  <div class="add-item-button add-row-button">
    <span class="button add-row">
      <span class="helper-tip display-none"><?php _e('Add a row', 'cf7-grid-layout');?></span>
      <span class="dashicons dashicons-plus"></span>
      <span><?php _e('Row', 'cf7-grid-layout');?></span>
    </span>
    <span class="button add-table">
      <span class="helper-tip display-none"><?php _e('Add a table of repetitive fields', 'cf7-grid-layout');?></span>
      <span class="dashicons dashicons-plus"></span>
      <span><?php _e('Table', 'cf7-grid-layout');?></span>
    </span>
    <span class="button add-collapsible">
      <span class="helper-tip display-none"><?php _e('Add a collapsible section', 'cf7-grid-layout');?></span>
      <span class="dashicons dashicons-plus"></span>
      <span><?php _e('Collapsible', 'cf7-grid-layout');?></span>
    </span>
    <span class="button add-tab">
      <span class="helper-tip display-none"><?php _e('Add a repetitive tabbular fields section', 'cf7-grid-layout');?></span>
      <span class="dashicons dashicons-plus"></span>
      <span><?php _e('Tab', 'cf7-grid-layout');?></span>
    </span>
    <span class="button add-slide display-none">
      <span class="helper-tip display-none"><?php _e('Add another slide', 'cf7-grid-layout');?></span>
      <span class="dashicons dashicons-plus"></span>
      <span><?php _e('Slide', 'cf7-grid-layout');?></span>
    </span>
  </div>
</template>
<template id="grid-cf7-forms">
  <div class="cf7sg-external-form" data-form="">
    <div class="ext-form-controls">
      <select class="cf7sg-form-select">
        <option value=""><?php _e('Select contact form 7','cf7-grid-layout')?></option>
        <?php
          $cf7_forms = get_posts(array(
            'post_type' => 'wpcf7_contact_form',
            'post_status'=> 'publish',
            'posts_per_page' => -1,
            'post__not_in' => array($form_obj->id())
          ));
					$cnt =0;
					if(!empty($cf7_forms)):
					foreach($cf7_forms as $cf7_form):
						$v = get_post_meta($cf7_form->ID, '_cf7sg_version', true);
						if(version_compare($v, CF7SG_VERSION_FORM_UPDATE, '<')) continue;
						$cnt++;
        ?>
        <option value="<?php echo $cf7_form->post_name ?>"><?php echo $cf7_form->post_title ?></option>
      <?php  
					endforeach;
            wp_reset_postdata();
          endif;
      ?>
      </select>
			<?php if(0===$cnt) echo '<p><em>'.__("No forms compatible with form version.",'cf7-grid-layout').'</em></p>';?>
    </div>
    <div class="cf7sg-external-form-content"></div>
  </div>
</template>
<template id="grid-collapsible">
  <div class="cf7sg-container cf7sg-collapsible">
		<input type="checkbox" id="" name="" value="1" class="cf7sg-collapsible-title" />
    <label for="" class="cf7sg-collapsible-title"><span class="cf7sg-title"></span></label>
    <div class="cf7sg-row">
      <div class="ui-grid-ctrls grid-ctrls cf7sg-coll-ctrls">
        <span class="dashicons dashicons-move row-control grid-control"></span>
        <span class="control-label">
					<span class="section-label"><?php _e('Collapsible section', 'cf7-grid-layout');?></span>
					<span class="section-title"></span>
				</span>
        <span class="dashicons dashicons-admin-generic row-control grid-control"></span>
        <span class="dashicons dashicons-trash row-control grid-control"></span>
      </div>
      <div class="cf7sg-col cf7sg-collapsible-inner full">
        <template class="inner-template">#grid-row</template>
      </div>
    </div>
  </div>
</template>
<template id="grid-collapsible-with-toggle">
	<span data-on="<?php echo _x('Yes','toggle label','cf7-grid-layout')?>" data-off="<?php echo _x('No','toggle label','cf7-grid-layout')?>" class="cf7sg-toggle-button"></span>
</template>
<template id="grid-tabs">
  <div class="cf7-sg-tabs cf7sg-container" id="">
    <div class="cf7sg-row">
      <div class="ui-grid-ctrls grid-ctrls cf7sg-tabs-ctrls">
        <span class="dashicons dashicons-move row-control grid-control"></span>
        <span class="control-label">
					<span class="section-label"><?php _e('Tabbed section', 'cf7-grid-layout');?></span>
					<span class="section-title"></span>
				</span>
        <span class="dashicons dashicons-admin-generic row-control grid-control"></span>
        <span class="dashicons dashicons-trash row-control grid-control"></span>
      </div>
			<input type="radio" class="display-none cf7sg-tab-radio" name="" value="tab-1"/>
      <div class="cf7sg-col cf7sg-tabs-panel full">
          <label class="cf7sg-tab-title" for="" data-tplt="title (cnt)" data-title=""></label>
          <template class="inner-template">#grid-row</template>
      </div>
    </div>
  </div>
</template>
<template id="grid-table-footer-row">
  <div class="cf7sg-row cf7-sg-table-footer-row">
    <div class="cf7sg-col full">
      <div class="grid-column-tip">
        <div class="cf7-field-tip cf7-field-inner">
          <p class="content" style=""><?php _e('describe your table here','cf7-grid-layout')?></p>
          <input type="text" placeholder="<?php _e('describe your table here','cf7-grid-layout')?>" style="display: none;" id="">
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
      <div class="cf7sg-col full cf7sg-slider-section" data-next="" data-prev="" data-submit="<?php _e('Submit','cf7-grid-layout');?>" data-dots="true">
        <div class="cf7sg-container cf7sg-slide" id="">
          <div class="cf7sg-slide-title"><span class="cf7sg-title"></span></div>
          <div class="cf7sg-row">
            <div class="grid-ctrls cf7sg-slide-ctrls">
              <span class="dashicons dashicons-move row-control grid-control"></span>
              <span class="control-label">
                <span class="slide-label"><?php /* translator: the # will be replaced by a number */ _e('Slide #', 'cf7-grid-layout');?></span>
                <span class="slide-title"></span>
              </span>
              <span class="dashicons dashicons-admin-generic row-control grid-control"></span>
              <span class="dashicons php-icon row-control display-none" data-field="" data-tag="" data-search=""></span>
              <span class="dashicons dashicons-no-alt row-control grid-control"></span>
              <span class="dashicons dashicons-trash row-control grid-control"></span>
            </div>
            <div class="cf7sg-col full">
              <template class="inner-template">#grid-row</template>
            </div>
          </div>
        </div>
				<div class="add-item-button add-slide-button">
					<span class="button add-slide">
						<span class="helper-tip display-none"><?php _e('Add another slide', 'cf7-grid-layout');?></span>
						<span class="dashicons dashicons-plus"></span>
						<span><?php _e('Slide', 'cf7-grid-layout');?></span>
					</span>
				</div>
      </div>
    </div>
  </div>
</template>
<template id="grid-helper">
  <span class="dashicons dashicons-no-alt"></span>
  <span class="copy-helper"><?php _e('Click to copy!','cf7-grid-layout')?></span>
  <p><?php _e('Click-to-copy &amp; paste in your <em>functions.php</em> file.','cf7-grid-layout')?></p>
  <ul class="cf7sg-helper-list"></ul>
</template>
<template id="grid-js-helper">
  <span class="dashicons dashicons-no-alt"></span>
  <span class="copy-helper"><?php _e('Click to copy!','cf7-grid-layout')?></span>
  <p class="js-help"><?php echo wp_kses_post( sprintf(__('Click-to-copy &amp; paste in<br/><em>&lt;theme folder&gt;/js/%s.js</em> file.','cf7-grid-layout'), $cf7_key));?></p>
  <ul class="cf7sg-helper-list"></ul>
</template>
<template id="grid-col">
  <div class="grid-column ui-grid-ctrls cf7sg-ui-col">
    <span class="dashicons dashicons-move column-control grid-control"></span>
    <span class="cf7sg-responsive">
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
        <span class="popup-helper  display-none"><?php _e('Column offset','cf7-grid-layout')?></span>
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
        <span class="popup-helper display-none"><?php _e('Column size','cf7-grid-layout')?></span>
      </span>
      <span class="dashicons dashicons-admin-generic column-control grid-control"></span>
      <span class="dashicons dashicons-trash column-control grid-control"></span>
      <span class="dashicons php-icon column-control" data-field="" data-tag="" data-search="" style="display:none;"></span>
      <span class="js-icon column-control grid-control" style="display:none;"></span>
      <span class="dashicons dashicons-editor-code column-control grid-control"></span>
    </span>
    <span class="dashicons dashicons-ellipsis column-control grid-control display-none"></span>
    <div class="cf7-field-label cf7-field-inner">
      <p class="content"><?php _e('Field label','cf7-grid-layout')?></p>
      <input type="text" placeholder="<?php _e('Field label','cf7-grid-layout')?>"/>
    </div>
    <div class="cf7-field-type cf7-field-inner">
      <p class="content"><?php _e('[select a field]','cf7-grid-layout')?></p>
      <textarea class="cf7sg-field-entry" placeholder="<?php _e('select a field','cf7-grid-layout')?>"></textarea>
    </div>
    <div class="cf7-field-tip cf7-field-inner">
      <p class="content"><?php _e('describe your field','cf7-grid-layout')?></p>
      <input type="text" placeholder="<?php _e('describe your field here','cf7-grid-layout')?>" />
    </div>
    <textarea class="grid-input display-none"></textarea>
  </div>
  <div class="add-item-button add-field-button">
    <span class=button>
      <span class="dashicons dashicons-plus"></span>
      <span class="field-label display-none"><?php _e('Add Field', 'cf7-grid-layout');?></span>
      <span class="row-label display-none"><?php _e('Add Row', 'cf7-grid-layout');?></span>
    </span>
  </div>
</template>
<?php
