<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://syllogic.in
 * @since      1.0.0
 *
 * @package    CF7SmartGrid
 * @subpackage CF7SmartGrid/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post, $pagenow;
$cf7_key         = $post->post_name;
$cf7_conditional = '';
if ( is_plugin_active( 'cf7-conditional-fields/contact-form-7-conditional-fields.php' ) ) {
	$cf7_conditional = 'cf7-conditional-group';
}
?>
<?php require_once plugin_dir_path( __FILE__ ) . 'helpers/cf7sg-js-events.php'; ?>
<div id="editors">
	<?php
	$editor_disable  = ( 'post-new.php' === $pagenow ) ? ' disabled' : '';
	$js_file         = str_replace( ABSPATH, '', get_stylesheet_directory() . "/js/{$cf7_key}.js" );
	$js_file_exists  = file_exists( ABSPATH . $js_file );
	$jscm_required   = $js_file_exists ? ' required' : '';
	$jscm_required  .= $editor_disable;
	$css_file        = str_replace( ABSPATH, '', get_stylesheet_directory() . "/css/{$cf7_key}.css" );
	$css_file_exists = file_exists( ABSPATH . $css_file );
	$csscm_required  = $css_file_exists ? ' required' : '';
	$csscm_required .= $editor_disable;
	?>

	<div id="optional-editors">
	<a class="button jstab cf7sg-cmtab<?php echo esc_attr( $jscm_required ); ?>" href="javascript:void(0);"><?php esc_html_e( 'Add custom JS', 'cf7-grid-layout' ); ?></a>
	<div id="cf7-js-codemirror" class="display-none">
		<div  class="codemirror-theme"><?php esc_html_e( 'Editor theme:', 'cf7-grid-layout' ); ?>
		<?php $user_js_theme = get_user_meta( get_current_user_id(), '_cf7sg_js_cm_theme', true ); ?>
		<label>
			<input type="radio" value="light" name="cf7sg_js_codemirror_theme"<?php echo ( 'light' === $user_js_theme ? ' checked' : '' ); ?>/><?php esc_html_e( 'Light', 'cf7-grid-layout' ); ?>
		</label>
		<label>
			<input type="radio" value="dark" name="cf7sg_js_codemirror_theme" <?php echo ( 'dark' === $user_js_theme ? ' checked' : '' ); ?>/><?php esc_html_e( 'Dark', 'cf7-grid-layout' ); ?>
		</label>
		<input type="hidden" name="cf7sg_prev_js_file" class="prev-file" />
		</div>
		<textarea id="cf7-form-js" class="cf7-sg-hidden" data-file="<?php esc_html_e( 'File', 'cf7-grid-layout' ); ?>&nbsp;&gt;&gt;&nbsp;/<?php echo esc_attr( $js_file ); ?>" name="cf7sg_js_file" data-form="">
		<?php
		if ( $js_file_exists ) {
			$file = file_get_contents( ABSPATH . $js_file );
			echo esc_textarea( $file );
		} else {
			do_action( 'cf7sg_default_custom_js_template', $cf7_key );
		}
		?>
		</textarea>
	</div>
	<a class="button csstab cf7sg-cmtab <?php echo esc_attr( $csscm_required ); ?>" href=""><?php esc_html_e( 'Add custom CSS', 'cf7-grid-layout' ); ?></a>
	<div id="cf7-css-codemirror" class="display-none">
		<div  class="codemirror-theme"><?php esc_html_e( 'Editor theme:', 'cf7-grid-layout' ); ?>
		<?php $user_css_theme = get_user_meta( get_current_user_id(), '_cf7sg_css_cm_theme', true ); ?>
		<label>
			<input type="radio" value="light" name="cf7sg_css_codemirror_theme" <?php echo ( 'light' === $user_css_theme ? ' checked' : '' ); ?>/><?php esc_html_e( 'Light', 'cf7-grid-layout' ); ?>
		</label>
		<label>
			<input type="radio" value="dark" name="cf7sg_css_codemirror_theme" <?php echo ( 'dark' === $user_css_theme ? ' checked' : '' ); ?>/><?php esc_html_e( 'Dark', 'cf7-grid-layout' ); ?>
		</label>
		<input type="hidden" name="cf7sg_prev_css_file" class="prev-file" />
		</div>
		<textarea id="cf7-form-css" class="cf7-sg-hidden" data-file="<?php esc_html_e( 'File', 'cf7-grid-layout' ); ?>&nbsp;&gt;&gt;&nbsp;/<?php echo esc_attr( $css_file ); ?>" name="cf7sg_css_file" data-form="">
		<?php
		if ( $css_file_exists ) {
			$file = file_get_contents( ABSPATH . $css_file );
			echo esc_textarea( $file );
		} else {
			do_action( 'cf7sg_default_custom_css_template', $cf7_key );
		}
		?>
		</textarea>
	</div>
	</div>

	<div id="form-editor-tabs">
	<ul>
		<li><a class="button" href="#cf7-editor-grid"><?php esc_html_e( 'Grid', 'cf7-grid-layout' ); ?></a></li>
		<li><a class="button" href="#cf7-codemirror">&lt;HTML/&gt;</a></li>
	</ul>
	<div id="cf7-editor-grid" style="--cf7sg-col-label:'<?php /* esc_attr_e( 'Col', 'cf7-grid-layout' ); */ ?>'">
		<div class="cf7sg-hide-grid display-none">
		<p class="full-ui"><?php esc_html_e( 'Please toggle to full screen mode to edit this form', 'cf7-grid-layout' ); ?></p>
		<p class="no-ui display-none"><?php esc_html_e( 'Your device screen is too small to edit this form, use the HTML editor instead.', 'cf7-grid-layout' ); ?></p>
		</div >
		<div class="cf7sg-form-ctrls">
		<h3 class="cf7sg-form-label single"><?php esc_html_e( 'Single form', 'cf7-grid-layout' ); ?></h2>
		<h3 class="cf7sg-form-label multiple display-none"><?php esc_html_e( 'Multistep slider form', 'cf7-grid-layout' ); ?></h2>
		<span class="dashicons dashicons-admin-generic form-control grid-control"></span>
		<span class="dashicons dashicons-no-alt form-control grid-control display-none"></span>
		<div class="grid-controls display-none">
			<label class="slider-form-label unique-mod">
			<?php esc_html_e( 'Multistep slider form', 'cf7-grid-layout' ); ?>
			<input type="checkbox" class="slider-form wrap-control" />
			</label>
			<a class="button cf7sg-template-form" href="javascript:void(0);"><?php esc_html_e( 'Load form template', 'cf7-grid-layout' ); ?></a>
			<a class="button cf7sg-clear-form" href="javascript:void(0);"><?php esc_html_e( 'Clear the form', 'cf7-grid-layout' ); ?></a>
		</div>
		</div>
		<div id="grid-form" class="<?php echo esc_attr( $cf7_conditional ); ?>"></div>
	</div>
	<div id="cf7-codemirror">
		<div  class="codemirror-theme"><?php esc_html_e( 'Editor theme:', 'cf7-grid-layout' ); ?>
		<?php $user_theme = get_user_meta( get_current_user_id(), '_cf7sg_cm_theme', true ); ?>
		<label>
			<input type="radio" value="light" name="cf7sg_codemirror_theme"<?php echo ( 'light' === $user_theme ? ' checked' : '' ); ?>/><?php esc_html_e( 'Light', 'cf7-grid-layout' ); ?>
		</label>
		<label>
			<input type="radio" value="dark" name="cf7sg_codemirror_theme" <?php echo ( 'dark' === $user_theme ? ' checked' : '' ); ?>/><?php esc_html_e( 'Dark', 'cf7-grid-layout' ); ?>
		</label>
		</div>
		<textarea id="wpcf7-form" class="cf7-sg-hidden codemirror-cf7-update">
		<?php
		echo esc_textarea( $form_obj->prop( 'form' ) );
		/** NB @since 2.8.3 rename codemirror textarea#wpcf7-form and initially popullate with form.  */
		?>
		</textarea>
	</div>
	</div>
