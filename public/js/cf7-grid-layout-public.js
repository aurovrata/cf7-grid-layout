(function( $ ) {
	'use strict';

  var trackTabsFields = []; //to keep track of fields that are converted to arrays.
  var trackTableFields = []; //to keep track of fields that are converted to arrays.
  var cf7sgPanels = {}; //object to store cloned panels
  var cf7sgCustomSelect2Templates = (function (ccst) {return ccst;}(cf7sgCustomSelect2Templates || {}));
  /*warning messages used for in-form validation*/
  $.fn.cf7sgWarning = function(msg, time){
    var $this = $(this);
    if(!$this.is(':input')){
      return $this;
    }
    if(isEmpty(time)) time=0;
    var $warning = $('<span class="cf7sg-validation-warning">'+msg+'<span class="confirm-button">ok</span></span>');
    $this.after($warning);
    if(time>0){
      $warning.delay(time).fadeOut('slow', function(){
        $this.remove();
      });
    }
  }

  $(document).ready( function(){
    //click delegation for warning windows
    $('form.wpcf7-form').on('click','.confirm-button', function(e){
      var $target = $(e.target);
      if($target.is('.cf7sg-validation-warning .confirm-button')){
        $target.parent().remove();
      }
    }).each(function(){ /** @since 4.4 setup form id */
      var $form = $(this), id = $form.closest('div.cf7-smart-grid').attr('id');
      $form.attr('id','wpcf7-'+id);
    });
    //.cf7-sg-table structure, smart grid only.
    var $cf7Form_table = $('div.cf7-smart-grid.has-table form.wpcf7-form');
    if($cf7Form_table.length){
      $('.container.cf7-sg-table', $cf7Form_table).each(function(){
        var $table = $(this), fid = $table.closest('div.cf7-smart-grid').attr('id');
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
          var name = $this.attr('name').replace('[]','');
          if(name.length>0){
            $this.addClass('cf7sg-'+name+' cf7sgrow-field');
          }
          /** @since 4.4 prefill */
          if( !objEmpty(cf7sg[fid],['prefill',name]) ){
            $this.prefillCF7Field(cf7sg[fid].prefill[name], fid);
            delete cf7sg[fid].prefill[name];
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
        $(':input', $row).each(function(){ /** @since 4.4 */
          var $input = $(this).prop('disabled', true),
            name = '_cf7sgcloned_'+$input.attr('name');
          $input.attr('name',name);
        });
        //add controls to the row to delete
        $row.append('<span class="row-control"><span class="dashicons dashicons-no-alt"></span></span>');
        //trigger table ready event for custom scripts to change the button text
        $cf7Form_table.on('cf7SmartGridReady', function(e){
          $table.trigger({type:'sgTableReady', 'table-id':$table.attr('id')});
        })
      });
      //event delegation on table buttons
      $cf7Form_table.click('.container', function(e){
        var $button = $(e.target);
        if( $button.is('div.cf7-sg-table-button a') ){ //----------add a row
          $button = $button.parent();
          /** @since 2.8.0 */
          if($button.hasClass('disabled')) return;

          var $table = $button.prev('.container');
          if($table.is('.cf7-sg-table-footer')) $table = $table.prev('.container');
          $table.cf7sgCloneRow(true, e.target);
        }else if($button.is('.cf7-sg-table .row-control .dashicons')){ //---------- delete the row, delete button only on last row
          $button.closest('.container').cf7sgRemoveRow();
        }
      });
    }//end table structure

    //inline validation any forms
    var cf7Form_validation = $('div.cf7-smart-grid.has-validation form.wpcf7-form');
    if(cf7Form_validation.length){

      var validation = $('input[type="number"][class*="sgv-"]', cf7Form_validation)
      validation.each(function(){
        var $this = $(this), name = $this.attr('name'),
          fid = $this.closest('div.cf7-smart-grid').attr('id'),
          val = $this.attr('value');
        if(!objEmpty( cf7sg[fid],['prefill',name]) ){
          $this.prefillCF7Field(cf7sg[fid].prefill[name], fid);
          val = cf7sg[fid].prefill[name];
          delete cf7sg[fid].prefill[name];
        }
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
    //enable collapsible rows only smart grid
    var cf7Form_accordion = $('div.cf7-smart-grid.has-accordion form.wpcf7-form');
    if(cf7Form_accordion.length>0){
      //enable the toggle buttons
      cf7Form_accordion.filter('div.has-toggles form.wpcf7-form').each(function(){
        var $form = $(this);
        var toggled_accordion = $('.cf7sg-collapsible.with-toggle', $form);
        /**
        * @since 1.1.0 track toggle status using toggle ids.
        */
        var toggleStatus = '', $toggleHiddenStatus = $('input[name="_cf7sg_toggles"]', $form);
        var trackToggle = false, disableGroupActiveToggle = {};
        if( $toggleHiddenStatus.length>0 ){
          if($toggleHiddenStatus.val().length>0){
            toggleStatus = JSON.parse($toggleHiddenStatus.val());
          }else toggleStatus = {};
          trackToggle = true;
        }
        $.fn.trackToggle = function(track){
          if(!trackToggle) return false;
          var $toggledSection = $(this),
            tid = $toggledSection.attr('id');

          if( !$toggledSection.is('.with-toggle') ) return false;
          if(track){
            var $text = $('.cf7sg-collapsible-title', $toggledSection).clone(),
              onText = $text.children('.toggle').data('on');
            $text.children('.toggle').remove();
            toggleStatus[tid] = $text.text().trim() + "|" + onText;
          }else{
            if(toggleStatus.hasOwnProperty(tid)) delete toggleStatus[tid];
          }
          //store the toggle status in the hidden field.
          if('undefined' != typeof tid && $toggleHiddenStatus.length>0 ){
            $toggleHiddenStatus.val(JSON.stringify(toggleStatus));
          }
        }
        //event delegation on the header click to sync the toggle state
        $form.on('click','.cf7sg-collapsible.with-toggle', function(e){
          var $header;
          var $target =  $(e.target);
          if($target.is('span.cf7sg-title.toggled') || $target.is('.toggle-on') || $target.is('.toggle-off') || $target.is('.toggle') ){
            $header = $target.closest('.cf7sg-collapsible-title');
          }else if($target.parent().is('.cf7sg-collapsible.with-toggle') ){
            $header = $target;
          }else{
            return;
          }
          //cancel section close if disabled.
          if($('.toggle', $header).is('.disabled')) return false;
          var $toggledSection = $header.closest('.container.cf7sg-collapsible'), id= $toggledSection.attr('id');

          //close other toggled sections if we have a group.
          var group = $header.parent().removeClass('collapsed').data('group');
          if(group){
            $('.cf7sg-collapsible.with-toggle[data-group="'+group+'"]', $form).each(function(){
              var $toggled = $(this), $togl = $('.toggle', $toggled);
              var cid = $toggled.attr('id');
              if(id === cid){
                if(disableGroupActiveToggle[group]) $togl.toggleClass('disabled', true);
                return; //current toggle.
              }
              if(0===$toggled.accordion('option','active')){
                $toggled.addClass('collapsed');
                $togl.toggleClass('disabled', false).data('toggles').toggle(false);
                $toggled.accordion('option','active',false);
                $('.row.ui-accordion-content :input', $toggled).prop('disabled', true);
                $toggled.trackToggle(false);
              }
            });
          }

          var toggleSwitch = $header.children('.toggle').data('toggles');
          if('undefined' == typeof toggleSwitch && cf7sg.debug){
            console.log('undefined toggleSwitch, header parent:');
            console.log($header);
            console.log('e.target:');
            console.log(e.target);
          }
          if( $header.hasClass('ui-state-active') ){
            toggleSwitch.toggle(true);
            //enable input fields and convert to niceselect.
            var inputs = $('.row.ui-accordion-content :input', $header.parent()).not('.cf7-sg-cloned-table-row :input').not('.collapsed :input').prop('disabled', false);
            if($form.is('.has-nice-select form')){
              inputs.filter('.wpcf7-form-control.nice-select:enabled').niceSelect();
            }
            $toggledSection.trackToggle(true);
          }else{
            toggleSwitch.toggle(false);
            $('.row.ui-accordion-content :input', $header.parent()).each(function(){
              /**@since 2.7.1*/
              var val = this.value; //trim the value to remove spaces.
              $(this).val(val.trim()).prop('disabled', true);
            });
            $toggledSection.trackToggle(false);
          }
        });//end for toggle click delegation

        toggled_accordion.each(function(){
          var $section = $(this);
          var cssId = $section.attr('id');
          if(typeof cssId == 'undefined'){
            cssId = randString(6);
            $section.attr('id', cssId); //assign a random id
          }
          var state = $section.data('open'), group=$section.data('group'),toggled = false;

          if(group && 'undefined' == typeof disableGroupActiveToggle[group]){
            disableGroupActiveToggle[group]=false;
          }
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
          var $cf72post = $form.closest('div.cf7_2_post'),
            disableFields = ( 0 == $cf72post.length) && !toggled,
            fid = $section.closest('div.cf7-smart-grid').attr('id');

          $(':input', $section.children('.row')).each(function(){
            var $field = $(this), name = $field.attr('name').replace('[]','');
            if( !objEmpty(cf7sg[fid], ['prefill','_cf7sg_toggles',cssId]) ){
              $field.prefillCF7Field(cf7sg[fid].prefill[name], fid);
              state = 0;
              toggled = true;
              delete cf7sg[fid].prefill[name];
            }
            if(disableFields) $field.prop('disabled', true);
          });

          if(!toggled) $section.addClass('collapsed');
          //disable fields within a closed toggled section
          //setup the toggle button
          $section.children('.cf7sg-collapsible-title').children('.toggle').setupToggle(toggled, group);
          if(toggled){
            $section.trackToggle(true);
            if(group) disableGroupActiveToggle[group]=true;
          }
          //enable the accordion
          $('#'+cssId).accordion({
            collapsible:true,
            icons:false,
            active:state,
            header:'> div.cf7sg-collapsible-title',
            heightStyle: "content",
            activate: function(event, ui){
              $(this).trigger('sgContentIncrease');
            },
            beforeActivate: function(event, ui){
              if($('.toggle', ui.oldHeader).is('.disabled')) return false;
            },
            create: function(e){
              $(this).trigger({type:'sgCollapsibleRowsReady','section-id':cssId})
            }
          });
        }); //end for each toggle section.

        /** @since 2.3.1 move event biding out of each() loop. */
        //listen for new content added to this accordion
        toggled_accordion.on('sgContentIncrease', function(){
          $(this).accordion("refresh");
        });
      }); //end collapsible rows with toggle buttons

      //now enable the other collapsible rows
      cf7Form_accordion.each(function(){
        /** @since 3.4.0 differentiate accordion of collapsible rows*/
        var $form = $(this),$rows = $('.cf7sg-collapsible', $form).not('.cf7sg-collapsible.with-toggle').not('.cf7sg-accordion-rows > .cf7sg-collapsible').not('.cf7sg-slider-section >.cf7sg-collapsible');
        $rows = $rows.add( $('.cf7sg-accordion-rows', $form) );
        var promises = [];
        $rows.each(function(){
          var $row = $(this);
          var cssId = $row.attr('id');
          if(typeof cssId == 'undefined'){
            cssId = randString(6);
            $row.attr('id', cssId); //assign a random id
          }
          var state = $row.data('open'), open =false;

          if(typeof state == 'undefined'){
            state = false;
          }else{
            switch(state){
              case true:
                state = 0;
                open=true;
                break;
            }
          }
          // if(!open) $row.addClass('collapsed');

          var options={
            heightStyle: "content",
            create: function(e){
              $(this).trigger({type:'sgCollapsibleRowsReady','section-id':cssId})
            }
          };
          /** @since 3.4.0 handle accordion rows for stepped flow */
          if($row.is('.cf7sg-accordion-rows')){
            /** @since 4.7.1 ensure unique headers for toggle sections within accordions */
            $row.children('.cf7sg-collapsible').children('.cf7sg-collapsible-title').addClass('accordion');
            Object.assign(options,{header: 'div.cf7sg-collapsible-title.accordion',animate:false})
          }else{
            Object.assign(options,{
              collapsible:true,
              active:state,
              header: '> div.cf7sg-collapsible-title',
              activate: function(event, ui){
                $(this).trigger('sgContentIncrease');
              }
            });
          }
          $('#'+cssId).accordion(options);
          //listen for new content added to this accordion
          $row.on('sgContentIncrease', function(){
            $(this).accordion("refresh");
          })
        })
      })
      // cf7Form_accordion
    }//end collapsible rows
    //enable the tabs smart grid only.
    var $cf7Form_tabs = $('div.cf7-smart-grid.has-tabs form.wpcf7-form');
    if($cf7Form_tabs.length){
      cf7sgPanels = {}; //object to store cloned panels
      //delegate tab addition/deletion
      $cf7Form_tabs.click('ul.ui-tabs-nav li', function(e){
        var $target = $(e.target);
        var $container = $target.closest('.cf7-sg-tabs');
        if($target.is('.cf7sg-close-tab')){ //---------------------- close/delete tab.
          $container.cf7sgRemoveTab();
        }else if($target.is('.cf7sg-add-tab')){ //------------------- add tab.
          $container.cf7sgCloneTab(true,true);
        }
      });
      $( ".cf7-sg-tabs",  $cf7Form_tabs).each(function(){
        var $this = $(this), fid = $this.closest('div.cf7-smart-grid').attr('id');
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
            if($this.is('.cf7-sg-table :input')){
              $this.addClass('cf7sgtab-field');
              return;
            }
            var name = $this.attr('name').replace('[]','');
            if(name.length>0){
              $this.addClass('cf7sg-'+name+' cf7sgtab-field');
              /** @since 4.4 prefill fields */
              if( !objEmpty( cf7sg[fid],['prefill',name] ) ){
                $this.prefillCF7Field(cf7sg[fid][name],fid);
                delete cf7sg[fid].prefill[name];
              }
            }
          });

          //finally store a clone of the panel to be able to add new tabs
          var $clonedP = $('<div>').append($panel.clone());
          //disable all inputs in the cloned panel so they don't get submitted.
          $(':input', $clonedP).prop('disabled', true),
          cf7sgPanels[$panel.attr('id')] = $clonedP.html();
        }
        //create tab.
        $this.tabs( {create: function(e){$(this).trigger('sgTabsReady')} } );
      })
    }
    /** @since 4.4 prefill fields */
    $('div.cf7-smart-grid').each(function(){
      var $form= $(this), fid = $form.attr('id');
      if( !objEmpty( cf7sg[fid],['prefill'] ) ){
        Object.keys(cf7sg[fid].prefill).forEach(function(f){
          var $f = $('.'+f+' :input', $form);
          if(0==$f.length) $f = $(':input[name="'+f+'"]', $form); /* hidden field fix */
          $f.prefillCF7Field(cf7sg[fid].prefill[f], fid);
        })
      }
    });
    //enable jquery-ui select menu, any forms.
    var cf7Form_niceSelect = $('div.cf7-smart-grid.has-nice-select form.wpcf7-form');
    if(cf7Form_niceSelect.length > 0){
      //check if this is a mapped cf7-2-post form
      cf7Form_niceSelect.filter('div.cf7_2_post form.wpcf7-form').each(function(){
        var $form = $(this);
        var nonceID = $form.closest('div.cf7_2_post').attr('id');
        if(nonceID.length>0){
          $form.on(nonceID, function(event){
            $('.cf7sg-dynamic-dropdown.ui-select:enabled', $this).each(function(){
              $(this).niceSelect();
            });
            $('.wpcf7-form-control.nice-select:enabled', $this).each(function(){
              $(this).niceSelect();
            });
            $(this).trigger('sgNiceSelect');
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
        $form.on('cf7SmartGridReady', function(e){
          $form.trigger('sgNiceSelect')
        })
      });
    }
    //enabled select2 dropdowns any forms.
    var cf7Form_select2 = $('div.cf7-smart-grid.has-select2 form.wpcf7-form');

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
              $select2.select2($select2.cf7sgSelect2Options());
            });
            $this.trigger('sgSelect2');
          });
        }
      });
      //for non cf7 2 post forms, just enable the nice select
      cf7Form_select2.not('div.cf7_2_post form.wpcf7-form').each(function(){
        var $form = $(this);
        $('select.wpcf7-form-control.select2:enabled', $form).each(function(){
          var $select2 = $(this);
          $select2.select2($select2.cf7sgSelect2Options());

        });
        $form.on('cf7SmartGridReady', function(e){ $form.trigger('sgSelect2')})
      });
    }
		//enable datepicker
    var input = document.createElement( 'input' );
    input.setAttribute('type','date');
    var html5date = (input.type == 'date');
		var cf7Form_datepicker = $('div.cf7-smart-grid.has-date form.wpcf7-form');
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
            /** @since 3.1 enable datepicker on text fields */
            $('input.wpcf7-text.datepicker:enabled', $(this)).each(function(){
              var $date = $(this);
							var id = $date.attr('id');
							if(typeof id == 'undefined'){
		            id = randString(6);
		            $date.attr('id', id); //assign a random id
		          }
              $date.setupDatePicker();
						});
					});
				}
			});
			//for non cf7 2 post forms, just enable the datepicker
			cf7Form_datepicker.not('div.cf7_2_post form.wpcf7-form').each(function(){
				$('input.wpcf7-text.datepicker:enabled', $(this)).each(function(){
          var $date = $(this);
					var id = $date.attr('id');
					if(typeof id == 'undefined'){
						id = randString(6);
						$date.attr('id', id); //assign a random id
					}
          $date.setupDatePicker();
				});
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

    var $form_slider = $('div.cf7-smart-grid.has-slider form.wpcf7-form');
    $form_slider.each(function(){
      var $form = $(this);
      $('.cf7sg-slider-section').each(function(){
        var glider, $slider = $(this).wrapInner('<div class="glider"></div>'),
          slideCount = -1,
          $glider = $('.glider',$slider),
          $prev = $('<span class="slider-control slider-prev"></span>'),
          $next = $('<span class="slider-control slider-next"></span>'),
          isSubmit=false, $submit=null,
          sOptions = {
            arrows: {
              prev: '.slider-prev',
              next: '.slider-next'
          }};
        if($slider.data("prev").length>0){
          $prev.text($slider.data("prev")).addClass('ui-button');
        }else $prev.addClass("dashicons dashicons-arrow-left-alt");
        if($slider.data("next").length>0){
          $next.text($slider.data("next")).addClass('ui-button');
        }else $next.addClass("dashicons dashicons-arrow-right-alt");
        $slider.append($prev).append($next);
        // $slider.append('<div role="tablist" class="dots"></div>');
        $prev.hide(); //hide on first slide.

        if($slider.data('submit').length>0){
          isSubmit = true;
          $submit = $('<input type="submit" value="'+$slider.data('submit')+'" class="wpcf7-form-control wpcf7-submit">');
          $next.after($submit);
          let m = ( $submit.outerHeight() - 16 )/2;
          $submit.hide().after('<span style="margin:'+m+'px 5px;" class="ajax-loader"></span>');
        }
        /** @since 4.7.2 enable dots */
        if($slider.data('dots')){
          $prev.before('<span class="slider-dots"></span>');
          sOptions['dots']= '.slider-dots';
        }
        //bind events.
        $glider.on('glider-loaded',function(e){
          $slider.trigger({
            type:'sgSliderReady',
            'total': $glider.find('.glider-slide').length
          })
        }).on('glider-slide-visible', function(e){
          $prev.show();
          $next.show();
          if(isSubmit) $submit.hide();
          switch(e.detail.slide){
            case 0: //hide prev button;
              $prev.hide();
              break;
            case glider.slides.length-1:
              $next.hide();
              if(isSubmit) $submit.show();
              break;
          }
          $(e.target).find('.glider-slide.active').trigger({
            type:'sgSlideChange',
            'current':e.detail.slide,
            'last': $(e.target).find('.glider-slide').length-1
          })
        });
        //init slider.
        glider = new Glider($glider[0],sOptions);

        $slider.on('sgRowAdded sgRowDeleted',function(e){
          glider.refresh(true);
        })
      })
    });
    $.fn.sgCurrentSlide = function(){
      var $slider = $(this);
      if( !$slider.is('.cf7sg-slider-section') ) return false;
      return parseInt($slider.find('.glider-slide.active').data('gslide'));
    }
    $.fn.sgChangeSlide = function(slide){
      var $slider = $(this);
      if( !$slider.is('.cf7sg-slider-section') ) return $slider;
      var go = Glider($('.glider', $slider)[0]),
        current = $slider.sgCurrentSlide();

      if(isEmpty(slide)){ //if empty move to next slide
        if(current<go.slides.length) go.scrollItem(current++)
      }else if(slide < 0){ //move to previous slide.
        if(current > 0) go.scrollItem(current--)
      }else{ //move to slide index.
        slide = parseInt(slide);
        if(slide>=0 && slide <= go.slides.length) go.scrollItem(slide);
      }
      return $slider;
    }
    /** If the Post My CF7 Form is mapping this form, lets check if toggled sections are filled and therefore open them.
    *@since 1.1.0
    */
    $('div.cf7_2_post div.cf7-smart-grid.has-toggles form.wpcf7-form').each(function(){
      var $form = $(this), fid = $form.closest('div.cf7-smart-grid').attr('id');
      var nonceID = $form.closest('div.cf7_2_post').attr('id');
      if(nonceID.length>0){
        $form.on(nonceID, function(event){
          $('.cf7sg-collapsible.with-toggle', $(this)).each(function(){
            var $this = $(this);
            var id = $this.attr('id');
            if( objEmpty(cf7sg[fid],['toggles_status',id]) ){
              $('.row.ui-accordion-content :input', $this).prop('disabled', true);
            }else{
              $this.children('.cf7sg-collapsible-title').trigger('click');
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

    /**
    * Smart Grid is now ready
    * @since 4.4.0 enable form pre-load.
    */
    $('div.cf7-smart-grid form.wpcf7-form').addClass('cf7sg-ready').trigger('cf7SmartGridReady');

    /**
    * listen for cf7 submit invalid field event, and open parent sections and tabs.
    *@since 1.1.0
    */
    $('div.cf7-smart-grid').on('wpcf7:invalid wpcf7invalid wpcf7mailsent', '.wpcf7', function(e){
      var $target = $(e.target), invalids;
      switch(e.type){
        case 'wpcf7mailsent': /** @since 4.6.0 success, redirect? */
          if(! isEmpty(cf7sg[e.delegateTarget.id]) && cf7sg[e.delegateTarget.id].redirect.length>0){
            var $draft = $('.cf7_2_post_draft', $target);
            if(0==$draft.length || 'false' === $draft.val()){
              window.location.replace(cf7sg[e.delegateTarget.id].redirect);
            }
          }
          break;
        case 'wpcf7invalid':
        case 'wpcf7:invalid':
          invalids = e.detail;
          /** @since cf7 5.2 */
          if('undefined' != typeof invalids.apiResponse){
            invalids = invalids.apiResponse.invalid_fields;
            for(var idx in invalids){
              var $input = $(invalids[idx].into),
                $section = $input.parents('.cf7sg-collapsible:not(.glider-slide)');
                /** @since 4.7.0 add class to flag as error */
              for(var sdx = 0; sdx< $section.length;sdx++){
                $($section[sdx]).attr('data-cf7sg','error');
                if($($section[sdx]).is('.cf7sg-accordion-rows .cf7sg-collapsible')){
                  $($section[sdx]).closest('.cf7sg-accordion-rows').accordion("option","active",sdx);
                }else{
                  $section.accordion("option","active",0); //activate.
                }
              }
              //tabs.
              $section=$input.closest('.cf7-sg-tabs-panel');
              if($section.length>0){ //activate the tab and flag it.
                var tabid = $section.attr('id');
                $section = $section.closest('.cf7-sg-tabs');
                var t = $('.cf7-sg-tabs-list', $section).children();
                for(var tdx=0;tdx<t.length;tdx++){
                  if(tabid == $(t[tdx]).attr('aria-controls')){
                    $(t[tdx]).attr('data-cf7sg','error');
                    $section.tabs( "option", "active", tdx );
                    break;
                  }
                }
              }
              $section = $input.closest('.cf7sg-slider-section');
              if($section.length>0){
                var $slide = $input.closest('.glider-slide');
                $slide.attr('data-cf7sg','error');
                Glider($('.glider', $section)[0]).scrollItem($slide.data('gslide'));
                if($section.data('dots')){
                  $('.slider-dots button[data-index="'+$slide.data('gslide')+'"]', $section).attr('data-cf7sg','error');
                }
              }
            }
          }
          break;
      }
    }).submit(function(e) {
      var $target = $(e.target);
      $('.cf7-sg-tabs-list li[data-cf7sg="error"]', $target).attr('data-cf7sg','');
      $('.cf7sg-collapsible[data-cf7sg="error"]', $target).attr('data-cf7sg','');
      $('.slider-dots button[data-cf7sg="error"]', $target).attr('data-cf7sg','');
    });
    /** on hover popup message for disabled submit buttons
    * @since 2.6.0
    */
    $('div.cf7-smart-grid.has-grid .wpcf7-submit').each(function(){
      var $submit = $(this), fid=$submit.closest('div.cf7-smart-grid').attr('id');
      $submit.after('<span class="cf7sg-popup display-none">'+cf7sg[fid].submit_disabled+'</span>').parent().addClass('cf7sg-popup');
    });
    /** enable max rows.
    * @since 2.8.0
    */
    $('div.cf7-smart-grid.has-table').on('sgRowAdded', '.container.cf7-sg-table',function(e){
      var max, msg, $table = $(this), fid=$table.closest('div.cf7-smart-grid').attr('id');
      max = $table.data('max');
      if('undefined' == typeof max || max == false) return;
      max--;
      if(max==e['row']){
        var msg = $table.data('max-row-msg');
        msg = isEmpty(msg) ? cf7sg[fid].max_table_rows:msg;
        msg = '<span class="max-limit wpcf7-not-valid-tip">'+msg+'</span>';
        $table.siblings('.cf7-sg-table-button').addClass('disabled').prepend(msg);
      }
    });
    $('div.cf7-smart-grid.has-table').on('sgRowDeleted', '.container.cf7-sg-table',function(event){
      var max, row, $table = $(this);
      max = $table.data('max');
      if('undefined' == typeof max || max == false) return;
      $table.siblings('.cf7-sg-table-button').removeClass('disabled').children('.max-limit').remove();
    });
  }); //end on document ready().
  /*
    jQuery extended functions
  */
  /** @since 4.0 enable permalinks in post options */
  var linkOption = function(state){
    if (!state.id) { return state.text; }
    var $option=$(state.element);
    return $('<a href="' + $option.data('permalink') + '">' + state.text + '</a>');
  }
  /** @since 4.4 prefill fields */
  $.fn.prefillCF7Field = function(val, formID){
    var $field = $(this);
    if(! $field.is(':input') ) return false;

    var $form = $(this), fname = $field.attr('name'),
      field = $field[0], ftype = field.type;
    // for(fname of Object.keys(values)){
    if(isEmpty(field)){
      if(cf7sg.debug) console.log('CF7SG ERROR: Unable to retrieve form element '+fname);
      return $field;
    }

    if($field.length>0) ftype = $field[0].type
    switch(ftype){
      case 'select-multiple':
      case 'select':
        if(!Array.isArray(val)) val = [val];
        val.forEach(function(v){
          field.querySelector('option[value="'+v+'"]').selected=true;
        });
        break;
      case 'checkbox':
      case 'radio':
        field = $(field).closest('.' + fname.replace('[]','')).get(0);
        if(!Array.isArray(val)) val = [val];
        val.forEach(function(v){
          field.querySelector('input[value="'+v+'"]').checked=true;
        });
        break;
      default:
        field.value = val;
        break;
    }
    return $field;
  }
  $.fn.cf7sgSelect2Options = function(){
    var $select2 = $(this), s2options = {tags: $select2.is('.tags')}, field = $select2.attr('name').replace('[]','');
    if($select2.is('.cf7sg-permalinks')){
      s2options['templateSelection'] = linkOption;
      s2options['templateResult'] = linkOption;
    }
    if('undefined' != typeof cf7sgCustomSelect2Templates.templateSelection){
      s2options['templateSelection'] = cf7sgCustomSelect2Templates.templateSelection;
    }
    if('undefined' != typeof cf7sgCustomSelect2Templates.templateResult){
      s2options['templateResult'] = cf7sgCustomSelect2Templates.templateResult;
    }
    if('undefined' != typeof cf7sgCustomSelect2Templates[field]){
      if('undefined' != typeof cf7sgCustomSelect2Templates[field].templateSelection){
        s2options['templateSelection'] = cf7sgCustomSelect2Templates[field].templateSelection;
      }
      if('undefined' != typeof cf7sgCustomSelect2Templates[field].templateResult){
        s2options['templateResult'] = cf7sgCustomSelect2Templates[field].templateResult;
      }
    }
    return s2options;
  }
  //toggle a collapsible section.
  $.fn.activateCF7sgCollapsibleSection = function(activate){
    if(null===activate) activate = true;
    var $section = $(this), $header;
    if( !$section.is('.cf7sg-collapsible') ) return false;
    $header = $section.children('.cf7sg-collapsible-title');
    switch(true){
      case ( activate && !$header.is('.ui-state-active') ):
      case ( !activate && $header.is('.ui-state-active') ):
        $header.trigger('click'); //toggle.
        break;
    }
    return $section;
  }
  //getCF7field function.
  $.fn.getCF7field = function(name, obj){
    if(null===obj) obj={};
    var $form = $(this);
    if('undefined' == typeof name || 0==name.length){
      if(cf7sg.debug) console.log('CF7 Smart-grid ERROR: getCF7field() requires valid field name.');
      return false;
    }
    if(!$form.is('.wpcf7-form')){
      if(cf7sg.debug) console.log('CF7 Smart-grid ERROR: getCF7field() using unknown form');
      return false;
    }
    var hasTab = ('undefined' != typeof obj.tab), hasRow = ('undefined' != typeof obj.row), $result=[];
    switch(true){
      case hasTab && obj.tab>0 && hasRow && obj.row>0: //row x on tab n.
        $result = $(':input[name="'+name+'_tab-'+obj.tab+'_row-'+obj.row+'"]', $form);
        break;
      case hasTab && hasRow: //field in first row on first tab
        $result = $(':input[name="'+name+'"]', $form);
        break;
      case hasTab && obj.tab>0 && hasRow://row=0 on tab n.
        $result = $(':input[name="'+name+'_tab-'+obj.tab+'"]', $form);
        break;
      case hasTab && obj.tab>0://all rows on tab n. | single value on tab n.
        $result = $(':input[name*="'+name+'_tab-'+obj.tab+'"], :input[name*="'+name+'_tab-'+obj.tab+'_row-"]', $form);
        break;
      case hasTab://all rows of tab 0. | single value of tab 0.
        $result = $(':input[name*="'+name+'_row-"], :input[name="'+name+'"]', $form);
        break;
      case hasRow && obj.row>0 && hasTab: //row n on tab=0;
        $result = $(':input[name="'+name+'_row-'+obj.row+'"]', $form);
        break;
      case hasRow && obj.row>0: //row n on all tabs | single value on row n.
        $result = $(':input[name="'+name+'_row-'+obj.row+'"]', $form).add($(':input[name*="'+name+'_tab-"]', $form).filter(':input[name$="_row-'+obj.row+'"]') );
        break;
      case hasRow://row 0 for all tabs | single value in row 0.
        $result = $(':input[name="'+name+'"], :input[name$="'+name+'_tab-"]', $form);
        break;
      default: //single field in form | all fields in all rows on all tabs.
        $result = $(':input[name*="'+name+'"]', $form);
    }
    return $result.not('.cf7-sg-cloned-table-row :input');
  }
	//datepicker for date fields
	$.fn.setupDatePicker = function(){
    var $date = $(this);
		if(!$date.is('.wpcf7-date:enabled') && !$date.is('.wpcf7-text.datepicker:enabled')){
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
  //disable add row button.
  $.fn.toggleCF7sgTableRowAddition = function(active){
    if(null===active) active = false;
    var $table = $(this);
    if(!$table.is('.container.cf7-sg-table')) return false;
    if(!active) $table.next('.cf7-sg-table-button').hide();
    else $table.next('.cf7-sg-table-button').show();
    // cf7-sg-table-footer
    return $table;
  }
  // disable row deletion
  $.fn.toggleCF7sgTableRowDeletion = function(active){
    if(null===active) active = false;
    var $table = $(this);
    if(!$table.is('.container.cf7-sg-table')) return false;
    if(!active) $('.row.cf7-sg-table:nth-last-child(2) .row-control', $table).addClass('display-none');
    else $('.row.cf7-sg-table:nth-last-child(2) .row-control', $table).removeClass('display-none');
    return $table;
  }
  //count rows.
  $.fn.cf7sgCountRows = function(){
    var $table = $(this);
    if(!$table.is('.container.cf7-sg-table')) return false;
    return $table.children('.row').not('.cf7-sg-cloned-table-row').length;
  }
  //removeRow
  $.fn.cf7sgRemoveRow = function(){
    var $table = $(this);
    if(!$table.is('.container.cf7-sg-table')) return false;
    var rows =  $table.children('.row').not('.cf7-sg-cloned-table-row');
    if(rows.length>1){
      rows.last().remove();
      $table.trigger('sgRowDeleted');
      /** @since 2.4.2 track table fields */
      var $tracker = $table.children('.cf7sg-tracker-field');
      if($tracker.length) $tracker.val(rows.length-1);
    }
    return $table;
  }
  //clone table row
  $.fn.cf7sgCloneRow = function(initSelect, el){
    /*initSelect is false if called from cf7_2_post field loading script,
    else if true whehn triggered from the front-end user event.*/
    /** @since 4.9.1 el is the element target that triggered the add row */
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
    var rowIdx = $table.children( '.row.cf7-sg-table').length - 1, //minus hidden row.
     $cloneRow = $('.cf7-sg-cloned-table-row', $table),
     $row = $cloneRow.clone(), fid = $table.closest('div.cf7-smart-grid').attr('id');

    $row.removeClass('cf7-sg-cloned-table-row').attr('data-row',rowIdx);
    if(cf7sg[fid].table_labels) $('.field > label',$row).remove();
    //show row so select2 init properly
    if($footer.length>0){
      $footer.before($row.show());
    }else{
      $cloneRow.before($row.show());
    }
    // Polyfill for IE11 to support endsWith() - https://developer.mozilla.org/en/docs/Web/JavaScript/Reference/Global_Objects/String/endsWith
    if (!String.prototype.endsWith) {
      String.prototype.endsWith = function(searchString, position) {
          var subjectString = this.toString();
          if (typeof position !== 'number' || !isFinite(position) || Math.floor(position) !== position || position > subjectString.length) {
            position = subjectString.length;
          }
          position -= searchString.length;
          var lastIndex = subjectString.indexOf(searchString, position);
          return lastIndex !== -1 && lastIndex === position;
      };
    }
    //add input name as class to parent span
    $(':input', $row).each(function(){
      var $input = $(this);
      //enable inputs
      $input.prop('disabled', false);
      var name = $input.attr('name').replace('_cf7sgcloned_',''); /** @since 4.4 */
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
        $input.select2($input.cf7sgSelect2Options());
        $input.trigger('sgSelect2');
      }
    });
    //when the button is clicked, trigger a content increase for accordions to refresh
    $table.trigger('sgContentIncrease');
    $table.trigger({type:'sgRowAdded',row:rowIdx, button:el});
    /** @since 2.4.2 track table fields */
    var $tracker = $table.children('.cf7sg-tracker-field');
    if($tracker.length) $tracker.val(rowIdx+1); //rowIdx is zero based.
    return $table;
  }
  //disable/enable tab addtion
  $.fn.toggleCF7sgTabAddition = function(active){
    if(null===active) active = false;
    var $tab = $(this);
    if(!$tab.is('div.cf7-sg-tabs')) return false;
    if(!active) $('.cf7sg-add-tab', $tab).hide();
    else $('.cf7sg-add-tab', $tab).show();
    return $tab;
  }
  //disable/enable tab deletion
  $.fn.toggleCF7sgTabDeletion = function(active){
    if(null===active) active = false;
    var $tab = $(this);
    if(!$tab.is('div.cf7-sg-tabs')) return false;
    if(!active) $('.cf7-sg-tabs-list li:last-child .cf7sg-close-tab', $tab).addClass('display-none').hide();
    else $('.cf7-sg-tabs-list li:last-child .cf7sg-close-tab', $tab).removeClass('display-none').show();
    return $tab;
  }
  //count tabs.
  $.fn.cf7sgCountTabs = function(){
    var $tab = $(this);
    if(!$tab.is('div.cf7-sg-tabs')) return false;
    return $tab.find('.cf7-sg-tabs-list').children('li').length;
  }
  //remove last tab.
  $.fn.cf7sgRemoveTab = function(){
    var $tab = $(this);
    if(!$tab.is('div.cf7-sg-tabs')) return false;
    var $tabList = $tab.find('.cf7-sg-tabs-list').children('li');
    if($tabList.length>1){
      var panelId = $tabList.last().find('a').attr('href');
      $tab.find('div'+panelId).remove(); //remove panel
      //if the last panel was active then activate.
      if( $tabList.last().remove().is('.ui-state-active') ) $tab.tabs({active:$tabList.length-2}); //remove tab
      $tab.trigger('sgTabRemoved');
      //show last close button
      var $lastClose = $tabList.eq($tabList.length-2).find('.cf7sg-close-tab:not(.display-none)');
      if($lastClose.length > 0) $lastClose.show();
      /** @since 2.4.2 udpate the tracker field*/
      var $tracker = $tab.children('.cf7sg-tracker-field');
      if($tracker) $tracker.val($tab.children('.cf7-sg-tabs-panel').length);
    }
    return $tab;
  }
  //clone tabs, called on a div.cf7-sg-tabs
  $.fn.cf7sgCloneTab = function(initSelect, human){
    if(null===human) human = false;
    var $tab = $(this);
    if(typeof initSelect === 'undefined') initSelect =true;
    /*initSelect is false if called from cf7_2_post field loading script,
    else if true whehn triggered from the front-end user event.*/
    if(!$tab.is('div.cf7-sg-tabs')) return false;

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
      var $this = $(this),
        isCloneRow = $this.is('.cf7-sg-cloned-table-row :input'),
        name = $this.attr('name'),
        suffix = '';
      //enable inputs
      if(!isCloneRow) $this.prop('disabled', false);
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
        $this.select2($this.cf7sgSelect2Options());
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
    //enable tabs in the new panel. Deprecated.
    $( '.cf7-sg-tabs', $newPanel ).each(function(){
      $(this).tabs();
    });

    //enable the collapsible titles & toggle buttons
    $('.cf7sg-collapsible.with-toggle', $newPanel).each(function(){
      var $section = $(this);
      /**
      * @since 1.1.0 grouped toggles/disabled inputs.
      */
      var rootId = $section.attr('id'), id = rootId +'_tab-'+(tabCount-1);
      $section.attr('id',id);//reset unique id.
      var group = $section.data('group');
      if(group){
        group = group+'_tab-'+(tabCount-1);
        $section.attr('data-group', group);
      }
      var state = $section.data('open');
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
      $('.toggle', $section).setupToggle(toggled, group);
      if(toggled) $section.trackToggle(true);
      if(!toggled && initSelect){
        /*disable fields within a closed toggled section.
        * if toggled, then it is open. if initselect, it is triggered from user event.
        * else it is triggered from the cf7_2_post script and we need to wait for field values to be filled.
        */
        $('.row.ui-accordion-content :input', $section).prop('disabled', true);
        $section.addClass('collapsed');
      }
      $('.cf7sg-collapsible', $newPanel).accordion({
        collapsible:true,
        icons:false,
        active:state,
        header:'> div.cf7sg-collapsible-title',
        heightStyle: "content",
        activate: function(event, ui){
          $(this).trigger('sgContentIncrease');
        },
        beforeActivate: function(event, ui){
          if($('.toggle', ui.oldHeader).is('.disabled')) return false;
        },
        create: function(e){
          $(this).trigger({type:'sgCollapsibleRowsReady','section-id':rootId, 'tab-index':tabCount-1});
        }
      });
    }); //end collapsible titles.


    $tab.tabs( "refresh" );
    //if this was from a click, human user, then activate the tab.
    if(human) $tab.tabs( "option", "active", -1 );
    /** @since 1.2.2 */
    //trigger new tab event for custom js.
    $newPanel.trigger({type:'sgTabAdded','tab-index':tabCount-1});
    //trigger new table ready.
    $('.cf7-sg-table.container', $newPanel).each(function(){
      var $table = $(this), orgid = $table.attr('id');
      $table.attr('id',orgid+'_tab-'+(tabCount-1));
      $table.trigger({type:'sgTableReady', 'table-id':orgid,'tab-index':tabCount-1});
    });
    /** @since 2.4.2 track tabs and their fields.*/
    //increment tab count tacker.
    var $tracker = $tab.children('.cf7sg-tracker-field');
    if($tracker) $tracker.val($tab.children('.cf7-sg-tabs-panel').length);
    return $tab;
  }

  //setup toggles
  $.fn.setupToggle = function(state, group){
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
      if(group && state) $this.toggleClass('disabled',true); //disable active grouped toggle.
    }
    return $this;
  }

  // if this is an updated form (due to chages in embeded forms), send grid fields back to server.
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
    serverRequest.fail(function(jqXHR, textStatus){
      console.log('CF7 Smart Grid ERROR sending grid fields to server: '+textStatus);
    })
  })
  //check object branch is empty.
  function objEmpty(p,c){
    if(isEmpty(c)) c = []; //ie11
    if(isEmpty(p)) return true;
    var parent = p;
    for(var idx=0; idx<c.length;idx++){
      if(isEmpty(parent[c[idx]])) return true;
      parent = c[idx];
    }
    return false;
  }
  //empty checks for undefined, null, false, NaN, ''
  function isEmpty(v){
    if('undefined' === typeof v || null===v) return true;
    return typeof v === 'number' ? isNaN(v) : !Boolean(v);
  }
})( jQuery )
