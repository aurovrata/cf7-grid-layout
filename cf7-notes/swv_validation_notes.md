# CF7 v5.6 SWV validation
Schema-Woven Validation API - enables validation server-sie and client-side using JSON object.

## Generic rules
Basic Rules are defined in `contact-form-7/includes/swv/rules`

For example, required rule, `contact-form-7/includes/swv/rules/required.php`

extends the abstract class `WPCF7_SWV_Rule` `contact-form-7/includes/swv/swv.php

```php
class WPCF7_SWV_RequiredRule extends WPCF7_SWV_Rule{
  public function validate( $context ) {
    //validates required field.
  }
}
```
## Specific rules

Field specific rules use generic rules to build a rule set.

For example an `email*` rule set is made up of the generic `email` and `required` generic rules. (`contact-form-7/modules/text.php`)

```php
function wpcf7_swv_add_text_rules( $schema, $contact_form ) {
	$tags = $contact_form->scan_form_tags( array(
		'basetype' => array( 'text', 'email', 'url', 'tel' ),
	) );
  ...
}
```

## Rules are collected into a `$schema` object as part of the form object

Rule class files are loaded at `wpcf7_init` time, (`contact-form-7/includes/swv/rules/swv.php`)

```php
add_action( 'wpcf7_init', 'wpcf7_swv_load_rules', 10, 0 );
function wpcf7_swv_load_rules() {
  //...
	include_once $path;
}
```

It is possible to load extra rule classes with the `wpcf7_init` action.

The Schema holder `WPCF7_SWV_SchemaHolder` class  is `use`'d as a `trait` method [inheritance](https://www.php.net/manual/en/language.oop5.traits.php) in the form object, (`contact-form-7/includes/contact-form.php`)

```php
class WPCF7_ContactForm {

	use WPCF7_SWV_SchemaHolder;
  ...
}
```
The class `WPCF7_SWV_SchemaHolder` (`contact-form-7/includes/swv/schema-holder.php`)it exposes the method `get_schema()` which fires an action to collect all the rules required to validate the form tags,

```php
public function get_schema() {
  ...
  do_action( 'wpcf7_swv_create_schema', $schema, $this );

  return $this->schema = $schema;
}
```

the action `'wpcf7_swv_create_schema'` is then hooked by the functions that define the specific field rule set, for example the `email*` rule set defined and hooked by the following function (`contact-form-7/modules/text.php`), (*note* the specific rules sets are defined in the `modules` folder)

```php
add_action(
	'wpcf7_swv_create_schema',
	'wpcf7_swv_add_text_rules',
	10, 2
);

function wpcf7_swv_add_text_rules( $schema, $contact_form ) {
	$tags = $contact_form->scan_form_tags( array(
		'basetype' => array( 'text', 'email', 'url', 'tel' ),
	) );
  ...
}
```

# Validating the form tags using the schema

The schema holder class exposes a `validate_schema()` method which is called by the submission `WPCF7_Submission` class `validate()` method (`contact-form-7/includes/submission.php`),

```php
private function validate() {
  ...
  $result = new WPCF7_Validation();
  $this->contact_form->validate_schema(
    array(
      'text' => true,
      'file' => false,
      'field' => array(),
    ),
    $result
  );
  ...
  foreach ( $tags as $tag ) {
    $type = $tag->type;
    $result = apply_filters( "wpcf7_validate_{$type}", $result, $tag );
  }
  $result = apply_filters( 'wpcf7_validate', $result, $tags );
  ...
}
```

**NOTE**: The `WPCF7_Validation $result` object already contains the invalid tags by the time the `wpcf7_validate_{$type}` filter is fired.  In previous versions (<5.5.x), the filter was hooked by field validating functions and validation was taking place at that point.  This no longer works, except for existing plugin extensions  that have yet to implement this new validation procedure.


## Adding extra schemas rules to the validation process

In order to validate additional fields that are dynamically added by a user on the front-end such as repetitive field plugins, additional schema rules need to be added for each additional fields.

If the additional fields are a repetition of an existing field (in the form design as a field tag), then it it possible to clone the existing tag's schema and replace its field-name with the additional field.  The schema validation method will look up the field-name in the `$_POST` object.  For example the `required` rule, (`contact-form-7/includes/swv/rules/required.php`).
```php
class WPCF7_SWV_RequiredRule extends WPCF7_SWV_Rule {

	...

	public function validate( $context ) {
		$field = $this->get_property( 'field' );

		$input = isset( $_POST[$field] ) ? $_POST[$field] : '';

		$input = wpcf7_array_flatten( $input );
		$input = wpcf7_exclude_blank( $input );

		if ( empty( $input ) ) {
			return new WP_Error( 'wpcf7_invalid_required',
				$this->get_property( 'error' )
			);
		}

		return true;
	}
}
```

To add a custom schema rules for repetitive fields, one could clone the original field rule and insert it into the schema validation process as follows,

```php
add_action('wpcf7_swv_create_schema', 'add_addtional_swv_schemas', PHP_INT_MAX , 2);
/**
 * @param WPCF7_SWV_CompositeRule $schema itself a WPCF7_SWV_Rule
 * @param WPCF7_ContactForm $contact_form form object.
 */
function add_addtional_swv_schemas($schema, $contact_form){
  $tags = $contact_form->scan_form_tags();
  $rules = array();
  foreach($schema->rules() as $rule){ //$rule is a WPCF7_SWV_Rule object.
    if( !isset($rules[$rule['field']]))$rules[$rule['field']] = array(); //can have multiple rules.
    $rules[$rule['field']][] = $rule;

	foreach ( $tags as $tag ) {
    $new_tag = clone $tag;
    $new_tag['name'] = $tag['name'].'_duplicate';
    foreach($rules[$tag['name']]as $rule){
      //create a new clone of the same class.
      $rule_class = $rule->get_class();
      $new_rule  = $rule->to_array();
      $new_rule['field'] = $new_tag['name'];
      $new_rule = new $rule_class($new_rule); //cloned rule object for new field
      $schema->add_rule($new_rule); //add it to the schema to process by cf7 validation.
    }
  }
}
```
however, the above **DOES NOT WORK** because when a field is found to be invalid, the CF7 plugin again scans the form to retrieve the field's tag object (why???),
contact-form-7/includes/validation.php line 32,

```php
	$tags = wpcf7_scan_form_tags( array( 'name' => trim( $context ) ) );
	$tag = $tags ? new WPCF7_FormTag( $tags[0] ) : null;
```
which results in a `null` tag and therefore the function simply exits without insert the invalid message into the submission results.

The only way to solve this is to hook the `wpcf7_validate` filter and rebuild the submission results object as before, but in addition to also run the entire schema validation process again in order to validate repetitive fields. An inefficient process typical of CF7 plugin extensions due to a lack to of vision from the CF7 author for extensibility and reusability.

# Solution for validating extra fields.

- Hook `wpcf7_swv_create_schema` as late as possible and clone all `WPCF7_SWV_Rule` required to validated the extra fields.
- Register an anonymous function on `wpcf7_validate` to use these closed rules.
- add extra field invalid messages to the `$result` object.
- remove any invalid field message that are in unused toggles.