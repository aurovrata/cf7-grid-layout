=== Smart Grid Layout Design for Contact Form 7 ===
Contributors: aurovrata
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=CNEEQHW889FE6
Tags: contact form 7, contact form 7 module, form layout, styling, contact form 7 extension, responsive layout
Requires at least: 4.7
Requires PHP: 5.6
Tested up to: 4.8.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugins allows pure css responsive grid layouts for contact form 7.  It enables rich internlinking of your CMS data via taxonomy/posts populated dropdown fields.  It also enables modular design of complex forms.

== Description ==

The plugin uses the [smart-grid](http://origin.css.gd/) css plugin to build beautiful form layouts.  It introduces a graphical editor to design your forms, as well as a coloured html systax editor built usign the excellent CodeMirror editor.  It is now possible to design smart layouts with ease.

In addition the plugin also introduces multiple smart input functionalities, such as,

* *tabled input sections*: these allows you to group several input fields as table rows, the plugin will automatically add an 'Add Row' button to your front end form, giving your users the ability to add multiple rows of your grouped fields.
* *tabbed sections*: with this plugin you can build tabbed sections of fields, allowing your uses to add additional tabs.  It is a similar concept to the tabled input section above, but in a tabbed layout insead.
* *collapsible sections*: for long and complex forms you can now group your front-end fields into collapsible sections, making it easier for user to see the big picture.
* *resusable sub-forms*: if you have fields which repeat across multiple forms, you can now build a sub-form which you can include in your form, saving you the troule of redesigning the form each time, but also making large forms much easier to maintain.
* *form categories*: the plugin introduces form taxnonmy to classify your forms for the use of online registration where users may need to be associated with a given set of forms to access.
* *dynamic dropdown fields*: these are special select fields which you can populate with either existing post titles, or managed lists such as units, or even using a custom filter.  This makes dynamic interlinking of existing CMS data in your dahsboard a piece of cake, giving you a very powerful tool for data capture.
* *plays nice with Post My CF7 Form plugin*: and best of all you can map all your forms to custom posts using the now stable [Post My CF7 Form](https://wordpress.org/plugins/post-my-contact-form-7/) plugin.
* *redesign the form editor*: this plugin now uses the WordPress default post editor page to edit/build forms, therefore making it easier for developer to plugin their functionality on top, while preserving all the hooks of Contact Form 7.

= Checkout our other CF7 plugin extensions =

* [CF7 Polylang Module](https://wordpress.org/plugins/cf7-polylang/) - this plugin allows you to create forms in different languages for a multi-language website.  The plugin requires the [Polylang](https://wordpress.org/plugins/polylang/) plugin to be installed in order to manage translations.

* [CF7 Multi-slide Module](https://wordpress.org/plugins/cf7-multislide/) - this plugin allows you to build a multi-step form using a slider.  Each slide has cf7 form which are linked together and submitted as a single form.

* [Post My CF7 Form](https://wordpress.org/plugins/post-my-contact-form-7/) - this plugin allows you to save you cf7 form to a custom post, map your fields to meta fields or taxonomy.  It also allows you to pre-fill fields before your form  is displayed.

* [CF7 Google Map](https://wordpress.org/plugins/cf7-google-map/) - allows google maps to be inserted into a Contact Form 7.  Unlike other plugins, this one allows map settings to be done at the form level, enabling diverse maps to be configured for each forms.

* [Smart Grid Layout Design for CF7](https://wordpress.org/plugins/cf7-grid-layout/) - allows responsive grid layout Conctact Form 7 form designs, enabling modular designs of complex forms, and rich inter-linking of your CMS data with taxonomy/posts populated dynamic dropdown fields.

= Documentation =

This plugin has a substantial set of FAQs and screenshots that is has a lot of information.  Please go through the FAQs and screenshot captions to understand how to use the basic functionality.

The plugin has a number of hooks (filters and actions) which can be leveaged to further customise your form layouts and fields.  Please refer to the Helper Metabox availble in the form post editor when you create/edit a form.  The helpers have commented code snippets whch you can copy to and paste in your `functions.php` file to further undertand how to use them.

Get in touch in the support forum if you some clarification.

A video will be made available in the near future to further demonstrate how to use this plugin, so what this space!

= Support Open-source effort =

This plugin would not have been possible without the following open-source efforts.  Please consider visiting these pluging pages and making a donation to its authors to say thank you.  Even small amount of beer money is always appreciated. Alternatively/additionaly you can help in the maintenance or translation effort.

* [Beautify](https://github.com/beautify-web/js-beautify) - a JQuery plugin to beautify html text, used in the text editor of this plugin.
* [CodeMirror](https://codemirror.net/) - a remarkable JQuery text editor that allows for colour-coded highlighting among many other functionality.  Used to edit form source code in text editor of this plugin.
* [CSS Smart Grid](http://origin.css.gd/) - a CSS plugin that allows for intuitive css styling of responsive grid layouts.  Used for building the responsive form layouts.
* [JQuery Clipboard](https://clipboardjs.com/) - copy text to the clipboard, used for helper links.
* [JQuery Nice Select](http://hernansartorio.com/jquery-nice-select/) - makes beautiful dropdown fields.
* [JQuery Select2](https://select2.org/) - this plugin converts dropdowns into powerful searcheable dropdown fields.
* [JQuery Toggles](https://simontabor.com/labs/toggles/) - enbles pretty toggle switches on collapsible sections.
* [PHP Query](https://github.com/punkave/phpQuery) - a php class that enables traversing and manipulation of html documents using css selectors like JQuery.  This is used to build the modular functionality of form designs.


== Installation ==

1. Install the Contact Form 7 plugin.
2. Unpack this plugin archive file into your wp-content/plugins folder.
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Read the FAQs & Screenshot captions to understand how to use this plugin.

== Frequently Asked Questions ==

= How do I create a dynamic dropdown list ?=

simply create a new dynamic dropdown field using the added tag in the list of available tags and select the type of dynamic list you want to populate with.  You create a list which will appear in the Information metabox in your edit page once you save your form.  It uses the taxonomy management funcitonality of WordPress but is not associated with any posts as such.  Simply edit the list by adding new terms to your list.  These will appear in your dropdown field.

Alternatively select an existing posts from your dashboard and the post titles will be used to populate the dropdown.

You also have the option to select a dynamic filter, and then the plugin will hook your functionality in your functions.php file to get your custom list.

=How do I make nice dropdown selects? =

When you create a dynamic dropdown, or a cf7 dropdown field, you add `class:nice-select` to your tag or 'nice-select' in the class text field option.  The plugin will convert your dropdown into a beautiful [nice-select](http://hernansartorio.com/jquery-nice-select/) field.

=How do I make powerful select2 dropdowns? =

When you create a dynamic dropdown, or a cf7 dropdown field, you add `class:select2` to your tag or 'select2' in the class text field option.  The plugin will convert your dropdown into a powerful and searchable [JQuery Select2](https://select2.org/) field.  You can also enable select custum user options (known as tagging in the plugin documentation: https://select2.org/tagging) by adding the 'tags' class to your cf7 tag, `class:tags`.

= How do I display a pretty toggle switch on my collapsible section? =

When you convert a row into a collapsible section (see [Screenshot 8](https://wordpress.org/plugins/cf7-grid-layout/#screenshots)), you can check the toggle option which will insert 2 data attributes into your html row, with labels for the toggle switch on/off state.  The default labels are 'yes' for on and 'no' for off.  You can change the `data-on` and `data-off` attributes in the html text editor.  Your form will display a [toggle switch](https://simontabor.com/labs/toggles/).

= How do I reset a form?=
Navigate to the 'text' editor of the form, select all the html and delete the editor content.  The plugin will create 1 default row with a single column in the 'grid' editor from which you can design your form afresh.

= The columns are filled with default html field wrappers, how do I change this ?=

The plugin wraps each cf7 tag with the following html,
`
<div class="field">
   <label></label>
   [CF7-tag shotcode]
   <p class="info-tip"></p>
</div>`
Furthermore, CF7 tags that are marked as required, have the following html `<em>*</em>` appended in the <label>. This enables for smart looking fields.

If you want to only modify a single column, simply find the column in the 'text' editor and modify the html wrapper as per your requirements.

If you want to change these wrappers for all the fields, then you can use the hook filters, `cf7sg_pre_cf7_field_html` to change the html before the cf7 tag, `cf7sg_post_cf7_field_html` to chagne the html after the cf7 tag, and `cf7sg_required_cf7_field_html` to change the required field label markup.

Each filter has 2 attributes passed to the hooked function,
`
add_filter('cf7sg_pre_cf7_field_html', 'filter_pre_html', 10, 2);
function filter_pre_html($html, $cf7_key){
  //the $html string to change
  //the $cf7_key is a unique string key to identify your form, which you can find in your form table i nthe dashboard.
}`

== Screenshots ==

1. This plugin replaces the CF7 post table page and post edit pages with WordPress core post edit and post pages.  This means that other plugins that build on WordPress standards for custom admin dashboard functionality sould now play nicely with CF7.  One out-of-the-box improvement is the ability to customise the CF7 form table columns being displayed.
2. Form type taxonomy is introduced to manage forms.  This is useful when designing forms that change accoring to the type of users and therefore leverages WP core taxonomy CMS functionality.
3. The CF7 form editor page is now replaced by the WP core `post.php` page for custom posts.  It offers a UI grid building tool to help design grid layouts.  This is done using a responsive CSS plugin, Smart grid, which divides a row into 12 equal width coloumns.
4. The CSS smart-grid plugin columns layout, the total width of a row including column off-sets need to add-up to 12 equivalent widths.  If you try to add more columns which takes the total width beyond these 12 units, you will end up with the extra columns flowing below.
5. The 'text' tab of the form designer allows you to edit the form source in a beautifu CodeMirror html editor with colour highlighted markup for both html as well as CF7 tags. This makes its a lot easier to customise your source code.  Switching back to the grid mode, the plugin will attempt to identify new rows/columns, but if it fails to recognise your custom html code, it will simply leave it as is and display it a separate textarea.
6. You can add new rows with the '+' button in the row controls, delete with the 'bin' button which only appears on the 2nd row onwards. You can edit the row with the 'pencil' button.  Similarly you can add columns with the '+' button on the column controls, delete with the 'bin' button (only available on the 2nd column onwards), and edit a column using the 'pencil' button.
7. Columns can be resized and offset.
8. A row can be converted into a collapsible 'accordion' style section to collapse part of your form into more manageable parts.
9. Columns can further be converted into grids, allowing for more complex layouts such as multiple rows within a column.
10. Once a column is converted into a grid, its inner rows have added capabilities.  You an convert an inner-row into a tabled section of fields.  Any fields which are added to this row will be cloneable into multiple rows by a user and therefore submit mutiple sets of these fields.
11. Here is an example of a tabled section of a form, where the plugin automatically inserts a button for your uses to clone the row and any fields within it.
12. When a row is added below a table row, you can convert it to a table footer. This is iseful if you want to add some instructions at the bottom of your table (or table footer headings).  The plugin will then insert the 'Add Row' button below this row.
13. Similar to a table row, you can convert an inner-row into a cloneable tabbed section of fields.  This is similar to the table concept except that users can add additional tabs which clone the entire set of fields presents in the tabbed row.  Make sure you give your tab a label.
14. On the form front-end your users will be able to add new tabs.
15. A column can be converted into an entire existing cf7 form by editing the column ('pencil' button) and selecting the option 'Insert Form'.  This will convert the column into a dropdown field from which you can selelect an existing form that you have previously designed.  This makes for modular design of forms.
16. This plugin introduces dynamic dropdowns, which allow you to manage dropdown field options using various content managed in your WordPress dashboard.  For example you can use taxonomy terms as options, or you can use existing post types' allowing your users to select/link exisitng content from your WordPress CMS managed data to their submission.  The dynamic dropdown can also be programmatically populated using a hook filter with the last option 'Custom'.
17. A benchmark field is available which allows you to display waarning when certain input values breach the benchmark limit.  The benchmark field also emits a javascript event when the limit is breached so that custom javascript action can be executed.




== Changelog ==
= 1.0 =
* A working plugin that plays nice with Post My CF7 Form plugin.
