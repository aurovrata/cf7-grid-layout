/* script to monitor and validate plugin update */
(function($){
  'use strict';

  var $cf7sgPlugin = $('#cf7-grid-layout-update');
  if($cf7sgPlugin){
    $(document).on('wp-plugin-update-success',function(e, response){
      if('undefined' != typeof response['slug'] && 'cf7-grid-layout'==response['slug']){
        //check if the update requires a form update.
        $('.updated-message.notice-success p',$cf7sgPlugin).append('<span class="cf7sg-msg">'+cf7sg.msg+'<span class="spinner"></span></span>');
        //check form version.
        $.post(ajaxurl,{
            'action':'validate_cf7sg_version_update',
            'nonce' : cf7sg.nonce
          }, function(response){
          $('.cf7sg-msg', $cf7sgPlugin).html(response);
        }).fail(function(){
          $('.cf7sg-msg', $cf7sgPlugin).html(cf7sg.error);
        })
      }
    })
  }
})(jQuery)
