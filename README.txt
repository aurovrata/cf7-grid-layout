=== Contact Form 7 Smart Grid ===
Contributors: aurovrata
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DVAJJLS8548QY
Tags: contact form 7, contact form 7 module, form layout, styling, contact form 7 extension, responsive layout
Requires at least: 4.7
Requires PHP: 5.6
Tested up to: 4.8
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugins allows pure css responsive form layouts for contact form 7.

== Description ==

The plugin uses teh smart-grid css plugin to build beautiful form layouts.  It introduces a graphical editor to design your forms, as well as a coloured html systax editor built usign the excellent CodeMirror editor.  It is now possible to design smart layouts with ease.

In addition the plugin also introduces multiple smart input functionalities, such as,

* *tabled input sections*: these allows you to group several input fields as table rows, the plugin will automatically add an 'Add Row' button to your front end form, giving your users the ability to add multiple rows of your grouped fields.
* *tabbed sections*: with this plugin you can build tabbed sections of fields, allowing your uses to add additional tabs.  It is a similar concept to the tabled input section above, but in a tabbed layout insead.
* *collapsible sections*: for long and complex forms you can now group your front end fields into collapsible sections, making it easier for user to see the big picture.
* *resusable sub-forms*: if you have fields which repeat across multiple forms, you can now build a sub-form which you can include in your form, saving you the troule of redesigning the form each time, but also making large forms much easier to maintain.
* *form categories*: the plugin introduces form taxnonmy to classify your forms for the use of online registration where users may need to be associated with a given set of forms to access.
* *dynamic dropdown fields*: these are special select fields which you can populate with either existing post titles, or managed lists such as units, or even using a custom filter.  This makes dynamic interlinking of existing CMS data in your dahsboard a piece of cake, giving you a very powerful tool for data capture.
* *plays nice with Post My CF7 Form plugin*: and best of all you can map all your forms to custom posts using the now stable [Post My CF7 Form](https://wordpress.org/plugins/post-my-contact-form-7/) plugin.
* *redesign the form editor*: this plugin now uses the WordPress default post editor page to edit/build forms, therefore making it easier for developer to plugin their functionality on top, while preserving all the hooks of Contact Form 7.

== Installation ==

1. Install the Contact Form 7 plugin.
2. Unpack this plugin archive file into your wp-content/plugins folder.
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How do I create a dynamic dropdown list ?=

simply create a new dynamic dropdown field using the added tag in the list of available tags and select the type of dynamic list you want to populate with.  You create a list which will appear in the Information metabox in your edit page once you save your form.  It uses the taxonomy management funcitonality of WordPress but is not associated with any posts as such.  Simply edit the list by adding new terms to your list.  These will appear in your dropdown field.

Alternatively select an existing posts from your dashbaord and the post titles will be used to populate the dropdown.

You also have the option to select a dynamic filter, and then the plugin will hook your functionality in your functions.php file to get your custom list.


== Screenshots ==

1. The CF7 form editor page is now replaced by the WP core `post.php` page for custom posts.  It offers a UI grid building tool as well as a CodeMirror html editor with colour highlighted markup for both html as well as CF7 tags.  You can add new rows with the '+' button in the row controls, delete with the 'bin' button which only appears on the 2nd row onwards. You can re-order rows with the 'crosshair' button and you can edit the row with the 'pencil' button.  Similarly you can add columns with the '+' button on the column controls, delete with the 'bin' button (only available on the 2nd column onwards), rearrange columns within a row using the 'crosshairs' button, and edit a column using the 'pencil' button.  This plugin also introduces CF7 Form Type taxonomy to organise your forms.
2. The CodeMirror html editor with colour highlighted markup for both html as well as CF7 tags.
3. A row can be converted into a collapsible 'accordion' style section to collapse part of your form into more manageable parts.
4. A column can be converted into an entire existing cf7 form by editing the column ('pencil' button) and selecting the option 'Insert Form'.
5. An existing form can be inserted into the column by selecting the form from the dropdown menu.  Moving to the CodeMirror html editor view will reveal the entire sub-form structure.
6. A column can be converted into a more complex inner-structure by editing the column ('pencil' button in the control bar above the column) and selecting the option 'Make grid' which will convert the column into an inner-row.
7. An inner-row (see previous screenshot) can be converted into a table input which allows a front-end user to add (duplicate) the fields you have entered into the table row and thereby submit multiple entries of the same row set of fields.
8. A row that immediately follows a table input row can be converted into a table footer row which can be used a table caption and the 'Add Row' button added by the plugin when the form is built will be appended below the footer row. (see the next screenshot).
9. An example of a book review form where each row submitted (added) by a user represents a book review.
10. The plugin introduces a dynamic dropdown field which can be populated using a specially created taxonomy (allowing ease of list options form the admin dashboard).
11. The dynamic dropdown field can also be populated using existing custom posts available on your site.  This is useful if you have a form with users need to select options that represent custom-posts in your site. You can further reduce the posts to be displayed by selecting terms from one of its associated taxonomy.  In this example I wish to display a dropdown with a list forms from which a user can register form, but I have differentiated my  forms between partial forms which are used to build final forms.
12. An inner-row can be converted into a tabbed group of fields (see screenshot 6).  Tabbed rows cannot contain table input rows, and currently any array fields will cause errors, so tabbed rows should not contain radio fields (stored as arrays) or multi-select dropdowns.
13. A tabbed row is converted into jQuery tabs which a user can dynamically duplciate to enter multiple sets of the fields present in the initial tab.
14. A row can be converted into a collapsible section to sections of the forms to be closed like an accordion tab.


== Changelog ==

= 1.0 =
* A change since the previous version.
* Another change.
