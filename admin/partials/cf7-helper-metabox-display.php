<?php
//helper snippets
$post_my_form_only = ' no-post-my-form';
if(is_plugin_active( 'post-my-contact-form-7/cf7-2-post.php' )){
  $post_my_form_only='';
}
?>
<p>Click on a link to copy the helper snippet code and paste it in your <em>functions.php</em> file.</p>
<div id="helperdiv" class="postbox">
  <button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Helper</span><span class="toggle-indicator" aria-hidden="true"></span></button>
  <h2 class="hndle ui-sortable-handle"><span>Pre-form-loading hooks</span></h2>
  <div class="inside">
    <p>Hooks fired prior to the form loading</p>
    <ol class="cf7sg-hooks helper-list">
      <?php  require_once plugin_dir_path( __FILE__ ) .'helpers/cf7sg-pre-form-load.php'; ?>
    </ol>
  </div>
</div>
<div id="submithelperdiv" class="postbox">
  <button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Helper</span><span class="toggle-indicator" aria-hidden="true"></span></button>
  <h2 class="hndle ui-sortable-handle"><span>Post-form-submit hooks</span></h2>
  <div class="inside">
    <p>Hooks fired after the form is submitted</p>
    <ol class="cf7sg-hooks helper-list">
      <?php  require_once plugin_dir_path( __FILE__ ) .'helpers/cf7sg-post-form-submit.php'; ?>
    </ol>
  </div>
</div>
<div id="fieldhelperdiv" class="postbox" style="display:none;">
  <ul class="cf7sg-hooks helper-list">
    <?php require_once plugin_dir_path( __FILE__ ) .'helpers/cf7sg-form-fields.php'; ?>
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
          return text.replace(/\{\$form_key\}/gi, key);
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
