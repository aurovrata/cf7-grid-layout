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
}" href="javascript:void(0);">Filter</a> custom form submission validation of any field.
</li>
<li>
  <a class="helper" data-cf72post="add_filter( 'cf7sg_annotate_mail_attach_grid_files','annotate_mail_attachments',10,6);
  /**
  * @param string $note an empty text to filter.
  * @param string $field the name of the file field being attached
  * @param string $row the row index, empty if first row and zero-based otherwise. Null if not a row field type.
  * @param string $tab the tab index, empty if first tab and zero-based otherwise. Null if not a tab field type.
  * @param int $attachment_index the index of the attachment in the mail.
  * @param string $cf7_key unique form key.
  * @return string an annotation note to be appended at the end of your mail body.
  */
function annotate_mail_attachments($note, $field, $row, $tab, $attachment_index, $cf7_key){
  /* this filter is used to annotate complex file field submissions such as tables or tabs or tables within tabs.  The index of the attachment in the mail is given to better annotate your attachments.  Annotations are appended at the end of the mail body and you should take care to add newline/html breaks for your own clarity.  The row and tab indexes are provided to help you identify from which file field the attachment is coming from.  A null value for $tab/$row is passed for field types which are neither. So table fields would have $row either as an empty string or a zero-based value. Empty are first rows.  In case a field is table within a tab, then the field from the first row of the first tab would have both $tab and $row as empty strings.
  */
  if('{$form_key}'!==$cf7_key && {$field_name}'!==$field){
    return $note;
  }
  //for example a file field in a table within a tabbed section.
  if(empty($row)) $row = 0;
  if(empty($tab)) $tab = 0;

  $note = '<div>'.$attachment_index.'-('.$field.'['.$tab.']['.$row.'])</div>';
  return $note;
}" href="javascript:void(0);">Filter</a> mail annotation for complex array file field attachments.
</li>
