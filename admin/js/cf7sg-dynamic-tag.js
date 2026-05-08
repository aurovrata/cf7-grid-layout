
(function( $ ) {
  'use strict';

  $('.cf7sg-dynamic-list-tag-manager').change(':input', function(e){
    let $target = $(e.target),
      $form = $target.closest('.cf7sg-dynamic-list-tag-manager'),
      tagname = $form.data('tag'),
      $tab = $('input[name="sections"]:checked', $form),
      $is_cat = $('input[name="is_hierarchical"]', $form),
      $taxonomy = $('input[name="taxonomy_slug"]', $form),
      $plural = $('input[name="plural_name"]', $form),
      $single = $('input[name="singular_name"]', $form),
      $name = $('input[name="name"]', $form),
      $hasNesting = $('#enable-branches'),
      source = 'taxonomy',
      selectType = $('.list-style:checked', $form).val();

    switch(true){
      case $target.is('select.post-list'):
        $('div.post-taxonomies.cf7sg-dynamic-tag',$form).hide();
        let $tax = $('.post-taxonomies.cf7sg-dynamic-tag.'+e.target.value, $form).show();
        //enable select2 if not yet initialised.
        $('select.select2', $tax).not('.select2-hidden-accessible').select2({
          placeholder: "Filter by term",
          multiple:true,
          dropdownParent:$tax,
          allowClear:true,
          forceAbove:true,
          templateSelection: function (state) {
            if (!state.id) {
              return state.text;
            }
            let label = state.element.closest('optgroup');
            if(label) label = label.getAttribute('label');
            else label='';
            return $('<span>'+label+':'+state.text+'</span>');
          }
        });
        break;
      case $target.is('select.taxonomy-list'):
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
        /** @since 4.11 allow nesting of lists */
        if($option.is('.hierarchical') && $hasNesting) $hasNesting.show();
        else if($hasNesting){
          $('input',$hasNesting).prop('checked',false);
          $hasNesting.hide();
        }
        break;
      case $target.is('.list-style'): //---------list-type
        $target.closest('td.cf7sg-dl-styles').find('span.cf7sg-se-option').hide().find(':input').prop('checked', false).val(''); //rest all/
        break;
      case $target.is('.source-tab'): //source selection.
        break;
    }
    /* which source ? */
    if($tab.is('.taxonomy-tab')){
      source = 'taxonomy';
    }else if($tab.is('.post-tab')){
      source = 'post';
    }else if($tab.is('.custom-tab')){
      source = 'filter';
    }
    //set filters.
    $('p.filter-hook a', $form).each(function(){
      let $a=$(this);
      if( !$a.is('.init') ){
        $a.attr('data-cf72post', $('#fieldhelperdiv li.'+this.classList+' a').data('cf72post') );
        $a.addClass('init').addClass('helper');
      }
      new Clipboard($a[0], {
        text: function(t) {
          let $f = $(t);
          let text = $f.data('cf72post');
          //get post slug
          let key = $('#post_name').val();
          text = text.replace(/\{\$form_key\}/gi, key);
          text = text.replace(/\{\$field_type\}/gi, tagname);
          text = text.replace(/\{\$field_name\}/gi, $name.val());
          text = text.replace(/\{\$field_name_slug\}/gi, $name.val().replace(/\-/g,'_'));
          text = text.replace(/\[dqt\]/gi, '"');
          return text;
        }
      })
    });
    //update tag field.
    let $req = $('input[name="required"]', $form),
      id = $('input[name="id"]', $form).val(),
      classes = $('input[name="class"]', $form).val(),
      postlinks='',postimgs='',
      values='', dataAttr = '',multiple = '',
      $post = $('select.post-list', $form),
      $tag = $form.siblings('.cf7sg-dynamic-tag-submit').find('input.tag.code');

      //other attributes.
      $('tr.others :input:visible:checked', $form).each(function(){
        multiple += ' '+this.value;
      })
    if(id.length > 0) id =' id:'+id;

    if(classes.length > 0){
      classes = classes.split(',').reduce((r,v)=>r+'class:'+v+' ', '');
    }
    classes = selectType.split(' ').reduce((r,v)=>r+'class:'+v+' ', classes);

    switch(source){
      case 'taxonomy':
        if($taxonomy.val().length > 0){
          values = '"slug:'+ $taxonomy.val();
          if($hasNesting && $('input',$hasNesting).is(':checked')) values +=':tree';
          values +='"';
        }
        break;
      case 'post':
        if($post.val().length > 0){
          let $tax = $('.post-taxonomies.'+$post.val()+' > select.select2', $form);
          /** @since 4.0 */
          if($('.include-links input',$form).is(':checked')) postlinks = ' permalinks';
          if($('.include-images input',$form).is(':checked')) postimgs = ' thumbnails';
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
        values = '"source:filter"';
        break;
    }
    //get any data-attribute hidden fields.
    $('input.data-attribute', $form).each(function(){
      if(''!=this.value.trim()) dataAttr += ' "data-'+this.value+'"';
    });

    //update tag.


    if($req.is(':checked')) tagname = tagname+'*';
    $tag.val('[' + tagname +' '+ $name.val() + multiple + postimgs + postlinks + id +' '+ classes + values + dataAttr +']');
  });

  //udpate the new category if created
  $('.cf7sg-dynamic-tag-submit .button').on('click', function(){
    let $form = $(this).closest('form'),
      tag = $form.data('id'),
      $is_cat = $('input[name="is_hierarchical"]', $form),
      $taxonomy = $('input[name="taxonomy_slug"]', $form),
      $plural = $('input[name="plural_name"]', $form),
      $single = $('input[name="singular_name"]', $form),
      $select = $('select.taxonomy-list', $form),
      $option = $select.find('option:selected');

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

})( jQuery );
