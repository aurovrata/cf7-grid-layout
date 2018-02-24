(function( $ ) {
	'use strict';
  $(document).ready( function(){
    var $cf7Form = $('form.wpcf7-form');
    $cf7Form.each(function(){
      if($(this).is('div.cf7_2_post form.wpcf7-form')){
        var nonce = $(this).closest('div.cf7_2_post').attr('id');
        $(this).on(nonce, function(){ //post my cf7 form plugin is ready.
          $('input.cf7sg-benchmark', $(this)).cf7sgBenchmark(false);
        });
      }
    });
    //event delgation.
    $cf7Form.on('change', 'input.cf7sg-benchmark', function(event){
      var $input = $(event.target);
      $input.cf7sgBenchmark(true);
    });
    $cf7Form.on('click', 'span.dashicons-no-alt', function(event){
      var $target = $(event.target);
      if($target.is('span.cf7sg-benchmark-warning .dashicons-no-alt')){
        $target.closest('span.cf7sg-benchmark-warning').hide();
        $target.remove();
      }
    });
  });
  $.fn.cf7sgBenchmark = function(warn){
    if(typeof warn === 'undefined') warn =false;
    if(!$(this).is('input.cf7sg-benchmark')){
      return $(this);
    }
    $(this).removeClass('cf7sg-benchmark-warning');
    $(this).next('span.cf7sg-benchmark-warning').remove();
    if('' == $(this).val()){
      return $(this);
    }
    var type = $(this).data('cf7sg-benchmark');
    var hidden = '';
    if($(this).is('input[type="hidden"]')){
      hidden = ' cf7sg-benchmark-hidden';
    }
    var msg = '<span class="cf7sg-benchmark-warning'+hidden+'"><span>warning<span class="dashicons dashicons-no-alt"></span></span><span class="cf7sg-benchmark-msg">'+$(this).data('cf7sg-benchmark-msg')+'</span></span>';
    switch(type){
      case 'above':
        var limit = $(this).data('cf7sg-benchmark-limit');
        if($(this).val() > limit ){
          $(this).after(msg);
          if(!warn) $(this).next('span.cf7sg-benchmark-warning').hide().find('.dashicons-no-alt').remove();
          $(this).addClass('cf7sg-benchmark-warning');
          if(hidden.length>0){
            $(this).trigger('cf7sg-benchmark-'+$(this).attr('name'));
          }
        }
        break;
      case 'below':
        var limit = $(this).data('cf7sg-benchmark-limit');
        if($(this).val() < limit ){
          $(this).after(msg);
          if(!warn) $(this).next('span.cf7sg-benchmark-warning').hide().find('.dashicons-no-alt').remove();
          $(this).addClass('cf7sg-benchmark-warning');
          if(hidden.length>0){
            $(this).trigger('cf7sg-benchmark-'+$(this).attr('name'));
          }
        }
        break;
      case 'range':
        var min = $(this).data('cf7sg-benchmark-min');
        var max = $(this).data('cf7sg-benchmark-max');
        if($(this).val() < min || $(this).val() > max ){
          $(this).after(msg);
          if(!warn) $(this).next('span.cf7sg-benchmark-warning').hide().find('.dashicons-no-alt').remove();
          $(this).addClass('cf7sg-benchmark-warning');
          if(hidden.length>0){
            $(this).trigger('cf7sg-benchmark-'+$(this).attr('name'));
          }
        }
        break;
    }

  }
  })( jQuery );
