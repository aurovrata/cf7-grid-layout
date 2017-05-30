if(<?echo $json_value?> !== undefined && <?echo $json_value?> instanceof Array){
  var $field = $('span.<?echo $field ?>', <? echo $js_form ?>);
  var $container;
<?php
switch($grid){
  case 'table':?>
  $container = $field.closest('.container.cf7-sg-table');
<?  break;
  case 'tabs':?>
  $container = $field.closest('.cf7-sg-tabs');
<?  break;
}?>
  var idx=0;
  for(idx = 0; idx<<?echo $json_value?>.length; idx++){
    $(':input', $field).val(<?echo $json_value?>[idx]);
    $field = $('span.<?echo $field ?>_'+(idx+1), <? echo $js_form ?>);
    if(0 == $field.length && (idx+1) < <?echo $json_value?>.length){
<?php switch($grid){
        case 'table':?>
      $container.cf7sgCloneRow();
<?        break;
        case 'tabs':?>
      $container.cf7sgCloneTab();
<?        break;
      }?>
      $field = $('span.<?echo $field ?>_'+(idx+1), <? echo $js_form ?>);
    }
  }
}