</div>
<div id="cf7sg-grid-modal" class="display-none"></div>
<template id="grid-default-form">
	<div class="cf7sg-container">
		<div class="cf7sg-row">
			<div class="cf7sg-col sgc-12"></div>
		</div>
	</div>
	<div class="cf7sg-container">
	<div class="cf7sg-row">
		<div class="cf7sg-col sgc-12 sgc-lg-4 sgc-md-4 sgc-sm-4 sgc-lg-off-8 sgc-md-off-8 sgc-sm-off-8 cf7sgfocus">
			<div class="cf7sg-field"><label for=""></label>[submit "<?php /* translators: form submit field label */ esc_attr_e( 'Submit', 'cf7-grid-layout' ); ?>"]
				<p class="info-tip"></p>
			</div>
		</div>
	</div>
</div>
</template>
<template id="cf7sg-grid-modal-tpl">
	<section class="grid-ctrls">
		<h3 class="cf7sg-uirs"><?php esc_html_e( 'Row Settings', 'cf7-grid-layout' ); ?></h3>
		<h3 class="display-none cf7sg-uics"><?php esc_html_e( 'Column Settings', 'cf7-grid-layout' ); ?></h3>
		<h3 class="display-none cf7sg-uiss"><?php esc_html_e( 'Slide Settings', 'cf7-grid-layout' ); ?></h3>
		<h3 class="display-none cf7sg-uifs"><?php esc_html_e( 'Form Settings', 'cf7-grid-layout' ); ?></h3>
		<div class="grid-controls">
			<div class="cf7sg-uirs">
					<input id="cf7sg-uirs-g" type="radio" name="cf7sg-uirst" checked="checked" class="cf7sg-uirs-tab"/>
					<label for="cf7sg-uirs-g"><span><?php esc_html_e( 'General', 'cf7-grid-layout' ); ?></span></label>
					<div class="cf7sg-settab">
						<div class="cf7sg-uirs-label"><?php esc_html_e( 'Row type', 'cf7-grid-layout' ); ?></div>
						<div class="cf7sg-row-type cf7sg-uirs-ctrl">
							<p><?php esc_html_e( 'Transform this row', 'cf7-grid-layout' ); ?></p>
							<div class="cf7sg-switch-vertical">
								<input id="svrow" type="radio" name="cf7sg-row-switch" checked="checked" class="cf7sg-uirs-rowtype"/>
								<label for="svrow"><?php esc_html_e( 'Grid row', 'cf7-grid-layout' ); ?></label>
								<input id="svtable" type="radio" name="cf7sg-row-switch" class="cf7sg-uirs-rowtype"/>
								<label for="svtable"><?php esc_html_e( 'Table row', 'cf7-grid-layout' ); ?></label>
								<input id="svcoll" type="radio" name="cf7sg-row-switch" class="cf7sg-uirs-rowtype"/>
								<label for="svcoll"><?php esc_html_e( 'Collapsible row', 'cf7-grid-layout' ); ?></label>
								<input id="svtabs" type="radio" name="cf7sg-row-switch"class="cf7sg-uirs-rowtype"/>
								<label for="svtabs"><?php esc_html_e( 'Tabbed section', 'cf7-grid-layout' ); ?></label>
								<span class="cf7sg-toggle-outside">
									<span class="cf7sg-toggle-inside"></span>
								</span>
							</div>
						</div>
					</div>
			</div>
			<div class="cf7sg-uirs cf7sg-uirs-coll">
				<input id="cf7sg-uirs-coll" type="radio" name="cf7sg-uirst" class="cf7sg-uirs-tab"/>
				<label for="cf7sg-uirs-coll"><span><?php esc_html_e( 'Collapsible Row', 'cf7-grid-layout' ); ?></span></label>
				<div class="cf7sg-settab">
					<div class="cf7sg-uirs-label"><?php esc_html_e( 'Section title', 'cf7-grid-layout' ); ?></div>
					<div class="cf7sg-uirs-ctrl"><input type="text" value="" id="cf7sg-coll-title"/></div>
					<div class="cf7sg-uirs-label"><?php esc_html_e( 'Toggled', 'cf7-grid-layout' ); ?></div>
					<div class="cf7sg-uirs-ctrl">
						<input id="cf7sg-uirs-coll-tgl" type="checkbox" class="collapsible-toggle" /><?php esc_html_e( 'enable togle switch', 'cf7-grid-layout' ); ?>
						<p class="important-info"><em><?php esc_html_e( 'NOTE: Fields within a toggled collapsed sections are conditional, even if marked as required. Only if the user toggles the section open are the fields submitted and validated.', 'cf7-grid-layout' ); ?></em></p>
						<div class="cf7sg-uirs-toggled">
							<label><?php esc_html_e( 'Toggle labels', 'cf7-grid-layout' ); ?>: <?php esc_html_e( 'On', 'cf7-grid-layout' ); ?>-</label>
							<input size="6" type="text" value="Yes" id="cf7sg-is-toggled"/> | 
							<label><?php esc_html_e( 'off', 'cf7-grid-layout' ); ?>-</label><input size="6" type="text" value="No" id="cf7sg-isnt-toggled"/>
						</div>
					</div>
				</div>
			</div>
			<div class="cf7sg-uirs cf7sg-uirs-table">
				<input id="cf7sg-uirs-table" type="radio" name="cf7sg-uirst" class="cf7sg-uirs-tab"/>
				<label for="cf7sg-uirs-table"><span><?php esc_html_e( 'Table Row', 'cf7-grid-layout' ); ?></span></label>
				<div class="cf7sg-settab">
					<div class="cf7sg-uirs-label"><?php esc_html_e( 'Button label', 'cf7-grid-layout' ); ?></div>
					<div class="cf7sg-uirs-ctrl"><input type="text" id="cf7sg-uirs-table-button" value="<?php esc_html_e( 'Add Row', 'cf7-grid-layout' ); ?>"/></div>
					<div class="cf7sg-uirs-label"><?php esc_html_e( 'Table footer row', 'cf7-grid-layout' ); ?></div>
					<div class="cf7sg-uirs-ctrl">
						<input type="checkbox" id="cf7sg-uirs-table-footer" /><?php esc_html_e( 'enable footer row', 'cf7-grid-layout' ); ?>
						<p><em><?php esc_html_e( 'Adds an addtional row below the table and above the control button for additional content such as helper text.', 'cf7-grid-layout' ); ?></em></p>
					</div>
				</div>
		</div>
			<div class="cf7sg-uirs cf7sg-uirs-tabs">
				<input id="cf7sg-uirs-tabs"  type="radio" name="cf7sg-uirst" class="cf7sg-uirs-tab"/>
				<label for="cf7sg-uirs-tabs"><span><?php esc_html_e( 'Tabbed Section', 'cf7-grid-layout' ); ?></span></label>
				<div class="cf7sg-settab">
					<div class="cf7sg-uirs-label"><?php esc_html_e( 'Tab label', 'cf7-grid-layout' ); ?></div>
					<div class="cf7sg-uirs-ctrl"><input type="text" id="cf7sg-uirs-tab-label" value=""/></div>
				</div>
			</div>
			<div class="cf7sg-uis-col cf7sg-uirs display-none">
				<input id="cf7sg-uirs-col"  type="radio" name="cf7sg-uirst" class="cf7sg-uirs-tab"/>
				<label for="cf7sg-uirs-col"><span><?php esc_html_e( 'General', 'cf7-grid-layout' ); ?></span></label>
				<div class="cf7sg-settab">
					<div class="cf7sg-uirs-label"><?php esc_html_e( 'Column offset', 'cf7-grid-layout' ); ?></div>
					<div class="cf7sg-uirs-ctrl cf7sg-uics-ctrl">
						<select id="cf7sg-uisc-off">
							<option value=""><?php esc_html_e( 'no offset', 'cf7-grid-layout' ); ?></option>
							<option value="off-1">1</option>
							<option value="off-2">2</option>
							<option value="off-3">3 (1/4)</option>
							<option value="off-4">4 (1/3)</option>
							<option value="off-5">5</option>
							<option value="off-6">6 (1/2)</option>
							<option value="off-7">7</option>
							<option value="off-8">8 (2/3)</option>
							<option value="off-9">9 (3/4)</option>
							<option value="off-10">10</option>
							<option value="off-11">11</option>
						</select>
					</div>
					<div class="cf7sg-uirs-label"><?php esc_html_e( 'Column width', 'cf7-grid-layout' ); ?></div>
					<div class="cf7sg-uirs-ctrl cf7sg-uics-ctrl">
						<select id="cf7sg-uisc-size">
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3 (1/4)</option>
							<option value="4">4 (1/3)</option>
							<option value="5">5</option>
							<option value="6">6 (1/2)</option>
							<option value="7">7</option>
							<option value="8">8 (2/3)</option>
							<option value="9">9 (3/4)</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12 (<?php esc_html_e( 'full', 'cf7-grid-layout' ); ?>)</option>
						</select>
					</div>
					<div class="cf7sg-uirs-label"><?php esc_html_e( 'Column layout', 'cf7-grid-layout' ); ?></div>
					<div class="cf7sg-uirs-ctrl cf7sg-uics-ctrl">
						<p><?php esc_html_e( 'Transform this column', 'cf7-grid-layout' ); ?></p>
						<div class="cf7sg-switch-vertical">
							<input id="svcfield" type="radio" name="cf7sg-col-switch" checked="checked" class="cf7sg-uirs-coltype"/>
							<label for="svcfield"><?php esc_html_e( 'Field cell', 'cf7-grid-layout' ); ?></label>
							<input id="svcgrid" type="radio" name="cf7sg-col-switch" class="cf7sg-uirs-coltype"/>
							<label for="svcgrid"><?php esc_html_e( 'Inner grid', 'cf7-grid-layout' ); ?></label>
							<input id="svcform" type="radio" name="cf7sg-col-switch" class="cf7sg-uirs-coltype"/>
							<label for="svcform"><?php esc_html_e( 'Modular form', 'cf7-grid-layout' ); ?></label>
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
					<span class="cf7sg-uirs"><?php esc_html_e( 'Conditional Row', 'cf7-grid-layout' ); ?></span>
					<span class="display-none cf7sg-uics"><?php esc_html_e( 'Conditional Column', 'cf7-grid-layout' ); ?></span>
				</label>
				<div class="cf7sg-settab">
					<div class="cf7sg-uirs-label"><?php esc_html_e( 'Conditional', 'cf7-grid-layout' ); ?></div>
					<div class="cf7sg-uirs-ctrl">
						<input type="checkbox" id="conditional-grp"/>
						<label for="conditional-grp">
							<span class="cf7sg-uirs"><?php esc_html_e( 'Make this row conditional', 'cf7-grid-layout' ); ?></span>
							<span class="display-none cf7sg-uics"><?php esc_html_e( 'Make this column conditional', 'cf7-grid-layout' ); ?></span>
						</label>
						<div class="cf7sg-uirs-cg">
							<label for="conditional-grp-name"><?php esc_html_e( 'Group name', 'cf7-grid-layout' ); ?></label>
							<input type="text" id="conditional-grp-name"/>
						</div>
					</div>
				</div>
			</div>
			<div class="cf7sg-uirs cf7sg-uirs-hooks display-none">
				<input id="cf7sg-uirs-adv"  type="radio" name="cf7sg-uirst" class="cf7sg-uirs-tab"/>
				<label for="cf7sg-uirs-adv"><span><?php /* translators: custom php hooks */ esc_html_e( 'Custom', 'cf7-grid-layout' ); ?></span></label>
				<div class="cf7sg-settab">
					<div class="cf7sg-uirs-label cf7sg-uirs-php"><?php esc_html_e( 'PHP Filters', 'cf7-grid-layout' ); ?></div>
					<div class="cf7sg-uirs-ctrl cf7sg-uics-ctrl cf7sg-uirs-php">
						<span class="copy-helper"><?php esc_html_e( 'Click to copy!', 'cf7-grid-layout' ); ?></span>
						<p><?php echo wp_kses( __( 'Click-to-copy &amp; paste in your <em>functions.php</em> file.', 'cf7-grid-layout' ), array( 'em' => 1 ) ); ?></p>
						<ul class="cf7sg-helper-list"></ul>
					</div>
					<div class="cf7sg-uirs-label cf7sg-uirs-js"  style="display:none"><?php esc_html_e( 'JS Filters', 'cf7-grid-layout' ); ?></div>
					<div class="cf7sg-uirs-ctrl cf7sg-uics-ctrl cf7sg-uirs-js" style="display:none">
						<span class="copy-helper"><?php esc_html_e( 'Click to copy!', 'cf7-grid-layout' ); ?></span>
						<p class="js-help">
							<?php
							$jspath = "&lt;theme folder&gt;/js/{$cf7_key}.js";
							/* translators: path to file*/
							echo sprintf( esc_html__( 'Click-to-copy &amp; paste in file: %s', 'cf7-grid-layout' ), '<em>' . esc_attr( $jspath ) . '</em>' );
							?>
						</p>
						<ul class="cf7sg-helper-list"></ul>
					</div>
				</div>
			</div>
			<div class="cf7sg-uirs cf7sg-uirs-align">
				<input id="cf7sg-uirs-align"  type="radio" name="cf7sg-uirst" class="cf7sg-uirs-tab"/>
				<label for="cf7sg-uirs-align">
					<span class="cf7sg-uirs"><?php esc_html_e( 'Align Row', 'cf7-grid-layout' ); ?></span>
					<!-- <span class="display-none cf7sg-uics"><?php esc_html_e( 'Conditional Column', 'cf7-grid-layout' ); ?></span> -->
				</label>
				<div class="cf7sg-settab">
					<div class="cf7sg-uirs-label"><?php esc_html_e( 'Alignment', 'cf7-grid-layout' ); ?></div>
					<div class="cf7sg-uirs-ctrl">
						<p><?php esc_html_e( 'Vertical row alignment', 'cf7-grid-layout' ); ?></p>
						<div class="cf7sg-switch-vertical alignment">
							<input id="svtop" type="radio" name="cf7sg-valign-switch" checked="checked" class="cf7sg-uirs-valign"/>
							<label for="svtop"><?php esc_html_e( 'Top', 'cf7-grid-layout' ); ?></label>
							<input id="svmiddle" type="radio" name="cf7sg-valign-switch" class="cf7sg-uirs-valign"/>
							<label for="svmiddle"><?php esc_html_e( 'Middle', 'cf7-grid-layout' ); ?></label>
							<input id="svbottom" type="radio" name="cf7sg-valign-switch" class="cf7sg-uirs-valign"/>
							<label for="svbottom"><?php esc_html_e( 'Bottom', 'cf7-grid-layout' ); ?></label>
							<span class="cf7sg-toggle-outside">
								<span class="cf7sg-toggle-inside"></span>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="cf7sg-form-ctrls">
	<h3 class="cf7sg-uirs"><?php esc_html_e( 'Form Settings', 'cf7-grid-layout' ); ?></h3>
	<div class="grid-controls">
			<div class="cf7sg-uirs cf7sg-uifs">
				<input id="cf7sg-uifs-g" type="radio" name="cf7sg-uifst" checked="true" class="cf7sg-uirs-tab"/>
				<label for="cf7sg-uifs-g"><span><?php esc_html_e( 'General', 'cf7-grid-layout' ); ?></span></label>
				<div class="cf7sg-settab">
					<div class="cf7sg-uirs-label"><?php esc_html_e( 'Form type', 'cf7-grid-layout' ); ?></div>
					<div class="cf7sg-uirs-ctrl">
						<div class="cf7sg-switch-vertical">
							<input id="svfsingle" type="radio" name="cf7sg-form-switch" checked="checked" class="cf7sg-uirs-formtype"/>
							<label for="svfsingle"><?php esc_html_e( 'Single form', 'cf7-grid-layout' ); ?></label>
							<input id="svfmulti" type="radio" name="cf7sg-form-switch" class="cf7sg-uirs-formtype"/>
							<label for="svfmulti"><?php esc_html_e( 'Multistep slider form', 'cf7-grid-layout' ); ?></label>
							<span class="cf7sg-toggle-outside">
								<span class="cf7sg-toggle-inside"></span>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="cf7sg-uirs cf7sg-multi-form display-none">
				<input id="cf7sg-uifs-m" type="radio" name="cf7sg-uifst" class="cf7sg-uirs-tab"/>
				<label for="cf7sg-uifs-m"><span><?php esc_html_e( 'Multistep', 'cf7-grid-layout' ); ?></span></label>
				<div class="cf7sg-settab">
					<div class="cf7sg-uirs-label "><?php esc_html_e( 'Slide indicator', 'cf7-grid-layout' ); ?></div>
					<div class="cf7sg-uirs-ctrl">
						<input type="checkbox" required="true" id="cf7sg-uifs-dots" checked="true"/>
						<em><?php esc_html_e( 'Enable the slide progress indicator', 'cf7-grid-layout' ); ?></em>
					</div>
					<div class="cf7sg-uirs-label "><?php esc_html_e( 'Next slide button', 'cf7-grid-layout' ); ?></div>
					<div class="cf7sg-uirs-ctrl">
						<input type="text" id="cf7sg-uifs-next" size="12"/>
						<p><em><?php esc_html_e( 'If left empty, defaults to an arrow icon', 'cf7-grid-layout' ); ?></em></p>
					</div>
					<div class="cf7sg-uirs-label "><?php esc_html_e( 'Previous slide button', 'cf7-grid-layout' ); ?></div>
					<div class="cf7sg-uirs-ctrl">
						<input type="text" id="cf7sg-uifs-prev" size="12"/>
						<p><em><?php esc_html_e( 'If left empty, defaults to an arrow icon', 'cf7-grid-layout' ); ?></em></p>
					</div>
					<div class="cf7sg-uirs-label "><?php esc_html_e( 'Submit button', 'cf7-grid-layout' ); ?></div>
					<div class="cf7sg-uirs-ctrl">
						<input type="text" required="true" id="cf7sg-uifs-submit" value="<?php esc_html_e( 'Submit', 'cf7-grid-layout' ); ?>" size="12"/>
						<p>
							<em>
							<?php
							echo wp_kses( __( 'On the last slide, the <em>next</em> slide button is replaced with the form submit button', 'cf7-grid-layout' ), array( 'em' => 1 ) );
							?>
							</em>
						</p>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="cf7sg-slide-ctrls">
		<h3 class="cf7sg-uirs"><?php esc_html_e( 'Slide Settings', 'cf7-grid-layout' ); ?></h3>
	<div class="grid-controls">
		<div class="cf7sg-uirs cf7sg-uiss">
					<input id="cf7sg-uiss-g" type="radio" name="cf7sg-uisst" class="cf7sg-uirs-tab" checked="true"/>
					<label for="cf7sg-uiss-g"><span><?php esc_html_e( 'General', 'cf7-grid-layout' ); ?></span></label>
					<div class="cf7sg-settab">
						<div class="cf7sg-uirs-label"><?php esc_html_e( 'Slide title', 'cf7-grid-layout' ); ?></div>
						<div class="cf7sg-uirs-ctrl">
							<input type="text" id="cf7sg-slide-title"/>
							<p><em><?php esc_html_e( "Optional, leave empty if you don't want a title displayed at the top of the slide.", 'cf7-grid-layout' ); ?></em></p>
						</div>
					</div>
			</div>
		</div>
	</section>
