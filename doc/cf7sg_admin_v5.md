## CSS Flexbox form layout.

The forms use flexbox plugin [flexboxgrid.com](http://flexboxgrid.com/) which has 4 responsive column layout schemes,
- `sgc-1/12` extra small screens and the default (mobile -first)
- `sgc-sm-1/12` small screens (mainly tablets and landscape mobile)
- `sgc-md-1/12` medium screens (laptops and small desktops)
- `sgc-lg-1/12` large desltop screens

### Default form layout

On the UI the default columns (`sgc-full | sgc-12`) are configured for extra small screens, ie the will revert to single column layouts on small screens, to replicate previous versions of the plugin.

When a a column is resized/divided, the new column size will retain the default (`sgc-full`), while getting 3 new classes (`sgc-sm-1 sgc-md-x sgc-lg-x`), where `x` is the column size.  This will display the column layout on small/medium/large screens, again in line with previous versions of the plugin.

# Offset layouts #

Similarly to the column layouts, any offsets will have 3 classes added (`sgc-sm-off-x sgc-md-off-x sgc-lg-off-x`)

### Responsive layouts

in a future version of this plugin, users will be able to change the column layout for each screen size.

When selecting a screen size, only allow users to change the column size, but not the number of columns.  Number of columns is set in general (large screen mode) and then the size they render to on smaller screens can be changed.

When switching to responsive mode on the UI editor,

`$.fn.setColumnUIControl` will need to be called to reset the column dropdown fields to the selected screen size classes.
`$.fn.filterColumnControls` do the menus need to be filtered ?  as no longer maintaining 12 col sizes.
`if( $target.is('.centred-menu.column-setting *') ) { //----- show column sizes`  so display the column menu and change the column size will need to deal with the curretn screen size classes.