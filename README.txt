=== Plugin Name ===
Contributors: (this should be a list of wordpress.org userid's)
Donate link: http://syllogic.in
Tags: comments, spam
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Here is a short description of the plugin.  This should be no more than 150 characters.  No markup here.

== Description ==

This is the long description.  No limit, and you can use Markdown (as well as in the following sections).

For backwards compatibility, if this section is missing, the full length of the short description will be used, and
Markdown parsed.

A few notes about the sections above:

*   "Contributors" is a comma separated list of wp.org/wp-plugins.org usernames
*   "Tags" is a comma separated list of tags that apply to the plugin
*   "Requires at least" is the lowest version that the plugin will work on
*   "Tested up to" is the highest version that you've *successfully used to test the plugin*. Note that it might work on
higher versions... this is just the highest one you've verified.
*   Stable tag should indicate the Subversion "tag" of the latest stable version, or "trunk," if you use `/trunk/` for
stable.

    Note that the `readme.txt` of the stable tag is the one that is considered the defining one for the plugin, so
if the `/trunk/readme.txt` file says that the stable tag is `4.3`, then it is `/tags/4.3/readme.txt` that'll be used
for displaying information about the plugin.  In this situation, the only thing considered from the trunk `readme.txt`
is the stable tag pointer.  Thus, if you develop in trunk, you can update the trunk `readme.txt` to reflect changes in
your in-development version, without having that information incorrectly disclosed about the current stable version
that lacks those changes -- as long as the trunk's `readme.txt` points to the correct stable tag.

    If no stable tag is provided, it is assumed that trunk is stable, but you should specify "trunk" if that's where
you put the stable version, in order to eliminate any doubt.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `cf7-grid-layout.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= A question that someone might have =

An answer to that question.

= What about foo bar? =

Answer to foo bar dilemma.

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
12.

== Changelog ==

= 1.0 =
* A change since the previous version.
* Another change.

= 0.5 =
* List versions from most recent at top to oldest at bottom.

== Upgrade Notice ==

= 1.0 =
Upgrade notices describe the reason a user should upgrade.  No more than 300 characters.

= 0.5 =
This version fixes a security related bug.  Upgrade immediately.

== Arbitrary section ==

You may provide arbitrary sections, in the same format as the ones above.  This may be of use for extremely complicated
plugins where more information needs to be conveyed that doesn't fit into the categories of "description" or
"installation."  Arbitrary sections will be shown below the built-in sections outlined above.

== A brief Markdown Example ==

Ordered list:

1. Some feature
1. Another feature
1. Something else about the plugin

Unordered list:

* something
* something else
* third thing

Here's a link to [WordPress](http://wordpress.org/ "Your favorite software") and one to [Markdown's Syntax Documentation][markdown syntax].
Titles are optional, naturally.

[markdown syntax]: http://daringfireball.net/projects/markdown/syntax
            "Markdown is what the parser uses to process much of the readme file"

Markdown uses email style notation for blockquotes and I've been told:
> Asterisks for *emphasis*. Double it up  for **strong**.

`<?php code(); // goes in backticks ?>`