</template>
<textarea id="wpcf7-form-hidden" name="wpcf7-form" class="hidden" data-config-field="form.body"><?php echo esc_textarea( $form_obj->prop( 'form' ) ); ?></textarea>
<!-- cf7sg - track embeded sub-forms -->
<input type="hidden" value="" id="cf7sg-embeded-forms" name="cf7sg-embeded-forms" />
<input type="hidden" value="" id="cf7sg-tabs-fields" name="cf7sg-tabs-fields" />
<input type="hidden" value="" id="cf7sg-table-fields" name="cf7sg-table-fields" />
<input type="hidden" value="" id="cf7sg-toggle-fields" name="cf7sg-toggle-fields" />
<input type="hidden" value="" id="cf7sg-tabbed-toggles" name="cf7sg-tabbed-toggles" />
<input type="hidden" value="" id="cf7sg-grouped-toggles" name="cf7sg-grouped-toggles" />
<!-- CF7 SG UI editor templates -->
<template id="grid-row" data-table-button="<?php /*translators: public table button label */ esc_attr_e( 'Add Row', 'cf7-grid-layout' ); ?>">
	<div class="cf7sg-container">
	<div class="cf7sg-row">
		<div class="ui-grid-ctrls grid-ctrls cf7sg-ui-row">
		<span class="dashicons dashicons-move row-control grid-control"></span>
		<span class="control-label">
			<span class="row-label display-none"><?php esc_html_e( 'Row', 'cf7-grid-layout' ); ?></span>
			<span class="table-label display-none"><?php esc_html_e( 'Table', 'cf7-grid-layout' ); ?></span>
			<span class="collapsible-label display-none"><?php esc_html_e( 'Collapsible section', 'cf7-grid-layout' ); ?></span>
		</span>
		<span class="php-icon cf7sg-hook row-control display-none" data-field="" data-tag="" data-search=""></span>
		<span class="dashicons dashicons-admin-generic row-control grid-control"></span>
		<span class="dashicons dashicons-trash row-control grid-control"></span>
		<span class="dashicons dashicons-plus row-control grid-control"></span>
		</div>
		<div class="cf7sg-col sgc-12">
		<template class="inner-template">#grid-col</template>
		</div>
	</div>
	</div>
	<div class="add-item-button add-row-button">
	<span class="button add-row">
		<span class="helper-tip display-none"><?php esc_html_e( 'Add a row', 'cf7-grid-layout' ); ?></span>
		<span class="dashicons dashicons-plus"></span>
		<span><?php esc_html_e( 'Row', 'cf7-grid-layout' ); ?></span>
	</span>
	<span class="button add-table">
		<span class="helper-tip display-none"><?php esc_html_e( 'Add a table of repetitive fields', 'cf7-grid-layout' ); ?></span>
		<span class="dashicons dashicons-plus"></span>
		<span><?php esc_html_e( 'Table', 'cf7-grid-layout' ); ?></span>
	</span>
	<span class="button add-collapsible">
		<span class="helper-tip display-none"><?php esc_html_e( 'Add a collapsible section', 'cf7-grid-layout' ); ?></span>
		<span class="dashicons dashicons-plus"></span>
		<span><?php esc_html_e( 'Collapsible', 'cf7-grid-layout' ); ?></span>
	</span>
	<span class="button add-tab">
		<span class="helper-tip display-none"><?php esc_html_e( 'Add a repetitive tabbular fields section', 'cf7-grid-layout' ); ?></span>
		<span class="dashicons dashicons-plus"></span>
		<span><?php esc_html_e( 'Tab', 'cf7-grid-layout' ); ?></span>
	</span>
	<span class="button add-slide display-none">
		<span class="helper-tip display-none"><?php esc_html_e( 'Add another slide', 'cf7-grid-layout' ); ?></span>
		<span class="dashicons dashicons-plus"></span>
		<span><?php esc_html_e( 'Slide', 'cf7-grid-layout' ); ?></span>
	</span>
	</div>
