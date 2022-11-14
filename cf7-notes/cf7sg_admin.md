## Debugging CF7sg forms with prefilled fields.

The plugin has a cookie based prefill mechanism when the forms are previewed from the admin form editor.

Any values that are submitted on a preview form are saved as a non-expiring transient.  Each submission overwrites any previous values.  When the form is loaded a cookie is set with the stored transient values, allowing the form to be prefilled with previously submitted values.  This saves time in filling fields when trying to debug a form.  Simply clearing the cache (ctrl + f5 on a browser) will clear the cookie allowing the form to reset its fields.

The prefilling is achieving using the `cf7sg_prefill_form_fields` filter.

The public class `public/class-cf7-grid-layout-public.php` method,

```php
/**
  * Track form field value submissions for preview forms.
  * Hooked to action 'wpcf7_before_send_mail'
  *@since 4.4.0
  *@param WPCF7_Contact_Form $form cf7 form object.
  */
  public function on_submit_success($form){
    ...
    if( !isset($_POST['_cf7sg_preview']) ) return;
    $prefill = array();
    foreach( $form->scan_form_tags() as $tag){
      if( isset($_POST[$tag->name]) and !empty($_POST[$tag->name]) ){
          $prefill[$tag->name] = $_POST[$tag->name];
      }
      $prefill['_cf7sg_toggles'] = self::$array_toggled_panels[$form->id()];
    }

    if(!empty($prefill)) setcookie('_cf7sg_'. sanitize_text_field( $_POST['_wpcf7_key'] ), json_encode($prefill),0,'/');
  }

```

setsup the cookie for the form page on submition.

The cookie's value is then added using the filter in the `assets/cf7-admin-table/admin/cf7-post-admin-table.php` class, a general class that handles core funcitonality of the cf7sg, in the method that setups the cf7sg shortcode.