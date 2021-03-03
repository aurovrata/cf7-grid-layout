<li>
  <a class="helper" data-cf72post="add_filter( 'cf7sg_validate_submission','validate_field_submission',10,3);
function validate_field_submission($validation_errors, $submission, $cf7_key){
  /* $submission an array of <field-name>=>$value pairs one for each submitted field.
  tabbed/tabled sections fields have arrays as $values.
  tables within tabs have aray of array as values.
  if a value is not valid, return a <field-name>=><error message string> pair in the $validation_errors array. The sbumission process will be cancelled and teh user required to correct the field before re-submitting.
  $cf7_key unique form key to identify your form, $cf7_id is its post_id.
  */
  if('{$form_key}'==$cf7_key ){
    //$validation_errors is an array of field-names=>error messages.
    //these include the simple validation exposed in the CF7 plugin for required fields/special field formats.
    if(isset($validation_errors['location-city']) && $submission['location-city'] === 'Chennai'){
      $validation_errors['location-city'] = 'location cannot be Chennai!';
    }
    //for fields within tables, these are stored as arrays, one for each row.
    foreach($validation_errors['my-table-field1'] as $row_index=>$error){
      if(isset($submission['my-table-field1'][$row_index]) && $submission['my-table-field1'][$row_index]>5){
        $validation_errors['my-table-field1'][$row_index] = 'value should be less than 5';
      }
    }
    //for fields in tables that are within tab sections, these are stored as 2-dimensional arrays.
    foreach($validation_errors['my-table-field1'] as $tab_index=>$e_array){
      foreach($e_array as $row_index => $error){
        if(isset($submission['my-table-field1'][$tab_index][$row_index]) && $submission['my-table-field1'][$tab_index][$row_index]>5){
          $validation_errors['my-table-field1'][$tab_index][$row_index] = 'value should be less than 5';
        }
      }
    }
  }
  return $validation_errors;
}" href="javascript:void(0);"><?=__('Filter','cf7-grid-layout')?></a> <?=__('custom form submission validation of any field.','cf7-grid-layout')?>
</li>
<li>
  <a class="helper" data-cf72post="add_filter( 'cf7sg_annotate_mail_attach_grid_files','annotate_mail_attachments',10,6);
  /**
  * @param string $label an empty text to filter.
  * @param string $field the name of the file field being attached
  * @param string $file_name file name attached.
  * @param string $row the row index, empty if first row and zero-based otherwise. Null if not a row field type.
  * @param string $tab the tab index, empty if first tab and zero-based otherwise. Null if not a tab field type.
  * @param string $cf7_key unique form key.
  * @return string an annotation note to be appended at the end of your mail body.
  */
function annotate_mail_attachments($label, $field, $file_name, $tab, $row, $cf7_key){
  /* this filter is used to annotate complex file field submissions such as tables or tabs or tables within tabs.  The index of the attachment in the mail is given to better annotate your attachments.  Annotations are appended at the end of the mail body and you should take care to add newline/html breaks for your own clarity.  The row and tab indexes are provided to help you identify from which file field the attachment is coming from.  A null value for $tab/$row is passed for field types which are neither. So table fields would have $row either as an empty string or a zero-based value. Empty are first rows.  In case a field is table within a tab, then the field from the first row of the first tab would have both $tab and $row as empty strings.
  */
  if('{$form_key}'!==$cf7_key){
    return $label;
  }
  //for example a file field in a table within a tabbed section.
  $label = '<div>('.$field.'['.$tab.']['.$row.'])</div>';
  return $label;
}" href="javascript:void(0);"><?=__('Filter','cf7-grid-layout')?></a> <?=__('mail annotation for complex array file field attachments.','cf7-grid-layout')?>
</li>
<li>
  <a class="helper" data-cf72post="add_filter( 'cf7sg_mailtag_grid_fields','insert_table_in_mail',10,5);
/**
* this filter is used to build an html formated string to rpelace a mail tag of a field that is in a table or tab structure. NOTE: this filter is only fired if the mail format is set to html.
* In case the field is in a table that is within a tab, then the $data field will be an array of arrays.
* @param string $html an empty html string to filter.
* @param string $field the name of the file field being attached
* @param string $data an array of submitted data.
* @param string $cf7_key unique form key.
* @param boolean $table_in_tab flag tables fields in tabed sections (data is array of array).
* @return string an html string to replace the mail tag.
*/
function insert_table_in_mail($html, $field, $data, $cf7_key, $table_in_tab){
  if('{$form_key}'!==$cf7_key){ //always validate the form being submitted.
    return $html;
  }
  $build = true;
  $pre = $table_in_tab ? '<ul style=&quot;list-style-type:none;display:inline-block;float:left;padding:5px&quot;>':'';
  $display = $table_in_tab ? '':'inline-';
  $float = $table_in_tab ? '':'float:left;';
  $end='';
  $tabIdx = 0;
  $keys = array_keys($data);

  while($build){
    switch($field){ //if either of fields present in the table...
      case 'field-one':
        $label = 'First';
        break;
      case 'field-two':
        $label = 'Second';
        break;
      case 'field-three':
        $label = 'Third';
        break;
      default: //else this isn't a field we want in the table.
        $build=false;
        break;
    }
    if($build){
      $html .=$pre.'<ul style=&quot;list-style-type:none;border-right:1px solid black;display:'.$display.'block;'.$float.'padding:5px&quot;>';
      $col = $data;
      if($table_in_tab){
        $col = $data[$keys[$tabIdx]];
        $tabIdx++;
        $label .='('.$tabIdx.')';
        $build = $tabIdx < count($data);
        $pre = ''; //reset now.
        if(!$build) $end='</ul>'; //last loop, hence close.
      }
      $html .='<li style=&quot;background-color:lightgray;margin:0;padding:3px 5px&quot;>'.$label.'</li>';
      foreach($col as $key=>$value){
        $html .='<li style=&quot;margin:0px;padding:3px 5px&quot;>'.$value.'</li>';
      }
      $html .='</ul>'.$end;
    }
  }
  return $html;
}" href="javascript:void(0);"><?=__('Filter','cf7-grid-layout')?></a> <?=__('Tabled/Tabbed mail tags','cf7-grid-layout')?>
</li>
<li>
  <a class="helper" data-cf72post="add_filter( 'cf7sg_valid_form_submission','valid_data_submission',10,3);
function validate_field_submission( $submission, $cf7_key, $form_id){
  if('{$form_key}'==$cf7_key ){
    //$submission is an array of all submited data, including files.
    //if you have a file field called upload, you can get the file as
    //$file_path = $submission['upload'][0];   //this can be an array fo arrays if it is a repetitive field.
  }
}" href="javascript:void(0);"><?=__('Action','cf7-grid-layout')?></a> <?=__('to access valid submit data.','cf7-grid-layout')?>
</li>