</template>
<template id="grid-cf7-forms">
	<div class="cf7sg-external-form" data-form="">
	<div class="ext-form-controls">
		<select class="cf7sg-form-select">
		<option value=""><?php esc_html_e( 'Select contact form 7', 'cf7-grid-layout' ); ?></option>
		<?php
		$cf7_forms       = get_posts(
			array(
				'post_type'      => 'wpcf7_contact_form',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'post__not_in'   => array( $form_obj->id() ),
			)
		);
					$cnt = 0;
		if ( ! empty( $cf7_forms ) ) :
			foreach ( $cf7_forms as $cf7_form ) :
						$v = get_post_meta( $cf7_form->ID, '_cf7sg_version', true );
				if ( version_compare( $v, CF7SG_VERSION_FORM_UPDATE, '<' ) ) {
					continue;
				}
						$cnt++;
				?>
			<option value="<?php echo esc_attr( $cf7_form->post_name ); ?>"><?php echo esc_attr( $cf7_form->post_title ); ?></option>
				<?php
			endforeach;
						wp_reset_postdata();
			endif;
		?>
		</select>
			<?php
			if ( 0 === $cnt ) {
				echo '<p><em>' . esc_html__( 'No forms compatible with form version.', 'cf7-grid-layout' ) . '</em></p>';}
			?>
	</div>
	<div class="cf7sg-external-form-content"></div>
	</div>
