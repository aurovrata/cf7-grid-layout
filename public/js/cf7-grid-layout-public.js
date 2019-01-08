(function( $ ) {
	'use strict';

  var trackTabsFields = []; //to keep track of fields that are converted to arrays.
  var trackTableFields = []; //to keep track of fields that are converted to arrays.
  var cf7sgPanels = {}; //object to store cloned panels

  /*warning messages used for in-form validation*/
  $.fn.cf7sgWarning = function(msg){
    var $this = $(this);
    if(!$this.is(':input')){
      return $this;
    }
    var $warning = $('<span class="cf7sg-validation-warning">'+msg+'<span class="confirm-button">ok</span></span>');
    $this.after($warning);
    // $warning.delay(5000).fadeOut('slow', function(){
    //   $this.remove();
    // });
  }

  $(document).ready( function(){
    //click delegation for warning windows
    $('form.wpcf7-form').on('click','.confirm-button', function(event){
      var $target = $(event.target);
      if($target.is('.cf7sg-validation-warning .confirm-button')){
        $target.parent().remove();
      }
    })
    //.cf7-sg-table structure
    var $cf7Form_table = $('div.has-table form.wpcf7-form');
    if($cf7Form_table.length){
      $('.container.cf7-sg-table', $cf7Form_table).each(function(){
        var $table = $(this);
        if($table[0].hasAttribute('id')){ /** @since 2.4.2 track table fields*/
          var $tracker = $('<input class="cf7sg-tracker-field" value="1" type="hidden">').attr('name', $table.attr('id'));
          $table.prepend($tracker);
        }
        var $row = $('.row.cf7-sg-table', $table);
        var label = 'Add Row';
        //get label for button.
        switch(true){
          case $table[0].hasAttribute('data-button'):
            label = $table.data('button');
            break;
          case $row[0].hasAttribute('data-button'): //bw compatibility.
            label = $row.data('button');
            break;
        }
        //change the input and select fields to arrays for storage
        var trackFields = false;
        //  if($cf7Form_table.is('div.has-update form.wpcf7-form')) trackFields = 'table';
        $row.find(':input').each(function(){
          var $this = $(this);
          var name = $this.attr('name');
          if(name.length>0){
            $this.addClass('cf7sg-'+name.replace('[]',''));
          }
        });

        //add a button at the end of the $table to add new rows
        var $footer = $table.next('.container.cf7-sg-table-footer');
        if($footer.length>0){
          $footer.detach();
          $table.after($footer);
          $footer.after('<div class="cf7-sg-table-button"><a href="javascript:void(0);" class="ui-button">'+label+'</a></div>');
        }else{
          $table.after('<div class="cf7-sg-table-button"><a href="javascript:void(0);" class="ui-button">'+label+'</a></div>');
        }
        //append a hidden clone of the first row which we can use to add
        $row.attr('data-row','0');
        $row = $row.clone().addClass('cf7-sg-cloned-table-row');
        $row.attr('data-row','-1');
        $table.append($row.hide());
        //disable all inputs from the clone row
        $(':input', $row).prop('disabled', true);
        //add controls to the row to delete
        $row.append('<span class="row-control"><span class="dashicons dashicons-no-alt"></span></span>');
        //trigger table ready event for custom scripts to change the button text
        $table.trigger('sgTableReady');

      });
      //event delegation on table buttons
      $cf7Form_table.click('.container', function(event){
        var $button = $(event.target);
        if( $button.is('div.cf7-sg-table-button a') ){ //----------add a row
          $button = $button.parent();
          var $table = $button.prev('.container');
          if($table.is('.cf7-sg-table-footer')) $table = $table.prev('.container');
          $table.cf7sgCloneRow();
        }else if($button.is('.cf7-sg-table .row-control .dashicons')){ //---------- delete the row, delete button only on last row
          var $table = $button.closest('.container');
          $button.closest('.row.cf7-sg-table').remove();
          var rows = $table.children('.row.cf7-sg-table').not('.cf7-sg-cloned-table-row').length
          $table.trigger('sgRowDeleted');
          /** @since 2.4.2 track table fields */
          var $tracker = $table.children('.cf7sg-tracker-field');
          if($tracker.length) $tracker.val(rows);
        }
      });
    }//end table structure

    //inline validation
    var cf7Form_validation = $('div.has-validation form.wpcf7-form');
    if(cf7Form_validation.length){
      var validation = $('input[type="number"][class*="sgv-"]', cf7Form_validation)
      validation.each(function(){
        var $this = $(this);
        var val = $this.attr('value');
        $this.data('current',val);
      });
      cf7Form_validation.change( 'input[type="number"]', function( event ) {
        if( !$(event.target).is('input[type="number"]')){
          return;
        }
        var $field = $(event.target);
        var prev = $field.data('current');
        var warning = false;
        switch( true ){
          case 0 == $field.val() && $field.is('.sgv-no-zero'):
            $field.after('<span class="cf7sg-validation-warning">Value cannot be zero</span>');
            $field.val(prev);
            warning=true;
            break;
          case 0 > $field.val() && $field.is('.sgv-no-negative'):
            $field.after('<span class="cf7sg-validation-warning">Value cannot be negative</span>');
            $field.val(prev);
            warning=true;
            break;
          case ''== $field.val() && $field.hasClass('sgv-not-empty'):
            $field.after('<span class="cf7sg-validation-warning">Value cannot be empty</span>');
            $field.val(prev);
            warning=true;
        }
        if(warning){
          $field.next('span.cf7sg-validation-warning').delay(3000).fadeOut('slow').remove();
        }
      });
    }//end validation

    //enable the tabs
    var $cf7Form_tabs = $('div.has-tabs form.wpcf7-form');
    if($cf7Form_tabs.length){
      cf7sgPanels = {}; //object to store cloned panels
      $( ".cf7-sg-tabs",  $cf7Form_tabs).each(function(){
        var $this = $(this);
        $this.tabs();
        //add a button to create more tabs
        var $list = $this.children('.cf7-sg-tabs-list');
        if( 1 == $list.children('li').length){
          $list.after('<ul class="cf7sg-add-tab ui-tabs-nav"><li class="ui-state-default ui-corner-top"><a class="cf7sg-add-tab ui-tabs-anchor"><span class="cf7sg-add-tab dashicons dashicons-plus"></span></a></li></ul>');
          //clone the tab
          var $panel = $this.children('.cf7-sg-tabs-panel').first();
          /** @since 2.4.2 track tab fields */
          var $tracker = $('<input class="cf7sg-tracker-field" value="1" type="hidden">').attr('name', $panel.attr('id'));
          $this.prepend($tracker);

           //add class to all fields
          $panel.find(':input').each(function(){
            var $this = $(this);
            if($this.is('.cf7-sg-table :input')) return;
            var name = $this.attr('name');
            if(name.length>0){
              $this.addClass('cf7sg-'+name.replace('[]',''));
            }
          });

          //finally store a clone of the panel to be able to add new tabs
          var $clonedP = $('<div>').append($panel.clone());
          //disable all inputs in the cloned panel so they don't get submitted.
          $(':input', $clonedP).prop('disabled', true);
          cf7sgPanels[$panel.attr('id')] = $clonedP.html();
        }
      });
      //trigger tabs ready event
      $cf7Form_tabs.trigger('sgTabsReady');
      //delegate tab addition/deletion
      $cf7Form_tabs.click('ul.ui-tabs-nav li', function(event){
        var $target = $(event.target);
        var $container = $target.closest('.cf7-sg-tabs');
        if($target.is('.cf7sg-close-tab')){ //---------------------- close/delete tab.
          var panelId = $target.siblings('a').attr('href');
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
          /** @since 2.4.2 udpate the tracker field*/
          var $tracker = $container.children('.cf7sg-tracker-field');
          if($tracker) $tracker.val($container.children('.cf7-sg-tabs-panel').length);
        }else if($target.is('.cf7sg-add-tab')){ //------------------- add tab.
          //add a new tab
          $container.cf7sgCloneTab();
        }
      });
    }
    //enable jquery-ui select menu,
    var cf7Form_niceSelect = $('div.has-nice-select form.wpcf7-form');
    if(cf7Form_niceSelect.length > 0){
      //check if this is a mapped cf7-2-post form
      cf7Form_niceSelect.filter('div.cf7_2_post form.wpcf7-form').each(function(){
        var $form = $(this);
        var nonceID = $form.closest('div.cf7_2_post').attr('id');
        if(nonceID.length>0){
          $form.on(nonceID, function(event){
            var $this = $(this);
            $('.cf7sg-dynamic-dropdown.ui-select:enabled', $this).each(function(){
              $(this).niceSelect();
            });
            $('.wpcf7-form-control.nice-select:enabled', $this).each(function(){
              $(this).niceSelect();
            });
            $this.trigger('sgNiceSelect');
          });
        }
      });
      //for non cf7 2 post forms, just enable the nice select
      cf7Form_niceSelect.not('div.cf7_2_post form.wpcf7-form').each(function(){
        var $form = $(this);
        $('.cf7sg-dynamic-dropdown.ui-select:enabled', $form).each(function(){
          $(this).niceSelect();
        });
        $('.wpcf7-form-control.nice-select:enabled', $form).each(function(){
          $(this).niceSelect();
        });
        $form.trigger('sgNiceSelect');
      });
    }
    //enabled select2 dropdowns
    var cf7Form_select2 = $('div.has-select2 form.wpcf7-form');
    if(cf7Form_select2.length > 0){
      //check if this is a mapped cf7-2-post form
      cf7Form_select2.filter('div.cf7_2_post form.wpcf7-form').each(function(){
        var $form = $(this);
        var nonceID = $form.closest('div.cf7_2_post').attr('id');
        if(nonceID.length>0){
          $form.on(nonceID, function(event){
            var $this = $(this);
            $('select.wpcf7-form-control.select2:enabled', $this).each(function(){
              var $select2 = $(this);
              $select2.select2({
                tags: $select2.is('.tags')
              });
            });
            $this.trigger('sgSelect2');
          });
        }
      });
      //for non cf7 2 post forms, just enable the nice select
      cf7Form_select2.not('div.cf7_2_post form.wpcf7-form').each(function(){
        var $this = $(this);
        $('select.wpcf7-form-control.select2:enabled', $this).each(function(){
          var $select2 = $(this);
          $select2.select2({
            tags: $select2.is('.tags')
          });
        });
        $this.trigger('sgSelect2');
      });
    }
		//enable datepicker
    var input = document.createElement( 'input' );
    input.setAttribute('type','date');
    var html5date = (input.type == 'date');
		var cf7Form_datepicker = $('div.has-date form.wpcf7-form');
		if(cf7Form_datepicker.length > 0){
			//check if this is a mapped cf7-2-post form
			cf7Form_datepicker.filter('div.cf7_2_post form.wpcf7-form').each(function(){
        var $form = $(this);
				var nonceID = $form.closest('div.cf7_2_post').attr('id');
				if(nonceID.length>0){
					$form.on(nonceID, function(event){
						//.wpcf7-form-control.wpcf7-date.wpcf7-validates-as-required.wpcf7-validates-as-date
						$('input.wpcf7-date:enabled', $(this)).each(function(){
              var $date = $(this);
							var id = $date.attr('id');
							if(typeof id == 'undefined'){
		            id = randString(6);
		            $date.attr('id', id); //assign a random id
		          }
              if(!html5date){
                $date.setupDatePicker();
              }
						});
					});
				}
			});
			//for non cf7 2 post forms, just enable the nice select
			cf7Form_datepicker.not('div.cf7_2_post form.wpcf7-form').each(function(){
				$('input.wpcf7-date:enabled', $(this)).each(function(){
          var $date = $(this);
					var id = $date.attr('id');
					if(typeof id == 'undefined'){
						id = randString(6);
						$date.attr('id', id); //assign a random id
					}
          if(!html5date){
            $date.setupDatePicker();
          }
				});
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
          var $button = $(this);
          var cssId = $button.attr('id');
          if(typeof cssId == 'undefined'){
            cssId = randString(6);
            $button.attr('id', cssId); //assign a random id
          }
          var state = $button.data('open');
          var toggled = false;
          if(typeof state == 'undefined'){
            state = false;
          }else{
            switch(state){
              case true:
                state = 0;
                toggled = true;
                break;
            }
          }
          /** If the Post My CF7 Form is mapping this form, lets check if toggled sections are filled and therefore open them.
          *@since 1.1.0
          */
          var $cf72post = form.closest('div.cf7_2_post');
          if( 0 == $cf72post.length){ //disable the input fields in toggled sections.
            if(!toggled){ //disable fields within a closed toggled section.
              $(':input', $(this).children('.row')).prop('disabled', true);
            }
          }//else deal with toggled fields once cf72post plugin has pre-filled sections.
          //setup the toggle button
          $button.children('.cf7sg-collapsible-title').children('.toggle').setupToggle(toggled);
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
        }); //end for each toggle section.

        /** @since 2.3.1 move event biding out of each() loop. */
        //listen for new content added to this accordion
        toggled_accordion.on('sgContentIncrease', function(){
          $(this).accordion("refresh");
        });
        //event delegation on the header click to sync the toggle state
        form.click(toggled_accordion, function(event){
          var $header;
          var $target =  $(event.target);
          if($target.is('span.cf7sg-title.toggled') || $target.is('.toggle-on') || $target.is('.toggle-off') ){
            $header = $target.closest('.cf7sg-collapsible-title');
          }else if($target.parent().is('.cf7sg-collapsible.with-toggle') ){
            $header = $target;
          }else{
            return;
          }
          var id = $header.closest('.container.cf7sg-collapsible').attr('id');
          /**
          * @since 1.1.0 track toggle status using toggle ids.
          */
          var toggleStatus = '';
          var $toggleHiddenStatus = $('input[name="_cf7sg_toggles"]', $(this));
          var trackToggle = false;
          if('undefined' != typeof id && $toggleHiddenStatus.length>0 ){
            if($toggleHiddenStatus.val().length>0){
              toggleStatus = JSON.parse($toggleHiddenStatus.val());
            }else toggleStatus = {};
            trackToggle = true;
          }
          //close other toggled sections if we have a group.
          var group = $header.parent().data('group');
          if(group){
            $('.cf7sg-collapsible.with-toggle[data-group="'+group+'"]', form).each(function(){
              var $toggled = $(this);
              var cid = $toggled.attr('id');
              if(id === cid) return; //current toggle.
              if(0===$toggled.accordion('option','active')){
                $toggled.accordion('option','active',false);
                $('.toggle', $toggled).data('toggles').toggle(false);
                $('.row.ui-accordion-content :input', $toggled).prop('disabled', true);
                if(trackToggle && toggleStatus.hasOwnProperty(cid)) delete toggleStatus[cid];
              }
            });
          }

          var toggleSwitch = $header.children('.toggle').data('toggles');
          if( $header.hasClass('ui-state-active') ){
            toggleSwitch.toggle(true);
            $('.row.ui-accordion-content :input', $header.parent()).not('.cf7-sg-cloned-table-row :input').prop('disabled', false);
            if(trackToggle){
              var $text = $header.clone();
              $text.children('.toggle').remove();
              toggleStatus[id] = $text.text().trim() + "|" + $header.children('.toggle').data('on');
            }
          }else{
            toggleSwitch.toggle(false);
            $('.row.ui-accordion-content :input', $header.parent()).each(function(){
              /**@since 2.7.1*/
              var val = this.value; //trim the value to remove spaces.
              $(this).val(val.trim()).prop('disabled', true);
            });
            if(trackToggle && toggleStatus.hasOwnProperty(id)) delete toggleStatus[id];
          }
          //store the toggle status in the hidden field.
          if('undefined' != typeof id && $toggleHiddenStatus.length>0 ){
            $toggleHiddenStatus.val(JSON.stringify(toggleStatus));
          }
        });//end for toggle click delegation
      }); //end collapsible rows with toggle buttons

      //now enable the other collapsible rows
      cf7Form_accordion.each(function(){
        var rows = $('.cf7sg-collapsible', $(this)).not('.cf7sg-collapsible.with-toggle');
        rows.each(function(){
          var $row = $(this);
          var cssId = $row.attr('id');
          if(typeof cssId == 'undefined'){
            cssId = randString(6);
            $row.attr('id', cssId); //assign a random id
          }
          var state = $row.data('open');
          if(typeof state == 'undefined'){
            state = false;
          }else{
            switch(state){
              case true:
                state = 0;
                break;
            }
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
          $row.on('sgContentIncrease', function(){
            $(this).accordion("refresh");
          });
        });
      });
      cf7Form_accordion.trigger('sgCollapsibleRowsReady')
    }//end collapsible rows

    /** If the Post My CF7 Form is mapping this form, lets check if toggled sections are filled and therefore open them.
    *@since 1.1.0
    */
    $('div.cf7_2_post div.has-toggles form.wpcf7-form').each(function(){
      var $form = $(this);
      var nonceID = $form.closest('div.cf7_2_post').attr('id');
      if(nonceID.length>0){
        $form.on(nonceID, function(event){
          $('.cf7sg-collapsible.with-toggle', $(this)).each(function(){
            var $this = $(this);
            var id = $this.attr('id');
            if('undefined' == typeof cf7sg.toggles_status || 'undefined' == typeof cf7sg.toggles_status[id]){
              $('.row.ui-accordion-content :input', $this).prop('disabled', true);
            }else{
              var toggle = $this.children('.cf7sg-collapsible-title');
              toggle.trigger('click');
            }
          });
        });
      }
    });
    //random string generator
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

    /*
     Smart Grid is now ready
    */
    $('div.cf7-smart-grid form.wpcf7-form').trigger("cf7SmartGridReady");

    /**
    * listen for cf7 submit invalid field event, and open parent sections and tabs.
    *@since 1.1.0
    */
    $('div.cf7-smart-grid').on('wpcf7:invalid', '.wpcf7', function(event, invalids){
      var $target = $(event.target);
      for(var idx in invalids.inputs){
        var name = invalids.inputs[idx].name;
        var $input = $(':input[name="'+name+'"]');
        var $section = $input.closest('.cf7sg-collapsible');
        if($section.length>0){
          $section.accordion("option","active",0); //activate.
        }
      }
    });
    /** on hover popup message for disabled submit buttons
    * @since 2.6.0
    */
    $('div.cf7-smart-grid .wpcf7-submit').after('<span class="cf7sg-popup display-none">'+cf7sg.submit_disabled+'</span>').parent().addClass('cf7sg-popup');

  }); //end on document ready().
  /*
    jQuery extended functions
  */
	//datepicker for date fields
	$.fn.setupDatePicker = function(){
    var $date = $(this);
		if(!$date.is('.wpcf7-date:enabled')){
			return $date;
		}
		var miny='';
		var maxy='' ;
		var min = $date.attr('min');
		if(typeof min == 'undefined'){
			min = null;
		}else{
			min = new Date(min);
			miny = min.getFullYear();
		}
		var max = $date.attr('max');
		if(typeof max == 'undefined'){
			max = null;
		}else{
			max = new Date(max);
			maxy = max.getFullYear();
		}
    $date.datepicker('destroy'); //in case some other plugin is setting up a datepicker.
		$date.datepicker({
      defaultDate: $date.val(),
      dateFormat: 'yy-mm-dd',
      minDate: min,
      maxDate:max,
      changeMonth: true,
      changeYear: true
    });

		if(miny>0 && maxy>0){
			$date.datepicker('option','yearRange',miny+':'+maxy);
		}else if(miny>0){
			$date.datepicker('option','yearRange',miny+':c+20');
		}else if(maxy>0){
			$date.datepicker('option','yearRange','c-20:'+maxy);
		}
		return $date;
	}
  //clone table row
  $.fn.cf7sgCloneRow = function(initSelect){
    if(typeof initSelect === 'undefined') initSelect =true;
    var $table = $(this);
    var $footer='';
    if($table.is('.cf7-sg-table-footer')){
      $footer = $table;
      $table = $table.closest('.container.cf7-sg-table');
    }
    //if not a table let's exit.
    if(!$table.is('.container.cf7-sg-table')){
      return $table;
    }
    var rowIdx = $table.children( '.row.cf7-sg-table').length - 1; //minus hidden row.
    var $cloneRow = $('.cf7-sg-cloned-table-row', $table);
    var $row = $cloneRow.clone();
    $row.removeClass('cf7-sg-cloned-table-row').attr('data-row',rowIdx);
    //show row so select2 init properly
    if($footer.length>0){
      $footer.before($row.show());
    }else{
      $cloneRow.before($row.show());
    }
    //add input name as class to parent span
    $(':input', $row).each(function(){
      var $input = $(this);
      //enable inputs
      $input.prop('disabled', false);
      var name = $input.attr('name');
      var suffix = '';
      if(name.endsWith('[]')){
        name = name.replace('[]','');
        suffix = '[]';
      }
      $input.attr('name', name+'_row-'+rowIdx+suffix);//.addClass('cf7sg-'+name);
      $input.closest('span.wpcf7-form-control-wrap').removeClass(name).addClass(name+'_row-'+rowIdx);
      //finally enabled the nice select dropdown.
      if($input.is('select.ui-select') && initSelect){
        $input.niceSelect();
      }
			if($input.is('select.nice-select') && initSelect){
        $input.niceSelect();
      }
      if($input.is('select.select2') && initSelect){
        $input.select2({
          tags: $input.is('.tags')
        });
        $input.trigger('sgSelect2');
      }
    });
    //when the button is clicked, trigger a content increase for accordions to refresh
    $table.trigger('sgContentIncrease');
    $row.trigger('sgRowAdded',rowIdx);
    /** @since 2.4.2 track table fields */
    var $tracker = $table.children('.cf7sg-tracker-field');
    if($tracker.length) $tracker.val(rowIdx+1); //rowIdx is zero based.
    return $table;
  }
  //clone tabs, called on a div.cf7-sg-tabs
  $.fn.cf7sgCloneTab = function(initSelect){
    var $tab = $(this);
    if(typeof initSelect === 'undefined') initSelect =true;
    /*initSelect is false if called from cf7_2_post field loading script,
    else if true whehn triggered from the front-end user event.*/
    if(!$tab.is('div.cf7-sg-tabs')){
      return $tab;
    }
    var $tabList = $tab.children('.cf7-sg-tabs-list');
    var tabCount = $tabList.children('li').length + 1;
    var firstTabId  = $tab.children('.cf7-sg-tabs-panel').first().attr('id');
    var panelId = firstTabId + '-' + tabCount;
    //create a tab clone
    var $newTab = $tabList.children('li').first().clone();
    $newTab.find('a').attr('href','#'+panelId).text($newTab.text()+ ' ('+ tabCount + ')');
    $newTab.append('<span class="cf7sg-close-tab dashicons dashicons-no-alt"></span>'); //remove button
    $newTab.removeClass('ui-tabs-active ui-state-active');
    $tabList.find('li .cf7sg-close-tab').hide();
    //append tab to list
    $tabList.append( $newTab );
    //new panel
    var $newPanel = $( cf7sgPanels[firstTabId] );
    $newPanel.attr('id', panelId);
    //add input name as class to parent span
    $(':input', $newPanel).each(function(){
      var $this = $(this);
      var isCloneRow = $this.is('.cf7-sg-cloned-table-row :input');
      //enable inputs
      if(!isCloneRow) $this.prop('disabled', false);
      var name = $this.attr('name');
      var suffix = '';
      if(name.endsWith('[]')){
        name = name.replace('[]','');
        suffix = '[]';
      }
      /*
        fields in additional tabs will be suffixed with .tab-[0-9]+
        fields in additional rows in tables will be suffixed with .row-[0-9]+
        fields in additional rows in tables that are in additional tabs will be suffixed with .tab-[0-9]+.row-[0-9]+
      */
      $this.attr('name', name+'_tab-'+(tabCount-1)+suffix);//.addClass('cf7sg-'+name);
      $this.closest('span.wpcf7-form-control-wrap').removeClass(name).addClass(name + '_tab-' + (tabCount-1));
      //enable nice select on the dropdown.
      if(!isCloneRow && $this.is('select.ui-select') && initSelect){
        $this.niceSelect();
      }
      if(!isCloneRow && $this.is('select.nice-select') && initSelect){
        $this.niceSelect();
      }
      if(!isCloneRow && $this.is('select.select2') && initSelect){
        $this.select2({
          tags: $this.is('.tags')
        });
        $this.trigger('sgSelect2');
      }
    });
    //append new panel
    $tab.append($newPanel);
    //change all the ids of inner tabs in the new panel
    var $innerTabs = $newPanel.find('ul.ui-tabs-nav li a');
    $innerTabs.each(function(){
      var $this = $(this);
      panelId = $this.attr('href');
      $this.attr('href', panelId+'-'+tabCount);
      var $innerPanel = $this.closest('ul.ui-tabs-nav').siblings('div'+panelId);
      $innerPanel.attr( 'id' , panelId.substring(1)+'-'+tabCount );
    });
    //enable tabs in the new panel
    $( '.cf7-sg-tabs', $newPanel ).each(function(){
      $(this).tabs();
    });

    //enable the collapsible titles & toggle buttons
    $('.cf7sg-collapsible.with-toggle', $newPanel).each(function(){
      var $this = $(this);
      /**
      * @since 1.1.0 grouped toggles/disabled inputs.
      */
      var id = $this.attr('id')+'_tab-'+(tabCount-1);
      $this.attr('id',id);//reset unique id.
      var group = $this.data('group');
      if(group){
        group = group+'_tab-'+(tabCount-1);
        $this.attr('data-group', group);
      }
      var state = $this.data('open');
      var toggled = false;
      if(typeof state == 'undefined'){
        state = false;
      }else{
        switch(state){
          case true:
            state = 0;
            toggled=true;
            break;
        }
      }
      $('.toggle', $this).setupToggle(toggled);
      $('.cf7sg-collapsible', $newPanel).accordion({
        collapsible:true,
        icons:false,
        active:state,
        header:'> div.cf7sg-collapsible-title',
        heightStyle: "content",
        activate: function(event, ui){
          $(this).trigger('sgContentIncrease');
        }
      });
      if(!toggled && initSelect){
        /*disable fields within a closed toggled section.
        * if toggled, then it is open. if initselect, it is triggered from user event.
        * else it is triggered from the cf7_2_post script and we need to wait for field values to be filled.
        */
        $('.row.ui-accordion-content :input', $this).prop('disabled', true);
      }
    }); //end collapsible titles.


    $tab.tabs( "refresh" );
    $tab.tabs( "option", "active", -1 );
    /** @since 1.2.2 */
    //trigger new tab event for custom js.
    $newPanel.trigger('sgTabAdded',tabCount);
    /** @since 2.4.2 track tabs and their fields.*/
    //increment tab count tacker.
    var $tracker = $tab.children('.cf7sg-tracker-field');
    if($tracker) $tracker.val($tab.children('.cf7-sg-tabs-panel').length);
    return $tab;
  }

  //setup toggles
  $.fn.setupToggle = function(state){
    var $this = $(this);
    if(typeof state === 'undefined') state =false;
    if( !$this.is('.toggle') ){
      return $this;
    }
    if($this.length > 0){
      var onText = $this.data('on');
      if(onText.length == 0){
        onText = 'Yes';
      }
      var offText = $this.data('off');
      if(onText.length == 0){
        offText = 'No';
      }
      $this.toggles( { drag:false, text:{ on:onText, off:offText }, on: state});
    }
    return $this;
  }

  // if this is an updated form (due to chagen in embeded forms), send grid fields back to server.
  $('div.cf7-smart-grid.has-update form.wpcf7-form').on('cf7SmartGridReady', function(){
    var $form = $(this);
    var serverRequest = $.ajax({
      type: 'POST',
      url: cf7sg.url,
      dataType: 'json',
      data: {
        'action':'save_grid_fields',
        'nonce' :$('input[name="_wpnonce"]', $form).val(),
        'tabs_fields' : JSON.stringify(trackTabsFields),
        'table_fields' : JSON.stringify(trackTableFields),
        'id': $('input[name="_wpcf7"]', $form).val()
      }
    });
    // serverRequest.done(function(msg){
    //   console.log('success');
    // });
    serverRequest.fail(function(jqXHR, textStatus){
      console.log('CF7 Smart Grid ERROR sending grid fields to server: '+textStatus);
    });
  });
})( jQuery );
