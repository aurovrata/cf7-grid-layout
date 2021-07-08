(function($){
  let $tags = $('#select2-tags'), $select2 = $('#dynamic_select-select2');
  $tags.change(function(e){
    if($tags.is(':checked')){
      $select2.val('select2 tags').change();
    }
  });
  $('.list-style.dynamic_select').change(function(e){
    if($select2.is(':checked')){
      $tags.prop('disabled', false);
      $tags.closest('span').show();
    }else{
      $tags.prop('checked', false);
      $tags.prop('disabled', true);
      $tags.closest('span').hide();
      $select2.val('select2');
    }
  });
})(jQuery);