</template>
<template id="grid-collapsible">
	<div class="cf7sg-container cf7sg-collapsible">
		<input type="checkbox" id="" value="1" class="cf7sg-collapsible-title" />
	<label for="" class="cf7sg-collapsible-title"><span class="cf7sg-title"></span><span class="cf7sg-toggle dashicons dashicons-insert"></span></label>
	<div class="cf7sg-row">
		<div class="ui-grid-ctrls grid-ctrls cf7sg-coll-ctrls">
		<span class="dashicons dashicons-move row-control grid-control"></span>
		<span class="control-label">
					<span class="section-label"><?php esc_html_e( 'Collapsible section', 'cf7-grid-layout' ); ?></span>
					<span class="section-title"></span>
				</span>
		<span class="dashicons dashicons-admin-generic row-control grid-control"></span>
		<span class="dashicons dashicons-trash row-control grid-control"></span>
		</div>
		<div class="cf7sg-col cf7sg-collapsible-inner sgc-12">
		<template class="inner-template">#grid-row</template>
		</div>
	</div>
	</div>
</template>
<template id="grid-collapsible-with-toggle">
	<span data-on="<?php echo esc_html_x( 'Yes', 'toggle label', 'cf7-grid-layout' ); ?>" data-off="<?php echo esc_html_x( 'No', 'toggle label', 'cf7-grid-layout' ); ?>" class="cf7sg-toggle"></span>
