<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://syllogic.in
 * @since      1.5.0
 *
 * @package    Cf7_2_Post
 * @subpackage Cf7_2_Post/admin/partials
 */
 //TODO: add a check box to include or not address fields
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="control-box cf7-dynamic-select">
  <fieldset>
    <legend>Dynamic Select Dropdown field</legend>
    <table id="dynamic-select-tag-generator" class="form-table">
      <tbody>
        <tr>
      	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'contact-form-7' ) ); ?></label></th>
      	<td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
      	</tr>
        <tr>
        	<th scope="row">Field type</th>
        	<td><input name="required" type="checkbox"> Required field<br /></td>
      	</tr>
        <tr>
          <th scope="row">Taxonomy source</th>
          <td>
            <select class="taxonomy-list">
              <option value="" data-name="" >Choose a Taxonomy</option>
              <option class="cf7sg-new-taxonomy" value="new_taxonomy" data-name="New Category">New Categories</option>
            <?php
            //get options.
            $dropdowns = get_option('_cf7sg_dynamic_dropdown_taxonomy',array());
            $slugs = array();

            foreach($dropdowns as $all_lists){
              foreach($all_lists as $slug => $taxonomy){
                if(isset($slugs[$slug]) ){
                  continue;
                }else{
                  $slugs[$slug] = $slug;
                }
                echo '<option selected data-name="' . $taxonomy['singular'] . '" value="'. $taxonomy['slug'] . '" class="cf7sg-taxonomy">' . $taxonomy['plural'] . '</option>';
              }
            }
            //inset the default post tags and category
            ?>
            <option value="post_tag" data-name="Post Tag" class="system-taxonomy">Post Tags</option>
            <option value="category" data-name="Post Category" class="system-taxonomy">Post Categories</option>
            <?php
            $system_taxonomies = get_taxonomies( array('public'=>true, '_builtin' => false), 'objects' );
            foreach($system_taxonomies as $taxonomy){
              if( !empty($taxonomy_slug) && $taxonomy_slug == $taxonomy->name ) continue;
              echo '<option value="' . $taxonomy->name . '" data-name="' . $taxonomy->labels->singular_name . '" class="system-taxonomy">' . $taxonomy->labels->name . '</option>';
            }
            ?>
            </select>
          </td>
        </tr>
        <tr>
          <th scope="row">New Taxonomy</th>
          <td class="cf72post-new-taxonomy">
            <label>Plural Name<br />
            <input disabled="true" class="cf72post-new-taxonomy" type="text" name="plural_name" value=""></label>
            <label >Singular Name<br />
            <input disabled="true"  class="cf72post-new-taxonomy" type="text" name="singular_name" value=""></label>
            <label>Slug<br />
            <input disabled="true"  class="cf72post-new-taxonomy" type="text" name="taxonomy_slug" value="" /></label>
            <label class="hidden"><input class="cf72post-new-taxonomy" type="checkbox" name="is_hierarchical" />hierarchical</label>
          </td>
        </tr>
        <tr>
          <th>
            <label for="tag-generator-panel-number-id">Id attribute</label>
          </th>
          <td>
            <input name="id" class="idvalue oneline option" id="tag-generator-panel-dynamic-select-id" type="text">
          </td>
        </tr>
        <tr>
          <th>
            <label for="tag-generator-panel-number-class">Class attribute</label>
          </th>
          <td>
            <input name="class" class="classvalue oneline option" id="tag-generator-panel-dynamic-select-class" type="text">
          </td>
        </tr>
      </tbody>
    </table>
  </fieldset>
</div>
<div class="insert-box">
  <input type="hidden" name="values" value="" />
  <input type="text" name="dynamic-select" class="tag code" readonly="readonly" onfocus="this.select()" />

  <div class="submitbox">
      <input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
  </div>

  <br class="clear" />
