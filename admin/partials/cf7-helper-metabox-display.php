<p><?=__('Click on a link to copy the helper snippet code and paste it in your <em>functions.php</em> file.','cf7-grid-layout')?></p>
<div id="helperdiv" class="postbox">
  <div class="postbox-header">
    <h2><span><?=__('Pre-form-loading hooks','cf7-grid-layout')?></span></h2>
      <div class="handle-actions hide-if-no-js">
        <button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text"><?=__('Toggle panel: Helper','cf7-grid-layout')?></span><span class="toggle-indicator" aria-hidden="true"></span></button>
    </div>
  </div>
  <div class="inside">
    <p><?=__('Hooks fired prior to the form loading','cf7-grid-layout')?></p>
    <ol class="cf7sg-hooks helper-list">
      <?php  require_once plugin_dir_path( __FILE__ ) .'helpers/cf7sg-pre-form-load.php'; ?>
    </ol>
  </div>
</div>
<div id="submithelperdiv" class="postbox">
  <div class="postbox-header">
    <h2 class="hndle ui-sortable-handle"><span><?=__('Post-form-submit hooks','cf7-grid-layout')?></span></h2>
    <div class="handle-actions hide-if-no-js">
      <button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text"><?=__('Toggle panel: Helper','cf7-grid-layout')?></span><span class="toggle-indicator" aria-hidden="true"></span></button>
    </div>
  </div>
  <div class="inside">
    <p><?=__('Hooks fired after the form is submitted','cf7-grid-layout')?></p>
    <ol class="cf7sg-hooks helper-list">
      <?php  require_once plugin_dir_path( __FILE__ ) .'helpers/cf7sg-post-form-submit.php'; ?>
    </ol>
  </div>
</div>
<div id="fieldhelperdiv" class="postbox" style="display:none;">
  <ul class="cf7sg-hooks helper-list">
    <?php do_action('cf7sg_ui_grid_helper_hooks') ?>
  </ul>
</div>
<!-- @since 3.3.0 -->
<div id="fieldhelperjs" class="postbox" style="display:none;">
  <ul class="cf7sg-hooks helper-list">
    <?php do_action('cf7sg_ui_grid_js_helper_hooks') ?>
  </ul>
</div>
<script type="text/javascript">
(function($){
	$(document).ready( function(){
    $('.helper-list li a', $('#helperdiv, #submithelperdiv')).each(function(){
      new Clipboard($(this)[0], {
        text: function(trigger) {
          var $target = $(trigger);
          var text = $target.data('cf72post');
          //get post slug
          var key = $('#post_name').val();
          text = text.replace(/\{\$form_key\}/gi, key);
          text = text.replace(/\{\$form_key_slug\}/gi, key.replace(/\-/g,'_'));
          text = text.replace(/\[dqt\]/gi, '"');
          return text;
        }
      });
    });
  });
})(jQuery)
</script>
<style>
.helper-list li{
  position: relative;
}
.helper-list li .helper::before {
    content: 'Click to copy!';
    display: none;
    position: absolute;
    top: -22px;
    left: 10px;
    background: #323232;
    color: white;
    padding: 2px 5px;
    border-radius: 3px;
    font-weight: bold;
}
.helper-list li .helper:hover::before {
    display: inline-block;
}
.helper-list li.no-post-my-form{
  display: none;
}
</style>
