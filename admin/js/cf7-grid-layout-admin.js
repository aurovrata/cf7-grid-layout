
/**
 Javascript to handle grid editor
 Event 'cf7sg-form-change' fired on #contact-form-editor element when codemirror changes occur
*/
(function( $ ) {
  var offsets = ['offset-one','offset-two', 'offset-three', 'offset-four', 'offset-five', 'offset-six', 'offset-seven', 'offset-eight', 'offset-nine', 'offset-ten', 'offset-eleven'];
  var columnsizes = ['one', 'two', 'one-fourth', 'one-third', 'five', 'one-half', 'seven', 'two-thirds', 'nine', 'ten', 'eleven', 'full'];
  var $wpcf7Editor,$grid,$rowControl = null;
  //graphics UI template pattern
  var $pattern = $('<div>').html(cf7grid.preHTML+'\\s*(\\[.*\\s*\\].*\\s*)+\\s*'+cf7grid.postHTML);
  var required = cf7grid.requiredHTML.replace('*', '\\*');
  // console.log('r:'+required);
  $pattern.find('label').html('((\\s*.*)('+required+'){1}|(\\s*.*))');
  $pattern.find('.info-tip').text('(.*\\s*)');
  // console.log('p:'+$pattern.html());
  var templateRegex = new RegExp($pattern.html(), 'ig');
  var wpcf7Value = '';

	$(document).ready( function(){
    $wpcf7Editor = $('textarea#wpcf7-form-hidden');
    $grid = $('#grid-form');
    $rowControl = $('#top-grid-controls');

    /*
    Build grid from existing form------------------------- BUILD UI FORM
    */
    function buildGridForm(){
      var formhtml = $wpcf7Editor.text();
      if(0===formhtml.length){
        formhtml = '<div class="container"><div class="row"><div class="columns full"></div></div></div>';
      }
      var $form = $('<div>').append( formhtml );
      //remove the external forms
      var external = {};
      $('.cf7sg-external-form', $form).each(function(){
        var id = $(this).data('form');
        external[id] = $(this).children('.cf7sg-external-form-content').remove();
        //check for form update.
        var data = {
    			'action' : 'get_cf7_content',
          'id': $('#post_ID').val(),
          'nonce'  : $('#_wpcf7nonce').val(),
    			'cf7_key' : id,
          'update'  :true
    		};
        $.post(ajaxurl, data, function(response) {
          if(response.length > 0){
            external[id] = $('<div class="cf7sg-external-form-content">').append(response);
          }
    		});
      });
      //replace columns content with textareas
      /*--------------------------------------------------- convert columns */
      $('div.columns', $form).each(function(){
        var $area =  $($('#grid-col').html());
        if($(this).children().is('.container')){
          $('textarea.grid-input', $area).remove();
          $('div.cf7-field-inner', $area).remove();
        }else{
          if(cf7grid.ui) $('textarea.grid-input', $area).html($(this).html().trim());
          else $('textarea.grid-input', $area).val($(this).html().trim());

          $(this).children().remove();
          $(this).text('');
        }
        $(this).prepend($area);
      });
      $('div.row', $form).each(function(){
        $(this).append( $('#grid-row .row-controls').clone() );
      });
      /*--------------------------------------------------- convert collapsible sections  */
      $('div.container.cf7sg-collapsible', $form).each(function(){
        var text = $(this).children('.cf7sg-collapsible-title').text();
        var $toggle = $('.toggle', $(this).children('.cf7sg-collapsible-title'));
        if($toggle.length>0){
          $toggle = $toggle.clone();
        }

        $(this).children('.cf7sg-collapsible-title').remove();
        $(this).prepend( $('#grid-collapsible').html());
        $('input', $(this).children('.cf7sg-collapsible-title')).not('[type="checkbox"]').val(text);
        if($toggle.length>0){
          $(this).children('.cf7sg-collapsible-title').append($toggle);
          $('input[type="checkbox"]', $(this).children('.cf7sg-collapsible-title') ).prop('checked', true);
        }
        var $ctrl = $(this).children('.row').children('.row-controls').find('.collapsible-row-label');
        $('input', $ctrl).prop('checked', true);
        //toggle disable the sibling input
        $('input', $ctrl.siblings('.unique-mod')).prop('disabled', function(i,v){return !v;});
      });
      /*--------------------------------------------------- convert tables */
      $('div.container.cf7-sg-table', $form).each(function(){
        var $ctrl = $(this).find('.row.cf7-sg-table > .row-controls' ).first().find('.table-row-label');
        $('input', $ctrl).prop('checked', true);
        //set button label
        var text = $('.row.cf7-sg-table', $(this)).data('button');
        if(typeof text  == 'undefined'){
          text = 'Add Row';
          $(this).attr('data-button',text);
        }
        $ctrl.next('.table-row-button').children('input').val(text);
        //toggle disable the sibling input
        $('input', $ctrl.siblings('.unique-mod')).prop('disabled', function(i,v){return !v;});
        //toggle footer row
        var $footer = $(this).next();
        if($footer.is('.cf7-sg-table-footer')){
          $ctrl = $footer.children('.row').first().find('.row-controls .footer-row-label');
          $('input.footer-row', $ctrl).prop('checked', true);
          $('input', $ctrl.siblings('.unique-mod')).prop('disabled', function(i,v){return !v;});
        }
      });
      //tabs
      /*--------------------------------------------------- convert tabs */
      $('ul.cf7-sg-tabs-list li', $form).each(function(){
        var text = $(this).children('a').text();
        $(this).append($('#grid-tabs ul li label').clone());
        $('label input', $(this)).val(text);
        //setup checkbox
        var $ctrl = $(this).parent().siblings('.cf7-sg-tabs-panel');
        $ctrl = $ctrl.children('.row').find('.row-controls' ).first().find('.tabs-row-label');
        $('input.tabs-row', $ctrl).prop('checked', true);
        $('input', $ctrl.siblings('.unique-mod')).prop('disabled', function(i,v){return !v;});
      });
      //reinsert the external forms
      $('.cf7sg-external-form', $form).each(function(){
        var id = $(this).data('form');
        if($('#grid-cf7-forms .form-select option[value="'+id+'"]' ).length > 0 ){
          //add controls
          $(this).append($('#grid-cf7-forms .form-controls').clone());
          $('.form-controls .form-select', $(this)).val(id);
          $(this).append( external[id] );
        }else{
          $(this).append($( $('#grid-cf7-forms .cf7sg-external-form').html() ) );
        }
      });
      //add the form to the grid
      $grid.html($form.children());
      //set the value of each textarea as inner text
      $('textarea', $grid).each(function(){
        $(this).html($(this).val());
      });
      /*--------------------------------------------------- if ui mode, then convert to gui template */
      if(cf7grid.ui){
        $('div.columns', $grid).each(function(){
          if($(this).children().is('.container')) return true;
          $(this).html2gui();
        });
      }else{
        //set the first textarea as our default tag consumer
        var $textareaSelected = $('textarea', $grid).first();
        $textareaSelected.attr('id', 'wpcf7-form');
        //change this to whichever is live
        $('textarea', $grid).live('focus', function(){
          $textareaSelected.attr('id','');
          $textareaSelected.html($textareaSelected.val()); //set its inner html
          $textareaSelected = $(this).attr('id','wpcf7-form');
        });
      }
    } //end buildGridForm()


    //make columns sortable
    // $('.row', $grid).sortable({
    //   //placeholder: "ui-state-highlight",
    //   handle:'.grid-column .dashicons-move',
    //   containment:'.row',
    //   items: '> .columns'
    // });
    //make rows sortable
    // $('.container', $grid).sortable({
    //   //placeholder: "ui-state-highlight",
    //   handle:'.row-controls .dashicons-move',
    //   containment:'parent',
    //   items: '> .container'
    // });

    //make rows with columns sortable
    // $('.row .columns').sortable({
    //   //placeholder: "ui-state-highlight",
    //   handle:'.row-controls .dashicons-move',
    //   containment:'parent',
    //   items: '> .container'
    // });

    //offset/size change using event delegation
    /*---------------------------------------------------------------------------- ui menus */
    $grid.on('change', function(event){
      var $target = $(event.target);
      if($target.is('.column-setting')){ //----------- column size/offset settings
        var validation = ['dummy'];
        if( $target.is('.column-offset') ){
          validation = offsets;
        }else if( $target.is('.column-size') ){
          validation = columnsizes;
        }else{
          return false;
        }
        var $column = $target.closest('.columns');
        var classList = $column.attr('class').split(/\s+/);
        var idx;
        for(idx=0; idx<classList.length; idx++){
          if($.inArray(classList[idx], validation) > -1){
             $column.removeClass(classList[idx]);
          }
        }
        $column.addClass($target.val());
        //filter the options
        $target.closest('.grid-controls').filterColumnControls();
      }else if($target.is('.form-select')){ //-------------- external form selection
        var $container = $target.closest('.cf7sg-external-form');
        $container.attr('data-form', $target.val());
        //TODO: disable the selected options
        //ajax get form content
        var data = {
    			'action' : 'get_cf7_content',
          'id': $('#post_ID').val(),
          'nonce'  : $('#_wpcf7nonce').val(),
    			'cf7_key' : $target.val()
    		};
        $.post(ajaxurl, data, function(response) {
    			$('.cf7sg-external-form-content', $container).attr('id','cf7sg-form-'+$target.val()).html(response);
    		});
      }
      /*
        Collapsible / tabs rows input changes
      */
      if($target.is('.cf7sg-collapsible-title label input[type="text"]')){ //------- Collapsible title
        $target.siblings('input[type="hidden"]').val($target.val());
      }else if( $target.is('.cf7sg-collapsible-title label input[type="checkbox"]')){ //------- Collapsible title toggled
        var $title = $target.closest('.cf7sg-collapsible-title');
        if($target.is(':checked')){
          $title.append($('#grid-collapsible-with-toggle').html());
          $title.closest('.container.cf7sg-collapsible').addClass('with-toggle');
        }else{
          $('.toggle', $title).remove();
          $title.closest('.container.cf7sg-collapsible').removeClass('with-toggle');
        }
      }else if($target.is('ul.cf7-sg-tabs-list li label input[type="text"]')){ //------- Tabs title
        $target.parent().siblings('a').text($target.val());
      }else if($target.is('label.table-row-button input')){
        $target.closest('.container.cf7-sg-table').attr('data-button',$target.val());
      }
      if(cf7grid.ui){
        if($target.is('.cf7-field-inner textarea')){
          var label = $target.scanCF7Tag();
          $target.siblings('p.content').html(label);//.show();
          $target.parent().siblings('textarea.grid-input').updateGridForm();
        }else if($target.is('.cf7-field-inner input')){
          $target.siblings('p.content').html($target.val());
          $target.parent().siblings('textarea.grid-input').updateGridForm();
        }
      }
    });

    //grid click event delegation
    $grid.on('click', function(event){
      var $target = $(event.target);
      switch(true){
        case $target.is('input[type="text"]:visible'):
        case $target.is('textarea:visible'): //click on a field.
        case $target.is('select:visible'):
          return true;
          break;
      }
      //close any open row/column controls
      closeAllControls();

      /*
        Row controls
        ----------------------------------------------------------------------------ROW CONTRLS
      */
      var $parentRow;
      if($target.is('.dashicons-trash.row-control')){ //--------TRASH
        var $parentContainer = $target.closest('.container');
        var $parent = $parentContainer.parent();
        $parentContainer.remove();
        if( $parent.is('.columns') ) { //verify is this is the last row being deleted
          if( 0 == $parent.children('.container').length ){
            //add a text area to the column
            $parent.children('.grid-column').append('<textarea class="grid-input"></textarea>');
          }
        }
      }else if($target.is('.dashicons-trash.form-control')){ //--------TRASH included form
        $target.closest('.cf7sg-external-form').remove();
      }else if($target.is('.dashicons-trash.form-control') ){ //-----------TRASH external form
        $target.closest('cf7sg-external-form').remove();
      }else if($target.is('.dashicons-plus.row-control')){ //-----------ADD
        $target.closest('.container').insertNewRow();
      }else if($target.is('.dashicons-edit.row-control')){ //-----------Show controls
        //hide any other controls that might be open
        //taken care by closeAllControls
        // $('.grid-controls', $grid).hide();
        // $('.dashicons-no-alt', $grid).hide();
        // $('.dashicons-edit', $grid).show();
        //now show this control
        $target.siblings('.grid-controls').show();
        $target.hide();
        $target.siblings('.dashicons-no-alt').show();
        /*
        TODO: use $('.grid-controls')filterColumnControls() to make sure columns sizes/offsets are correct.
        possibly introduce a boolean to check if filter has been run already on this row
        */
      }else if( $target.is('.dashicons-no-alt.row-control') ) { //----------------hide controls
        //take care by closeAllControls
        // $target.siblings('.grid-controls').hide();
        // $target.hide();
        // $target.siblings('.dashicons-edit').show();
      }else if($target.is('input.collapsible-row')){ //-------------checkbox collapsible row
        var $container = $target.closest('.container');
        if($target.is(':checked')){
          $container.addClass('cf7sg-collapsible');
          $container.prepend($('#grid-collapsible').html());
        }else{
          $container.removeClass('cf7sg-collapsible');
          $container.children('.cf7sg-collapsible-title').remove();
        }
        //toggle disable the sibling input
        $target.parent().siblings('label.unique-mod').children('input').prop('disabled', function(i,v){return !v;});
      }else if($target.is('input.table-row')){ //-------------checkbox table row
        if($target.is(':checked')){
          $target.closest('.row').addClass('cf7-sg-table');
          $target.closest('.container').addClass('cf7-sg-table');
        }else{
          $target.closest('.row').removeClass('cf7-sg-table');
          $target.closest('.container').removeClass('cf7-sg-table');
        }
        //toggle disable the sibling input
        $target.parent().siblings('label.unique-mod').children('input').prop('disabled', function(i,v){return !v;});
      }else if($target.is('input.tabs-row')){ //-------------checkbox tabbed row
        var $panel = $target.closest('.container');
        if($target.is(':checked')){
          var id = 'cf7-sg-tab-'+(new Date).getTime();
          $panel.addClass('cf7-sg-tabs-panel').attr('id',id);
          $panel.before($('#grid-tabs').html());
          $panel.closest('.columns').addClass('cf7-sg-tabs');
          $('li a', $panel.siblings('ul.cf7-sg-tabs-list')).attr('href','#'+id);
        }else{
          $panel.removeClass('cf7-sg-tabs-panel');
          $panel.closest('.columns').removeClass('cf7-sg-tabs');
          $panel.siblings('ul.cf7-sg-tabs-list').remove();
        }
        //toggle disable the sibling input
        $target.parent().siblings('label.unique-mod').children('input').prop('disabled', function(i,v){return !v;});
      }else if($target.is('input.footer-row')){ //-------------checkbox footer row
        var $table = $target.closest('.container').prev();
        if($table.is('.container.cf7-sg-table')){
          if($target.is(':checked')){
            $target.closest('.container').addClass('cf7-sg-table-footer');
          }else{
            $target.closest('.container').removeClass('cf7-sg-table-footer');
          }
          //toggle disable the sibling input
          $target.parent().siblings('label.unique-mod').children('input').prop('disabled', function(i,v){return !v;});
        }
      }


      /*
        Column controls, show/hide contols, add column, and refresh select dropdowns for colum size on addition
      */

      var $parentColumn ;
      if( $target.is('.columns')){
        $parentColumn = $target;
      }else{
        $parentColumn = $target.closest('.columns');
      }
      //let's close any column controls : taken care by closeAllControls
      // if(0===$target.closest('.grid-controls').length ){
      //   $('.grid-column .grid-controls', $grid).hide();
      //   $('.grid-column .column-control.dashicons-no-alt', $grid).hide();
      //   $('.grid-column .column-control.dashicons-edit', $grid).show();
      //   //close any ui fields if any
      //   if(cf7grid.ui && !$target.is('.cf7-field-inner *')){
      //     $('.grid-column .cf7-field-inner span.dashicons').trigger('click');
      //   }
      // }
      //verify which target was clicked
      if( $target.is('.dashicons-edit.column-control') ){ //------------------show controls
        //now show this control
        $target.siblings('.grid-controls').show().filterColumnControls();
        $target.hide();
        $target.siblings('.dashicons-no-alt').show();
      }else if( $target.is('.dashicons-no-alt.column-control') ) { //----------------hide controls
        //do nothing since already closed
        // $target.siblings('.grid-controls').hide();
        // $target.hide();
        // $target.siblings('.dashicons-edit').show();
      }else if($target.is('.dashicons-trash.column-control') ){ //-------------------delete column

        $parentColumn.remove();
        //refilter
        //$target.siblings('.grid-controls').filterColumnControls();
      }else if( $target.is('.make-grid') ){ //--------------------add row/convert column to grid

        //close the grid-control box
        $target.parent().hide();
        $target.parent().siblings('.dashicons-no-alt').hide();
        $target.parent().siblings('.dashicons-edit').show();
        //convert to grid
        var $parentColumn = $target.closest('.columns');
        if($parentColumn.length > 0){
          //column already has a row?
          var text = '';
          if(0 == $parentColumn.children('.container').length){
            //keep the textarea and remove from the column
            if(cf7grid.ui){
              text = $('textarea.grid-input', $parentColumn).remove().text();
            }else{
              text = $('textarea.grid-input', $parentColumn).remove().val();
            }
            if(cf7grid.ui) $('div.cf7-field-inner', $parentColumn).remove();
          }
          $parentColumn.insertNewRow(text);
        }else{ //add to the main container
          $grid.insertNewRow();
        }
      }else if($target.is('.external-form')){ //---------------- insert cf7 form
        //close the grid-control box
        $target.parent().hide();
        $target.parent().siblings('.dashicons-no-alt').hide();
        $target.parent().siblings('.dashicons-edit').show();
        //replace container with form selector
        $target.closest('.container').after($('#grid-cf7-forms').html());
        $target.closest('.container').remove();
      }else if( $target.is('.dashicons-plus.column-control') ){ //--------------------add column
        var $parentColumn = $target.closest('.columns');
        var $parentRow = $parentColumn.closest('.row');
        var classList, idx , columns, row;
        var newSize=0;
        var sizes = [];
        var $newColumn = $('<div class="columns"></div>');
        var createColumn = true;
        //is the row filled up?
        var total = 0;
        //first check if the current column fills the entire row
        if( $parentColumn.is('.full') ){
          total = 12;
          columns = 1;
          sizes[0] = columnsizes.length - 1; //ie 11, the last value
        }else{
          row = $parentRow.getRowSize();
          total = row.length;
          sizes = row.cols;
          columns = sizes.length;
        }
        if(12 == total) {
          newSize = Math.floor( total/(columns + 1) ) - 1 ;
          if(newSize < 0 ){ //max columns reached
            /*TODO: display an error message and return */
            return false;
          }
          createColumn = false;
          var newClass = columnsizes[newSize];
          $parentRow.children('.columns').each(function(index){
            //make sure the column is not size 1
            if( sizes[index] > 0 ){
              $(this).changeColumnSize(columnsizes[sizes[index]], newClass );
              createColumn = true;
            }
          });
          if(!createColumn){ //not enough space to create extra column
            /*TODO: display an error message and return */
            return false;
          }
        }else if(total < 12){ //just add the new column as size 1
          if( (12 - total) < total){
            newSize = 12 - total - 1;
          }else{
            newSize = total - 1;
          }
        }else{ //should never reach here
          return false;
        }
        //add the new column
        $newColumn.append( $( $('#grid-col').html() ) );
        if(cf7grid.ui){
          $('textarea.grid-input' ,$newColumn).hide();
        }else{
          $('div.cf7-field-inner', $newColumn).hide();
        }
        $newColumn.changeColumnSize('',columnsizes[newSize]);
        $parentColumn.after($newColumn);
      }
      /*
       Column UI fields
      */
      if(cf7grid.ui){
        //close any open ui fields
        closeAlluiFields();
        if($target.is('.cf7-field-inner p.content')){
          $target.parent().showUIfield();
        }else if($target.is('.cf7-field-inner span.dashicons')){
          //field will be closed by closeAlluiFields
        }else if('none'!==$('#wpcf7-form').css('display') && !$target.is('#wpcf7-form')){
          changeTextarea();
        }
      }
    });

    // capture tab and move to the next field
    if(cf7grid.ui){
      $grid.keydown('div.cf7-field-inner', function(event){
        if(9 !== event.which ){//tab
          return;
        }
        $target = $(event.target);
        if($target.is('div.cf7-field-inner :input')){
          $target.closeUIfield();
          var $next = $target.parent().next('div.cf7-field-inner');
          if($next.length>0){
            $next.showUIfield();
            event.preventDefault(); //stop tab focus on this element.
          }
        }
      });

    }
    //general inputs into the textareas will trigger form change event
    $grid.on('input selectionchange propertychange', 'textarea', function(event){
      var $target = $(event.target);
      if($target.is('.cf7-field-type textarea')){
        return false; //form change trigger will happen after this
      }else if($target.is('textarea')){
        $('#contact-form-editor').trigger('cf7sg-form-change');
      }
    });
    //before grid editor is closed, update the form with the last textarea
    //event 'cf7grid-tab-finalise' is fired in cf7-grid-codemirror.js file
    $grid.on('cf7grid-form-finalise', function(){
      if(cf7grid.ui){
        changeTextarea(true);
        $('#wpcf7-form').parent().siblings('textarea.grid-input').on('change', function(){
          $grid.trigger('cf7grid-form-ready'); //codemirror initialisation
        });
      }else{
        var $txta = $('textarea#wpcf7-form');
        $txta.html($txta.val()+'\n');
        $grid.trigger('cf7grid-form-ready'); //codemirror initialisation
      }
    });

    //initial construction of grid form
    buildGridForm();
    $grid.on('build-grid', function(){
      buildGridForm();
    });
    //grid is ready
    $wpcf7Editor.trigger('grid-ready');
  }); //end document ready
  //function to close any ui fields
  function closeAlluiFields(){
    $('.cf7-field-inner :input:visible').each(function(){
      $(this).closeUIfield();
    });
  }
  //close controls row/column
  function closeAllControls(){
    $('.grid-controls:visible', $grid).each(function(){
      $(this).hide();
      $(this).siblings('.dashicons-no-alt').hide();
      $(this).siblings('.dashicons-edit').show();
    });
  }
  //trigger a change on the textarea#wpcf7-form field if its value has changed
  function changeTextarea(finalise = false){
    if(cf7grid.ui){
      var $lastTA = $('#wpcf7-form');
      if($lastTA.length >0 && wpcf7Value !== $lastTA.val()){
        wpcf7Value = '';
        $lastTA.trigger('change');
      }else if(finalise){
        $grid.trigger('cf7grid-form-ready'); //codemirror initialisation
      }
    }
  }
  /* some function definitions...*/
  $.fn.closeUIfield = function(){
    if(!$(this).is('.cf7-field-inner :input:visible')){
      return $(this);
    }
    if($(this).parent().is('.cf7-field-type')) changeTextarea();
    $(this).hide().attr('id', '');
    $(this).siblings('.dashicons-no-alt').hide();
    $(this).siblings('.content').show();
    return $(this);
  }
  $.fn.showUIfield = function(){
    if(!$(this).is('.cf7-field-inner')){
      return $(this);
    }
    $(this).find('p.content').hide();
    $(this).find('span.dashicons').show();
    var $input = $(':input', $(this)).show();
    $input.focus();
    if($input.is('textarea')){
      $input.attr('id', 'wpcf7-form');
      wpcf7Value = $input.val();
    }else{
      changeTextarea();
    }
    return $(this);
  }
  $.fn.html2gui = function(html=''){
    if(html.length === 0){
      //get the fields from the textarea
      html = $('textarea.grid-input', $(this)).text();
      if(html.length === 0){
        $('textarea.grid-input', $(this)).hide();
        return $(this);
      }
    }

     var lines = html.split(/\r\n|\r|\n/g);
     var search = '';
     for(var i=0; i<lines.length; i++){
       search += lines[i].trim();
     }
     var match = templateRegex.exec(search);
     if(null !== match){
       //populate the fields
       var $field = $('div.cf7-field-label', $(this));
       $('input', $field).val(match[1]);
       $('p.content', $field).html(match[1]);
       $field = $('div.cf7-field-type', $(this));
       var tag = $('textarea', $field).val(match[5]).scanCF7Tag();
       $('p.content', $field).html(tag);
       $field = $('div.cf7-field-tip', $(this));
       $('input', $field).val(match[6]);
       $('p.content', $field).html(match[6]);
       //hide the textarea
       $('textarea.grid-input', $(this)).hide();
       //reset global regex
       templateRegex.lastIndex = 0;
     }else{ //this html does not match our templates
       $('div.cf7-field-inner', $(this)).remove();
     }
   }

  $.fn.scanCF7Tag = function(){
    var $this = $(this);
    if(!$this.is('textarea')){
      return '';
    }
    var $parent = $this.parent();
    var cf7TagRegexp = /\[(.[^\s]*)\s*(.[^\s\]]*)[\s\[]*(.[^\[]*\"slug:([^\s]*)\"[\s^\[]*|[.^\[]*(?!\"slug:)[^\[]*)\]/img;
    var search = $this.val();
    var match = cf7TagRegexp.exec(search);
    var label='';
    var isRequired = false;
    var type = [];
    var tag='';
    var isSubmit = false;
    var count =0;
    while (match != null) {
      count++;
      label+='['+match[1]+' '+match[2]+']';
      tag = match[1].replace('*','');
      switch(tag){
        case 'submit':
        case 'save':
          tag +='-button';
          isSubmit = true;
          break;
        case 'textarea':
          if( match[0].search(/\s[0-9]{0,3}x[0-9]{1,3}\s?/ig) <0){
            var cf7sc = match[0].replace(']',' x5]');
            cf7sc = search.replace(match[0], cf7sc);
            $this.val(cf7sc);
          }
          break;
      }
      type[type.length] = tag;
      if('*' === match[1][match[1].length -1]){
        isRequired = true;
      }
      match = cf7TagRegexp.exec(search); //get the next match.
    }
    var classes = $('#grid-col div.cf7-field-type').attr('class');
    classes += " "+ type.join(' ');
    // $parent.removeClass('required');
    if(isRequired) classes += ' required';//$parent.addClass('required').
    var $parentColumn = $parent.closest('.columns');
    if($parentColumn.is('[class*="cf7-tags-"]')){
      $parentColumn.removeClass(function (index, className) {
        return (className.match (/(^|\s)cf7-tags-\S+/g) || []).join(' ');
      });
    }
    if(count>1){
      classes += ' cf7-tags-'+count;
      $parentColumn.addClass('cf7-tags-'+count);
    }
    $parent.attr('class',classes);
    if(isSubmit){
      $parent.parent().addClass('submit-field');
    }

    return label;
  }
  $.fn.updateGridForm = function(){
    if(!$(this).is('textarea.grid-input')){
      return $(this);
    }
    //label
    var label = $(this).siblings('div.cf7-field-label').find(':input').val();
    //field
    var field = $(this).siblings('div.cf7-field-type').find('textarea').val();
    if($(this).siblings('div.cf7-field-type').is('.required')){
      if(label.indexOf(cf7grid.requiredHTML)<0) label += cf7grid.requiredHTML;
    }
    //tip
    var tip = $(this).siblings('div.cf7-field-tip').find(':input').val();
    var $cell = $('<div>').append( cf7grid.preHTML + field + cf7grid.postHTML );
    $('label', $cell).text(label);
    $('.info-tip', $cell).text(tip);
    //update grid input and trigger change to udpate form
    if(cf7grid.ui) $(this).html($cell.html()+'\n').trigger('change');
    else $(this).val($cell.html()).trigger('change');
    return $(this);
  };

  $.fn.toggleSiblingUIFields = function(){
    if(!$(this).is('div.cf7-field-inner')){
      return $(this);
    }
    $(this).siblings('div.cf7-field-inner').each(function(){
      $('p.content', $(this)).show();
      $(':input', $(this)).hide().attr('id','');
      $('span.dashicons', $(this)).hide()
    });
  }
  $.fn.getRowSize = function(){
    var size, off, idx, foundSize, classList;
    var total = 0;
    var sizes = [0];
    $(this).children('.columns').each(function(index){
      classList = $(this).attr('class').split(/\s+/);
      foundSize = false;
      for(idx=0;idx<classList.length; idx++){
        size = $.inArray(classList[idx], columnsizes);
        off = $.inArray(classList[idx], offsets);
        if(size > -1){
          foundSize = true;
          sizes[index] = size;
          total += size + 1;
        }
        if(off > -1) total+= off+1;
      }
      if(!foundSize){ //by default a colum which is not set set is treated as 1
        sizes[index] = 0;
        total += 1;
      }
    });
    return {'length':total, 'cols':sizes};
  }
  $.fn.getColumnTotalSize = function(){
    if(! $(this).is('.columns')){
      return 0;
    }
    var off, foundSize, size = 0;
    var total = 0;
    var classList = $(this).attr('class').split(/\s+/);
    var $sizes = $(this).find('.grid-column select.column-size');
    var $offsets = $(this).find('.grid-column select.column-offset');
    $offsets.val('');
    $sizes.val('one');
    foundSize = false;
    for(var idx=0;idx<classList.length; idx++){
      if(!foundSize){
        size = $.inArray(classList[idx], columnsizes);
        if(size > -1){
          foundSize = true;
          total += size + 1;
          //reset select
          $sizes.val(classList[idx]);
        }
      }
      off = $.inArray(classList[idx], offsets);
      if(off > -1){
        total += off+1;
        $offsets.val(classList[idx]);
      }
    }
    if(!foundSize){ //by default a colum which is not set set is treated as 1
      size = 0; //by default a colum which is not set set is treated as 1
      total += 1;
    }
    return {'length':total, 'size':size};
  }
  //add new rows
  $.fn.insertNewRow = function(areaCode = ''){
    var append=true;
    if( $(this).is('.columns') || $(this).is($grid)){
      append=true;
    }else if($(this).is('.container') ){
      append=false;
    }else{
      return $(this);
    }
    var $newRow = $( $('#grid-row').html() );
    //append the column controls and textarea
    $('.columns', $newRow).append( $($('#grid-col').html()) );
    //append the new row to the column or container
    if(append) $(this).append($newRow);
    else $(this).after($newRow);
    //add the code to the textarea
    if(cf7grid.ui){
      $('textarea.grid-input',$newRow).html(areaCode).hide();//.trigger('change');
      $newRow.html2gui(areaCode);
    }else{
      $('textarea.grid-input',$newRow).val(areaCode);//.trigger('change');
      $('div.cf7-field-inner', $newRow).hide();
    }

    return $(this);
  }
  //refresh controls select
  $.fn.changeColumnSize = function(oldSize, newSize){
    if(oldSize.length > 0) $(this).removeClass(oldSize);
    $(this).addClass(newSize);
    $('.column-size option[value="'+newSize+'"]', $(this) ).prop('selected', true);
  }
  //$target.closest('.grid-controls').filterColumnControls();
  $.fn.filterColumnControls = function(){
    if(!$(this).is('.grid-controls')){
      return $(this);
    }
    //enable all options
    $('.column-size option', $(this) ).prop('disabled', false);
    $('.column-offset option', $(this) ).prop('disabled', false);
    var $parentRow = $(this).closest('.row');
    var $parentColumn = $(this).closest('.columns');
    var row = $parentRow.getRowSize();
    var col = $parentColumn.getColumnTotalSize();
    var idx, start, free = 0;
    if(row.length < 12) free = (12 - row.length);
    for(idx = start = col.size+1; idx < columnsizes.length; idx++){
      if( idx > (free + start - 1) ){
        $('.column-size option[value="'+columnsizes[idx]+'"]', $(this) ).prop('disabled', true);
      }
    }
    for(idx = start = col.length - col.size - 1 ;idx< offsets.length; idx++){
      if( idx > (free + start - 1) ){
        $('.column-offset option[value="'+offsets[idx]+'"]', $(this) ).prop('disabled', true);
      }
    }
    return $(this);
  }

})( jQuery );
