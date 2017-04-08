(function( $ ) {
	'use strict';
  $(document).ready( function(){

    //.cf7-sg-table structure
    var cf7Form_table = $('div.has-table form.wpcf7-form');
    if(cf7Form_table.length){
      var tables = $('.container.cf7-sg-table', cf7Form_table);

      tables.each(function(){
        var table = $(this);
        var row = $('.row.cf7-sg-table', table);
        //change the input and select fields to arrays for storage
        $('input, select', row).each(function(){
          var name = $(this).attr('name');
          if( -1 == name.lastIndexOf('[]') ){
            $(this).attr('name', name+'[]');
          }
        });
        //add a button at the end of the table to add new rows
        table.append('<div class="cf7-sg-table-button ui-button add-row">Add Row</div>');
        //append a hidden clone of the first row which we can use to add
        row = row.clone().addClass('cf7-sg-cloned-table-row');
        table.append(row.hide());
        //trigger table ready event for custom scripts to change the button text
        table.trigger('sgTableReady');

      });
      //event delegation on table buttons
      cf7Form_table.click('.container.cf7-sg-table > .cf7-sg-table-button', function(event){
        var button = $(event.target);
        if( !button.is('div.cf7-sg-table-button') ){
          return;
        }
        var table = button.closest('.container.cf7-sg-table');
        var row = $('.cf7-sg-cloned-table-row', table).clone().removeClass('cf7-sg-cloned-table-row');
        var footer = $('.row.cf7-sg-table-footer', table);
        if(footer.length > 0){
          footer.before(row.show());
        }else{
          button.before(row.show());
        }
        //when the button is clicked, trigger a content increase for accordions to refresh
        table.trigger('sgContentIncrease');
      });
    }//end table structure

    //inline validation
    var cf7Form_validation = $('div.has-validation form.wpcf7-form');
    if(cf7Form_validation.length){
      var validation = $('input[type="number"][class*="sgv-"]', cf7Form_validation)
      validation.each(function(){
        var val = $(this).attr('value');
        $(this).data('current',val);
      });
      cf7Form_validation.change( 'input[type="number"]', function( event ) {
        if( !$(event.target).is('input[type="number"]')){
          return;
        }
        var field = $(event.target);
        var prev = field.data('current');
        switch( true ){
          case field.hasClass('sgv-no-zero'):
            if( 0 == field.val()){
              $("<span>Value cannot be zero</span>").dialog({
                modal: true,
                buttons: {
                  Ok: function() {
                    $( this ).dialog( "close" );
                  }
                }
              });
              field.val(prev);
            }
          case field.hasClass('sgv-no-negative'):
            if( 0 > field.val()){
              $("<span>Value cannot be negative</span>").dialog({
                modal: true,
                buttons: {
                  Ok: function() {
                    $( this ).dialog( "close" );
                  }
                }
              });
              field.val(prev);
            }
          case field.hasClass('sgv-not-empty'):
            if( ''== field.val()){
              $("<span>Value cannot be empty</span>").dialog({
                modal: true,
                buttons: {
                  Ok: function() {
                    $( this ).dialog( "close" );
                  }
                }
              });
              field.val(prev);
            }
        }
      });
    }//end validation

    //enable tabs

    //enable the tabs
    var cf7Form_tabs = $('div.has-tabs form.wpcf7-form');
    if(cf7Form_tabs.length){
      $( ".cf7-sg-tabs",  cf7Form_tabs).each(function(){
        $(this).tabs();
      });
      cf7Form_tabs.trigger('sgTabsReady');
    }
    //enable jquery-ui select menu,
    var cf7Form_niceSelect = $('div.has-nice-select form.wpcf7-form');
    if(cf7Form_niceSelect.length > 0){
      //check if this is a mapped cf7-2-post form
      cf7Form_niceSelect.filter('div.cf7_2_post form.wpcf7-form').each(function(){
        var nonceID = $(this).closest('div.cf7_2_post').attr('id');
        if(nonceID.length>0){
          $(this).on(nonceID, function(){
            $('select.ui-select', $(this)).each(function(){
              $(this).niceSelect();
            });
            $(this).trigger('sgNiceSelect');
          });
        }
      });
      //for non cf7 2 post forms, just enable the nice select
      cf7Form_niceSelect.not('div.cf7_2_post form.wpcf7-form').each(function(){
        $('select.ui-select', $(this)).each(function(){
          $(this).niceSelect();
        });
        $(this).trigger('sgNiceSelect');
      });
    }

    //enable collapsible rows
    var cf7Form_accordion = $('div.has-accordion form.wpcf7-form');
    if(cf7Form_accordion.length>0){
      //enable the toggle buttons
      cf7Form_accordion.filter('div.has-toggles form.wpcf7-form').each(function(){
        var form = $(this);
        var toggled_accordion = $('.collapsible.with-toggle', form);
        toggled_accordion.each(function(){
          var cssId = $(this).attr('id');
          if(typeof cssId == 'undefined'){
            cssId = randString(6);
            $(this).attr('id', cssId); //assign a random id
          }
          var state = $(this).data('active');
          if(typeof state == 'undefined'){
            state = false;
          }
          //setup the toggle button
          var toggle = $(this).children('.collapsible-title').children('.toggle');
          if(toggle.length > 0){
            var onText = toggle.data('on');
            if(onText.length == 0){
              onText = 'Yes';
            }
            var offText = toggle.data('off');
            if(onText.length == 0){
              offText = 'No';
            }
            toggle.toggles( { text:{ on:onText, off:offText }, on: state});
          }
          //enable the accordion
          $('#'+cssId).accordion({
            collapsible:true,
            icons:false,
            active:state,
            header:'> div.collapsible-title',
            heightStyle: "content",
            activate: function(event, ui){
              $(this).trigger('sgContentIncrease');
            }
          });
          //listen for new content added to this accordion
          toggled_accordion.on('sgContentIncrease', function(){
            $(this).accordion("refresh");
          });
          //event delegation on the header click to sync the toggle state
         form.click(toggled_accordion, function(event){
            var header=$(this).find('.collapsible-title');
            if($(event.target).is('.toggle-on') || $(event.target).is('.toggle-off')){
              header = $(event.target).closest('.collapsible-title');
            }else if($(event.target).parent().is('.collapsible.with-toggle') ){
              header = $(event.target);
            }else{
              return;
            }
            var toggleSwitch = header.children('.toggle').data('toggles');
            if( header.hasClass('ui-state-active') ){
              toggleSwitch.toggle(true);
            }else{
              toggleSwitch.toggle(false);
            }
          });
        });
      });
      //now enable the other collapsible rows
      cf7Form_accordion.each(function(){
        var rows = $('.collapsible', $(this)).not('.collapsible.with-toggle');
        rows.each(function(){
          var cssId = $(this).attr('id');
          if(typeof cssId == 'undefined'){
            cssId = randString(6);
            $(this).attr('id', cssId); //assign a random id
          }
          var state = $(this).data('active');
          if(typeof state == 'undefined' || state.length > 0){
            state = false;
          }else{
            state = 0;
          }
          $('#'+cssId).accordion({
            collapsible:true,
            active:state,
            heightStyle: "content",
            header: '> div.collapsible-title',
            activate: function(event, ui){
              $(this).trigger('sgContentIncrease');
            }
          });
          //listen for new content added to this accordion
          $(this).on('sgContentIncrease', function(){
            $(this).accordion("refresh");
          });
        });
      });
      cf7Form_accordion.trigger('sgCollapsibleRowsReady')
    }//end collapsible rows
    //random string generator
    function randString(n){
      if(!n){
          n = 5;
      }
      var text = '';
      var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
      for(var i=0; i < n; i++){
          text += possible.charAt(Math.floor(Math.random() * possible.length));
      }
      return text;
    }
    $('div.cf7-smart-grid form.wpcf7-form').trigger("cf7SmartGridReady");
  });
})( jQuery );
