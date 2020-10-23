
(function( $ ) {
  'use strict';
  let $form = $('#dynamic-select-tag-generator'),
    $tag = $('input.tag' , $form.closest('.control-box').siblings('.insert-box')),
    $button = $tag.siblings('.submitbox').find('input.insert-tag'),
    $select = $('select.taxonomy-list', $form),
    $plural = $('input[name="plural_name"]', $form),
    $single = $('input[name="singular_name"]', $form),
    $taxonomy = $('input[name="taxonomy_slug"]', $form),
    $is_cat = $('input[name="is_hierarchical"]', $form),
    $req = $('input[name="required"]', $form),
    $name = $('input[name="name"]', $form),
    $id = $('input[name="id"]', $form),
    $cl = $('input[name="class"]', $form),
    $post = $(' select.post-list', $form),
    selectType = 'select';

  $('select.post-list').on('change', function(){
    $('div.post-taxonomies').hide();
    //$('div.post-taxonomies').off('change');
    let type = $(this).val();
    $('div#'+type).show();
    $('select.select2', $('div#'+type)).not('.select2-hidden-accessible').select2();
  });

  $form.on('change',':input', function(event){
    let $target = $(event.target),
      $tab = $('input[name="sections"]:checked'),
      source = 'taxonomy';

    $('#select-multiple').prop('disabled',false);

    if($target.is('select.taxonomy-list')){
      let $option = $target.find('option:selected');
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
    }else if($target.is('input.select-type')){ //---------select-type
      selectType = $('input.select-type:checked').val();
      if('select2' == selectType){
        $('input#select2-tags', $form).prop('disabled', false).parent().show();
      }else{
        $('input#select2-tags', $form).prop('checked', false);
        $('input#select2-tags', $form).prop('disabled', true).parent().hide();
      }
      if( $target.is('#nice-select:checked')  ){
        //no multiple in nice-select.
        $('#select-multiple').prop('checked',false);
        $('#select-multiple').prop('disabled',true);
      }
    }
    /* which source ? */
    if($tab.is('#taxonomy-tab')){
      source = 'taxonomy';
    }else if($tab.is('#post-tab')){
      source = 'post';
    }else if($tab.is('#custom-tab')){
      source = 'filter';
      let $a = $('p.position-relative a', $tab.parent());
      if( !$a.is('.init') ){
        $a.attr('data-cf72post', $('#fieldhelperdiv li.cf7sg_filter_source a').data('cf72post') );
        $a.addClass('init').addClass('helper');
      }
      new Clipboard($a[0], {
        text: function(t) {
          let $f = $(t);
          let text = $f.data('cf72post');
          //get post slug
          let key = $('#post_name').val();
          text = text.replace(/\{\$form_key\}/gi, key);
          text = text.replace(/\{\$field_name\}/gi, $name.val());
          text = text.replace(/\{\$field_name_slug\}/gi, $name.val().replace(/\-/g,'_'));
          text = text.replace(/\[dqt\]/gi, '"');
          return text;
        }
      })
    }
    updateCF7Tag(source);
  });
  //udpate the new category if created
  $button.on('click', function(){
    let $option = $select.find('option:selected');
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
    let values = $('input#cf72post-dynamic-select').val();
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
    let id=$id.val();
    if(id.length > 0) id =' id:'+id;

    let classes = $cl.val(), postlinks='';
    if(classes.length > 0){
      let classArr = classes.split(','), idx;
      classes='';
      for(idx=0; idx<classArr.length; idx++){
        classes += " class:" + classArr[idx].trim() + " ";
      }
    }
    let values = ''
    switch(source){
      case 'taxonomy':
        if($taxonomy.val().length > 0){
          values = ' "slug:'+ $taxonomy.val()+'"';
        }
        break;
      case 'post':
        if($post.val().length > 0){
          let $tax = $('div#'+$post.val()+' > select.select2');
          /** @since 4.0 */
          if($('#include-post-links').is(':checked')) postlinks = ' permalinks';
          values = ' "source:post:'+$post.val()+'"';
          if(null != $tax.val()){
            let term='';
            for(term of $tax.val()){
              values += ' "'+term+'"';
            }
          }
        }
        break;
      case 'filter':
        values = ' "source:filter"';
        break;
    }
    //select type
    switch(selectType){
      case 'nice':
        classes+= ' class:nice-select';
        break;
      case 'select2':
        classes += ' class:select2';
        if($('input#select2-tags').is(':checked')){
          classes += ' class:tags';
        }
        break;
      case 'standard':
      default:
        break;
    }

    //update tag.
    let type = 'dynamic_select ', multiple='';
    if($('#select-multiple').is(':checked')) multiple=' multiple';
    if($req.is(':checked')) type = 'dynamic_select* ';
    $tag.val('[' + type + $name.val() + multiple + postlinks + id + classes + values +']');
  }
})( jQuery );
