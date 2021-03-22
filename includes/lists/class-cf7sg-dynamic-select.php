<?php

/**
*
*
* @since 4.10.0
*/
require_once plugin_dir_path( __DIR__ ) . 'lists/class-cf7sg-dynamic-list.php';

class CF7SG_Dynamic_Select extends CF7SG_Dynamic_list{

  public function __construct(){
    parent::__construct('dynamic_select',__( 'dynamic-dropdown', 'cf7_2_post' ));
  }
  /**
  * define the style optoins for the dynamic list construct.
  * the stule unique slug will be inserted as class in the cf7 tag object, allowing the field styling.
  * @return Array an array of style-slug => style label.
  */
  public function admin_generator_tag_styles(){
    add_action('cf7sg_'.$this->tag_id.'_admin_tag_style-select2', array($this,'custom_select2'));
    return array(
      'select' => __('HTML Select field','cf7-grid-layout'),
      'select2' => '<a target="_blank" href="https://select2.org/getting-started/basic-usage">'.__('jQuery Select2','cf7-grid-layout').'</a>'
    );
  }
  /**
  * custom html + js script for select2 option.
  */
  public function custom_select2(){
    /*
      the class $tag_id along with the style slug is used to buitl the input id attribute,
      so this input field is: $this->tag_id.'-'.'select2';
      Here an optional 'tags' class will be aded to the CF7 tag object when select2 style is chosen.
    */
    ?>
    <span class="display-none">
      <input id="select2-tags" type="checkbox" disabled value="tags"/>
      <a target="_blank" href="https://select2.org/tagging"><?=__('Enable user options','cf7-grid-layout')?></a>
    </span>
    <script type="text/javascript">
    (function($){
      let $tags = $('#select2-tags'), $select2 = $('#<?=$this->tag_id?>-select2');
      $tags.change(function(e){
        if($tags.is(':checked')){
          $select2.val('select2 tags').change();
        }
      });
      $('.list-style.<?=$this->tag_id?>').change(function(e){
        if($select2.is(':checked')){
          $tags.prop('disabled', false);
          $tags.parent().show();
        }else{
          $tags.prop('checked', false);
          $tags.prop('disabled', true);
          $tags.parent().hide();
          $select2.val('select2');
        }
      });
    })(jQuery);
    </script>
    <?php
  }
}

if( !function_exists('cf7sg_create_dynamic_select_tag') ){
  function cf7sg_create_dynamic_select_tag(){
    //check if there is an existing instance in memory.
    $new_instance = CF7SG_Dynamic_Select::get_instances('dynamic_select');
    if(false===$new_instance) $new_instance=   new CF7SG_Dynamic_Select();
    return $new_instance;
  }
}
