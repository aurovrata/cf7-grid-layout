<?php
require_once plugin_dir_path( __FILE__ ) . 'admin/cf7-post-admin-table.php';

//reset the cf7 admin table
$cf7_admin = Cf7_WP_Post_Table::set_table();
if(!$cf7_admin->hooks()){
  add_action( 'admin_enqueue_scripts', array($cf7_admin , 'enqueue_styles'));
  add_action( 'admin_enqueue_scripts', array($cf7_admin , 'enqueue_script'));
  //add_action( 'admin_enqueue_scripts', array($this, 'enqueue_scripts') ));
  //modify the CF7 post type
  add_action('init', array( $cf7_admin, 'modify_cf7_post_type' ) , 20 );
  //cf7 sub-menu
  add_action('admin_menu', array( $cf7_admin, 'add_cf7_sub_menu' ));
  add_filter( 'custom_menu_order', array( $cf7_admin, 'change_cf7_submenu_order' ),1000);
  //modify the cf7 list table columns
  add_filter('manage_wpcf7_contact_form_posts_columns' , array( $cf7_admin, 'modify_cf7_list_columns' ));
  add_action('manage_wpcf7_contact_form_posts_custom_column', array( $cf7_admin, 'populate_custom_column') ,10,2 );
  add_filter('post_row_actions', array( $cf7_admin, 'modify_cf7_list_row_actions') , 10, 2 );
  //change the 'Add New' button link.
  //contact form 7 taxonomy type
  add_action('init', array( $cf7_admin, 'register_cf7_taxonomy' ) , 20 );

  add_action( 'quick_edit_custom_box',   array( $cf7_admin, 'quick_edit_box'), 100, 2 );
  //cf7-form shortcode
  add_shortcode( 'cf7-form', array( $cf7_admin, 'shortcode') );
  add_shortcode( 'cf7form', array( $cf7_admin, 'shortcode') );
  /** @since 2.6.0 */
  add_action('admin_footer', array($cf7_admin, 'update_form_highlight'));
}
