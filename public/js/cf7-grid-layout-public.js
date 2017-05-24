(function( $ ) {
	'use strict';

  var trackGridFields = []; //to keep track of fields that are converted to arrays.

  $(document).ready( function(){
    //.cf7-sg-table structure
    var $cf7Form_table = $('div.has-table form.wpcf7-form');
    if($cf7Form_table.length){

      $('.container.cf7-sg-table', $cf7Form_table).each(function(){
        var $table = $(this);
        var $row = $('.row.cf7-sg-table', $table);
        var label = $row.data('button');
        //change the input and select fields to arrays for storage
        $row.fields2arrays();
        //add a button at the end of the $table to add new rows
        var $footer = $table.next('.container.cf7-sg-table-footer');
        if($footer.length>0){
          $footer.after('<div class="cf7-sg-table-button"><a href="javascript:void(0);" class="ui-button">'+label+'</a></div>');
        }else{
          $table.after('<div class="cf7-sg-table-button"><a href="javascript:void(0);" class="ui-button">'+label+'</a></div>');
        }
        //append a hidden clone of the first row which we can use to add
        $row = $row.clone().addClass('cf7-sg-cloned-table-row');
        //disable all inputs from the clone row
        $(':input', $row).prop('disabled', true);
        //add controls to the row to delete
        $row.append('<span class="row-control"><span class="dashicons dashicons-no-alt"></span></span>');
        $table.append($row.hide());
        //trigger table ready event for custom scripts to change the button text
        $table.trigger('sgTableReady');

      });
      //event delegation on table buttons
      $cf7Form_table.click('.container', function(event){
        var $button = $(event.target);
        if( $button.is('div.cf7-sg-table-button a') ){ //----------add a row
          $button = $button.parent();
          var $table = $button.prev('.container');
          if($table.is('.cf7-sg-table-footer')){
            $table = $table.prev('.container.cf7-sg-table');
          }
          var rowIdx = $table.children( '.row.cf7-sg-table').length - 1; //minus hidden row.
          var $cloneRow = $('.cf7-sg-cloned-table-row', $table);
          var $row = $cloneRow.clone().removeClass('cf7-sg-cloned-table-row');
          //add input name as class to parent span
          $(':input', $row).each(function(){
            //enable inputs
            $(this).prop('disabled', false);
            var name = $(this).attr('name').replace('[]','');
            $(this).parent('span').removeClass(name).addClass(name+'_'+rowIdx);
            //finally enabled the nice select dropdown.
            if($(this).is('select.ui-select')){
              $(this).niceSelect();
            }
          });
          $cloneRow.before($row.show());
          //when the button is clicked, trigger a content increase for accordions to refresh
          $table.trigger('sgContentIncrease');
          $table.trigger('sgRowAdded');
        }else if($button.is('.cf7-sg-table .row-control .dashicons')){ //---------- delete the row
          $button.closest('.row.cf7-sg-table').remove();
          $button.closest('.container').trigger('sgRowDeleted');
        }
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
    var $cf7Form_tabs = $('div.has-tabs form.wpcf7-form');
    var panels = {}; //object to store cloned panels
    if($cf7Form_tabs.length){
      $( ".cf7-sg-tabs",  $cf7Form_tabs).each(function(){
        $(this).tabs();
        //add a button to create more tabs
        var $list = $(this).children('.cf7-sg-tabs-list');
        if( 1 == $list.children('li').length){
          $list.after('<ul class="cf7sg-add-tab ui-tabs-nav"><li class="ui-state-default ui-corner-top"><a class="cf7sg-add-tab ui-tabs-anchor"><span class="cf7sg-add-tab dashicons dashicons-plus"></span></a></li></ul>');
          //clone the tab
          var $panel = $(this).children('.cf7-sg-tabs-panel').first();
          //convert all fields to arrays
          $panel.fields2arrays();
          //handle tables within tabs
          $('.container.cf7-sg-table', $panel ).each(function(){
            //get the first field name in the table
            var iname = $(this).find(':input').first().attr('name');
            if(iname.lastIndexOf('[]') > 0){
              iname = iname.replace('[]','_cf7sg_tab_ext[]');
            }else{
              iname = iname + '_cf7sg_tab_ext[]';
            }
            //add a hidden field for storing tab id
            $(this).parent().children('.cf7-sg-table-button').append($('<input type="hidden" name="'+iname+'" class=".cf7sg-tabs-table-hidden"/>'))
          });
          //finally store a clone of the paenl to be able to add new tabs
          var $clonedP = $('<div>').append($panel.clone());
          //disable all inputs in the cloned panel so they don't get submitted.
          $(':input', $clonedP).prop('disabled', true);
          panels[$panel.attr('id')] = $clonedP.html();
        }
      });
      //trigger tabs ready event
      $cf7Form_tabs.trigger('sgTabsReady');
      //delegate tab addition/deletion
      $cf7Form_tabs.click('ul.ui-tabs-nav li', function(event){
        var $target = $(event.target);
        if($target.is('.cf7sg-close-tab')){ //---------------------- close tab
          var panelId = $target.siblings('a').attr('href');
          var $container = $target.closest('.cf7-sg-tabs');
          var activate = false;
          $container.children('div'+panelId).remove(); //remove panel
          if($target.closest('li').remove().is('.ui-state-active')){ //remove tab
            activate = true;
          }
          //show last close button
          var $lastClose = $container.find('.cf7-sg-tabs-list li:last-child .cf7sg-close-tab');
          if($lastClose.length > 0) $lastClose.show();
          if(activate){
            $container.tabs({active:0}); //activate the last tab
          }
        }else if($target.is('.cf7sg-add-tab')){ //------------------- add tab
          //add a new tab
          var $container = $target.closest('.cf7-sg-tabs');
          //var $addButton = $target.closest('ul.ui-tabs-nav.f7sg-add-tab').remove();
          var $tabList = $container.children('.cf7-sg-tabs-list');
          var tabCount = $tabList.children('li').length + 1;
          var firstTabId  = $container.children('.cf7-sg-tabs-panel').first().attr('id');
          var panelId = firstTabId + '-' + tabCount;
          //tab
          var $newTab = $target.closest( 'ul.cf7sg-add-tab' ).siblings( 'ul.ui-tabs-nav' ).children('li').first().clone();
          $newTab.find('a').attr('href','#'+panelId).text($newTab.text()+ ' ('+ tabCount + ')');
          $newTab.append('<span class="cf7sg-close-tab dashicons dashicons-no-alt"></span>'); //remove button
          $newTab.removeClass('ui-tabs-active ui-state-active');
          $tabList.find('li .cf7sg-close-tab').hide();
          //append tab to list
          $tabList.append( $newTab );
          //new panel
          var $newPanel = $( panels[firstTabId] );
          $newPanel.attr('id', panelId);
          //add input name as class to parent span
          $(':input', $newPanel).each(function(){
            //enable inputs
            $(this).prop('disabled', false);
            var name = $(this).attr('name').replace('[]','');
            $(this).parent('span').removeClass(name).addClass(name + '_' + (tabCount-1));
            //enable nice select on the dropdown.
            if($(this).is('select.ui-select')){
              $(this).niceSelect();
            }
          });
          //change all the ids of inner tabs in the new panel
          var $innerTabs = $newPanel.find('ul.ui-tabs-nav li a');
          $innerTabs.each(function(){
            panelId = $(this).attr('href');
            $(this).attr('href', panelId+'-'+tabCount);
            var $innerPanel = $(this).closest('ul.ui-tabs-nav').siblings('div'+panelId);
            $innerPanel.attr( 'id' , panelId.substring(1)+'-'+tabCount );
          });
          // //setup nice select in the new panel
          // $('select.ui-select:enabled', $newPanel).each(function(){
          //   $(this).niceSelect();
          // });
          //enable tabs in the new panel
          $( '.cf7-sg-tabs', $newPanel ).each(function(){
            $(this).tabs();
          });
          //enable the collapsible titles & toggle buttons
          $('.cf7sg-collapsible.with-toggle .toggle', $newPanel).setupToggle();
          $('.cf7sg-collapsible', $newPanel).accordion({
            collapsible:true,
            icons:false,
            active:false,header:'> div.cf7sg-collapsible-title',
            heightStyle: "content",
            activate: function(event, ui){
              $(this).trigger('sgContentIncrease');
            }
          });
          $('.cf7sg-collapsible.with-toggle', $newPanel).on('click',function(){
            var toggle = $(this).find('.toggle').data('toggles');
            if($(this).find('.cf7sg-collapsible-title').hasClass('ui-state-active') ){
              toggle.toggle(true);
            }else{
              toggle.toggle(false);
            }
          });
          //rename table fields in new panel
          $('.container.cf7-sg-table', $newPanel).each(function(){
            //hiden field is in the button which is a sibbling to this table container
            $('.cf7sg-tabs-table-hidden', $(this).parent()).val('_'+tabCount);
            //rename all fields
            $(':input', $(this)).each(function(){
                var iname = $(this).attr('name');
                if(iname.lastIndexOf('[]') > 0){
                  iname = iname.replace('[]','_'+tabCount+'[]');
                  $(this).attr('name', iname);
                }
            });
          });
          //append new panel
          $container.append($newPanel);
          $container.tabs( "refresh" );
          //$tabList.after($addButton);
        }
      });
    }
    //enable jquery-ui select menu,
    var cf7Form_niceSelect = $('div.has-nice-select form.wpcf7-form');
    if(cf7Form_niceSelect.length > 0){
      //check if this is a mapped cf7-2-post form
      cf7Form_niceSelect.filter('div.cf7_2_post form.wpcf7-form').each(function(){
        var nonceID = $(this).closest('div.cf7_2_post').attr('id');
        if(nonceID.length>0){
          $(this).on(nonceID, function(){
            $('select.ui-select:enabled', $(this)).each(function(){
              $(this).niceSelect();
            });
            $(this).trigger('sgNiceSelect');
          });
        }
      });
      //for non cf7 2 post forms, just enable the nice select
      cf7Form_niceSelect.not('div.cf7_2_post form.wpcf7-form').each(function(){
        $('select.ui-select:enabled', $(this)).each(function(){
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
        var toggled_accordion = $('.cf7sg-collapsible.with-toggle', form);
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
          $(this).children('.cf7sg-collapsible-title').children('.toggle').setupToggle(state);

          //enable the accordion
          $('#'+cssId).accordion({
            collapsible:true,
            icons:false,
            active:state,
            header:'> div.cf7sg-collapsible-title',
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
            var header=$(this).find('.cf7sg-collapsible-title');
            if($(event.target).is('.toggle-on') || $(event.target).is('.toggle-off')){
              header = $(event.target).closest('.cf7sg-collapsible-title');
            }else if($(event.target).parent().is('.cf7sg-collapsible.with-toggle') ){
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
        var rows = $('.cf7sg-collapsible', $(this)).not('.cf7sg-collapsible.with-toggle');
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
            header: '> div.cf7sg-collapsible-title',
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
    /*
     Smart Grid is now ready
    */
    $('div.cf7-smart-grid form.wpcf7-form').trigger("cf7SmartGridReady");

  });
  /*
    jQuery extended functions
  */
  //setup toggles
  $.fn.setupToggle = function(state=false){
    if( !$(this).is('.toggle') ){
      return $(this);
    }
    if($(this).length > 0){
      var onText = $(this).data('on');
      if(onText.length == 0){
        onText = 'Yes';
      }
      var offText = $(this).data('off');
      if(onText.length == 0){
        offText = 'No';
      }
      $(this).toggles( { text:{ on:onText, off:offText }, on: state});
    }
  }
  //convert fields to arrays for tables and tabs
  $.fn.fields2arrays = function(){
    $('input, select', $(this)).each(function(){
      var name = $(this).attr('name');
      if( -1 == name.lastIndexOf('[]') ){
        trackGridFields[trackGridFields.length] = $(this).attr('name');
        $(this).attr('name', name+'[]');
        $(this).addClass('cf7sg-'+name);
      }
      /*
       TODO: convert existing arrays to array of arrays, or possibly use objects?
       this will allow for tables within tabs.
       */
      /*
      TODO: limit tabs within tabs and tables within tables.,
      this is currently handled in the grid using css, however we will need to inforce it in the js too
      */
      /*
      TODO: what about radio/checkbox fields, how to handle these if they are arrays?
      */
    });
  }
  //send grid fields back to server

  $('form.wpcf7-form').on('cf7SmartGridReady', function(){
    var $form = $(this);
    var serverRequest = $.ajax({
      type: 'POST',
      url: cf7sg_ajaxData.url,
      dataType: 'json',
      data: {
        'action':'save_grid_fields',
        'nonce' :$('input[name="_wpnonce"]', $form).val(),
        'grid_fields' : JSON.stringify(trackGridFields),
        'id': $('input[name="_wpcf7"]', $form).val()
      }
    });
    serverRequest.done(function(msg){
      console.log('success');
    });
    serverRequest.fail(function(jqXHR, textStatus){
      console.log('CF7 Smart Grid ERROR sending grid fields to server: '+textStatus);
    });
  });
})( jQuery );
