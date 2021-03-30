=== Smart Grid-Layout Design for Contact Form 7 ===
Contributors: aurovrata, StrangeTech, altworks, Birmania, netzgestaltung
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=EDV4MEJLPT4VY
Tags: contact form 7 module, form custom styling, contact form 7 extension, responsive forms, multi-step form, form builder, multi-slide slider form, repetitie fields, form custom javascript
Requires at least: 4.7
Requires PHP: 5.6
Tested up to: 5.7.0
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugins allow pure CSS responsive grid layouts for contact form 7.  It enables rich interlinking of your CMS data via taxonomy/posts populated dropdown fields.  It also enables modular design of complex forms.

== Description ==

The plugin uses the [smart-grid](http://origin.css.gd/) CSS plugin to build beautiful form layouts.  It introduces a graphical editor to design your forms, as well as a coloured html syntax editor built using the excellent CodeMirror editor.  It is now possible to design smart layouts with ease.

v4.0 introduces a tutorial sections within the dashboard for quick rerefence to various youtube tutorials.  For a full list of available tutorials visit this playlist.

[youtube https://www.youtube.com/playlist?list=PLblJwjs_dFBsynXEstrV3fCIC7GBmK9HW]

In addition, the plugin also introduces multiple smart input functionalities, such as,

* **tabled input sections**: these allow you to group several **repetitive input fields** as table rows, the plugin will automatically add an 'Add Row' button to your front end form, giving your users the ability to add multiple rows of your grouped fields.
* **tabbed sections**: with this plugin you can build tabbed sections of **repetitive fields**, allowing your users to add additional tabs.  It is a similar concept to the tabled input section above, but in a tabbed layout instead.
* **collapsible sections**: for long and complex forms you can now group your front-end fields into collapsible sections, making it easier for user to see the big picture.
* **toggled collapsible sections** for optional sections.  A toggle with a default Yes/No value is inserted, allowing your users to submit optional fields which within the section can be set to required in your design (See FAQ section for more info).
* **grouped toggled sections** for either/or optional sections.
* **reusable sub-forms**: if you have fields which repeat across multiple forms, you can now build a sub-form which you can include in your form, saving you the trouble of redesigning the form each time, but also making large forms much easier to maintain.
* **form categories**: the plugin introduces form taxonomy to classify your forms for the use of online registration where users may need to be associated with a given set of forms to access.
* **dynamic dropdown fields**: these are special select fields which you can populate with either existing post titles, or managed lists such as units, or even using a custom filter.  This makes dynamic interlinking of existing CMS data in your dashboard a piece of cake, giving you a very powerful tool for data capture.
* **plays nice with Post My CF7 Form plugin**: and best of all you can map all your forms to custom posts using the now stable [Post My CF7 Form](https://wordpress.org/plugins/post-my-contact-form-7/) plugin.
* **redesign the form editor**: this plugin now uses the WordPress default post editor page to edit/build forms, therefore making it easier for developer to plugin their functionality on top, while preserving all the hooks of Contact Form 7.

**Looking for Collaborators**
Are you a WordPress developer or an HTML/JavaScript wizard?  Want to collaborate on this plugin?  There are some really great pieces of functionality that are in the roadmap for this plugin, but I just don't have the time or resources to get them all on file in a timely manner.  So join me on [GitHub](https://github.com/aurovrata/cf7-grid-layout/wiki/Roadmap) if you want to collaborate.

**For plugin developers**
If you wish to leverage the in-editor helper code functionality for your CF7 plugin, you need to use the following hooks,
`cf7sg_ui_grid_js_helper_hooks` - include js bind event code helpers.
`cf7sg_ui_grid_helper_hooks` - include php filter/action hooks code helpers.
`cf7sg_enqueue_admin_editor_scripts` - to enqueue scipts on the admin editor page to bind to editor events for further dynamic code helpers.

If you wish to see an example on how to use this, please check the Google Map CF7 extension plugin code.  The `cf7-google-map/includes/class-cf7-googleMap.php` list the above hooks and the function calls are in the `cf7-google-map/admin/class-cf7-googleMap-admin.php` file.

= Checkout our other CF7 plugin extensions =

* [CF7 Polylang Module](https://wordpress.org/plugins/cf7-polylang/) - this plugin allows you to create forms in different languages for a multi-language website.  The plugin requires the [Polylang](https://wordpress.org/plugins/polylang/) plugin to be installed in order to manage translations.

* [CF7 Multi-slide Module](https://wordpress.org/plugins/cf7-multislide/) - this plugin allows you to build a multi-step form using a slider.  Each slide has cf7 form which are linked together and submitted as a single form.

* [Post My CF7 Form](https://wordpress.org/plugins/post-my-contact-form-7/) - this plugin allows you to save you cf7 form to a custom post, map your fields to meta fields or taxonomy.  It also allows you to pre-fill fields before your form  is displayed.

* [CF7 Google Map](https://wordpress.org/plugins/cf7-google-map/) - allows Google Maps to be inserted into a Contact Form 7.  Unlike other plugins, this one allows map settings to be done at the form level, enabling diverse maps to be configured for each form.

* [Smart Grid-Layout Design for CF7](https://wordpress.org/plugins/cf7-grid-layout/) - allows responsive grid layout Contact Form 7 form designs, enabling modular designs of complex forms, and rich inter-linking of your CMS data with taxonomy/posts populated dynamic dropdown fields.

= Documentation =

This plugin has a substantial set of [FAQs](https://wordpress.org/plugins/cf7-grid-layout/#faq) and [screenshots](https://wordpress.org/plugins/cf7-grid-layout/#screenshots) that is has a lot of information.  Please go through the FAQs and screenshot captions to understand how to use the basic functionality.

The plugin has a number of hooks (filters and actions) which can be leveraged to further customise your form layouts and fields.  Please refer to the Helper Metabox available in the form post editor when you create/edit a form.  The helpers have commented code snippets which you can copy to and paste in your `functions.php` file to further understand how to use them. (See [screenshot](https://wordpress.org/plugins/cf7-grid-layout/#screenshots) #21).

= Support Open-source effort =

This plugin would not have been possible without the following open-source efforts.  Please consider visiting these plugins pages and making a donation to its authors to say thank you.  Even small amount of beer money is always appreciated. Alternatively/additionally you can help in the maintenance or translation effort.

* [Beautify](https://github.com/beautify-web/js-beautify) - a jQuery plugin to beautify html text, used in the text editor of this plugin.
* [CodeMirror](https://codemirror.net/) - a remarkable jQuery text editor that allows for colour-coded highlighting among many other functionality.  Used to edit form source code in text editor of this plugin.
* [CSS Smart Grid](http://origin.css.gd/) - a CSS plugin that allows for intuitive CSS styling of responsive grid layouts.  Used for building the responsive form layouts.
* [jQuery Clipboard](https://clipboardjs.com/) - copy text to the clipboard, used for helper links.
* [jQuery Nice Select](http://hernansartorio.com/jquery-nice-select/) - makes beautiful dropdown fields.
* [jQuery Select2](https://select2.org/) - this plugin converts dropdowns into powerful searchable dropdown fields.
* [jQuery Toggles](https://simontabor.com/labs/toggles/) - enables pretty toggle switches on collapsible sections.
* [PHP Simple HTML Dom](https://github.com/voku/simple_html_dom) - a php library that enables traversing and manipulation of html documents using CSS selectors like jQuery.  This is used to build the modular functionality of form designs.
* [Glider.js slider](https://nickpiscitelli.github.io/Glider.js/) - a A blazingly fast, crazy small, fully responsive, mobile-friendly, dependency free, native scrolling list with paging controls!

= Thanks to =
Birmania [@birmania](https://profiles.wordpress.org/birmania/) for providing:

* a fix for JS toggles.
* a fix for file fields in tabs as mail attachments

Andrew Browning [@altworks](https://profiles.wordpress.org/altworks/) for providing:

* an IE polyfill for frontend table fields.

PenhTech [@penhtech](https://wordpress.org/support/users/penhtech/)

* a fix for continue warnings in php7.3

Thomas Fellinger [@netzgestaltung](https://profiles.wordpress.org/netzgestaltung/)

* a fix for [Really Simple Captcha](https://wordpress.org/plugins/really-simple-captcha/) plugin.

= Privacy Notices =

This plugin, in itself, does not:

* track users by stealth;
* write any user personal data to the database;
* send any data to external servers;
* use cookies.

== Installation ==

1. Install the Contact Form 7 plugin.
2. Unpack this plugin archive file into your wp-content/plugins folder.
3. Activate the plugin through the 'Plugins' menu on WordPress.
4. Create a new form to leverage the grid editor, existing forms can only be edited in text mode.
5. Read the FAQs & Screenshot captions to understand how to use this plugin.

== Frequently Asked Questions ==

= 1. How do I drag and sort columns in the grid editor ? =

Columns can be rearranged within a row by simply dragging and dropping using the handled icon in the columns head.  You can also drag and drop a column into another row, if the target row has sufficient space to receive the column, else a warning msg will appear.  In that case, make some room in the target row and/or resize the column so as to ensure it will fit in the row.

Similarly, you can re-organise your rows within a given grid.  Your initial form is grid.  You can convert an existing column into a grid.

= 2. How do I create a dynamic dropdown list ?=

Simply create a new dynamic dropdown field using the added tag in the list of available tags and select the type of dynamic list you want to populate with.  You create a list which will appear in the Information metabox in your edit page once you save your form.  It uses the taxonomy management functionality of WordPress but is not associated with any posts as such.  Simply edit the list by adding new terms to your list.  These will appear in your dropdown field.

Alternatively select an existing post from your dashboard and the post titles will be used to populate the dropdown.

You also have the option to select a dynamic filter, and then the plugin will hook your functionality in your functions.php file to get your custom list.

= 3. How do I make nice dropdown selects? =

When you create a dynamic dropdown, or a cf7 dropdown field, you add `class:nice-select` to your tag or 'nice-select' in the class text field option.  The plugin will convert your dropdown into a beautiful [nice-select](http://hernansartorio.com/jquery-nice-select/) field.

= 4. How do I make powerful select2 dropdowns? =

When you create a dynamic dropdown, or a cf7 dropdown field, you add `class:select2` to your tag or 'select2' in the class text field option.  The plugin will convert your dropdown into a powerful and searchable [jQuery Select2](https://select2.org/) field.  You can also enable select custom user options (known as tagging in the plugin documentation: https://select2.org/tagging) by adding the 'tags' class to your cf7 tag, `class:tags`.

= 5.How do I display a pretty toggle switch on my collapsible section? =

When you convert a row into a collapsible section (see [screenshot 8](https://wordpress.org/plugins/cf7-grid-layout/#screenshots)), you can check the toggle option which will insert 2 data attributes into your html row, with labels for the toggle switch on/off state.  The default labels are 'yes' for on and 'no' for off.  You can change the `data-on` and `data-off` attributes in the html text editor.  Your form will display a [toggle switch](https://simontabor.com/labs/toggles/).

= 6.How do I reset a form?=
Navigate to the 'text' editor of the form, select all the html and delete the editor content.  The plugin will create 1 default row with a single column in the 'grid' editor from which you can design your form afresh.

= 7.The columns are filled with default html field wrappers, how do I change this ?=

The plugin wraps each cf7 tag with the following html,
`
<div class="field">
   <label></label>
   [CF7-tag shortcode]
   <p class="info-tip"></p>
</div>`
Furthermore, CF7 tags that are marked as required, have the following html `<em>*</em>` appended in the <label>. This enables for smart looking fields.

If you want to only modify a single column, simply find the column in the 'text' editor and modify the html wrapper as per your requirements.

If you want to change these wrappers for all the fields, then you can use the hook filters, `cf7sg_pre_cf7_field_html` to change the html before the cf7 tag, `cf7sg_post_cf7_field_html` to change the html after the cf7 tag, and `cf7sg_required_cf7_field_html` to change the required field label markup.

Each filter has 2 attributes passed to the hooked function,
`
add_filter('cf7sg_pre_cf7_field_html', 'filter_pre_html', 10, 2);
function filter_pre_html($html, $cf7_key){
  //the $html string to change
  //the $cf7_key is a unique string key to identify your form, which you can find in your form table in the dashboard.
}`

= 8.How can I customise my form ?=
**Custom scripts**
The plugin will look for a JavaScript file `js/{$cf7key}.js` from the base of your theme root folder and load it on the page where your form is displayed.  Create a `js/` subfolder in your theme (or child theme folder), and create a file called `<your-form-cf7key>.js` in which you place your custom JavaScript code.

In addition, if you need to localise your custom script, you can do so using the following action hook,
`
add_action('smart_grid_register_custom_script', 'localise_custom_scritp', 10,1);
function localise_custom_scritp($cf7_key){
  if('my-form'!=$cf7_key) return;
  //your script is enqueued with the handle $cf7_key.'-js'
  wp_localize_script($cf7_key.'-js', 'customObj', array('key1'=>'value1'));
}
`

The `$cf7key` is the unique key associated with your form which you can find in the Information metabox of your form edit page.

**Alternatively you can now use the custom JS editor** built into the form editor,  See this [video tutorial](https://www.youtube.com/watch?v=wTdLVtU_mlU&list=PLblJwjs_dFBslCf0mDCrDYGpm5hBY3sWv&index=4&t=205s) for more details.

If you wish to [wp_enqueue_script](https://developer.wordpress.org/reference/functions/wp_enqueue_script/) a general JavaScript file for all your forms, you can use the hook `smart_grid_register_scripts`,
`add_action('smart_grid_register_scripts', 'add_js_to_forms');
function add_js_to_forms(){
   wp_enqueue_script('my-custom-script', '<path to your custom js file>', array(), null, true);
}`
**custom styling**
Similarly, you can create a `css/` subfolder in your theme folder and create a file in it called `<your-form-cf7key>.css` and place your custom styling for your form.  The plugin will then load this CSS file on the page where your form is displayed.

If you wish to [wp_enqueue_styles](https://developer.wordpress.org/reference/functions/wp_enqueue_style/) a general CSS stylesheet file for all your forms, you can use the hook `smart_grid_register_styles`,
`add_action('smart_grid_register_styles', 'add_css_to_forms');
function add_css_to_forms(){
   wp_enqueue_style('my-custom-style', '<path to your custom sheet>', array(), null, 'all');
}`

**Alternatively you can now use the custom CSS editor** built into the form editor,  See this [video tutorial](https://www.youtube.com/watch?v=0DYxRpWc_F0&list=PLblJwjs_dFBsynXEstrV3fCIC7GBmK9HW&index=3&t=1080s) for more details.

= 9.Can I have required fields in toggled sections? =
Yes, as of v1.1 of this plugin, toggled sections input fields are disabled when collapsed/unused, and therefore any fields within these sections are not submitted.  So you can design fields to be required when toggled sections are used, and the fields will be validated accordingly too.

Please note that in the back-end, these fields which are listed in the form layout but are not submitted are set eventually set as null in the filtered submitted data.  So if you hook a functionality post the form-submission, be aware that you also need to test submitted values for `NULL` as opposed to empty.

= 10.Can I group toggled sections so as to have either/or sections ?=
Yes, with v1.1 you can the `data-group` attribute which by default is empty to regroup toggled sections and therefore ensure that only 1 of these grouped sections is used by a user.  Edit your form in the html editor (Text tab) and fill the `data-group` attribute with the same value (no spaces) for each toggled section (`div.container.with-toggle`) you wish to re-group,
`
<div class="container cf7sg-collapsible with-toggle" id="0sTn7L" data-group="group1">
  <div class="cf7sg-collapsible-title"><span class="cf7sg-title toggled">Name &amp; Contact</span>
    <div class="toggle toggle-light" data-on="Yes" data-off="No"></div>
  </div>
  <div class="row">
    <div class="columns one-third">
    </div>
    <div class="columns one-third">
    </div>
    <div class="columns one-third">
    </div>
  </div>
</div>
<div class="container cf7sg-collapsible with-toggle" id="CNeqCy" data-group="group1">
  <div class="cf7sg-collapsible-title"><span class="cf7sg-title toggled">Address</span>
    <div class="toggle toggle-light" data-on="Yes" data-off="No"></div>
  </div>
  <div class="row">
    <div class="columns full">
    </div>
  </div>
</div>
`

= 11.I am using Post My CF7 Form plugin, how are toggles status saved in the database? =
When you install Post My CF7 Form plugin to map your form submissions to posts in the dashboard, this plugin will automatically save the toggle status, so that draft forms can be re-loaded as well accessing the data for later use.  The status of the toggle is saved to the custom meta-field `cf7sg_toggles_status`,
`
$toggles = get_post_meta($post->ID, 'cf7sg_toggles_status', true);
`
this will retrieve an array with the following `key=>value` pairs,
`
<toggle-element-id>=>""<toggle-label>|Yes"
`
the key is the unique id of your toggle `.container` element.  If you navigate to the element in the text editor you will notice that a random 'id' attribute has already been set, you can change this to something more meaningful.
The value of the array if set at the text string comprised of the toggle label (which you filled in), followed by the positive selection string (toggle open status) which is 'Yes' by default, and separated by the '|' (pipe) character.  If the toggle has not been opened, no key/value pairs will be saved in the array for that toggle.

= 12. How can I navigate/search the text editor? =
As of v1.3 a search functionality has been introduced.  Click anywhere in the text editor and press your search key combination (for example 'Ctrl+F' on Windows/Linux), you will see a search box at the top of the editor.  This is useful if you want to edit a specific field, so once you have added a new cf7 field tag with the name say 'your-email', you can then search for it on the text editor to locate the code.

= 13. Can the text editor highlighting be turned off? =
yes, you can use the following filters to either switch off only the shortcode highlighting, (add the following line to your functions.php file)

`add_filter('cf7sg_admin_editor_mode', function($mode, $form_key){return '';}, 10, 2);`

and you can also turn off highlight altogether by inserting this additional line to your functions.php file,
`add_filter('cf7sg_admin_editor_theme', function($theme, $form_key){return '';}, 10, 2);`

= 14. Can collapsible sections be shown as open by default? =

Yes, identify the row in your text editor which implements your collapsible section, and add the `data-open="true"` attribute to it,

`<div class="container cf7sg-collapsible" data-open="true" ...`

= 14bis. How can I display a table of fields in a mail message? =
If you have a set of fields that are in a table/tab structure, the plugin is not aware of their relationship and as such does not build a table layout in a mail when you use them as tags in a message.  However, a filter is provided for you to archive this.  The filter #3 in the Post-form submit hooks allows you to build a table layout in an *html* mail.  Ensure the html format checkbox is selected in your mail settings, else the filter will not fire.
In your mail message body, place the mail tags contiguously for each field that is present in your table.  Hence, assuming you have a table with 3 fields, field-one, field-two, field-three.  Place their tags in the mail body as

`[field-one][field-two][field-three]`

copy the filter helper code (see [screenshot](https://wordpress.org/plugins/cf7-grid-layout/#screenshots) #21) and place it in your *functions.php* file.  The code sample assumes the above example fields and sets up a list element for each field, along with a column header.  These list elements are then styled to display them as tables in your mail.

= 15. How can I set a maximum number of rows to a table ? =

As of v2.8, this functionality has now been included.  You will need to add the `data-max` attribute to your table (div.container.cf7-sg-table) in the text editor and set it to the row limit you want (see [screenshot](https://wordpress.org/plugins/cf7-grid-layout/#screenshots) #22).  NOTE: in a lengthy form it is easy to navigate directly to the table or field's text code line using the shortcode navigation buttons provided in the Grid editor, (see [screenshot](https://wordpress.org/plugins/cf7-grid-layout/#screenshots) #19)

When the limit of rows is reached, the add button is disabled and a message being displayed besides it.  This message can be customised using the CF7 messages for the form (see the message tab in the form form editor), the message is labelled 'Message displayed when max tables rows reached.'.  This message applies to all tables in a given form.  Should you need to customise the messages for multiple tables, you can use the `data-max-row-msg` attribute on the table element (div.container.cf7-sg-table), with a custom message.

= 16. Can I add custom input or other html elements in the form ? =

Yes this is possible.  However, keep in mind that the forms are sanitised using [`wp_kses()`](https://codex.wordpress.org/Function_Reference/wp_kses) function, so any html elements which are not included in the set of permitted elements will be stripped out of the form.  A filter is included for you add additional html code permitted by the sanitisation function,

`
add_filter('cf7sg_kses_allowed_html', 'add_custom_html', 10,2);
function add_custom_html($allowed, $cfk7_key){
  if('my-custom-form' !== cfk7_key) return $allowed;
  $allowed['pre']=array('class'=1);
  return $allowed;
}
`
= 17. Is it possible to modify the default html markup for the grid cell and/or the required html markup in the label ? =

Yes, there are filters that have been created to allow a user to modify the html structure.  See the Hook &amp; Filters #1, #2, and #3 ([screenshot](https://wordpress.org/plugins/cf7-grid-layout/#screenshots) #21).

These filters will only impact new forms or new cells (rows/columns) created.

For the required HTML markup in the label it is possible to manually change the html without the use of the filter by simply marking up your required symbol/text with a `span` element.  For example,

`<span>(req)<\span>`

in order to replace the existing markup.

= 18. I can only edit my form in 'Text' mode, I cannot see the 'Grid' tab, why? =
existing forms are not editable in the grid editor.

Create a new form to be able to leverage the grid editor functionality.

this plugin allows you to create grid layout forms by creating a HTML markup and a CSS stylesheet which displays your form fields in a responsive grid.

The UI editor works by recognising the HTML structure that it creates.  If you change that structure it tries to rebuild the UI and place your custom code in a text editor instead of a column cell...however, if you change the outer structure (the `div.container`) then it assumes the entire form is no longer using the UI markup and therefore disable the UI editor altogether.

= 19. Form previews throws 404 page not found error =

You need to refresh your permalinks, please go to Settings->Permalinks in your dashboard, scroll down and hit the 'Save' button.

== Screenshots ==

1. (1) This plugin replaces the CF7 post table page and post edit pages with WordPress core post edit and post pages.  This means that other plugins that build on WordPress standards for custom admin dashboard functionality should now play nicely with CF7.  One out-of-the-box improvement is the ability to customise the CF7 form table columns being displayed.
2. (2) Form type taxonomy is introduced to manage forms.  This is useful when designing forms that change according to the type of users and therefore leverages WP core taxonomy CMS functionality.
3. (3) The CF7 form editor page is now replaced by the WP core `post.php` page for custom posts.  It offers a UI grid building tool to help design grid layouts.  This is done using a responsive CSS plugin, Smart grid, which divides a row into 12 equal width columns.
4. (4)The CSS smart-grid plugin columns layout, the total width of a row including column offsets need to add-up to 12 equivalent widths.  If you try to add more columns which takes the total width beyond these 12 units, you will end up with the extra columns flowing below.
5. (5) The 'text' tab of the form designer allows you to edit the form source in a beautiful CodeMirror html editor with colour highlighted markup for both html and CF7 tags. This makes it a lot easier to customise your source code.  Switching back to the grid mode, the plugin will attempt to identify new rows/columns, but if it fails to recognise your custom html code, it will simply leave it as is and display it a separate textarea.
6. (6) You can add new rows with the '+' button in the row controls, delete with the 'bin' button which only appears on the 2nd row onwards. You can edit the row with the 'pencil' button.  Similarly, you can add columns with the '+' button on the column controls, delete with the 'bin' button (only available on the 2nd column onwards), and edit a column using the 'pencil' button.
7. (7) Columns can be resized and offset.
8. (8) A row can be converted into a collapsible 'accordion' style section to collapse part of your form into more manageable parts.
9. (9) Columns can further be converted into grids, allowing for more complex layouts such as multiple rows within a column.
10. (10) Once a column is converted into a grid, its inner rows have added capabilities.  You can convert an inner-row into a tabled section of fields.  Any fields which are added to this row will be cloneable into multiple rows by a user and therefore submit multiple sets of these fields.
11. (11) Here is an example of a tabled section of a form, where the plugin automatically inserts a button for your uses to clone the row and any fields within it.
12. (12) When a row is added below a table row, you can convert it to a table footer. This is useful if you want to add some instructions at the bottom of your table (or table footer headings).  The plugin will then insert the 'Add Row' button below this row.
13. (13) Similar to a table row, you can convert an inner-row into a cloneable tabbed section of fields.  This is similar to the table concept except that users can add additional tabs which clone the entire set of fields presents in the tabbed row.  Make sure you give your tab a label.
14. (14) On the form front-end your users will be able to add new tabs.
15. (15) A column can be converted into an entire existing cf7 form by editing the column ('pencil' button) and selecting the option 'Insert Form'.  This will convert the column into a dropdown field from which you can select an existing form that you have previously designed.  This makes for modular design of forms.
16. (16) This plugin introduces dynamic dropdowns, which allow you to manage dropdown field options using various content managed in your WordPress dashboard.  For example, you can use taxonomy terms as options, or you can use existing post types' allowing your users to select/link existing content from your WordPress CMS managed data to their submission.  The dynamic dropdown can also be programmatically populated using a hook filter with the last option 'Custom'.
17. (17) If you create a dynamic-dropdown field or select filter as source, the plugin expects the options to be provided by a filter.  Your field cell will have an extra 'filter' icon at the top, click it to reveal the filters available.  The plugin has an extensible framework which allows other plugins to leverage the in-editor helper codes to be integrated for specific fields.  For example the Google Map CF7 extension is such an example, and will expose *PHP filter helper codes* as well as *JavaScript helper codes* to customise your field further.
You can click on the filter link which will copy a helper code snippet which you can paste in your *functions.php* file and customise to provide the options list.
For JavaScript helper codes, paste them in your `<theme folder>/js/<form-unique-key>.js` file (see FAQ #8 for more details).
18. (18) A benchmark field is available which allows you to display warning when certain input values breach the benchmark limit.  The benchmark field also emits a JavaScript event when the limit is breached so that custom JavaScript action can be executed.
19. (19) Click on the code icon in any given column cell of the grid UI editor and it will take you to the equivalent code lines in the text editor.
20. (20) v2.0 of the plugin introduces inline field hooks helpers.  These are specific hooks which allow you to filter custom aspect of the field.  Not all tags have field specific hooks, so if any are defined they will show up with the icon in the control bar.
21. (21) The plugin include hooks for further customisation.  Handy helper code snippets are provided within form editor in the metabox 'Actions & Filers', with a set of links on which you can click to copy  the code snippet and paste it in your *functions.php* file.
22. (22) You can set a maximum number of rows a user can add to a table, by adding the `data-max` attribute to your table element.
23. (23) You can filter mail tags, hover your mouse over the blue information icon next to each tag and click the link, this will copy the filter code to your clipboard which you can paste into your functions.php file.
== Changelog ==
= 4.10.0 =
* abstraction of dynamic lists to open the possibility for other tag fields.
* fix file required PHP fatal error.
= 4.9.2 =
* fix admin js to ensure form field udpates reflect.
* added 'cf7sg_save_post' action for plugins to save form attributes.
= 4.9.1 =
* admin css style fix.
* pass button element in sgAddRow event.
= 4.9.0 =
* fix css/js codemirror empty line.
* change in file schema, CF7 5.4 file validation handling not longer exposes additional file validation/transport.
* files now stored in submission data array.
* added actino 'cf7sg_valid_form_submission' fired once submission is validated.
= 4.8.2 =
* fix for new file validation in CF7 5.4.
* ajax submission spinner styling update.
= 4.8.1 =
* enabled other attributes for input elements in html text.
* fix HTML/UI form sync.
* add 'cf7sg_preserve_cf7_data_schema' filter for plugin owners to preserve CF7 data schema.
= 4.8.0 =
* fix missing search.png image for minified css.
* added custom attribute data-max-row-msg on tables.
= 4.7.8 =
* fix ie11 js fn.
= 4.7.7 =
* enable custom classes on collapsible title element.
* remove for..of loops for ie11
* fixed 'cf7sg_dynamic_dropdown_default_value' filter for default value label in dynamic seelect fields.
= 4.7.6 =
* improve cf7sg js object validation.
= 4.7.5 =
* fix cf7sg js object validation for non-grid forms.
* do not flag non-grid forms if not requiring grid js resoures.
= 4.7.4 =
* fix redirect for non-grid forms.
= 4.7.3 =
* fix hidden textarea cf7 editor.
= 4.7.2 =
* fix helper code for mailtag filter.
= 4.7.1 =
* fix bug preventing toggled sections within accordions.
* fix accordion activation/flagging when contains invalid fields.
* added dots button to slides.
= 4.7.0 =
* enable tab select for panels with invalid inputs.
* flag tabs with invalid fields.
* flag slides with invalid fields.
* flag collapsible sections with invalid fields.
* fix default dropdown cf7 tag value.
* upgrade glider.js to fix slider submit button bug.
= 4.6.2 =
* fix prefill for hidden fields.
* deprecated cf7sg_mailtag_grid_fields hook, replaced with existing cf7sg_mailtag_{$field_name} for tabbed/tabled fields.
= 4.6.1 =
* fix redirect bug.
= 4.6.0 =
* fix tabs row controls.
* cleanup row removal in js script.
* added js functions cf7sgRemoveTab cf7sgCountTabs
* clean up tab removal in js script.
* added new tutorial.
* upgraded CodeMirror to v5.58.3.
* improved handling of multi-form on page.
* fix textarea tag default value bug.
* added page redirect option.
* deprecated hooks cf7_smart_grid_form_id / smart_grid_register_custom_script.
* added action hook cf7sg_enqueue_custom_script-$form_key.
= 4.5.0 =
* fix row cloning label removal bug.
* added js fn cf7sgCountRows, cf7sgRemoveRow.
* added new tutorial.
= 4.4.4 =
* fix codemirror tag match.
* improve corrupt form code retrieval when js error prevents editor loading.
= 4.4.3 =
* fix for conditional groups.
* fix for condiitonal plugin admin submit.
= 4.4.2 =
* fix preview translated forms.
= 4.4.1 =
* fix js min.
= 4.4.0 =
* added action 'cf7sg_enqueue_admin_editor_styles'
* added action 'cf7sg_enqueue_admin_table_styles'
* added action 'cf7sg_enqueue_admin_table_scripts'
* added filter 'cf7sg_include_hidden_form_fields'.
* added filter 'cf7sg_prefill_form_fields'.
* differentiate preview forms on submission.
* fix rtl full-screen/custom editors buttons.
* improve full-screen.
* enabled pre-fill of form fields.
* add auto re-fill for preview/demo forms.
* enable toggle fields within sliders/accordions.
* added filter 'cf7sg_max_form_width' to change max width of form.
* added filter 'cf7sg_responsive_width'  to change responsive width cutoff limit.
* updated translations PO files.
* added tr_TR, de_DE, he_IL locales .po files to languages.
= 4.3.2 =
* fix js slider bug.
= 4.3.1 =
* rotate rows/columns for rtl forms to fix mobile screen collaspe order.
* added tutorial for modular forms.
= 4.3.0 =
* added filter 'cf7sg_new_cf7_form_template_arguments' to enable new form template arguments, cf7-polylang can set locale.
* fixed toggle in accordion row field disabled bug.
* enable preview link for forms with cf7sg_page custom post type.
= 4.2.1 =
* fix notice dismissal bug.
= 4.2.0 =
* fix regex look back for Safari browser.
* replaced slider js plugin with Glider-js.
* added new video tutorial for slider forms.
= 4.1.4 =
* fix static reference.
= 4.1.3 =
* better control of cf7 form requests.
* js regex look behind fix.
= 4.1.2 =
* fix grid-loading script bug.
* fix tab navigation in UI editor.
= 4.1.1 =
* improve loading of resources for non-grid forms.
= 4.1.0 =
* added admin notices for plugin upgrade validation.
* fix singular fields with array values mail bug.
= 4.0.1 =
* tables in tabs data consolidation bug fix.
* custom css/js dir creation fix.
* css/js editor window sroll lock fix.
= 4.0.0 =
* custom js/css editors.
* added 'cf7sg_dynamic_dropdown_taxonomy_query' filter.
* slider forms.
* multi-step forms.
* added 'cf7sg_remove_table_row_labels' filter to remove labels in table rows.
* added 'cf7sg_admin_form_editor_jstags_other_items' for plugin developer.
* added 'cf7sg_admin_form_editor_jstags_last_item' for plugin developers.
* enabled javascript helper codes on js editor.
* added js functionality to further customise tables and tab structures.
* fixed table id attributes in tabbed sections.
* improved grouped toggles.
* tutorial admin page with embeded youtube videos.
* new pointers.
* added a general form mailtag.
* added html table construct for table/tabbed fields in mailtags.
* added mailtag for toggles and grouped toggles.