</div>
<script>
(function( $ ) {
  'use strict';
  var $tag = $('input.tag' , $('#dynamic-select-tag-generator').closest('.control-box').siblings('.insert-box'));
  var $button = $tag.siblings('.submitbox').find('input.insert-tag');
  var $select = $('#dynamic-select-tag-generator select.taxonomy-list');
  var $plural = $('#dynamic-select-tag-generator input[name="plural_name"]');
  var $single = $('#dynamic-select-tag-generator input[name="singular_name"]');
  var $taxonomy = $('#dynamic-select-tag-generator input[name="taxonomy_slug"]');
  var $is_cat = $('#dynamic-select-tag-generator input[name="is_hierarchical"]');
  var $req = $('#dynamic-select-tag-generator input[name="required"]');
  var $name = $('#dynamic-select-tag-generator input[name="name"]');
  var $id = $('#dynamic-select-tag-generator input[name="id"]');
  var $cl = $('#dynamic-select-tag-generator input[name="class"]');

  $('#dynamic-select-tag-generator').on('change',':input', function(event){
    var $target = $(event.target);
    if($target.is('select.taxonomy-list')){
      var $option = $target.find('option:selected');
      $taxonomy.val($target.val());
      $plural.val($option.text());
      $single.val($option.data('name'));
      if($option.is('.cf7sg-new-taxonomy')){
        $plural.prop('disabled',false);
        $single.prop('disabled',false);
        $taxonomy.prop('disabled',false);
        $is_cat.parent().show();
      }else if($target.val().length > 0){
        $plural.prop('disabled',true);
        $single.prop('disabled',true);
        $taxonomy.prop('disabled',true);
        //$plural.val('');
        $is_cat.parent().hide();
      }
    }
    updateCF7Tag();
  });
  //udpate the new category if created
  $button.on('click', function(){
    var $option = $select.find('option:selected');
    if($option.is('.cf7sg-new-taxonomy')){
      $option.after('<option data-name="'+$single.val()+'" value="'+$taxonomy.val()+'">'+$plural.val()+'</option>');
      $option.next().prop('selected', true);
      $plural.prop('disabled',true);
      $single.prop('disabled',true);
      $taxonomy.prop('disabled',true);
      $is_cat.parent().hide();
      //store this new value in the hidden field
      var values = $('input#cf72post-dynamic-select').val();
      if(0 == values.length){
        values = [];
      }else{
        values = JSON.parse(values);
      }
      values[values.length] = {
        "slug":$taxonomy.val(),
        "singular":$single.val(),
        "plural":$plural.val(),
        "hierarchical":$is_cat.is(':checked')
      };
      $('input#cf72post-dynamic-select').val(JSON.stringify(values));
    }
  });


  function updateCF7Tag() {
    var id=$id.val();
    if(id.length > 0) id =' id:'+id;

    var classes = $cl.val();
    if(classes.length > 0){
      var classArr = classes.split(',');
      var idx;
      classes='';
      for(idx=0; idx<classArr.length; idx++){
        classes += " class:" + classArr[idx].trim() + " ";
      }
    }
    var values = ''
    if($taxonomy.val().length > 0){
      values = ' "slug:'+ $taxonomy.val()+'"';
    }
    /*
    if($select.find('option:selected').is('.cf7sg-new-taxonomy')){
      values += ' "hierarchical:'+ $is_cat.is(':checked')+'"';
      values += ' "single:'+ $single.val()+'"';
      values += ' "plural:'+ $plural.val()+'"';
    }*/
    //update tag.
    var type = 'dynamic_select ';
    if($req.is(':checked')) type = 'dynamic_select* ';
    $tag.val('[' + type + $name.val() + id + classes + values +']');
  }
  })( jQuery );
</script>
<style>
/*[dynamic-select] tag*/
#dynamic-select-tag-generator .cf72post-new-taxonomy label {
    float: left;
    width: 24%;
    margin-right: 0.5%;
    color: gray;
}
#dynamic-select-tag-generator input.cf72post-new-taxonomy{
  width:100%;
}
#dynamic-select-tag-generator .cf72post-new-taxonomy label:last-child {
    margin-right: 0;
    margin-top: 21px;
}
#dynamic-select-tag-generator .cf72post-new-taxonomy label:last-child input{
  width:auto;
}
#dynamic-select-tag-generator .cf72post-new-taxonomy label:first-child {
    width: 26.5%;
}
#dynamic-select-tag-generator option.system-taxonomy {
    background: #0085ba;
    color: white;
}
#dynamic-select-tag-generator .hidden{
  display:none;
}
#dynamic-select-tag-generator option.cf7sg-taxonomy {
    background-color: #098f09;
    color: white;
}
</style>
