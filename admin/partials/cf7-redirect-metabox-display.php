<?php
/**
* display metabox for redirect.
* @since 4.6.0.
*/
$redirect = get_post_meta($post->ID, '_cf7sg_page_redirect',true);
$cache = get_post_meta($post->ID, '_cf7sg_cache_redirect_data',true);
$unit = MINUTE_IN_SECONDS;
$time = 5;
$disabled = ' disabled';
$checked ='';
if(!empty($cache) && is_array($cache)){
  $checked = 'checked';
  $time = $cache[0];
  $unit = $cache[1];
  $disabled = '';
}
 ?>
<div>
  <label for="cf7-page-redirect"><?= __('Redirect on successful form submission to','cf7-grid-layout')?>:
  <?php wp_dropdown_pages(array(
    'id'=>'cf7-page-redirect',
    'name'=>'cf7sg_page_redirect',
    'selected'=>$redirect,
    'show_option_none'=>__('Select a page','cf7-grid-layout'),
  ));?>
  </label>
  <div>
    <label for="is-cf7sg-cached">
      <input type="checkbox" id="is-cf7sg-cached" name="cache_cf7sg_submit" <?=$checked?>/>
      <?= __('Cache the submitted form data for','cf7-grid-layout')?>
    </label>
    <fieldset id="cf7sg-cache-limit" <?=$disabled?>>
      <label for="cf7sg-cached-time">
        <input type="number" value="<?=$time?>" id="cf7sg-cached-time" name="cf7sg_cached_time"/>
      </label>
      <label for="cf7sg-cached-unit">
        <select id="cf7sg-cached-unit" name="cf7sg_cached_unit">
          <option value="<?=MINUTE_IN_SECONDS?>" <?=($unit==MINUTE_IN_SECONDS)?'selected':''?>>
            <?=__('minute', 'cf7-grid-layout')?>
          </option>
          <option value="<?=HOUR_IN_SECONDS?>" <?=($unit==HOUR_IN_SECONDS)?'selected':''?>>
            <?=__('hour', 'cf7-grid-layout')?>
          </option>
          <option value="<?=DAY_IN_SECONDS?>" <?=($unit==DAY_IN_SECONDS)?'selected':''?>>
            <?=__('day', 'cf7-grid-layout')?>
          </option>
        </select>
      </label>
    </fieldset>
    <p>
      <?= __('If you need access to the submitted data on the redirected page, then check this option.  It will cache the submitted form fields and files as a <a href="https://developer.wordpress.org/apis/handbook/transients/">transient</a>, allowing you to retrieve it on the redirected page with the following id:', 'cf7-grid-layout');?><code>'_cf7sg_'.$_GET['cf7sg']</code>
    </p>
    <script type="text/javascript">
    (function($){
      'use strict';
      $('#is-cf7sg-cached').change(function(){
        if(this.checked) $('#cf7sg-cache-limit').prop('disabled', false);
        else $('#cf7sg-cache-limit').prop('disabled', true);
      })
    })(jQuery)
    </script>
  </div>
</div>
