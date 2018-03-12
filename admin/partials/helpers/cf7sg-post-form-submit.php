<li>
  <a class="helper" data-cf72post="add_filter( 'cf7sg_validate_submission','validate_field_submission',10,3);
function validate_field_submission($validation_errors, $submission, $cf7_key){
  /* $submission an array of <field-name>=>$value pairs one for each submitted field.
  tabbed/tabled sections fields have arrays as $values.
  tables within tabs have aray of array as values.
  if a value is not valid, return a <field-name>=><error message string> pair in the $validation_errors array. The sbumission process will be cancelled and teh user required to correct the field before re-submitting.
  $cf7_key unique form key to identify your form, $cf7_id is its post_id.
  */
  if('{$form_key}'!==$cf7_key ){
    if($submission['location-city'] === 'Chennai'){
      $validation_errors['location-city'] = 'location cannot be Chennai!';
    }
  }
  return $validation_errors;
}" href="javascript:void(0);">Filter</a> custom form submission validation of any field.
</li>