</template>
<template id="grid-tabs">
	<div class="cf7-sg-tabs cf7sg-container" id="">
	<div class="cf7sg-row">
		<div class="ui-grid-ctrls grid-ctrls cf7sg-tabs-ctrls">
		<span class="dashicons dashicons-move row-control grid-control"></span>
		<span class="control-label">
					<span class="section-label"><?php esc_html_e( 'Tabbed section', 'cf7-grid-layout' ); ?></span>
					<span class="section-title"></span>
				</span>
		<span class="dashicons dashicons-admin-generic row-control grid-control"></span>
		<span class="dashicons dashicons-trash row-control grid-control"></span>
		</div>
			<input type="radio" class="display-none cf7sg-tab-radio" id="" name="lbl-" value="tab-1" checked/>
			<ul class="cf7sg-tab-title" data-tplt="title (cnt)" data-title=""><li><label for=""></label><span class="dashicons dashicons-dismiss cf7sg-tab-dismis"></span></li></ul>
			<a class="cf7sg-tab-button button"><?php esc_html_e( 'Add tab', 'cf7-grid-layout' ); ?></a>
			<div class="cf7sg-col cf7sg-tabs-panel sgc-12">
				<template class="inner-template">#grid-row</template>
		</div>
	</div>
	</div>
</template>
<template id="grid-table-footer-row">
	<div class="cf7sg-row cf7-sg-table-footer-row">
	<div class="cf7sg-col sgc-12">
		<div class="grid-column-tip">
		<div class="cf7-field-tip cf7-field-inner">
			<p class="content" style=""><?php esc_html_e( 'describe your table here', 'cf7-grid-layout' ); ?></p>
			<input type="text" placeholder="<?php esc_attr_e( 'describe your table here', 'cf7-grid-layout' ); ?>" style="display: none;" id="">
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
		<div class="cf7sg-col sgc-12 cf7sg-slider-section" data-next="" data-prev="" data-submit="<?php esc_attr_e( 'Submit', 'cf7-grid-layout' ); ?>" data-dots="true">
		<div class="cf7sg-container cf7sg-slide" id="">
			<div class="cf7sg-slide-title"><span class="cf7sg-title"></span></div>
			<div class="cf7sg-row">
			<div class="grid-ctrls cf7sg-slide-ctrls">
				<span class="dashicons dashicons-move row-control grid-control"></span>
				<span class="control-label">
				<span class="slide-label"><?php /* translators: the # will be replaced by a number */ esc_html_e( 'Slide #', 'cf7-grid-layout' ); ?></span>
				<span class="slide-title"></span>
				</span>
				<span class="php-icon cf7sg-hook row-control display-none" data-field="" data-tag="" data-search=""></span>
				<span class="dashicons dashicons-admin-generic row-control grid-control"></span>
				<span class="dashicons dashicons-no-alt row-control grid-control"></span>
				<span class="dashicons dashicons-trash row-control grid-control"></span>
			</div>
			<div class="cf7sg-col sgc-12">
				<template class="inner-template">#grid-row</template>
			</div>
			</div>
		</div>
				<div class="add-item-button add-slide-button">
					<span class="button add-slide">
						<span class="helper-tip display-none"><?php esc_html_e( 'Add another slide', 'cf7-grid-layout' ); ?></span>
						<span class="dashicons dashicons-plus"></span>
						<span><?php esc_html_e( 'Slide', 'cf7-grid-layout' ); ?></span>
					</span>
				</div>
		</div>
	</div>
	</div>
