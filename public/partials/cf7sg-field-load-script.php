<?php
/*
This file outputs javascript to enable the form loading of grid fiels (tabs and tables)
*/
?>
if(<?= $json_value?> !== undefined && <?= $json_value?> instanceof Array){
  var $field = $('span.<?= $field ?>', <?= $js_form ?>);
  var $container;
<?php
switch($grid){
  case 'table':
?>
  $container = $field.closest('.container.cf7-sg-table');
<?php
    break;
  case 'tabs':
?>
  $container = $field.closest('.cf7-sg-tabs');
<?php
    break;
}
?>
  for(var idx = 0; idx<<?= $json_value?>.length; idx++){
    var $input = $(':input', $field).val(<?= $json_value?>[idx]);
    if( $input.is('select')){
      $input.get(0).value = <?= $json_value?>[idx];
      $('option[value="'+<?= $json_value?>[idx]+'"]', $input).attr('selected', true);
    }
    //$('.cf7sg-<?= $field ?>').eq(idx).val(<?= $json_value?>[idx]);
    $field = $('span.<?= $field ?>_'+(idx+1), <?= $js_form ?>);
    if(0 == $field.length && (idx+1) < <?= $json_value?>.length){
<?php
switch($grid){
  case 'table':
?>
       $container.cf7sgCloneRow();
<?php
    break;
  case 'tabs':
?>
      $container.cf7sgCloneTab();
<?php
    break;
}
?>
      $field = $('span.<?= $field ?>_'+(idx+1), <?= $js_form ?>);
    }
  }
}
