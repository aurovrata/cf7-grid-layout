(function( $ ) {

	$(document).ready( function(){
    var offsets = ['offset-one','offset-two', 'offset-three', 'offset-four', 'offset-five', 'offset-six', 'offset-seven', 'offset-eight', 'offset-nine', 'offset-ten', 'offset-eleven'];
    var columnsizes = ['one', 'two', 'one-fourth', 'four', 'five', 'one-half', 'seven', 'two-thirds', 'nine', 'ten', 'eleven', 'full'];
    var $wpcf7Editor = $('textarea#wpcf7-form-hidden');
    var $grid = $('#grid-form');
    var $rowControl = $('#top-grid-controls');

    function buildGridForm(){
      var $form = $('<div>').append( $wpcf7Editor.text() );
      //remove the external forms
      var external = {};
      $('.cf7sg-external-form', $form).each(function(){
        var id = $(this).data('form');
        external[id] = $(this).children('.cf7sg-external-form-content').remove();
      });
      //replace columns content with textareas
      $('div.columns', $form).each(function(){
        var $area =  $($('#grid-col').html());
        if($(this).children().is('.container')){
          $('textarea.grid-input', $area).remove();
        }else{
          $('textarea.grid-input', $area).val($(this).html().trim());
          $(this).children().remove();
          $(this).text('');
        }
        $(this).prepend($area);
      });
      $('div.row', $form).each(function(){
        $(this).append( $('#grid-row .row-controls').clone() );
      });
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
      $('div.container.cf7-sg-table', $form).each(function(){
        var $ctrl = $(this).find('.row.cf7-sg-table > .row-controls').first().find('.table-row-label');
        $('input', $ctrl).prop('checked', true);
        //toggle disable the sibling input
        $('input', $ctrl.siblings('.unique-mod')).prop('disabled', function(i,v){return !v;});
      });
      //tabs
      $('ul.cf7-sg-tabs-list li', $form).each(function(){
        var text = $(this).children('a').text();
        $(this).append($('#grid-tabs ul li label').clone());
        $('label input', $(this)).val(text);
      });
      //reinsert the external forms
      $('.cf7sg-external-form', $form).each(function(){
        var id = $(this).data('form');
        //add controls
        $(this).append($('#grid-cf7-forms .form-controls').clone());
        $('.form-controls .form-select', $(this)).val(id);
        $(this).append( external[id] );
      });
      //add the form to the grid
      $grid.html($form.children());
      //set the value of each textarea as inner text
      $('textarea', $grid).each(function(){
        $(this).html($(this).val());
      });
      //set the first textarea as our default tag consumer
      var $textareaSelected = $('textarea', $grid).first();
      $textareaSelected.attr('id', 'wpcf7-form');
      //change this to whichever is live
      $('textarea', $grid).live('focus', function(){
        $textareaSelected.attr('id','');
        $textareaSelected.html($textareaSelected.val()); //set its inner html
        $textareaSelected = $(this).attr('id','wpcf7-form');
      });

    } //end buildGridForm()

    //initial contrustion of grid form
    buildGridForm();
    $grid.on('build-grid', function(){
      buildGridForm();
    });
    //make columns sortable
    $('.row', $grid).sortable({
      placeholder: "ui-state-highlight",
      handle:'.grid-column .dashicons-move',
      container:'.row',
      items: '> .columns'
    });
    //make rows sortable
    $grid.sortable({
      placeholder: "ui-state-highlight",
      handle:'.row-controls .dashicons-move',
      container:$grid,
      items: '> .container'
    });
    //make rows with columns sortable
    $('.columns').sortable({
      placeholder: "ui-state-highlight",
      handle:'.row-controls .dashicons-move',
      container:$grid,
      items: '> .container'
    });

    /*$grid.on('change', 'textarea.grid-input', function(event){
      var $target = $(event.target);
      if($target.is('textarea') ){
        $target.text($target.val());
      }
    });*/
    //offset/size change using event delegation
    $grid.on('change', $('select'), function(event){
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
    			'action': 'get_cf7_content',
    			'cf7_id': $target.val()
    		};
        $.post(ajaxurl, data, function(response) {
    			$('.cf7sg-external-form-content', $container).html(response);
    		});
      }
    });
    //show controls using delegation
    /*
      Row controls
    */

    $grid.on('click', $('.row-control'), function(event){
      var $target = $(event.target);
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
      }else if($target.is('.dashicons-trash.form-control') ){ //-----------TRASH external form
        $target.closest('cf7sg-external-form').remove();
        //TODO: update via ajax external form removal
      }else if($target.is('.dashicons-plus.row-control')){ //-----------ADD
        $target.closest('.container').parent().appendNewRow();
      }else if($target.is('.dashicons-edit.row-control')){ //-----------Show controls
        //hide any other controls that might be open
        $('.grid-controls', $grid).hide();
        $('.dashicons-no-alt', $grid).hide();
        $('.dashicons-edit', $grid).show();
        //now show this control
        $target.siblings('.grid-controls').show();
        $target.hide();
        $target.siblings('.dashicons-no-alt').show();
        /*
        TODO: use $('.grid-controls')filterColumnControls() to make sure columns sizes/offsets are correct.
        possibly introduce a boolean to check if filter has been run already on this row
        */
      }else if( $target.is('.dashicons-no-alt.row-control') ) { //----------------hide controls
        $target.siblings('.grid-controls').hide();
        $target.hide();
        $target.siblings('.dashicons-edit').show();
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

    });
    /*
      Collapsible / tabs rows
    */
    $grid.change('input', function(event){
      var $target = $(event.target);
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
      }
    });
    /*
      Column controls, show/hide contols, add column, and refresh select dropdowns for colum size on addition
    */
    $grid.on('click', $('.column-control'), function(event){
      var $target = $(event.target);
      var $parentColumn ;
      if( $target.is('.columns')){
        $parentColumn = $target;
      }else{
        $parentColumn = $target.closest('.columns');
      }
      //verify which target was clicked
      if( $target.is('.dashicons-edit.column-control') ){ //------------------show controls
        //hide any other controls that might be open
        $('.grid-controls', $grid).hide();
        $('.dashicons-no-alt', $grid).hide();
        $('.dashicons-edit', $grid).show();
        //now show this control
        $target.siblings('.grid-controls').show().filterColumnControls();
        $target.hide();
        $target.siblings('.dashicons-no-alt').show();
      }else if( $target.is('.dashicons-no-alt.column-control') ) { //----------------hide controls
        $target.siblings('.grid-controls').hide();
        $target.hide();
        $target.siblings('.dashicons-edit').show();
      }else if($target.is('.dashicons-trash.column-control') ){ //-------------------delete column
        $parentColumn.remove();
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
            text = $('textarea.grid-input', $parentColumn).remove().val();
          }
          $parentColumn.appendNewRow(text);
        }else{ //add to the main container
          $grid.appendNewRow();
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
        $newColumn.changeColumnSize('',columnsizes[newSize]);
        $parentColumn.after($newColumn);
      }
    });
    //refresh controls select
    $.fn.changeColumnSize = function(oldSize, newSize){
      if(oldSize.length > 0) $(this).removeClass(oldSize);
      $(this).addClass(newSize);
      $('.column-size option[value="'+newSize+'"]', $(this) ).prop('selected', true);
    }

    $.fn.filterColumnControls = function(){
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
      foundSize = false;
      for(idx=0;idx<classList.length; idx++){
        size = $.inArray(classList[idx], columnsizes);
        off = $.inArray(classList[idx], offsets);
        if(size > -1){
          foundSize = true;
          total += size + 1;
        }
        if(off > -1) total += off+1;
      }
      if(!foundSize){ //by default a colum which is not set set is treated as 1
        size = 0; //by default a colum which is not set set is treated as 1
        total += 1;
      }
      return {'length':total, 'size':size};
    }
    //add new rows
    $.fn.appendNewRow = function(areaCode = ''){
      if( $(this).is('.columns') || $(this).is($grid)){
        var $newRow = $( $('#grid-row').html() );
        //append the column controls and textarea
        $('.columns', $newRow).append( $($('#grid-col').html()) );
        //add the code to the textarea
        $('textarea.grid-input',$newRow).val(areaCode);
        //finaly append the new row to the column or container
        $(this).append($newRow);
      }
    }
    //grid is ready
    $wpcf7Editor.trigger('grid-ready');
  });


})( jQuery );