</template>
<template id="grid-col">
	<div class="grid-column ui-grid-ctrls cf7sg-ui-col">
	<span class="dashicons dashicons-move column-control grid-control"></span>
	<span class="php-icon cf7sg-hook column-control" data-field="" data-tag="" data-search="" style="display:none;"></span>
	<span class="js-icon cf7sg-hook column-control grid-control" style="display:none;"></span>
	<span class="cf7sg-responsive">
		<span class="column-label column-control">
		<div class="column-offset centred-menu column-setting unset" style="--cf7sg-cm-val:0">
			<div class="cm-list">
			<div class="cm-item" data-cmi="0" data-cmv="">[.]</div>
			<div class="cm-item" data-cmi="1" data-cmv="off-1">[1]</div>
			<div class="cm-item" data-cmi="2" data-cmv="off-two">[2]</div>
			<div class="cm-item" data-cmi="3" data-cmv="off-3">[3]</div>
			<div class="cm-item" data-cmi="4" data-cmv="off-4">[4]</div>
			<div class="cm-item" data-cmi="5" data-cmv="off-5">[5]</div>
			<div class="cm-item" data-cmi="6" data-cmv="off-6">[6]</div>
			<div class="cm-item" data-cmi="7" data-cmv="off-7">[7]</div>
			<div class="cm-item" data-cmi="8" data-cmv="off-8">[8]</div>
			<div class="cm-item" data-cmi="9" data-cmv="off-9">[9]</div>
			<div class="cm-item" data-cmi="10" data-cmv="off-10">[10]</div>
			<div class="cm-item" data-cmi="11" data-cmv="off-11">[11]</div>
			</div>
		</div>
		<span class="popup-helper  display-none"><?php esc_html_e( 'Column offset', 'cf7-grid-layout' ); ?></span>
		<div class="column-size centred-menu column-setting" style="--cf7sg-cm-val:11">
			<div class="cm-list">
			<div class="cm-item" data-cmi="0" data-cmv="1">1</div>
			<div class="cm-item" data-cmi="1" data-cmv="2">2</div>
			<div class="cm-item" data-cmi="2" data-cmv="3">3 (1/4)</div>
			<div class="cm-item" data-cmi="3" data-cmv="4">4 (1/3)</div>
			<div class="cm-item" data-cmi="4" data-cmv="5">5</div>
			<div class="cm-item" data-cmi="5" data-cmv="6">6 (1/2)</div>
			<div class="cm-item" data-cmi="6" data-cmv="7">7</div>
			<div class="cm-item" data-cmi="7" data-cmv="8">8 (2/3)</div>
			<div class="cm-item" data-cmi="8" data-cmv="9">9 (3/4)</div>
			<div class="cm-item" data-cmi="9" data-cmv="10">10</div>
			<div class="cm-item" data-cmi="10" data-cmv="11">11</div>
			<div class="cm-item" data-cmi="11" data-cmv="12">12 (<?php esc_html_e( 'full', 'cf7-grid-layout' ); ?>)</div>
			</div>
		</div>
		<span class="popup-helper display-none"><?php esc_html_e( 'Column size', 'cf7-grid-layout' ); ?></span>
		</span>
		<span class="dashicons dashicons-admin-generic column-control grid-control"></span>
		<span class="dashicons dashicons-trash column-control grid-control"></span>
		<span class="dashicons dashicons-editor-code column-control grid-control"></span>
	</span>
	<span class="dashicons dashicons-ellipsis column-control grid-control display-none"></span>
	<div class="cf7-field-label cf7-field-inner">
		<p class="content"><?php esc_html_e( 'Field label', 'cf7-grid-layout' ); ?></p>
		<input type="text" placeholder="<?php esc_html_e( 'Field label', 'cf7-grid-layout' ); ?>"/>
	</div>
	<div class="cf7-field-type cf7-field-inner">
		<p class="content"><?php esc_html_e( '[select a field]', 'cf7-grid-layout' ); ?></p>
		<textarea class="cf7sg-field-entry" placeholder="<?php esc_html_e( 'select a field', 'cf7-grid-layout' ); ?>"></textarea>
	</div>
	<div class="cf7-field-tip cf7-field-inner">
		<p class="content"><?php esc_html_e( 'describe your field', 'cf7-grid-layout' ); ?></p>
		<input type="text" placeholder="<?php esc_html_e( 'describe your field here', 'cf7-grid-layout' ); ?>" />
	</div>
	<textarea class="grid-input display-none"></textarea>
		<span class="cf7sg-col-add dashicons dashicons-table-col-after"></span>
	</div>
	<div class="add-item-button add-field-button">
	<span class=button>
		<span class="dashicons dashicons-plus"></span>
		<span class="field-label display-none"><?php esc_html_e( 'Add Field', 'cf7-grid-layout' ); ?></span>
		<span class="row-label display-none"><?php esc_html_e( 'Add Row', 'cf7-grid-layout' ); ?></span>
	</span>
	</div>
</template>
<?php
