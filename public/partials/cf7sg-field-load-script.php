<?php
/*
This file outputs javascript to enable the form loading of grid fields (tabs and tables)
*/
?>
if(<?= $json_value?> !== undefined && <?= $json_value?> instanceof Object){
  var $input = $('span.wpcf7-form-control-wrap.<?= $field ?> :input:enabled', <?= $js_form ?>);
  var $field = $input.parent();
<?php
$add_string='';
switch($grid){
  case 'table':
    $add_string = '$container.cf7sgCloneRow(false);';
?>
  var $container = $field.closest('.container.cf7-sg-table');
  var count = $container.children( '.row.cf7-sg-table').length - 1;
<?php
    break;
  case 'tab':
    $add_string='$container.cf7sgCloneTab(false);';
?>
  var $container = $field.closest('.columns.cf7-sg-tabs');
  var count = $container.children('.cf7-sg-tabs-list').children('li').length ;
<?php
    break;
}
switch($grid){
  case 'tab':
  case 'table':
?>
  var idx=0;
  var keys = Object.keys(<?= $json_value?>).length;
  for(var key in <?= $json_value?>){
    if(idx>0) $field = $('span.wpcf7-form-control-wrap.<?= $field ?>'+key, $container);
    idx++;
    if(<?= $json_value?>[key]) {
      $(':input', $field).val(<?= $json_value?>[key]).trigger('change');
    }
    if(count < keys && idx < keys){
      <?= $add_string?>
    }
  }
<?php
    break;
  case 'both':
?>
  var $tabContainer = $field.closest('.columns.cf7-sg-tabs');
  var eRows, eTabs = $tabContainer.children('.cf7-sg-tabs-list').children('li').length ;
  var $rowContainer;
  var rIdx, tIdx=0;
  var rows, tabs = Object.keys(<?= $json_value?>).length;
  for(var tab in <?= $json_value?>){
    if(tIdx>0) $field = $('span.wpcf7-form-control-wrap.<?= $field ?>'+tab, $tabContainer);
    $rowContainer = $field.closest('.container.cf7-sg-table');
    eRows = $rowContainer.children( '.row.cf7-sg-table').length - 1;
    rIdx = 0;
    tIdx++;
    var table = <?= $json_value?>[tab];
    rows = Object.keys(table).length;
    for(var row in table){
      if(rIdx>0) $field = $('span.wpcf7-form-control-wrap.<?= $field ?>'+tab+row, $rowContainer);
      rIdx++;
      if(table[row]) $(':input', $field).val(table[row]).trigger('change');
      if(eRows < rows && rIdx < rows){
        $rowContainer.cf7sgCloneRow(false); //add another row
      }
    }
    if(eTabs <  tabs && tIdx < tabs){
      $tabContainer.cf7sgCloneTab(false); //add a new tab
    }
  }
<?php
    break;
}
?>
}
