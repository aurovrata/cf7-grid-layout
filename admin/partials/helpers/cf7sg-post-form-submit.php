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
    if($submission['location-city'] === 'Chennai'){
      $validation_errors['location-city'] = 'location cannot be Chennai!';
    }
  }
  return $validation_errors;
}" href="javascript:void(0);"><?=__('Filter','cf7-grid-layout')?></a> <?=__('custom form submission validation of any field.','cf7-grid-layout')?>
</li>
<li>
  <a class="helper" data-cf72post="add_filter( 'cf7sg_annotate_mail_attach_grid_files','annotate_mail_attachments',10,7);
  /**
  * @param string $label an empty text to filter.
  * @param string $field the name of the file field being attached
  * @param string $row the row index, empty if first row and zero-based otherwise. Null if not a row field type.
  * @param string $tab the tab index, empty if first tab and zero-based otherwise. Null if not a tab field type.
  * @param int $attachment_index the index of the attachment in the mail.
  * @param string $file_name file name attached.
  * @param string $cf7_key unique form key.
  * @return string an annotation note to be appended at the end of your mail body.
  */
function annotate_mail_attachments($label, $field, $row, $tab, $attachment_index, $file_name, $cf7_key){
  /* this filter is used to annotate complex file field submissions such as tables or tabs or tables within tabs.  The index of the attachment in the mail is given to better annotate your attachments.  Annotations are appended at the end of the mail body and you should take care to add newline/html breaks for your own clarity.  The row and tab indexes are provided to help you identify from which file field the attachment is coming from.  A null value for $tab/$row is passed for field types which are neither. So table fields would have $row either as an empty string or a zero-based value. Empty are first rows.  In case a field is table within a tab, then the field from the first row of the first tab would have both $tab and $row as empty strings.
  */
  if('{$form_key}'!==$cf7_key){
    return $label;
  }
  //for example a file field in a table within a tabbed section.
  $label = '<div>'.$attachment_index.'-('.$field.'['.$tab.']['.$row.'])</div>';
  return $label;
}" href="javascript:void(0);"><?=__('Filter','cf7-grid-layout')?></a> <?=__('mail annotation for complex array file field attachments.','cf7-grid-layout')?>
</li>
<li>
  <a class="helper" data-cf72post="add_filter( 'cf7sg_mailtag_grid_fields','insert_table_in_mail',10,4);
/**
* this filter is used to build an html formated string to rpelace a mail tag of a field that is in a table or tab structure. NOTE: this filter is only fired if the mail format is set to html.
* In case the field is in a table that is within a tab, then the $data field will be an array of arrays.
* @param string $html an empty html string to filter.
* @param string $field the name of the file field being attached
* @param string $data an array of submitted data.
* @param string $cf7_key unique form key.
* @return string an html string to replace the mail tag.
*/
function insert_table_in_mail($html, $field, $data, $cf7_key){
  if('contact-form'!==$cf7_key){ //always validate the form being submitted.
    return $html;
  }
  $build = true;
  switch($field){ //if either of fields present in the table...
    case 'field-one':
      $label = 'First';
      $html ='<ul style=&quot;list-style-type:none;border-right:1px solid black;display:inline-block;float:left;padding:5px&quot;>';
      break;
    case 'field-two':
      $label = 'Second';
      $html ='<ul style=&quot;list-style-type:none;border-right:1px solid black;display:inline-block;float:left;padding:5px&quot;>';
      break;
    case 'field-three':
      $label = 'Third';
      /*styling for last column*/
      $html ='<ul style=&quot;list-style-type:none;display:inline-block;clear:right;padding:5px&quot;>';
      break;
    default: //else this isn't a field we want in the table.
      $build=false;
      break;
  }
  if($build){
    $html .='<li style=&quot;background-color:lightgray;margin:0;padding:3px 5px&quot;>'.$label.'</li>'
    foreach($data as $key=>$value){
      $html .='<li style=&quot;margin:0px;padding:3px 5px&quot;>'.$value.'</li>';
    }
    $html .='</ul>';
  }
  return $html;
}" href="javascript:void(0);"><?=__('Filter','cf7-grid-layout')?></a> <?=__('Tabled/Tabbed mail tags','cf7-grid-layout')?>
</li>
