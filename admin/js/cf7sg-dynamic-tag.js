
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
  var $post = $('#dynamic-select-tag-generator  select.post-list');

  $('select.post-list').on('change', function(){
    $('div.post-taxonomies').hide();
    //$('div.post-taxonomies').off('change');
    var type = $(this).val();
    $('div#'+type).show();
    $('select.select2', $('div#'+type)).not('.select2-hidden-accessible').select2();

  });
  $('#dynamic-select-tag-generator').on('change',':input', function(event){
    var $target = $(event.target);
    var $tab = $('input[name="sections"]:checked');
    var source = 'taxonomy';
    if($target.is('select.taxonomy-list')){
      var $option = $target.find('option:selected');
      $taxonomy.val($target.val());
      $plural.val($option.text());
      $single.val($option.data('name'));
      //by default disable name fields.
      $plural.prop('disabled',true);
      $single.prop('disabled',true);
      $taxonomy.prop('disabled',true);
      $is_cat.parent().hide();
      if($option.is('.cf7sg-new-taxonomy')){
        $plural.prop('disabled',false);
        $single.prop('disabled',false);
        $taxonomy.prop('disabled',false);
        $is_cat.parent().show();
      }else if($option.is('.cf7sg-taxonomy')){
        $plural.prop('disabled',false);
        $single.prop('disabled',false);
      }
    }
    /* which source ? */
    if($tab.is('#taxonomy-tab')){
      source = 'taxonomy';
    }else if($tab.is('#post-tab')){
      source = 'post';
    }else if($tab.is('#custom-tab')){
      source = 'filter';
    }
    updateCF7Tag(source);
  });
  //udpate the new category if created
  $button.on('click', function(){
    var $option = $select.find('option:selected');
    if($option.is('.cf7sg-new-taxonomy') ){
      $option.after('<option data-name="'+$single.val()+'" value="'+$taxonomy.val()+'">'+$plural.val()+'</option>');
      //$option.next().prop('selected', true);
    }else if($option.is('.cf7sg-taxonomy')){
      $option.data('name', $single.val());
      $option.text( $plural.val());
    }else{
      return true; //for other dynamic options just continue.
    }
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
    //rest the selection
    $plural.prop('disabled',true).val('');
    $single.prop('disabled',true).val('');;
    $taxonomy.prop('disabled',true).val('');
    $is_cat.prop('checked', false).parent().hide();
    $select.val('');//reset the dropdown.
  });


  function updateCF7Tag(source='taxonomy') {
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
    switch(source){
      case 'taxonomy':
        if($taxonomy.val().length > 0){
          values = ' "slug:'+ $taxonomy.val()+'"';
        }
        break;
      case 'post':
        if($post.val().length > 0){
          var $tax = $('div#'+$post.val()+' > select.select2');
          //.val();
          values = ' "source:post:'+$post.val()+'"';
          if(null != $tax.val()){
            var term='';
            for(term of $tax.val()){
              values += ' "'+term+'"';

            }
          }
        }
        break;
      case 'filter':
        values = ' source:filter';
        break;
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
