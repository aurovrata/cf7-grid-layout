var cf7sgCustomSelect2Templates = (function (ccs2t) {return ccs2t;}(cf7sgCustomSelect2Templates || {}));
var cf7sgCustomHybridddTemplates = (function (cchddt) {return cchddt;}(cf7sgCustomHybridddTemplates || {}));

(function( $ ) {
	'use strict';

  let trackTabsFields = []; //to keep track of fields that are converted to arrays.
  let trackTableFields = []; //to keep track of fields that are converted to arrays.
  let cf7sgPanels = {}; //object to store cloned panels
  /*warning messages used for in-form validation*/
  $.fn.cf7sgWarning = function(msg, time){
    let $this = $(this);
    if(!$this.is(':input')){
      return $this;
    }
    if(isEmpty(time)) time=0;
    let $warning = $('<span class="cf7sg-validation-warning">'+msg+'<span class="confirm-button">ok</span></span>');
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
      let $target = $(e.target);
      if($target.is('.cf7sg-validation-warning .confirm-button')){
        $target.parent().remove();
      }
    }).each(function(){ /** @since 4.4 setup form id */
      let $form = $(this), id = $form.closest('div.cf7-smart-grid').attr('id');
      $form.attr('id','wpcf7-'+id);
    });
    //.cf7-sg-table structure, smart grid only.
    let cf7Forms = $('div.cf7-smart-grid.has-table form.wpcf7-form');
    if(cf7Forms.length){
      $('.cf7sg-container.cf7-sg-table', cf7Forms).each(function(){
        let $table = $(this), fid = $table.closest('div.cf7-smart-grid').attr('id'),
          oneD = [], //tabbed prefills.
          $row = $('.cf7sg-row.cf7-sg-table', $table),
          label = $row.get(0).hasAttribute('data-button') ? $table.data('button') : 'Add Row',
          $footer = $row.next('.cf7sg-row.cf7-sg-table-footer-row');

        if($table[0].hasAttribute('id')){ /** @since 2.4.2 track table fields*/
          let $tracker = $('<input class="cf7sg-tracker-field" value="1" type="hidden">').attr('name', $table.attr('id'));
          $table.prepend($tracker);
        }
        if(cf7sg[fid].table_labels){ //setup field-info tips into the table footer.
					$table.addClass('hide-field-labels');
					if($footer.length===0){ 
						$footer = $row.clone();
						$row.after($footer);
						$footer.attr('class','cf7sg-row cf7-sg-table-footer-row');
					}else{
						$footer.prepend($row.clone().removeClass('cf7-sg-table'));
					}
					$('.cf7sg-field > *:not(.info-tip)',$footer).remove();
				}
        //change the input and select fields to arrays for storage
        $row.find(':input').each(function(){
          let $in = $(this),
            fname = $in.attr('name').replace('[]','');
          if(fname.length>0){
            $in.addClass('cf7sg-'+fname+' cf7sgrow-field');
             /** @since 4.4 prefill or preview */
            if( !objEmpty(cf7sg[fid],['prefill',fname]) && !$in.is('.cf7-sg-tabs-panel :input')){ 
              oneD[fname] = cf7sg[fid].prefill[fname]; //tabbed tables will be handled in tab init.
            delete cf7sg[fid].prefill[fname];
          }
          }
        });

        //add a button at the end of the $table to add new rows
        // if($footer.length>0){
        //   // $footer.detach();
        //   // $table.after($footer);
        //   $footer.after('<div class="cf7-sg-table-button"><a href="javascript:void(0);" class="button">'+label+'</a></div>');
        // }else{
				$table.append('<div class="cf7-sg-table-button"><a href="javascript:void(0);" class="button">'+label+'</a></div>');
        // }
        //append a hidden clone of the first row which we can use to add
        $row.attr('data-row','0');
				//add controls to the row to delete
        $row.append('<span class="cf7sg-row-control display-none"><span class="dashicons dashicons-dismiss"></span></span>');
        $row = $row.clone().attr('class','cf7-sg-cloned-table-row');
        $row.attr('data-row','-1');
        $table.append($row.hide());
        //disable all inputs from the clone row
        $(':input', $row).each(function(){ /** @since 4.4 */
          let $input = $(this).prop('disabled', true),
            name = '_cf7sgcloned_'+$input.attr('name');
          $input.attr('name',name);
        });
        
        //trigger table ready event for custom scripts to change the button text
        cf7Forms.on('cf7SmartGridReady', function(e){
          $table.trigger({type:'sgTableReady', 'table-id':$table.attr('id')});
        });
        /** @since 4.15.0 prefill */
        for(const fname in oneD){
          if(String(oneD[fname]) !== '[object Object]'){
            if(cf7sg.debug) console.log(`ERROR: Prefill table field ${fname} value should be array`);
            return;
          } 
          let rc=0, f='';
          for(const rdx in oneD[fname]) {
            f = (rc>0 ? `${fname}_row-${rc}` : fname);
            if( $table.children( '.cf7sg-row.cf7-sg-table').not('.cf7-sg-cloned-table-row').length < (rc+1) ) $table.cf7sgCloneRow(false, null);
            $(`:input[name=${f}]`, $table).prefillCF7Field(fname, oneD[fname][rdx],fid);
            rc++;
          }
        }
      });
      //event delegation on table buttons
      cf7Forms.on('click','.cf7-sg-table-button a.button, .cf7sg-row-control .dashicons', function(e){
        let $button = $(e.target),
				  $table = $button.closest('.cf7sg-container');
        if( $button.is('div.cf7-sg-table-button a.button') ){ //----------add a row
          $button = $button.parent();
          /** @since 2.8.0 */
          if($button.hasClass('disabled')) return;

          // if($table.is('.cf7-sg-table-footer')) $table = $table.prev('.cf7sg-container');
          $table.cf7sgCloneRow(true, e.target);
        }else if($button.is('.cf7sg-row-control .dashicons')){ //---------- delete the row, delete button only on last row
          $button.closest('.cf7sg-row').cf7sgRemoveRow();
        }
      });
    }//end table structure

    //inline validation any forms
    cf7Forms = $('div.cf7-smart-grid.has-validation form.wpcf7-form');
    if(cf7Forms.length){

      let validation = $('input[type="number"][class*="sgv-"]', cf7Forms)
      validation.each(function(){
        let $this = $(this), fname = $this.attr('name'),
          fid = $this.closest('div.cf7-smart-grid').attr('id'),
          val = $this.attr('value');
        if(!objEmpty( cf7sg[fid],['prefill',fname]) ){
          $this.prefillCF7Field(fname, cf7sg[fid].prefill[fname], fid);
          val = cf7sg[fid].prefill[fname];
          delete cf7sg[fid].prefill[fname];
        }
        $this.data('current',val);
      });
      cf7Forms.change( 'input[type="number"]', function( event ) {
        if( !$(event.target).is('input[type="number"]')){
          return;
        }
        let $field = $(event.target);
        let prev = $field.data('current');
        let warning = false;
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
    cf7Forms = $('div.cf7-smart-grid.has-accordion form.wpcf7-form');
    if(cf7Forms.length>0){
      //enable the toggle buttons
      cf7Forms.filter('div.has-toggles form.wpcf7-form').each(function(){
        let $form = $(this);
        let toggled_accordion = $('.cf7sg-collapsible.with-toggle', $form);
        /**
        * @since 1.1.0 track toggle status using toggle ids.
        */
        let toggleStatus = '', $toggleHiddenStatus = $('input[name="_cf7sg_toggles"]', $form);
        let trackToggle = false, disableGroupActiveToggle = {};
        if( $toggleHiddenStatus.length>0 ){
          if($toggleHiddenStatus.val().length>0){
            toggleStatus = JSON.parse($toggleHiddenStatus.val());
          }else toggleStatus = {};
          trackToggle = true;
        }
        $.fn.trackToggle = function(track){
          if(!trackToggle) return false;
          let $toggledSection = $(this),
            tid = $toggledSection.attr('id');

          if( !$toggledSection.is('.with-toggle') ) return false;
          if(track){
            let $text = $('.cf7sg-collapsible-title', $toggledSection).clone(),
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
          let $header;
          let $target =  $(e.target);
          if($target.is('span.cf7sg-title.toggled') || $target.is('.toggle-on') || $target.is('.toggle-off') || $target.is('.toggle') ){
            $header = $target.closest('.cf7sg-collapsible-title');
          }else if($target.parent().is('.cf7sg-collapsible.with-toggle') ){
            $header = $target;
          }else{
            return;
          }
          //cancel section close if disabled.
          if($('.toggle', $header).is('.disabled')) return false;
          let $toggledSection = $header.closest('.container.cf7sg-collapsible'), id= $toggledSection.attr('id');

          //close other toggled sections if we have a group.
          let group = $header.parent().removeClass('collapsed').data('group');
          if(group){
            $('.cf7sg-collapsible.with-toggle[data-group="'+group+'"]', $form).each(function(){
              let $toggled = $(this), $togl = $('.toggle', $toggled);
              let cid = $toggled.attr('id');
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

          let toggleSwitch = $header.children('.toggle').data('toggles');
          if('undefined' == typeof toggleSwitch && cf7sg.debug){
            console.log('undefined toggleSwitch, header parent:');
            console.log($header);
            console.log('e.target:');
            console.log(e.target);
          }
          if( $header.hasClass('ui-state-active') ){
            toggleSwitch.toggle(true);
            //enable input fields and convert to niceselect.
            let inputs = $('.row.ui-accordion-content :input', $header.parent()).not('.cf7-sg-cloned-table-row :input').not('.collapsed :input').prop('disabled', false);
            if($form.is('.has-nice-select form')){
              inputs.filter('.wpcf7-form-control.nice-select:enabled').niceSelect();
            }
            $toggledSection.trackToggle(true);
          }else{
            toggleSwitch.toggle(false);
            $('.row.ui-accordion-content :input', $header.parent()).each(function(){
              /**@since 2.7.1*/
              let val = this.value; //trim the value to remove spaces.
              $(this).val(val.trim()).prop('disabled', true);
            });
            $toggledSection.trackToggle(false);
          }
        });//end for toggle click delegation

        toggled_accordion.each(function(){
          let $section = $(this);
          let cssId = $section.attr('id');
          if(typeof cssId == 'undefined'){
            cssId = randString(6);
            $section.attr('id', cssId); //assign a random id
          }
          let state = $section.data('open'), group=$section.data('group'),toggled = false;

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
          let $cf72post = $form.closest('div.cf7_2_post'),
            disableFields = ( 0 == $cf72post.length) && !toggled,
            fid = $section.closest('div.cf7-smart-grid').attr('id');

          $(':input', $section.children('.row')).each(function(){
            let $field = $(this), fname = $field.attr('name').replace('[]','');
            if( !objEmpty(cf7sg[fid], ['prefill','_cf7sg_toggles',cssId]) ){
              $field.prefillCF7Field(fname, cf7sg[fid].prefill[fname], fid);
              state = 0;
              toggled = true;
              delete cf7sg[fid].prefill[fname];
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
      cf7Forms.each(function(){
        /** @since 3.4.0 differentiate accordion of collapsible rows*/
        let $form = $(this),$rows = $('.cf7sg-collapsible', $form).not('.cf7sg-collapsible.with-toggle').not('.cf7sg-accordion-rows > .cf7sg-collapsible').not('.cf7sg-slider-section >.cf7sg-collapsible');
        $rows = $rows.add( $('.cf7sg-accordion-rows', $form) );
        let promises = [];
        $rows.each(function(){
          let $row = $(this);
          let cssId = $row.attr('id');
          if(typeof cssId == 'undefined'){
            cssId = randString(6);
            $row.attr('id', cssId); //assign a random id
          }
          let state = $row.data('open'), open =false;

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

          let options={
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
      // cf7Forms
    }//end collapsible rows
    //enable the tabs smart grid only.
    cf7Forms = $('div.cf7-smart-grid.has-tabs form.wpcf7-form');
    if(cf7Forms.length){
      cf7sgPanels = {}; //object to store cloned panels
      //delegate tab addition/deletion
      cf7Forms.on('click','.cf7sg-tab-button, .cf7sg-tab-dismis', function(e){
        let $target = $(e.target);
        if($target.is('.cf7sg-tab-dismis')){ //---------------------- close/delete tab.
          $target.closest('li').cf7sgRemoveTab();
        }else { //------------------- add tab.
          $target.closest('.cf7-sg-tabs').cf7sgCloneTab(true,true);
        }
      });
      $( ".cf7-sg-tabs",  cf7Forms).each(function(){
        let $tab = $(this), fid = $tab.closest('div.cf7-smart-grid').attr('id'), $lbl,
          $list = $tab.find('ul.cf7sg-tab-title'), //list of tab labels
					$panel = $tab.find('.cf7sg-col.cf7sg-tabs-panel').first(),
					$tracker = $('<input class="cf7sg-tracker-field" value="1" type="hidden">').attr('name', $tab.attr('id')), //track tabs added by user.
          oneD = [], //tabbed prefills.
          twoD = []; //tabbed and tabled prefills.
        //add a style element for css.
				$tab.append('<style>');
        if( 1 == $list.children('li').length){
					$lbl = $list.find('label');
					$lbl.after($lbl.clone().addClass('tactive'));
					
          // $list.after('<ul class="cf7sg-add-tab ui-tabs-nav"><li class="ui-state-default ui-corner-top"><a class="cf7sg-add-tab ui-tabs-anchor"><span class="cf7sg-add-tab dashicons dashicons-plus"></span></a></li></ul>');
          /** @since 2.4.2 track tab fields */
          $tab.prepend($tracker);

          //add class to all fields and check for any prefill values.
          $panel.find(':input').each(function(){
            let $in = $(this),
              fname = $in.attr('name').replace('[]',''),
              prefill=false;
            if( !objEmpty( cf7sg[fid],['prefill',fname] ) ) prefill = true;

            if($in.is('.cf7-sg-table :input')){
              $in.addClass('cf7sgtab-field');
              if(prefill){ 
                twoD[fname] = cf7sg[fid].prefill[fname];
                delete cf7sg[fid].prefill[fname];
              }
            } else if(fname.length>0){ 
              $in.addClass('cf7sg-'+fname+' cf7sgtab-field');
              if(prefill){ 
                oneD[fname] = cf7sg[fid].prefill[fname];
                delete cf7sg[fid].prefill[fname];
              }
            }
          });

          //finally store a clone of the panel to be able to add new tabs
          let $clonedP = $('<div>').append($panel.clone());
          //disable all inputs in the cloned panel so they don't get submitted.
          $(':input', $clonedP).prop('disabled', true);
          cf7sgPanels[$tab.attr('id')] = $clonedP.html();
					//prefill any values
         let  f='', tc=0, rc=0;
					/** @since 4.4 prefill fields */
					for(const fname in oneD){
						if(String(oneD[fname]) !== '[object Object]'){
							if(cf7sg.debug) console.log(`ERROR: Prefill tab field ${fname} value should be array`);
							return;
						}
						tc = 0;
						for(const tdx in oneD[fname]){
							f = ( tc>0 ? `${fname}_tab-${tc}`:fname );
							if( $list.children('li').length < (tc+1) ) $tab.cf7sgCloneTab(true, false);
							$(`:input[name=${f}]`).prefillCF7Field(fname, oneD[fname][tdx],fid);
							tc++;
						}
					}
					/* Tabbed tables*/
					for(const fname in twoD){
						if(String(twoD[fname]) !== '[object Object]'){
							if(cf7sg.debug) console.log(`ERROR: Prefill tabbed table field ${fname} value should be 2D array`);
							return;
						}
						let $table = null;
						tc=0;
						for(const tdx in twoD[fname]){
							if(String(twoD[fname][tdx]) !== '[object Object]'){
								if(cf7sg.debug) console.log(`ERROR: Prefill tabbed table field ${fname} value should be 2D array`);
								return;
							}
							f = ( tc>0 ? `${fname}_tab-${tc}`:fname );
							if( $list.children('li').length < (tc+1) ) $tab = $tab.cf7sgCloneTab(true, false);
							//get the field's table in the current tab.
							$table = $(`:input[name=${f}]`, $tab).closest('.container.cf7-sg-table');
							rc=0;
							for(const rdx in twoD[fname][tdx]){
								f = (rc>0 ? `${f}_row-${rc}` : f);
								if( $table.children( '.row.cf7-sg-table').not('.cf7-sg-cloned-table-row').length < (rc+1) ) $table.cf7sgCloneRow(false, null);
								$(`:input[name=${f}]`, $table).prefillCF7Field(fname, twoD[fname][tdx][rdx],fid);
								rc++;
							}
							tc++;
						}
					}
				} 
				
				$tab.trigger('sgTabsReady')
      })
    }
    /** @since 4.4 prefill fields */
    $('div.cf7-smart-grid').each(function(){
      let $form= $(this), fid = $form.attr('id');
      if( !objEmpty( cf7sg[fid],['prefill'] ) ){
        Object.keys(cf7sg[fid].prefill).forEach(function(f){
          let $f = $(':input[name="'+f+'"]', $form); /* hidden field fix */
          $f.prefillCF7Field(f, cf7sg[fid].prefill[f], fid);
        })
      }
    });
    //enable jquery-ui select menu, any forms.
    cf7Forms = $('div.cf7-smart-grid.has-nice-select form.wpcf7-form');
    if(cf7Forms.length > 0){
      //check if this is a mapped cf7-2-post form
      cf7Forms.filter('div.cf7_2_post form.wpcf7-form').each(function(){
        let $form = $(this);
        let nonceID = $form.closest('div.cf7_2_post').attr('id');
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
      cf7Forms.not('div.cf7_2_post form.wpcf7-form').each(function(){
        let $form = $(this);
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
    cf7Forms = $('div.cf7-smart-grid.has-select2 form.wpcf7-form');

    if(cf7Forms.length > 0){
      //check if this is a mapped cf7-2-post form
      cf7Forms.filter('div.cf7_2_post form.wpcf7-form').each(function(){
        let $form = $(this);
        let nonceID = $form.closest('div.cf7_2_post').attr('id');
        if(nonceID.length>0){
          $form.on(nonceID, function(event){
            let $this = $(this);
            $('select.wpcf7-form-control.select2:enabled', $this).each(function(){
              let $select2 = $(this);
              $select2.select2($select2.cf7sgSelect2Options());
            });
            $this.trigger('sgSelect2');
          });
        }
      });
      //for non cf7 2 post forms, just enable the nice select
      cf7Forms.not('div.cf7_2_post form.wpcf7-form').each(function(){
        let $form = $(this);
        $('select.wpcf7-form-control.select2:enabled', $form).each(function(){
          let $select2 = $(this);
          $select2.select2($select2.cf7sgSelect2Options());

        });
        $form.on('cf7SmartGridReady', function(e){ $form.trigger('sgSelect2')})
      });
    }
    /** @since 4.11 enable hybriddd fields */
    cf7Forms= $('div.cf7-smart-grid.has-hybriddd form.wpcf7-form');

    if(cf7Forms.length > 0){
      //check if this is a mapped cf7-2-post form
      cf7Forms.filter('div.cf7_2_post form.wpcf7-form').each(function(){
        let $form = $(this);
        let nonceID = $form.closest('div.cf7_2_post').attr('id');
        if(nonceID.length>0){
          $form.on(nonceID, function(event){
            $('.cf7sg-dynamic_checkbox', $form).each(function(){
              new HybridDropdown(this, $(this).cf7sgHybridddOptions());
            })
          });
        }
      });
      cf7Forms.not('div.cf7_2_post form.wpcf7-form').each(function(){
        let $form = $(this);
        $('.cf7sg-dynamic_checkbox', $form).each(function(){
          new HybridDropdown(this, $(this).cf7sgHybridddOptions());
        })
      })
    }
		//enable datepicker
    let input = document.createElement( 'input' );
    input.setAttribute('type','date');
    let html5date = (input.type == 'date');
		cf7Forms = $('div.cf7-smart-grid.has-date form.wpcf7-form');
		if(cf7Forms.length > 0){
			//check if this is a mapped cf7-2-post form
			cf7Forms.filter('div.cf7_2_post form.wpcf7-form').each(function(){
        let $form = $(this);
				let nonceID = $form.closest('div.cf7_2_post').attr('id');
				if(nonceID.length>0){
					$form.on(nonceID, function(event){
						//.wpcf7-form-control.wpcf7-date.wpcf7-validates-as-required.wpcf7-validates-as-date
						$('input.wpcf7-date:enabled', $(this)).each(function(){
              let $date = $(this);
							let id = $date.attr('id');
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
              let $date = $(this);
							let id = $date.attr('id');
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
			cf7Forms.not('div.cf7_2_post form.wpcf7-form').each(function(){
				$('input.wpcf7-text.datepicker:enabled', $(this)).each(function(){
          let $date = $(this);
					let id = $date.attr('id');
					if(typeof id == 'undefined'){
						id = randString(6);
						$date.attr('id', id); //assign a random id
					}
          $date.setupDatePicker();
				});
        $('input.wpcf7-date:enabled', $(this)).each(function(){
          let $date = $(this);
					let id = $date.attr('id');
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
    /* init slider */
    cf7Forms = $('div.cf7-smart-grid.has-slider form.wpcf7-form');
    cf7Forms.each(function(){
      let $form = $(this),
        id = $form.closest('.cf7-smart-grid').attr('id');

      $('.cf7sg-slider-section').each(function(){
        let glider, $slider = $(this).wrapInner('<div class="glider"></div>'),
          slideCount = -1,
          $glider = $('.glider',$slider),
          $control = $slider.closest('.container').next('.cf7sg-slider-controls'),
          $prev = $('<span class="slider-control slider-prev"></span>'),
          $next = $('<span class="slider-control slider-next"></span>'),
          isSubmit=false, $submit=null,
          sOptions = {
            arrows: {
              prev: '.slider-prev',
              next: '.slider-next'
          }};
        if($control.length>0){
          $prev = $('.slider-prev', $control);
          $next = $('.slider-next', $control);
          let rowStyle = document.createElement('style');
          rowStyle.setAttribute('id',id+'-slider-controls');
          rowStyle.type = "text/css";
          rowStyle.innerText = "#"+id+" .cf7sg-slider-controls > .cf7sg-submit-controls {max-width: calc(100% - "+($prev.outerWidth()+10)+"px)}"
          document.head.appendChild(rowStyle);
          $submit = $('.cf7sg-submit-controls',$control);
          if($submit.length>0){
            isSubmit = true;
            $submit.hide();//shown on the last slide.
          }
        }else{
          if('undefined' != typeof $slider.data("prev")){
            if($slider.data("prev").length>0){
              $prev.text($slider.data("prev")).addClass('ui-button');
            }else $prev.addClass("dashicons dashicons-arrow-left-alt");
          }
          if('undefined' != typeof $slider.data("next")){
            if($slider.data("next").length>0){
              $next.text($slider.data("next")).addClass('ui-button');
            }else $next.addClass("dashicons dashicons-arrow-right-alt");
          }
          /** @since 4.11 enable custom slider control row */

          $slider.append($prev).append($next);

          if($slider.data('submit').length>0){
            isSubmit = true;
            $submit = $('<span><input type="submit" value="'+$slider.data('submit')+'" class="slider-control wpcf7-form-control wpcf7-submit"></span>');
            $next.after($submit);
            let m = ( $submit.outerHeight() - 16 )/2;
            $submit.hide().append('<span style="margin:'+m+'px 5px;" class="ajax-loader"></span>');
          }
        }
        // $slider.append('<div role="tablist" class="dots"></div>');
        $prev.hide(); //hide on first slide.


        /** @since 4.7.2 enable dots */
        if($slider.data('dots')){
          $prev.before('<span class="slider-dots"></span>');
          sOptions['dots']= '.slider-dots';
          let dots = $slider.data('dots');
          if(true!==dots) sOptions['labelDots'] = dots.split(',');
          let sbmt = $('form.wpcf7-form input[type=submit]:not(.cf7_2_post_save)').get(0);
          if(sbmt){
            sbmt = window.getComputedStyle(sbmt);
            let style = document.createElement('style');
            style.setAttribute('id','cf7sg-slider-dots');
            style.type = "text/css";
            style.innerText = '.glider-dot:hover, .glider-dot:focus, .glider-dot.active{background:'+sbmt['background-color']+';color:'+sbmt['color']+'}';
            document.body.appendChild(style);
          }
        }
        //bind events.
        $glider.on('glider-loaded',function(e){
          $slider.trigger({
            type:'sgSliderReady',
            'total': $glider.find('.glider-slide').length
          })
        }).on('glider-slide-visible', function(e){
          /** @since 4.11.0 scroll to top of slide */
          let scrollUp = true;
          $prev.show();
          $next.show();
          if(isSubmit) $submit.hide();
          switch(e.detail.slide){
            case 0: //hide prev button;
              $prev.hide();
              scrollUp = false;
              break;
            case glider.slides.length-1:
              $next.hide();
              if(isSubmit) $submit.show();
              break;
          }
          if(scrollUp && cf7sg[id].slider_auto_scroll) $(window).scrollTop($slider.offset().top-35);

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
    /* custom jquery functions */
    $.fn.sgCurrentSlide = function(){
      let $slider = $(this);
      if( !$slider.is('.cf7sg-slider-section') ) return false;
      return parseInt($slider.find('.glider-slide.active').data('gslide'));
    }
    $.fn.sgChangeSlide = function(slide){
      let $slider = $(this);
      if( !$slider.is('.cf7sg-slider-section') ) return $slider;
      let go = Glider($('.glider', $slider)[0]),
        current = $slider.sgCurrentSlide();

      if(isEmpty(slide)){ //if empty move to next slide
        if(current<go.slides.length) go.scrollItem(current+1)
      }else if(slide < 0){ //move to previous slide.
        if(current > 0) go.scrollItem(current-1)
      }else{ //move to slide index.
        slide = parseInt(slide);
        if(slide>=0 && slide <= go.slides.length) go.scrollItem(slide);
      }
      return $slider;
    }
    /** If the Post My CF7 Form is mapping this form, lets check if toggled sections are filled and therefore open them.
    *@since 1.1.0
    */
    $('div.cf7-smart-grid.has-toggles div.cf7_2_post form.wpcf7-form').each(function(){
      let $form = $(this), fid = $form.closest('div.cf7-smart-grid').attr('id');
      let nonceID = $form.closest('div.cf7_2_post').attr('id');
      if(nonceID.length>0){
        $form.on(nonceID, function(event){
          $('.cf7sg-collapsible.with-toggle', $(this)).each(function(){
            let $this = $(this);
            let id = $this.attr('id');
            if( objEmpty(cf7sg[fid],['toggles',id]) ){
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
      let text = '';
      let possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';
      for(let i=0; i < n; i++){
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
      let $target = $(e.target), invalids;
      switch(e.type){
        case 'wpcf7mailsent': /** @since 4.6.0 success, redirect? */
          if(! isEmpty(cf7sg[e.delegateTarget.id]) && cf7sg[e.delegateTarget.id].redirect.length>0){
            let $draft = $('.cf7_2_post_draft', $target);
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
            for(let idx in invalids){
              let $input = $(invalids[idx].into),
                $section = $input.parents('.cf7sg-collapsible:not(.glider-slide)');
                /** @since 4.7.0 add class to flag as error */
              for(let sdx = 0; sdx< $section.length;sdx++){
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
                let tabid = $section.attr('id');
                $section = $section.closest('.cf7-sg-tabs');
                let t = $('.cf7-sg-tabs-list', $section).children();
                for(let tdx=0;tdx<t.length;tdx++){
                  if(tabid == $(t[tdx]).attr('aria-controls')){
                    $(t[tdx]).attr('data-cf7sg','error');
                    $section.tabs( "option", "active", tdx );
                    break;
                  }
                }
              }
              $section = $input.closest('.cf7sg-slider-section');
              if($section.length>0){
                let $slide = $input.closest('.glider-slide');
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
      let $target = $(e.target);
      $('.cf7-sg-tabs-list li[data-cf7sg="error"]', $target).attr('data-cf7sg','');
      $('.cf7sg-collapsible[data-cf7sg="error"]', $target).attr('data-cf7sg','');
      $('.slider-dots button[data-cf7sg="error"]', $target).attr('data-cf7sg','');
    });
    /** on hover popup message for disabled submit buttons
    * @since 2.6.0
    */
    $('div.cf7-smart-grid.has-grid .wpcf7-submit').each(function(){
      let $submit = $(this), fid=$submit.closest('div.cf7-smart-grid').attr('id');
      if('undefined' == typeof cf7sg[fid].submit_disabled) return;
      $submit.after('<span class="cf7sg-popup display-none">'+cf7sg[fid].submit_disabled+'</span>').parent().addClass('cf7sg-popup-box');
    });
    /** enable max rows.
    * @since 2.8.0
    */
    $('div.cf7-smart-grid.has-table').on('sgRowAdded', '.container.cf7-sg-table',function(e){
      let max, msg, $table = $(this), fid=$table.closest('div.cf7-smart-grid').attr('id');
      max = $table.data('max');
      if('undefined' == typeof max || max == false) return;
      max--;
      if(max==e['row']){
        let msg = $table.data('max-row-msg');
        msg = isEmpty(msg) ? cf7sg[fid].max_table_rows:msg;
        msg = '<span class="max-limit wpcf7-not-valid-tip">'+msg+'</span>';
        $table.siblings('.cf7-sg-table-button').addClass('disabled').prepend(msg);
      }
    });
    $('div.cf7-smart-grid.has-table').on('sgRowDeleted', '.container.cf7-sg-table',function(event){
      let max, row, $table = $(this);
      max = $table.data('max');
      if('undefined' == typeof max || max == false) return;
      $table.siblings('.cf7-sg-table-button').removeClass('disabled').children('.max-limit').remove();
    });
    /** @since 4.11 reintroduce html response in CF7.*/
    $('form.wpcf7-form').each(function(){
      let cf7 = this;
      cf7.querySelectorAll( '.wpcf7-response-output' ).forEach(div=> {
        div.classList.remove('wpcf7-response-output');
        div.classList.add('cf7sg-response-output');
        div.innerHTML = '<div class="wpcf7-response-output"></div>'
      });
      // ['wpcf7mailsent', 'wpcf7mailfailed', ]
      this.addEventListener('wpcf7submit', function(e){
        if(e.detail.apiResponse && e.detail.apiResponse.message){
          // console.log(e.detail);
          cf7.querySelectorAll( '.cf7sg-response-output' ).forEach( div => {
            let msg = e.detail.apiResponse.message;
            if(msg.indexOf('cf7sg->redirect:')==0){
              location = msg.replace('cf7sg->redirect:', '');
              return false; //exit forEach.
      			}else div.innerHTML = `${msg}<div class="wpcf7-response-output"></div>`;
      		})
        }
      })

    });
  }); //end on document ready().
  /*
    jQuery extended functions
  */
  /** @since 4.0 enable permalinks in post options */
  let linkOption = function(state){
    if (!state.id) { return state.text; }
    let $option=$(state.element);
    return $('<a href="' + $option.data('permalink') + '">' + state.text + '</a>');
  }
  /** @since 4.4 prefill fields */
  $.fn.prefillCF7Field = function(fname, val, formID){
    let $field = $(this);
    if(! $field.is(':input') ) return false;

    let $form = $(this), 
      field = $field[0], ftype = field.type;
    // for(fname of Object.keys(values)){
    if(isEmpty(field)){
      if(cf7sg.debug) console.log('CF7SG ERROR: Unable to retrieve form element '+ $field.attr('name'));
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
        field = field.closest('.wpcf7-form-control-wrap' );
        if(!Array.isArray(val)) val = [val];
        val.forEach(function(v){
          field.querySelector('input[value="'+v+'"]').checked=true;
        });
        break;
      default: /*else let's try with an event */
        field.value = val;
        break;
    }
    let pe = new CustomEvent(`c2p-prefill-field`, { name: fname,value: val });
    field.dispatchEvent(pe);
    return $field;
  }
  $.fn.cf7sgSelect2Options = function(){
    let $select2 = $(this),
      s2options = {tags: $select2.is('.tags')}, //select2 options
      field = $select2.attr('name').replace('[]',''); //field name
    if($select2.is('.cf7sg-permalinks')){
      s2options['templateSelection'] = linkOption;
      s2options['templateResult'] = linkOption;
    }
    if(cf7sgCustomSelect2Templates[field]){
      s2options = Object.assign(
        s2options, //default
        cf7sgCustomSelect2Templates[field] //user setttings.
      )
    }

    return s2options;
  }
  $.fn.cf7sgHybridddOptions = function(prefill={}){
    if(!this.is('.cf7sg-dynamic_checkbox')) return false;
    let fid = this.closest('div.cf7-smart-grid').attr('id'),
      hddoptions = {}, //select2 options
      field = this.data('field-name'); //field name

    if( this.is('.cf7sg-hybriddd') ){ //normal hybriddd dropdown.
      hddoptions['optionLabel']=function(lbl){ //is a value=>label object.
        let l ='', a='';
        if(Array.isArray(lbl)){
          l = lbl[0];
          for(let i=1;i<lbl.length;i++) a+=` ${lbl[i]}`;
        }else{
          l = lbl;
        }
        return `<span${a}>${l}</span>`;
      }
    }else if( this.is('.cf7sg-imagehdd') ){ //image hybriddd dropdown.
      hddoptions['optionLabel']=function(lbl){ //is a value=>label object.
        let l='',a='',img='';
        if(Array.isArray(lbl)){
          l=lbl[0];
          for(let i=1;i<lbl.length;i++){
            if(lbl[i].indexOf('data-thumbnail')>-1) img = lbl[i].replace('data-thumbnail', 'src');
            else a+=` ${lbl[i]}`;
          }
        }
        return `<div${a}><img ${img} alt="${l}" /><p>${l}</p></div>`;
      }
    }
    if(cf7sgCustomHybridddTemplates[field]){
      hddoptions = Object.assign(
        hddoptions, //default
        cf7sgCustomHybridddTemplates[field] //user setttings.
      )
    }
    //prefill is any values loaded
    if(!objEmpty(cf7sg[fid],['prefill',field])){
      switch(typeof cf7sg[fid].prefill[field] ){
        case 'object':
          hddoptions['selectedValues']=Object.values(cf7sg[fid].prefill[field]);
          break;
        default:
          hddoptions['selectedValues']=[cf7sg[fid].prefill[field]];
        break;
      }
    }

    return hddoptions;
  }
  //toggle a collapsible section.
  $.fn.activateCF7sgCollapsibleSection = function(activate){
    if(null===activate) activate = true;
    let $section = $(this), $header;
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
    let $form = $(this);
    if('undefined' == typeof name || 0==name.length){
      if(cf7sg.debug) console.log('CF7 Smart-grid ERROR: getCF7field() requires valid field name.');
      return false;
    }
    if(!$form.is('.wpcf7-form')){
      if(cf7sg.debug) console.log('CF7 Smart-grid ERROR: getCF7field() using unknown form');
      return false;
    }
    let hasTab = ('undefined' != typeof obj.tab), hasRow = ('undefined' != typeof obj.row), $result=[];
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
    let $date = $(this);
		if(!$date.is('.wpcf7-date:enabled') && !$date.is('.wpcf7-text.datepicker:enabled')){
			return $date;
		}
		let miny='';
		let maxy='' ;
		let min = $date.attr('min');
		if(typeof min == 'undefined'){
			min = null;
		}else{
			min = new Date(min);
			miny = min.getFullYear();
		}
		let max = $date.attr('max');
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
    let $table = $(this);
    if(!$table.is('.container.cf7-sg-table')) return false;
    if(!active) $table.next('.cf7-sg-table-button').hide();
    else $table.next('.cf7-sg-table-button').show();
    // cf7-sg-table-footer
    return $table;
  }
  // disable row deletion
  $.fn.toggleCF7sgTableRowDeletion = function(active){
    if(null===active) active = false;
    let $table = $(this);
    if(!$table.is('.container.cf7-sg-table')) return false;
    if(!active) $('.row.cf7-sg-table:nth-last-child(2) .cf7sg-row-control', $table).addClass('display-none');
    else $('.row.cf7-sg-table:nth-last-child(2) .cf7sg-row-control', $table).removeClass('display-none');
    return $table;
  }
  //count rows.
  $.fn.cf7sgCountRows = function(){
    let $table = $(this);
    if(!$table.is('.container.cf7-sg-table')) return false;
    return $table.children('.row').not('.cf7-sg-cloned-table-row').length;
  }
  //removeRow
  $.fn.cf7sgRemoveRow = function(){
    let $row = $(this), doDelete=true, $table, $rows, idx;
    if(!$row.is('.cf7sg-row.cf7-sg-table')) return false;
    $table = $row.closest('.cf7sg-container.cf7-sg-table');
		$rows = $row.siblings('.cf7-sg-table.cf7sg-row');
    switch($rows.length){
			case 0: //do nothing, cannot remove the last row.
				doDelete = false;
				break;
			case 1: //hide the delete button.
				$rows.find('.cf7sg-row-control').hide();
			default:
				break;		
		}
		if(doDelete){
			idx = $row.data('row');
      $row.remove();
			//$rows are the remaining row siblings.
			for(let rdx = idx; rdx < $rows.length; rdx++){ //re-index all those below
				$rows.eq(rdx).attr('data-row', rdx);
				$(':input', $rows.eq(rdx)).each((x,i)=>{
					let $i = $(i),
						name = $i.attr('name'), $id,
						$s = $i.closest('span.wpcf7-form-control-wrap'),
						ndx = name.lastIndexOf('_row-'),
						sfx = (name.slice(name.length-2)==='[]') ? '[]':'';
					if(rdx>0){ 
						//remove the class from its span.
						$s.removeClass(name);
						ndx += 5; //include '_row-'
						name = name.slice(0,ndx);
						//rename
						$i.attr('name', name+rdx+sfx); 
						if($s.data('name')) $s.attr('data-name', name+rdx);

					}else{
						name = name.slice(0,ndx);
						//rename
						$i.attr('name', name+sfx); 
						if($s.data('name')) $s.attr('data-name', name);

					}
					//re-id
					name = $i.attr('id');
					let $l=null;
					if(name){ 
						$id = $i;
						$l = $id.siblings(`label[for="${name}"]`);
					}else if( $i.is('[type="radio"]') || $i.is('[type="checkbox"]') ){ 
						name = $s.attr('id');
						$id = $s;
					}
					if(name){
						if(rdx>0){
							ndx = name.lastIndexOf('_row-')+5; //include '_row-'
							name = name.slice(0,ndx);
							$id.attr('id', name+rdx);
							if($l) $l.attr('id', name+rdx);
						}else{
							ndx=name.lastIndexOf('_row-');
							name = name.slice(0,ndx);
							$id.attr('id', name);
							if($l) $l.attr('id', name);
						}
					}
				});
				//TODO check HybridDropdown / Select2
			}
      /** @since 2.4.2 track table fields */
      let $tracker = $table.children('.cf7sg-tracker-field');
      if($tracker.length) $tracker.val($rows.length); //$rows are the remaining siblings.
      $table.trigger('sgRowDeleted');
    }
    return $table;
  }
  /** 
	 * clone table row
	 * boolean initSelect, whether or not to initialise select2 fields, is false if called from cf7_2_post field loading script,
    else is true whehn triggered from the front-end user event
	 * @since 4.9.1 el is the HTMLelement target that triggered the add row
	 * */
  $.fn.cf7sgCloneRow = function(initSelect, el){
    if(typeof initSelect === 'undefined') initSelect =true;
    let $table = $(this);
    
    //if not a table let's exit.
    if(!$table.is('.cf7sg-container.cf7-sg-table')){
      return $table;
    }
    let $rows = $table.children( '.cf7sg-row.cf7-sg-table'),
     	$cloneRow = $('.cf7-sg-cloned-table-row', $table),
     	$new = $cloneRow.clone(), fid = $table.closest('div.cf7-smart-grid').attr('id');

    $new.attr('class', $rows.last().attr('class')).attr('data-row',$rows.length);
    //show row so select2 init properly
		//add the new row after the last one.
    $rows.last().after($new.show());
		// if($rows.length === 1) $('.cf7sg-row-control',$rows).show();
		
    // Polyfill for IE11 to support endsWith() - https://developer.mozilla.org/en/docs/Web/JavaScript/Reference/Global_Objects/String/endsWith
    if (!String.prototype.endsWith) {
      String.prototype.endsWith = function(searchString, position) {
          let subjectString = this.toString();
          if (typeof position !== 'number' || !isFinite(position) || Math.floor(position) !== position || position > subjectString.length) {
            position = subjectString.length;
          }
          position -= searchString.length;
          let lastIndex = subjectString.indexOf(searchString, position);
          return lastIndex !== -1 && lastIndex === position;
      };
    }
    //add input name as class to parent span
    $(':input', $new).each(function(){
      let $input = $(this), 
        iid = $input.attr('id'),
        $span = $input.closest('span.wpcf7-form-control-wrap'),
        isRadio = $input.is('[type="radio"]') || $input.is('[type="checkbox"]');

      if(!iid && isRadio) iid = $span.attr('id');
      //enable inputs
      $input.prop('disabled', false);
      let name = $input.attr('name').replace('_cf7sgcloned_',''); /** @since 4.4 */
      let suffix = '';
      if(name.endsWith('[]')){
        name = name.replace('[]','');
        suffix = '[]';
      }
      $input.attr('name', name+'_row-'+$rows.length+suffix);//.addClass('cf7sg-'+name);
      $span.addClass(name+'_row-'+$rows.length);
      /** @since 4.14 fix SWV validation in CF7 v5.6 */
      if($span.data('name')) $span.attr('data-name', name+'_row-'+$rows.length);
      if(iid){ 
        if(isRadio){
          $span.attr('id',iid+'_row-'+$rows.length);
        }else{
          $input.attr('id', iid+'_row-'+$rows.length);
          $span.siblings(`label[for="${iid}"]`).attr('for',iid+'_row-'+$rows.length);
        }
      }
      //finally enabled the select2 dropdown.
      if($input.is('select.select2') && initSelect){
        $input.select2($input.cf7sgSelect2Options());
        $input.trigger('sgSelect2');
      }
    });
    /** @since 4.12 enable hybrid fields in new rows */
    $('.cf7sg-dynamic_checkbox', $new).each(function(){
      new HybridDropdown(this, $(this).cf7sgHybridddOptions());
    });
    //when the button is clicked, trigger a content increase for accordions to refresh
    $table.trigger('sgContentIncrease');
    $table.trigger({type:'sgRowAdded',row:$rows.length, button:el});
    /** @since 2.4.2 track table fields */
    let $tracker = $table.children('.cf7sg-tracker-field');
    if($tracker.length) $tracker.val($rows.length+1); //rowIdx is zero based.
    return $table;
  }
  //disable/enable tab addtion
  $.fn.toggleCF7sgTabAddition = function(active){
    if(null===active) active = false;
    let $tab = $(this);
    if(!$tab.is('div.cf7-sg-tabs')) return false;
    if(!active) $('.cf7sg-add-tab', $tab).hide();
    else $('.cf7sg-add-tab', $tab).show();
    return $tab;
  }
  //disable/enable tab deletion
  $.fn.toggleCF7sgTabDeletion = function(active){
    if(null===active) active = false;
    let $tab = $(this);
    if(!$tab.is('div.cf7-sg-tabs')) return false;
    if(!active) $('.cf7-sg-tabs-list li:last-child .cf7sg-close-tab', $tab).addClass('display-none').hide();
    else $('.cf7-sg-tabs-list li:last-child .cf7sg-close-tab', $tab).removeClass('display-none').show();
    return $tab;
  }
  //count tabs.
  $.fn.cf7sgCountTabs = function(){
    let $tab = $(this);
    if(!$tab.is('div.cf7-sg-tabs')) return false;
    return $tab.find('.cf7-sg-tabs-list').children('li').length;
  }
  //remove last tab.
  $.fn.cf7sgRemoveTab = function(){
    let $tab = $(this);
    if(!$tab.is('.cf7sg-tab-title li')) return false;
    let tabFor = $tab.find('label').attr('for'), tabIdx, lbl,
		  $tabList = $tab.parent(), //ul list of tabs
			$radios = $tab.parent().siblings('.cf7sg-tab-radio'), //list of radio buttons.
			$panels = $tab.parent().siblings('.cf7sg-tabs-panel'); //list of panels
		
		//label template.
		lbl = 'title (cnt)';
		if(typeof $tabList.data('tplt')!== 'undefined') lbl = $tabList.data('tplt');
		if(typeof $tabList.data('title')!== 'undefined'){ 
			lbl = lbl.replace('title', $tabList.data('title'));
		}else{ 
			lbl = lbl.replace('title','');
		}
		lbl = lbl.trim();
		//remove hte tab and its panel.
		tabIdx = $radios.filter(`#${tabFor}`).remove().val(); //remove radio button
		tabIdx = parseInt(tabIdx.replace('tab-',''));
		$panels.eq(tabIdx-1).remove(); //remove panel.
		//re-index existing tab panels, starting from the tabIdx (which is the next one in zero-based $panels ).
		$tabList = $tabList.find('li'); //list of tabs, before removal to maintain count and order.
		$tab.remove(); //remove the tab.

		if(tabIdx>1) tabFor = $radios.eq(0).attr('id');
		for(let tdx = tabIdx; tdx < $panels.length; tdx++){
			//radio & label.
			if(tdx === 1){ 
				$radios.eq(tdx).attr('id',`${tabFor}`).val(`tab-${tdx}`);
				$tabList.eq(tdx).find('label').attr('for',`${tabFor}`).text(lbl.replace('cnt', tdx));
			}else{
				$radios.eq(tdx).attr('id',`${tabFor}-${tdx}`).val(`tab-${tdx}`);
				$tabList.eq(tdx).find('label').attr('for',`${tabFor}-${tdx}`).text(lbl.replace('cnt', tdx));
			}
			//label
			$(':input', $panels.eq(tdx)).each((x,i)=>{
				let $i = $(i),
					name = $i.attr('name'), $id,
					$s = $i.closest('span.wpcf7-form-control-wrap');
				if(tdx>1){ 
					//remove the old name class from its span.
					$s.removeClass(name);
					name = name.replace(`_tab-${tdx}`, `_tab-${(tdx-1)}`);
					$s.addClass(name);
				}else{
					name = name.replace(`_tab-${tdx}`, '');
				}
				//rename
				$i.attr('name', name); 
				if($s.data('name')) $s.attr('data-name', name.replace('[]',''));
				//re-id
				name = $i.attr('id');
				let $l=null;
				if(name){ 
					$id = $i;
					$l = $id.siblings(`label[for="${name}"]`);
				}else if( $i.is('[type="radio"]') || $i.is('[type="checkbox"]') ){ 
					name = $s.attr('id');
					$id = $s;
				}
				if(name){
					if(tdx>1){
						name = name.replace(`_tab-${tdx}`, `_tab-${(tdx-1)}`);
						$id.attr('id', name);
						if($l) $l.attr('id', name);
					}else{
						name = name.replace(`_tab-${tdx}`, '');
						$id.attr('id', name);
						if($l) $l.attr('id', name);
					}
				}
			});
		}
   
		/** @since 2.4.2 udpate the tracker field*/
		let $tracker = $tabList.closest('.cf7-sg-tabs').children('.cf7sg-tracker-field');
		if($tracker.length) $tracker.val($panels.length-1);
    return true;
  }
  //clone tabs, called on a div.cf7-sg-tabs
  $.fn.cf7sgCloneTab = function(initSelect, human){
    if(null===human) human = false; //flag to indicate if programmatic addition.
    let $tab = $(this);
    if(typeof initSelect === 'undefined') initSelect =true;
    /*initSelect is false if called from cf7_2_post field loading script,
    else if true whehn triggered from the front-end user event.*/
    if(!$tab.is('div.cf7-sg-tabs')) return false;

    let $tabList = $tab.find('ul.cf7sg-tab-title'), //list of tab labels
     tabCount = $tabList.children('li').length + 1,
     tabId  = $tab.attr('id'),
		 $radio = $tab.find('input.cf7sg-tab-radio').first().clone(), //list of tab input radiofields.
		 iid = $radio.attr('id'),lbl, css,
     $newTab = $tabList.children('li').first().clone(), //create a tab clone
		 $newPanel = $( cf7sgPanels[tabId] ); //new tab panel
		//new radio button
		$radio.attr('name',`cf7sg-lbl-${iid}`).attr('id',`${iid}-${tabCount}`).val(`tab-${tabCount}`);
		$tabList.before($radio);//insert the new radio button.
		//new label
		lbl = 'title (cnt)';
		if(typeof $tabList.data('tplt')!== 'undefined') lbl = $tabList.data('tplt');
		if(typeof $tabList.data('title')!== 'undefined'){ 
			lbl = lbl.replace('title', $tabList.data('title'));
		}else{ 
			lbl = lbl.replace('title','');
		}
		lbl = lbl.trim();
		lbl = lbl.replace('cnt', tabCount);
		lbl = $newTab.find('label').attr('for',`${iid}-${tabCount}`).text(lbl);
    // $newTab.append('<span class="cf7sg-close-tab dashicons dashicons-no-alt"></span>'); //remove button
    //append tab to list
    $tabList.append( $newTab );
    //add input name as class to parent span
    $(':input', $newPanel).each(function(){
      let $input = $(this),
        iid = $input.attr('id'),
        $span = $input.closest('span.wpcf7-form-control-wrap'),
        isCloneRow = $input.is('.cf7-sg-cloned-table-row :input'),
        name = $input.attr('name'),
        suffix = '';
      //enable inputs
      if(!isCloneRow) $input.prop('disabled', false);
      if(name.endsWith('[]')){
        name = name.replace('[]','');
        suffix = '[]';
      }
      /*
        fields in additional tabs will be suffixed with .tab-[0-9]+
        fields in additional rows in tables will be suffixed with .row-[0-9]+
        fields in additional rows in tables that are in additional tabs will be suffixed with .tab-[0-9]+.row-[0-9]+
      */
      $input.attr('name', name+'_tab-'+(tabCount-1)+suffix);//.addClass('cf7sg-'+name);
      $span.removeClass(name).addClass(name + '_tab-' + (tabCount-1));
      /** @since 4.14 fix SWV validation in CF7 v5.6 */
      if($span.data('name')) $span.attr('data-name', name+'_tab-'+(tabCount-1));
      if(iid){ 
        $input.attr('id', iid+'_tab-'+(tabCount-1));
        let $l = $span.siblings('label');
        if($l.attr('for') === iid) $l.attr('for',iid+'_tab-'+(tabCount-1));
      }
      //enable nice select on the dropdown.
      if(!isCloneRow && $input.is('select.ui-select') && initSelect){
        $input.niceSelect();
      }
      if(!isCloneRow && $input.is('select.nice-select') && initSelect){
        $input.niceSelect();
      }
      if(!isCloneRow && $input.is('select.select2') && initSelect){
        $input.select2($input.cf7sgSelect2Options());
        $input.trigger('sgSelect2');
      }
    });
    /** @since 4.12 enable hybrid fields in new tab */
    $('.cf7sg-dynamic_checkbox', $newPanel).not('.cf7-sg-cloned-table-row *').each(function(){
      new HybridDropdown(this, $(this).cf7sgHybridddOptions());
    });
    //append new panel
    $tab.children('.cf7sg-row').append($newPanel);
    
    //enable the collapsible titles & toggle buttons
    $('.cf7sg-collapsible.with-toggle', $newPanel).each(function(){
      let $section = $(this);
      /**
      * @since 1.1.0 grouped toggles/disabled inputs.
      */
      let rootId = $section.attr('id'), id = rootId +'_tab-'+(tabCount-1);
      $section.attr('id',id);//reset unique id.
      let group = $section.data('group');
      if(group){
        group = group+'_tab-'+(tabCount-1);
        $section.attr('data-group', group);
      }
      let state = $section.data('open');
      let toggled = false;
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
		 //trigger new table ready.
		 $('.cf7-sg-table.container', $newPanel).each(function(){
      let $table = $(this), orgid = $table.attr('id');
      $table.attr('id',orgid+'_tab-'+(tabCount-1));
      $table.trigger({type:'sgTableReady', 'table-id':orgid,'tab-index':tabCount-1});
    });
		//add style for the tab,
		// $style  `.cf7-smart-grid.has-grid .cf7sg-container.cf7-sg-tabs > .cf7sg-row .cf7sg-tab-radio:nth-of-type(2):checked ~ .cf7sg-tab-title li:nth-of-type(2) label {border-bottom-color: var(--cf7sg-tabs-bg-active); border-top-color: var(--cf7sg-tabs-border-active); background: var(--cf7sg-tabs-bg-active); color: inherit;}`
		// $tab.append($style);
		//if this was from a click, human user, then activate the tab.
    if(human) $radio.get(0).checked=true;
		/*Add styling fornew panel */
		css = $tab.find('style').text();
		css+=`.cf7-smart-grid.has-tabs .cf7sg-tab-radio:nth-of-type(${(tabCount)}):checked ~ .cf7sg-tab-title li:nth-of-type(${(tabCount)}) label{display: none;}`;
		css+=`.cf7-smart-grid.has-tabs .cf7sg-tab-radio:nth-of-type(${(tabCount)}):checked ~ .cf7sg-tab-title li:nth-of-type(${(tabCount)}) label.tactive{display: block;}`;
		css+=`.cf7-smart-grid.has-tabs .cf7sg-tab-radio:nth-of-type(${(tabCount)}):checked ~ .cf7sg-tabs-panel:nth-of-type(${(tabCount)}){display: block;}`;
		$tab.find('style').text(css);
    /** @since 1.2.2 */
    //trigger new tab event for custom js.
    $newPanel.trigger({type:'sgTabAdded','tab-index':tabCount-1});
   
    /** @since 2.4.2 track tabs and their fields.*/
    //increment tab count tacker.
    let $tracker = $tab.children('.cf7sg-tracker-field');
    if($tracker.length>0) $tracker.val(tabCount);
    return $tab;
  }

  //setup toggles
  $.fn.setupToggle = function(state, group){
    let $this = $(this);
    if(typeof state === 'undefined') state =false;
    if( !$this.is('.toggle') ){
      return $this;
    }
    if($this.length > 0){
      let onText = $this.data('on');
      if(onText.length == 0){
        onText = 'Yes';
      }
      let offText = $this.data('off');
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
    let $form = $(this);
    let serverRequest = $.ajax({
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
    let parent = p;
    for(let idx=0; idx<c.length;idx++){
      if(isEmpty(parent[c[idx]])) return true;
      parent = parent[c[idx]];
    }
    return false;
  }
  //empty checks for undefined, null, false, NaN, ''
  function isEmpty(v){
    if('undefined' === typeof v || null===v) return true;
    return typeof v === 'number' ? isNaN(v) : !Boolean(v);
  }
})( jQuery )
