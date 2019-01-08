=== Smart Grid-Layout Design for Contact Form 7 ===
Contributors: aurovrata
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=CNEEQHW889FE6
Tags: contact form 7, contact form 7 module, form layout, styling, contact form 7 extension, responsive layout
Requires at least: 4.7
Requires PHP: 5.6
Tested up to: 5.0.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugins allows pure css responsive grid layouts for contact form 7.  It enables rich interlinking of your CMS data via taxonomy/posts populated dropdown fields.  It also enables modular design of complex forms.

== Description ==
**NOTE WordPress 5.0 & Gutenberg editor update** - this plugin is now compatible with WP 5.0, however, the Gutenberg editor does not recognise [shortcodes with hyphons](https://www.getgutenberg.io/documentation/content/shortcodes/) (dash) characters, so a new shortcode is introduced from v2.5, `[cf7form ...]`. Your form table will now display this new shortcode.  Creating new pages/posts with Gutenberg from now on will require you use this new shortcode.  The old remains active for existing content.

The plugin uses the [smart-grid](http://origin.css.gd/) css plugin to build beautiful form layouts.  It introduces a graphical editor to design your forms, as well as a coloured html syntax editor built using the excellent CodeMirror editor.  It is now possible to design smart layouts with ease.

In addition the plugin also introduces multiple smart input functionalities, such as,

* **tabled input sections**: these allows you to group several input fields as table rows, the plugin will automatically add an 'Add Row' button to your front end form, giving your users the ability to add multiple rows of your grouped fields.
* **tabbed sections**: with this plugin you can build tabbed sections of fields, allowing your users to add additional tabs.  It is a similar concept to the tabled input section above, but in a tabbed layout insead.
* **collapsible sections**: for long and complex forms you can now group your front-end fields into collapsible sections, making it easier for user to see the big picture.
* **toggled collapsible sections** for optional sections.  A toggle with a default Yes/No value is inserted, allowing your users to submit optional fields which within the section can be set to required in your design (See FAQ section for more info).
* **reusable sub-forms**: if you have fields which repeat across multiple forms, you can now build a sub-form which you can include in your form, saving you the trouble of redesigning the form each time, but also making large forms much easier to maintain.
* **form categories**: the plugin introduces form taxonomy to classify your forms for the use of online registration where users may need to be associated with a given set of forms to access.
* **dynamic dropdown fields**: these are special select fields which you can populate with either existing post titles, or managed lists such as units, or even using a custom filter.  This makes dynamic interlinking of existing CMS data in your dashboard a piece of cake, giving you a very powerful tool for data capture.
* **plays nice with Post My CF7 Form plugin**: and best of all you can map all your forms to custom posts using the now stable [Post My CF7 Form](https://wordpress.org/plugins/post-my-contact-form-7/) plugin.
* **redesign the form editor**: this plugin now uses the WordPress default post editor page to edit/build forms, therefore making it easier for developer to plugin their functionality on top, while preserving all the hooks of Contact Form 7.

**Looking for Collaborators**
Are you a WordPress developer or an HTML/Javascript master?  Want to collaborate on this plugin?  There are some really great pieces of functionality that are in the roadmap for this plugin, but I just don't have the time or resources to get them all on file in a timely manner.  So join me on [GitHub](https://github.com/aurovrata/cf7-grid-layout/wiki/Roadmap) if you want to collaborate.

= Checkout our other CF7 plugin extensions =

* [CF7 Polylang Module](https://wordpress.org/plugins/cf7-polylang/) - this plugin allows you to create forms in different languages for a multi-language website.  The plugin requires the [Polylang](https://wordpress.org/plugins/polylang/) plugin to be installed in order to manage translations.

* [CF7 Multi-slide Module](https://wordpress.org/plugins/cf7-multislide/) - this plugin allows you to build a multi-step form using a slider.  Each slide has cf7 form which are linked together and submitted as a single form.

* [Post My CF7 Form](https://wordpress.org/plugins/post-my-contact-form-7/) - this plugin allows you to save you cf7 form to a custom post, map your fields to meta fields or taxonomy.  It also allows you to pre-fill fields before your form  is displayed.

* [CF7 Google Map](https://wordpress.org/plugins/cf7-google-map/) - allows google maps to be inserted into a Contact Form 7.  Unlike other plugins, this one allows map settings to be done at the form level, enabling diverse maps to be configured for each forms.

* [Smart Grid-Layout Design for CF7](https://wordpress.org/plugins/cf7-grid-layout/) - allows responsive grid layout Contact Form 7 form designs, enabling modular designs of complex forms, and rich inter-linking of your CMS data with taxonomy/posts populated dynamic dropdown fields.

= Documentation =

This plugin has a substantial set of FAQs and screenshots that is has a lot of information.  Please go through the FAQs and screenshot captions to understand how to use the basic functionality.

The plugin has a number of hooks (filters and actions) which can be leveraged to further customise your form layouts and fields.  Please refer to the Helper Metabox available in the form post editor when you create/edit a form.  The helpers have commented code snippets which you can copy to and paste in your `functions.php` file to further understand how to use them.

Get in touch in the support forum if you some clarification.

A video will be made available in the near future to further demonstrate how to use this plugin, so what this space!

= Support Open-source effort =

This plugin would not have been possible without the following open-source efforts.  Please consider visiting these plugins pages and making a donation to its authors to say thank you.  Even small amount of beer money is always appreciated. Alternatively/additionally you can help in the maintenance or translation effort.

* [Beautify](https://github.com/beautify-web/js-beautify) - a JQuery plugin to beautify html text, used in the text editor of this plugin.
* [CodeMirror](https://codemirror.net/) - a remarkable JQuery text editor that allows for colour-coded highlighting among many other functionality.  Used to edit form source code in text editor of this plugin.
* [CSS Smart Grid](http://origin.css.gd/) - a CSS plugin that allows for intuitive css styling of responsive grid layouts.  Used for building the responsive form layouts.
* [JQuery Clipboard](https://clipboardjs.com/) - copy text to the clipboard, used for helper links.
* [JQuery Nice Select](http://hernansartorio.com/jquery-nice-select/) - makes beautiful dropdown fields.
* [JQuery Select2](https://select2.org/) - this plugin converts dropdowns into powerful searchable dropdown fields.
* [JQuery Toggles](https://simontabor.com/labs/toggles/) - enables pretty toggle switches on collapsible sections.
* [PHP Query](https://github.com/punkave/phpQuery) - a php class that enables traversing and manipulation of html documents using css selectors like JQuery.  This is used to build the modular functionality of form designs.

= Thanks to =
Birmania (@birmania) for providing:
* a fix for js toggles.
* a fix for file fields in tabs as mail attachments

= Privacy Notices =

This plugin, in itself, does not:

* track users by stealth;
* write any user personal data to the database;
* send any data to external servers;
* use cookies.

== Installation ==

1. Install the Contact Form 7 plugin.
2. Unpack this plugin archive file into your wp-content/plugins folder.
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Read the FAQs & Screenshot captions to understand how to use this plugin.

== Frequently Asked Questions ==

= 1.How do I drag and sort columns in the grid editor ? =

Columns can be rearranged within a row by simply dragging and dropping using the handled icon in the columns head.  You can also drag and drop a column into another row, if the target row has sufficient space to receive the column, else a warning msg will appear.  In that case, make some room in the target row and/or resize the column so as to ensure it will fit in the row.

Similarly you can re-organise your rows within a given grid.  Your initial form is grid.  You can convert an existing column into a grid.

= 2.How do I create a dynamic dropdown list ?=

simply create a new dynamic dropdown field using the added tag in the list of available tags and select the type of dynamic list you want to populate with.  You create a list which will appear in the Information metabox in your edit page once you save your form.  It uses the taxonomy management functionality of WordPress but is not associated with any posts as such.  Simply edit the list by adding new terms to your list.  These will appear in your dropdown field.

Alternatively select an existing posts from your dashboard and the post titles will be used to populate the dropdown.

You also have the option to select a dynamic filter, and then the plugin will hook your functionality in your functions.php file to get your custom list.

= 3.How do I make nice dropdown selects? =

When you create a dynamic dropdown, or a cf7 dropdown field, you add `class:nice-select` to your tag or 'nice-select' in the class text field option.  The plugin will convert your dropdown into a beautiful [nice-select](http://hernansartorio.com/jquery-nice-select/) field.

= 4.How do I make powerful select2 dropdowns? =

When you create a dynamic dropdown, or a cf7 dropdown field, you add `class:select2` to your tag or 'select2' in the class text field option.  The plugin will convert your dropdown into a powerful and searchable [JQuery Select2](https://select2.org/) field.  You can also enable select custom user options (known as tagging in the plugin documentation: https://select2.org/tagging) by adding the 'tags' class to your cf7 tag, `class:tags`.

= 5.How do I display a pretty toggle switch on my collapsible section? =

When you convert a row into a collapsible section (see [Screenshot 8](https://wordpress.org/plugins/cf7-grid-layout/#screenshots)), you can check the toggle option which will insert 2 data attributes into your html row, with labels for the toggle switch on/off state.  The default labels are 'yes' for on and 'no' for off.  You can change the `data-on` and `data-off` attributes in the html text editor.  Your form will display a [toggle switch](https://simontabor.com/labs/toggles/).

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
The plugin will look for a javascript file `js/{$cf7key}.js` from the base of your theme root folder and load it on the page where your form is displayed.  Create a `js/` subfolder in your theme (or child theme folder), and create a file called `<your-form-cf7key>.js` in which you place your custom javascript code.

The `$cf7key` is the unique key associated with your form which you can find in the Information metabox of your form edit page.

If you wish to [wp_enqueue_script](https://developer.wordpress.org/reference/functions/wp_enqueue_script/) a general javascript file for all your forms, you can use the hook `smart_grid_register_scripts`,
`add_action('smart_grid_register_scripts', 'add_js_to_forms');
function add_js_to_forms(){
   wp_enqueue_script('my-custom-script', '<path to your custom js file>', array(), null, true);
}`
**custom styling**
Similarly you can create a `css/` subfolder in your theme folder and create a file in it called `<your-form-cf7key>.css` and place your custom styling for your form.  The plugin will then load this css file on the page where your form is displayed.

If you wish to [wp_enqueue_styles](https://developer.wordpress.org/reference/functions/wp_enqueue_style/) a general css stylesheet file for all your forms, you can use the hook `smart_grid_register_styles`,
`add_action('smart_grid_register_styles', 'add_css_to_forms');
function add_css_to_forms(){
   wp_enqueue_style('my-custom-style', '<path to your custom sheet>', array(), null, 'all');
}`

= 9.Can I have required fields in toggled sections? =
Yes, as of v1.1 of this plugin, toggled sections input fields are disabled when collapsed/unused, and therefore any fields within these sections are not submitted.  So you can design fields to be required when toggled sections are used, and the fields will be validated accordingly too.

Please note that in the back-end, these fields which are listed in the form layout but are not submitted are set eventually set as null in the filtered submitted data.  So if you hook a functionality post the form-submission, be aware that you also need to test submitted values for `NULL` as opposed to empty.

= 10.Can I group toggled sections so as to have either/or sections ?=
Yes, with v1.1 you can the `data-group` attribute which by default is empty to regroup toggled sections and therefore ensure that only 1 of these grouped sections is used by a user.  Edit your form in the html editor (Text tab) and fill the `data-group` attribute with the same value (no spaces) for each toggled section (`div.container.with-toggle`) you wish to re-group.

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

= 12.How can I navigate/search the text editor? =
As of v1.3 a search functionality has been introduced.  Click anywhere in the text editor and press your search key combination (for example 'Ctrl+F' on windows/linux), you will see a search box at the top of the editor.  This is useful if you want to edit a specific field, so once you have added a new cf7 field tag with the name say 'your-email', you can then search for it on the text editor to locate the code.

= 13. Can the text editor highlighting be turned off? =
yes, you can use the following filters to either switch off only the shortcode highlighting, (add the following line to your functions.php file)

`add_filter('cf7sg_admin_editor_mode', function($mode, $form_key){return '';}, 10, 2);`

and you can also turn off highlight altogether by inserting this additional line to your functions.php file,
`add_filter('cf7sg_admin_editor_theme', function($theme, $form_key){return '';}, 10, 2);`

= 14. Can collapsible sections be shown as open by default? =

Yes, identify the row in your text editor which implements your collapsible section, and add the `data-open="true"` attribute to it,

`<div class="container cf7sg-collapsible" data-open="true" ...`

= 14. How can I display a table of fields in a mail message? =
If you have a set of fields that are in a table/tab structure, the plugin is not aware of their relationship and as such does not build a table layout in a mail when you use them as tags in a message.  However, a filter is provided for you to achive this.  The filter #3 in the Post-form submit hooks allows you to build a table layout in an *html* mail.  Ensure the html format checkbox is selected in the your mail settings, else the filter will not fire.
In you mail message body, place the mail tags contiguously for each field that is present in your table.  Hence, assuming you have a table with 3 fields, field-one, field-two, field-three.  Place their tags in the mail body as

`[field-one][field-two][field-three]`

copy the filter helper code and place it in your `functions.php` file.  The code sample assumes the above example fields and sets up a list element for each field, along with a column header.  These list elements are then styled to display them as tables in your mail.

== Screenshots ==

1. (1) This plugin replaces the CF7 post table page and post edit pages with WordPress core post edit and post pages.  This means that other plugins that build on WordPress standards for custom admin dashboard functionality should now play nicely with CF7.  One out-of-the-box improvement is the ability to customise the CF7 form table columns being displayed.
2. (2) Form type taxonomy is introduced to manage forms.  This is useful when designing forms that change according to the type of users and therefore leverages WP core taxonomy CMS functionality.
3. (3) The CF7 form editor page is now replaced by the WP core `post.php` page for custom posts.  It offers a UI grid building tool to help design grid layouts.  This is done using a responsive CSS plugin, Smart grid, which divides a row into 12 equal width columns.
4. (4)The CSS smart-grid plugin columns layout, the total width of a row including column offsets need to add-up to 12 equivalent widths.  If you try to add more columns which takes the total width beyond these 12 units, you will end up with the extra columns flowing below.
5. (5) The 'text' tab of the form designer allows you to edit the form source in a beautiful CodeMirror html editor with colour highlighted markup for both html as well as CF7 tags. This makes it a lot easier to customise your source code.  Switching back to the grid mode, the plugin will attempt to identify new rows/columns, but if it fails to recognise your custom html code, it will simply leave it as is and display it a separate textarea.
6. (6) You can add new rows with the '+' button in the row controls, delete with the 'bin' button which only appears on the 2nd row onwards. You can edit the row with the 'pencil' button.  Similarly you can add columns with the '+' button on the column controls, delete with the 'bin' button (only available on the 2nd column onwards), and edit a column using the 'pencil' button.
7. (7) Columns can be resized and offset.
8. (8) A row can be converted into a collapsible 'accordion' style section to collapse part of your form into more manageable parts.
9. (9) Columns can further be converted into grids, allowing for more complex layouts such as multiple rows within a column.
10. (10) Once a column is converted into a grid, its inner rows have added capabilities.  You can convert an inner-row into a tabled section of fields.  Any fields which are added to this row will be cloneable into multiple rows by a user and therefore submit multiple sets of these fields.
11. (11) Here is an example of a tabled section of a form, where the plugin automatically inserts a button for your uses to clone the row and any fields within it.
12. (12) When a row is added below a table row, you can convert it to a table footer. This is useful if you want to add some instructions at the bottom of your table (or table footer headings).  The plugin will then insert the 'Add Row' button below this row.
13. (13) Similar to a table row, you can convert an inner-row into a cloneable tabbed section of fields.  This is similar to the table concept except that users can add additional tabs which clone the entire set of fields presents in the tabbed row.  Make sure you give your tab a label.
14. (14) On the form front-end your users will be able to add new tabs.
15. (15) A column can be converted into an entire existing cf7 form by editing the column ('pencil' button) and selecting the option 'Insert Form'.  This will convert the column into a dropdown field from which you can select an existing form that you have previously designed.  This makes for modular design of forms.
16. (16) This plugin introduces dynamic dropdowns, which allow you to manage dropdown field options using various content managed in your WordPress dashboard.  For example you can use taxonomy terms as options, or you can use existing post types' allowing your users to select/link existing content from your WordPress CMS managed data to their submission.  The dynamic dropdown can also be programmatically populated using a hook filter with the last option 'Custom'.
17. (17) If you create a dynamic-dropdown field and select filter as source, the plugin expects the options to be provided by a filter.  Your field cell will have an extra 'filter' icon at the top, click it to reveal the filters available.  You can click on the filter link which will copy a helper code snipet which you an paste in your *functions.php* file and customise to provide the options list.
18. (18) A benchmark field is available which allows you to display warning when certain input values breach the benchmark limit.  The benchmark field also emits a javascript event when the limit is breached so that custom javascript action can be executed.
19. (19) Click on the code icon in any given column cell of the grid UI editor and it will take you to the equivalent code lines in the text editor.
20. (20) v2.0 of the plugin introduces inline field hooks helpers.  These are specific hooks which allow to filter custom aspect of the field.  Not all tags have field specific hooks, so if any are defined they will show up with the icon in the control bar.
21. (21) The plugin include hooks for further customisation.  Handy helper code snippets are provided within form editor in the metabox 'Actions & Filers', with a set of links on which you can click to copy  the code snippet and paste it in your *functions.php* file.

== Changelog ==
= 2.7.1 =
* fix a bug on pretty pointer function call.
* trim values in toggles that are closed.
= 2.7.0 =
* fixed css bug for multiple forms per page.
* added table/tab mail tag filter 'cf7sg_mailtag_grid_fields'.
= 2.6.0 =
* add hover message for disabled submit fields.
* add upgrade warning to update all forms.
* fix bug on existing tables missing id attr.
* fix issue on single toggle required fields.
= 2.5.8 =
* fix admin edit page breaking with cf7 plugin update v5.1
= 2.5.7 =
* fix $has_toggles code.
= 2.5.6 =
* tabs/toggle libraries not being loaded.
* fix bug where all file fields being attached.
= 2.5.5 =
* bug fix singular file field attachments.
= 2.5.4 =
* fixed bug preventing tables being setup properly.
* toggles now are identied when the form is saved and this is used to prevent toggle js/css resources being loaded on the front-end if not required.
= 2.5.3 =
* fix open by default collapsible sections.
= 2.5.2 =
* fix for Gutenberg shortcode format.
= 2.5.1 =
* fix save bug.
= 2.5.0 =
* rewrite of validation engine to better handle array inputs.
* fix for file mail attachments.
* fix for checkbox validation.
= 2.4.1 =
* fix fatal error in cf7 mail tag.
= 2.4.0 =
* fix toggle sections not enalbing fields properly.
* disable toggle slide.
* fix mail attachments of files in tabbed/table sections.
* added 'cf7sg_annotate_mail_attach_grid_files' filter.
= 2.3.0 =
* enable form duplication.
* fix radio buttons on tabs.
* fix required file validation.
= 2.2.0 =
* allows custom filtered dynamic dropdown options to be html string.
= 2.1.6 =
* fix bug find form key by id
= 2.1.5 =
* better tracking of toggled fields to fix checkbox/radio validation bug.
* fix recaptcha field bug.
= 2.1.4 =
* fix new form template setup for polylang managed translated forms.
= 2.1.3 =
* delay loading of cf7 hidden fields to overcome CF7 Conditional Fields plugin [bug](https://wordpress.org/support/topic/bug-plugin-overwrite-cf7-hidden-fields/).
= 2.1.2 =
* bug fix click event on toggled titles.
= 2.1.1 =
* bug fix on helper classes for dynamic dropdowns.
= 2.1.0 =
* fix grid UI css issue.
* added hook to deactivate plugin when cf7 plugin is deactivated.
* improved email tag display for html mails for table and tab field values.
= 2.0.1 =
* bug fix inline helper for multiple tags in single cell.
* inline helper cleanup.
= 2.0.0 =
* cleanup of helpers.
* added dynamic dropdown field filter 'cf7sg_dynamic_dropdown_option_attributes'.
* added dynamic dropdown field filter 'cf7sg_dynamic_dropdown_option_label'.
* added dynamic inline filter helpers on grid UI cells.
