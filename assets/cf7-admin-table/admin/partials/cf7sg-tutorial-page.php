<?php
/**
* Page to display tutorial video links.
* @since 4.0.0
*/
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-tabs');
wp_enqueue_style('tabs-admin-ui-css', plugin_dir_url( __DIR__ ).'css/cf7sg-tutorials.css',false,'1.0',false);
wp_enqueue_style('grid-css', plugin_dir_url( __DIR__ ).'../../css.gs/smart-grid.admin.css', false,'1.0',false);

?>
<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
<div id="grid-form">
  <div id="topics-tabs" class="jqueryui-tabs">
    <?php
    $others = apply_filters('cf7sg_tutorial_panels', array());
    $tab_titles = array();
    $tab_panels = array();
    foreach($others as $title=>$file){
      if(is_string($title) and strlen($title)>2 and file_exists($file)){
        $tab_titles[] = $title;
        $tab_panels[] = $file;
      }else debug_msg( "CF7SG Tutorial panels ignoring '$title' if length<3 chars or '$file' not found.");
    }
     ?>
     <ul>
       <li><a href="#tab-intro"><?= __('Start','cf7-grid-layout')?></a></li>
       <li><a href="#tab-dynamic"><?= __('Dynamic lists','cf7-grid-layout')?></a></li>
       <li><a href="#tab-toggles"><?= __('Optional sections','cf7-grid-layout')?></a></li>
       <li><a href="#tab-tables"><?= __('Repetitive fields','cf7-grid-layout')?></a></li>
       <li><a href="#tab-slider"><?= __('Multi-slide forms','cf7-grid-layout')?></a></li>
       <li><a href="#tab-advance"><?= __('Advanced forms','cf7-grid-layout')?></a></li>
       <li><a href="#tab-pro"><?= __('Pro-help','cf7-grid-layout')?></a></li>
     <?php $idx=0;foreach($tab_titles as $title) : $idx++?>
       <li><a href="#tab-other-<?=$idx?>"><?=$title?></a></li>
     <?php endforeach;?>
     </ul>
     <div id="tab-intro">
      <?php require_once plugin_dir_path( __FILE__ ) .'cf7sg-tutorials-intro.php'; ?>
     </div>
     <div id="tab-dynamic">
       <?php require_once plugin_dir_path( __FILE__ ) .'cf7sg-tutorials-dynamic.php'; ?>
     </div>
     <div id="tab-toggles">
       <?php require_once plugin_dir_path( __FILE__ ) .'cf7sg-tutorials-toggles.php'; ?>
     </div>
     <div id="tab-tables">
       <?php require_once plugin_dir_path( __FILE__ ) .'cf7sg-tutorials-tables.php'; ?>
     </div>
     <div id="tab-slider">
       <?php require_once plugin_dir_path( __FILE__ ) .'cf7sg-tutorials-slider.php'; ?>
     </div>
     <div id="tab-advance">
       <?php require_once plugin_dir_path( __FILE__ ) .'cf7sg-tutorials-advance.php'; ?>
     </div>
     <div id="tab-pro">
       <?php require_once plugin_dir_path( __FILE__ ) .'cf7sg-tutorials-pro.php'; ?>
     </div>
   <?php $idx=0;foreach($tab_panels as $file) : $idx++?>
     <div id="tab-other-<?=$idx?>">
       <?php require_once $file; ?>
     </div>
   <?php endforeach;?>
  </div>
</div>
<script type="text/javascript">
(function($){
  $(document).ready(function(){
    $('.jqueryui-tabs').each(function(){
      $('#'+this.id).tabs({active:0})
    });
  })
})(jQuery)
</script>
