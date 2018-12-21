
/**
 Javascript to handle grid editor
 Event 'cf7sg-form-change' fired on #contact-form-editor element when codemirror changes occur
*/
(function( $ ) {
  var offsets = ['offset-one','offset-two', 'offset-three', 'offset-four', 'offset-five', 'offset-six', 'offset-seven', 'offset-eight', 'offset-nine', 'offset-ten', 'offset-eleven'];
  var columnsizes = ['one', 'two', 'one-fourth', 'one-third', 'five', 'one-half', 'seven', 'two-thirds', 'nine', 'ten', 'eleven', 'full'];
  var $wpcf7Editor,$grid,$rowControl = null;
  //graphics UI template pattern
  var $pattern = $('<div>').html(cf7grid.preHTML+'\\s*(\\[.*\\s*\\].*\\s*){0,}\\s*'+cf7grid.postHTML);
  var required = cf7grid.requiredHTML.replace('*', '\\*');
  // console.log('r:'+required);
  $pattern.find('label').html('((\\s*.*)('+required+'){1}|(\\s*.*))');
  $pattern.find('.info-tip').text('(.*\\s*)');
  //console.log('p:'+$pattern.html());
  var templateRegex = new RegExp($pattern.html(), 'ig');
  var seekTemplate = false;
  var cssTemplate = 'div.field';
  var $template = $('<div id="cf7sg-dummy">').append(cf7grid.preHTML+cf7grid.postHTML);
  $template = $template.children();
  if(1==$template.length && $template.find('label').length>0){
    seekTemplate = true;
    cssTemplate = $template.prop('nodeName');
    if($template.prop('class').length>0){
      cssTemplate += '.'+$template.attr('class').split(' ').join('.');
    }
  }
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
      var isGrid = true; //return value.
      $grid.html(''); //empty the grid.
      if(0===$form.children('.container').length){
        isGrid = false;
      }
      //remove the external forms
      $('.cf7sg-external-form .cf7sg-external-form-content', $form).remove();
      //replace columns content with textareas
      /*--------------------------------------------------- convert columns */
      $('div.columns', $form).each(function(){
        var $this = $(this);
        var $area =  $($('#grid-col').html());
        if($this.children().is('.container')){
          $('textarea.grid-input', $area).remove();
          $('div.cf7-field-inner', $area).remove();
        }else{
          if(cf7grid.ui) $('textarea.grid-input', $area).html($this.html().trim());
          else $('textarea.grid-input', $area).val($this.html().trim());

          $this.children().remove();
          $this.text('');
        }
        $this.prepend($area);
      });
      $('div.row', $form).each(function(){
        $(this).append( $('#grid-row .row-controls').clone() );
      });
      /*--------------------------------------------------- convert collapsible sections  */
      $('div.container.cf7sg-collapsible', $form).each(function(){
        var $this = $(this);
        var id = $this.attr('id');
        if(typeof id == 'undefined'){
          id = randString(6);
          $this.attr('id', id); //assign a random id
        }
        var text = $this.children('.cf7sg-collapsible-title span.cf7sg-title').text();
        if(0==text.length){ //pre v1.8 title?.
          text = $this.children('.cf7sg-collapsible-title').text();
        }
        var $toggle = $('.toggle', $this.children('.cf7sg-collapsible-title'));
        if($toggle.length>0){
          $toggle = $toggle.clone();
        }

        $this.children('.cf7sg-collapsible-title').remove();
        $this.prepend( $('#grid-collapsible').html());
        $('input', $this.children('.cf7sg-collapsible-title')).not('[type="checkbox"]').val(text);
        if($toggle.length>0){
          $this.children('.cf7sg-collapsible-title').append($toggle);
          $('input[type="checkbox"]', $this.children('.cf7sg-collapsible-title') ).prop('checked', true);
        }
        var $ctrl = $this.children('.row').children('.row-controls').find('.collapsible-row-label');
        $('input', $ctrl).prop('checked', true);
        //toggle disable the sibling input
        $('input', $ctrl.siblings('.unique-mod')).prop('disabled', function(i,v){return !v;});
      });
      /*--------------------------------------------------- convert tables */
      $('div.container.cf7-sg-table', $form).each(function(){
        var $this = $(this);
        var id = $this.attr('id');
        if(typeof id == 'undefined'){
          id = 'cf7-sg-table-'+(new Date).getTime();
          $this.attr('id', id);
        }
        var $ctrl = $this.find('.row.cf7-sg-table > .row-controls' ).first().find('.table-row-label');
        $('input', $ctrl).prop('checked', true);
        //set button label
        var text = $this.data('button');
        if(typeof text  == 'undefined'){
          text = 'Add Row';
          $this.attr('data-button',text);
        }
        $ctrl.next('.table-row-button').children('input').val(text);
        //toggle disable the sibling input
        $('input', $ctrl.siblings('.unique-mod')).prop('disabled', function(i,v){return !v;});
        //toggle footer row
        var $footer = $this.next();
        if($footer.is('.cf7-sg-table-footer')){
          $ctrl = $footer.children('.row').first().find('.row-controls .footer-row-label');
          $('input.footer-row', $ctrl).prop('checked', true);
          $('input', $ctrl.siblings('.unique-mod')).prop('disabled', function(i,v){return !v;});
        }
      });
      //tabs
      /*--------------------------------------------------- convert tabs */
      $('ul.cf7-sg-tabs-list li', $form).each(function(){
        var $this = $(this);
        var text = $this.children('a').text();
        $this.append($('#grid-tabs ul li label').clone());
        $('label input', $this).val(text);
        //setup checkbox
        var $ctrl = $this.parent().siblings('.cf7-sg-tabs-panel');
        $ctrl = $ctrl.children('.row').find('.row-controls' ).first().find('.tabs-row-label');
        $('input.tabs-row', $ctrl).prop('checked', true);
        $('input', $ctrl.siblings('.unique-mod')).prop('disabled', function(i,v){return !v;});
      });
      //reinsert the external forms
      $('.cf7sg-external-form', $form).each(function(){
        var $extform = $(this);
        $extform.append($( $('#grid-cf7-forms .cf7sg-external-form').html() ) );
        var id = $extform.data('form');
        if($('#grid-cf7-forms .form-select option[value="'+id+'"]' ).length > 0 ){
          //add controls
          //$extform.append($('#grid-cf7-forms .form-controls').clone());
          $('.form-controls .form-select', $extform).val(id);
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
              $('.cf7sg-external-form-content', $extform).attr('id','cf7sg-form-'+id).append( response );
            }//TODO if error insert a msg.
      		});

        }
      });
      //add the form to the grid
      if($form.children('.container').length >0 || $form.children('.cf7sg-external-form').length>0){
        $grid.append($form.children());
      }else{ //this is not a cf7sg form.
        $grid.html($form.html());
      }
      //set the value of each textarea as inner text
      $('textarea', $grid).each(function(){
        var $this = $(this);
        $this.html($this.val());
      });
      /*--------------------------------------------------- if ui mode, then convert to gui template */
      var $textareaSelected='';
      if(cf7grid.ui){
        $('div.columns', $grid).each(function(){
          var $this = $(this);
          if($this.children().is('.container')) return true;
          $this.html2gui();
        });
      }else{
        //set the first textarea as our default tag consumer
        $textareaSelected = $('textarea', $grid).first();
        $textareaSelected.attr('id', 'wpcf7-form');
      }
      //change this to whichever is live
      $('textarea', $grid).live('focus', function(){
        var $this = $(this);
        if($textareaSelected.length>0 && $textareaSelected.is('#wpcf7-form')){
          $textareaSelected.attr('id','');
          $textareaSelected.html($textareaSelected.val()); //set its inner html
        }
        if($this.is('.grid-input')){
          $textareaSelected = $this.attr('id','wpcf7-form');
        }
      });
      return isGrid;
    } //end buildGridForm()

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
          $title.closest('.container.cf7sg-collapsible').addClass('with-toggle').attr('data-group','');
        }else{
          $('.toggle', $title).remove();
          $title.closest('.container.cf7sg-collapsible').removeClass('with-toggle').removeAttr( 'data-group');
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
        ----------------------------------------------------------ROW CONTRLS
      */
      var $parentRow;
      if($target.is('.dashicons-trash.form-control')){ //--------TRASH included form
        $target.closest('.cf7sg-external-form').remove();
      }else if($target.is('.dashicons-plus.form-control') ){ //---ADD external form
        $target.closest('.cf7sg-external-form').insertNewRow();
      }else if($target.is('.dashicons-trash.row-control')){ //--------TRASH
        var $parentContainer = $target.closest('.container');
        var $parent = $parentContainer.parent();
        $parentContainer.remove();
        if( $parent.is('.columns') ) { //verify is this is the last row being deleted
          if( 0 == $parent.children('.container').length ){
            //add a text area to the column
            $parent.children('.grid-column').append('<textarea class="grid-input"></textarea>');
          }
        }
      }else if($target.is('.dashicons-plus.row-control')){ //-----------ADD Row
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
          var id = $container.attr('id');
          if(typeof id == 'undefined'){
            id = randString(6);
            $container.attr('id', id); //assign a random id
          }
          $container.prepend($('#grid-collapsible').html());
        }else{
          $container.removeClass('cf7sg-collapsible');
          $container.children('.cf7sg-collapsible-title').remove();
        }
        //toggle disable the sibling input
        $target.parent().siblings('label.unique-mod').children('input').prop('disabled', function(i,v){return !v;});
      }else if($target.is('input.table-row')){ //-------------checkbox table row
        if($target.is(':checked')){
          var id = 'cf7-sg-table-'+(new Date).getTime();
          $target.closest('.row').addClass('cf7-sg-table');
          $target.closest('.container').addClass('cf7-sg-table').attr('id',id);
        }else{
          $target.closest('.row').removeClass('cf7-sg-table');
          $target.closest('.container').removeClass('cf7-sg-table').removeAttr('id');
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
      }else if( $target.is('.dashicons-controls-repeat.column-control') ) { //--------show hooks
        var $helper =$('<div class="helper-popup">').html( $('#grid-helper').html());
        $target.after($helper);
        var $copy = $('.copy-helper', $helper);
        var field = $target.data('field');
        var tag = $target.data('tag');
        var search = $target.data('search');
        var $hooks = $(search, '#fieldhelperdiv').clone();
        $('.cf7sg-helper-list', $helper).append($hooks);
        $('a.helper', $helper).each(function(){
          new Clipboard($(this)[0], {
            text: function(trigger) {
              var $target = $(trigger);
              var text = $target.data('cf72post');
              //get post slug
              var key = $('#post_name').val();
              text = text.replace(/\{\$form_key\}/gi, key);
              text = text.replace(/\{\$field_name\}/gi, field);
              text = text.replace(/\{\$field_name_slug\}/gi, field.replace('-','_'));
              return text;
            }
          });
          $(this).append($copy.clone());
        });
        $helper.click('a.helper, .dashicons-no-alt', function(e){
          $(this).remove();
        });
      }else if($target.is('.icon-code.column-control') ){
        var $focus = $target.closest('.columns');
        //toggle cf7sgfocus class on inner field to focus on.
        if($focus.is('.cf7sgfocus')){
          $focus.removeClass('cf7sgfocus');
        }else{
          $('.cf7sgfocus', $grid).removeClass('cf7sgfocus');
          $focus.addClass('cf7sgfocus');
        }
        //move to text editor.
        $('#form-editor-tabs').tabs('option',{ active:1});
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
              $('div.cf7-field-inner', $parentColumn).remove();
            }else{
              text = $('textarea.grid-input', $parentColumn).remove().val();
            }
          }else{
            text = $parentColumn.children('.container').remove();
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
	      $target.html($target.val()+'\n'); //ensure changes are capture in the codemirror editor
        $('#contact-form-editor').trigger('cf7sg-form-change');
      }
    });
    //before grid editor is closed, update the form with the last textarea
    //event 'cf7grid-tab-finalise' is fired in cf7-grid-codemirror.js file
    $grid.on('cf7grid-form-finalise', function(){
      if(cf7grid.ui){
        $('#wpcf7-form').parent().siblings('textarea.grid-input').on('change', function(){
          $grid.trigger('cf7grid-form-ready'); //codemirror initialisation
        });
        $('textarea.grid-input#wpcf7-form').on('change', function(){ //special case for custom code in cf7ui mode
          $grid.trigger('cf7grid-form-ready'); //codemirror initialisation
        });
        changeTextarea(true);
      }else{
        var $txta = $('textarea#wpcf7-form');
        $txta.html($txta.val()+'\n');
        $grid.trigger('cf7grid-form-ready'); //codemirror initialisation
      }
    });

    //initial construction of grid form
    buildGridForm();
    $grid.on('build-grid', function(){
      if( !buildGridForm() ){
        $('#form-editor-tabs').tabs('option',{ active:1, disabled:true});
      }else{
        var $focus = $('.cf7sgfocus', $grid);
        if($focus.length>0){
          var scrollPos = $focus.offset().top - $(window).height()/2 + $focus.height()/2;
          //console.log(scrollPos);
          $(window).scrollTop(scrollPos);
          $focus.removeClass('cf7sgfocus');
        }
      }

    });
    //make columns sortable
    sortableRows();
    //make rows sortable
    $('.columns, #grid-form').sortable({
      //placeholder: "ui-state-highlight",
      handle:'.row-controls > .dashicons-move',
      axis: 'y',
      //containment:'parent',
      items: '> .container, > .cf7sg-external-form',
      helper:'clone'
    });
    //grid is ready
    $wpcf7Editor.trigger('grid-ready');
  }); //end document ready

  //random id function.
  function randString(n){
    if(!n){
        n = 5;
    }
    var text = '';
    var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';
    for(var i=0; i < n; i++){
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    }
    return text;
  }
  //function to close any ui fields
  function closeAlluiFields(){
    $('.cf7-field-inner :input:visible').each(function(){
      $(this).closeUIfield();
    });
  }
  //close controls row/column
  function closeAllControls(){
    $('.grid-controls:visible', $grid).each(function(){
      var $this = $(this);
      $this.hide();
      $this.siblings('.dashicons-no-alt').hide();
      $this.siblings('.dashicons-edit').show();
    });
    //close helper popups.
    $('.dashicons-controls-repeat.column-control+.helper-popup').remove();
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
  function sortableRows( $newRow='' ){
    if($newRow.lenght>0){
      $('.row', $grid).not($newRow).sortable('destroy');
    }
    $('.row', $grid).sortable({
      //placeholder: "ui-state-highlight",
      //containment:'.row',
      handle:'.grid-column > .dashicons-move',
      connectWith: '.row',
      helper:'clone',
      items: '> .columns',
      receive: function( event, ui ) {
        //ui.item validate column size relative to new row columns.
        //ui.sender original row. cnacel if column does not fit in new row.
        //$targetRow has more than 12 columns then cancel.
        var $targetRow = $(event.target);//receiving row
        var row = $targetRow.getRowSize();
        //var total = row.length;
        //var sizes = row.cols;
        //var columns = sizes.length;
        if( row.length > 12){
          ui.sender.sortable('cancel');
          var $warning = $('<span class="cf7sg-warning">Not enough space in this row to add column</span>');
          $targetRow.after($warning);
          $warning.delay(2000).fadeOut('slow', function(){
            $(this).remove();
          });
        }else{
          //make sure the row controls is at the end.
          var control = $targetRow.children('.row-controls').remove();
          $targetRow.append(control);
        }
      }
    });
  }
  /* some function definitions...*/
  $.fn.closeUIfield = function(){
    var $this = $(this);
    if(!$this.is('.cf7-field-inner :input:visible')){
      return $this;
    }
    if($this.parent().is('.cf7-field-type')) changeTextarea();
    $this.hide().attr('id', '');
    $this.siblings('.dashicons-no-alt').hide();
    $this.siblings('.content').show();
    return $this;
  }
  $.fn.showUIfield = function(){
    var $this = $(this);
    if(!$this.is('.cf7-field-inner')){
      return $this;
    }
    $this.find('p.content').hide();
    $this.find('span.dashicons').show();
    var $input = $(':input', $this).show();
    $input.focus();
    if($input.is('textarea')){
      $input.attr('id', 'wpcf7-form');
      wpcf7Value = $input.val();
    }else{
      changeTextarea();
    }
    return $this;
  }
  $.fn.html2gui = function(html){
    var $this = $(this);
    if(typeof html === 'undefined') html ='';
    if(html.length === 0){
      //get the fields from the textarea
      html = $('textarea.grid-input', $this).text();
      if(html.length === 0){
        $('textarea.grid-input', $this).hide();
        return $this;
      }
    }
    var singleField = true;
    var search = $('<div>').append(html);

    if(seekTemplate && 1==search.children(cssTemplate).length){
      var lines = html.split(/\r\n|\r|\n/g);
      search = '';
      for(var i=0; i<lines.length; i++){
       search += lines[i].trim();
      }
      var match = templateRegex.exec(search);
      if(null !== match){
        //populate the fields
        var $field = $('div.cf7-field-label', $this);
        $('input', $field).val(match[1]);
        $('p.content', $field).html(match[1]);
        $field = $('div.cf7-field-type', $this);
        var tag = $('textarea', $field).val(match[5]).scanCF7Tag();
        $('p.content', $field).html(tag);
        $field = $('div.cf7-field-tip', $this);
        $('input', $field).val(match[6]);
        $('p.content', $field).html(match[6]);
        //hide the textarea
        $('textarea.grid-input', $this).hide();
        //reset global regex
        templateRegex.lastIndex = 0;
      }else{ //this html does not match our templates
        $('div.cf7-field-inner', $this).remove();
      }
    }else{//this html does not match our templates
     $('div.cf7-field-inner', $this).remove();
    }
  }

  $.fn.scanCF7Tag = function(){
    var $this = $(this);
    if(!$this.is('textarea')){
      return '';
    }
    var $parent = $this.parent(); //.cf7-field-type.
    var $helper = $parent.siblings('.dashicons-controls-repeat');
    $helper.each(function(index){
      if(index>0){
        $(this).remove();
        return;
      }
      $(this).removeAttr('data-field').removeAttr('data-tag').removeAttr('data-search');
    });
    //reset helper.
    $helper = $parent.siblings('.dashicons-controls-repeat');

    var cf7TagRegexp = /\[(.[^\s]*)\s*(.[^\s\]]*)[\s\[]*(.[^\[]*\"source:([^\s]*)\"[\s^\[]*|[.^\[]*(?!\"source:)[^\[]*)\]/img;
    var search = $this.val();
    var match = cf7TagRegexp.exec(search);
    var label='';
    var isRequired = false;
    var type = [];
    var fields = [];
    var hooks = [];
    var tag='';
    var isSubmit = false;
    var count =0;
    var field = '';
    var stopSearch = false;
    while (match != null && !stopSearch) {
      count++;
      label+='['+match[1]+' '+match[2]+']';
      tag = match[1].replace('*','');
      field = match[2];
      var helpers = ['cf7sg-tag-all'];
      helpers[helpers.length] = 'cf7sg-tag-'+tag;
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
        case 'acceptance': //special case with closing tag.
          stopSearch = true;
          break;
        case 'recaptch':
        case 'recaptcha': //special case with closing tag.
          label='[recaptcha]';
          stopSearch = true;
          break;
        case 'dynamic_select':
          var source ='';
          switch(match.length){
            case 5: //match[4] exists.
              if('undefined' !== typeof match[4]){
                source = match[4].split(':');
                source = source[0];
                helpers[helpers.length] = 'cf7sg-tag-dynamic_select-'+source;
              }
            case 4:  //lets deal with match[3]
              if(0=== source.length && match[3].indexOf('slug:'>-1)){
                source = 'taxonomy';
              }
              if(match[3].indexOf('class:tags')>-1){
                helpers[helpers.length] = 'cf7sg-tag-dynamic_select-tags';
                if(source.length>0){
                  helpers[helpers.length] = 'cf7sg-tag-dynamic_select-'+source+'-tags';
                }
              }
              break;
          }
          break;
      }
      type[type.length] = tag;
      fields[fields.length] = field;
      hooks[hooks.length] = helpers;
      if('*' === match[1][match[1].length -1]){
        isRequired = true;
      }
      if(!stopSearch) match = cf7TagRegexp.exec(search); //get the next match.
    }
    var classes = $('#grid-col div.cf7-field-type').attr('class');
    classes += " "+ type.join(' ');
    field = fields.join(' ');
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
    /**@since 2.0.0
    * setup fields for tag specific filters/actions.
    */
    //for each tag get corresponding set of filters.
    var helperUsed = false;
    for (var i = 0, len = type.length; i < len; i++) {
      var search = '';
      var helpers = hooks[i];
      for (var j=0, jlen = helpers.length; j<jlen; j++){
        search += 'li.'+helpers[j]+',';
      }
      search = search.slice(0,-1); //remove last ','
      if($( search ,$('#fieldhelperdiv')).length>0){
        //this tag has soem filters.
        if(helperUsed){
          var $clone = $helper.clone();
          $helper.after($clone);
          $helper = $clone;
        }
        helperUsed = true;
        $helper.attr('data-field', fields[i]);
        $helper.attr('data-tag', type[i]);
        $helper.attr('data-search', search);
        $helper.show();
      }
    }
    if(isSubmit){
      $parent.parent().addClass('submit-field');
    }

    return label;
  }
  $.fn.updateGridForm = function(){
    var $this = $(this);
    if(!$this.is('textarea.grid-input')){
      return $this;
    }
    //label
    var $label = $this.siblings('div.cf7-field-label').find(':input');
    var label = $label.val();
    //field
    var field = $this.siblings('div.cf7-field-type').find('textarea').val();
    var idx = label.indexOf(cf7grid.requiredHTML)
    if($this.siblings('div.cf7-field-type').is('.required')){
      if(idx<0){
        label += cf7grid.requiredHTML;
        $label.val(label);//input field.
        $label.siblings('p.content').html(label);
      }
    }else{
      if(idx>=0){
        label = label.replace(cf7grid.requiredHTML, '');
        $label.val(label);//input field.
        $label.siblings('p.content').html(label);
      }
    }
    //tip
    var tip = $this.siblings('div.cf7-field-tip').find(':input').val();
    var $cell = $('<div>').append( cf7grid.preHTML + field + cf7grid.postHTML );
    $('label', $cell).html(label);
    $('.info-tip', $cell).html(tip);
    //update grid input and trigger change to udpate form
    if(cf7grid.ui) $this.html($cell.html()+'\n').trigger('change');
    else $this.val($cell.html()).trigger('change');
    return $this;
  };

  $.fn.toggleSiblingUIFields = function(){
    var $this = $(this);
    if(!$this.is('div.cf7-field-inner')){
      return $this;
    }
    $this.siblings('div.cf7-field-inner').each(function(){
      var $this = $(this);
      $('p.content', $this).show();
      $(':input', $this).hide().attr('id','');
      $('span.dashicons', $this).hide();
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
    var $this = $(this);
    if(! $this.is('.columns')){
      return 0;
    }
    var off, foundSize, size = 0;
    var total = 0;
    var classList = $this.attr('class').split(/\s+/);
    var $sizes = $this.find('.grid-column select.column-size');
    var $offsets = $this.find('.grid-column select.column-offset');
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
  $.fn.insertNewRow = function(areaCode){
    var $this = $(this);
    if(typeof areaCode === 'undefined') areaCode ='';
    var append=true;
    switch(true){
      case ( $this.is('.columns') || $this.is($grid)):
      case $this.is('.container.cf7-sg-tabs-panel'): /* fixes rows added in panels*/
        append=true;
        break;
      case ($this.is('.container') || $this.is('.cf7sg-external-form') ):
        append=false;
        break;
      default: //unknown element, maybe an error.
        return $this;
    }

    var $newRow = $( $('#grid-row').html() );
    //append the column controls and textarea
    $('.columns', $newRow).append( $($('#grid-col').html()) );
    //append the new row to the column or container
    if(append) $this.append($newRow);
    else $this.after($newRow);
    //is areaCode text or jQuery object?
    if(areaCode instanceof jQuery){
      $('.cf7-field-inner', $newRow).remove();
      $('textarea.grid-input',$newRow).remove();
      $('.columns', $newRow).append(areaCode);
    }else{
      //add the code to the textarea
      if(cf7grid.ui){
        $('textarea.grid-input',$newRow).html(areaCode).hide();//.trigger('change');
        $newRow.html2gui(areaCode);
      }else{
        $('textarea.grid-input',$newRow).val(areaCode);//.trigger('change');
        $('div.cf7-field-inner', $newRow).hide();
      }
    }
    //make new row's columns sortable.
    sortableRows($newRow);
    return $this;
  }
  //refresh controls select
  $.fn.changeColumnSize = function(oldSize, newSize){
    var $this = $(this);
    if(oldSize.length > 0) $this.removeClass(oldSize);
    $this.addClass(newSize);
    $('.column-size option[value="'+newSize+'"]', $this ).prop('selected', true);
  }
  //$target.closest('.grid-controls').filterColumnControls();
  $.fn.filterColumnControls = function(){
    var $this = $(this);
    if(!$this.is('.grid-controls')){
      return $this;
    }
    //enable all options
    $('.column-size option', $this ).prop('disabled', false);
    $('.column-offset option', $this ).prop('disabled', false);
    var $parentRow = $this.closest('.row');
    var $parentColumn = $this.closest('.columns');
    var row = $parentRow.getRowSize();
    var col = $parentColumn.getColumnTotalSize();
    var idx, start, free = 0;
    if(row.length < 12) free = (12 - row.length);
    for(idx = start = col.size+1; idx < columnsizes.length; idx++){
      if( idx > (free + start - 1) ){
        $('.column-size option[value="'+columnsizes[idx]+'"]', $this ).prop('disabled', true);
      }
    }
    for(idx = start = col.length - col.size - 1 ;idx< offsets.length; idx++){
      if( idx > (free + start - 1) ){
        $('.column-offset option[value="'+offsets[idx]+'"]', $this ).prop('disabled', true);
      }
    }
    return $this;
  }

})( jQuery );
