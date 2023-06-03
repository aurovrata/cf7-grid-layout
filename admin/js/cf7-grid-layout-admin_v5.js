
(function( $ ) {
  'use strict';
  /**
  * Javascript to handle grid editor
  * Event 'cf7sg-form-change' fired on #contact-form-editor element when codemirror changes occur
  */
  const offsets = ['offset-one','offset-two', 'offset-three', 'offset-four', 'offset-five', 'offset-six', 'offset-seven', 'offset-eight', 'offset-nine', 'offset-ten', 'offset-eleven'],
    columnsizes = ['one', 'two', 'one-fourth', 'one-third', 'five', 'one-half', 'seven', 'two-thirds', 'nine', 'ten', 'eleven', 'full'],
    columnLabels={'one':'1/12','two':'1/6', 'one-fourth':'1/4', 'one-third':'1/3', 'five':'5/12', 'one-half':'1/2', 'seven':'7/12', 'two-thirds':'2/3', 'nine':'3/4', 'ten':'5/6', 'eleven':'11/12', 'full':'Full'};
  const cf7FieldRgxp = '^([^\\s=\"\':]+)([\\s]+(([^\"]+\\s)+)?(\\"source:([^\\s\":]+)?(:[^\\s]*)?\\")?\\s?(\\"slug:([^\\s\":]+)(:[^\\s]*)?\\")?(?:.*)?)?$';
  /** @since 5.0 filter out tag fields that have form generators. */
  const cf7TagGen = Object.values(cf7grid.fieldtags).filter(v => !["count","range","captchac","captchar","reflection","select"].includes(v));
  let cf7TagRgxp, $wpcf7Editor, $grid, $gridEditor, $colTemplt, $rowTemplt; 
  //graphics UI template pattern, @since 4.11.7 fix classes on cell element.
  let $pattern = $('<div>').html('' +
    cf7grid.preHTML + 
    '\\s*(\\[.*\\s*\\].*\\s*){0,}\\s*' +
    cf7grid.postHTML),
    required = cf7grid.requiredHTML.replace('*', '\\*'),
    fieldPattern='';
  // console.log('r:'+required);
  $pattern.find('label').html('((.*)('+required+'){1}|(.*))');
  $pattern.find('.info-tip').text('(\\s*.*\\s*)');

  fieldPattern = $pattern.html().replace(/class="([\w\s]*)"/,'class="$1[\\s_a-zA-Z0-9-]*"');
  fieldPattern = fieldPattern.replace(/\sfor="([\w]*)"/,'(?:\\sfor="$1[\\w-]*")?');
  fieldPattern.replace('><','>\\s?<')
  // console.log('p:'+$pattern.html().replace('><','>\\s?<'));
  const templateRegex = new RegExp(fieldPattern, 'ig');
  let seekTemplate = false, cssTemplate = 'div.field',
    $template = $('<div id="cf7sg-dummy">').append(cf7grid.preHTML+cf7grid.postHTML);
  $template = $template.children();
  if(1==$template.length && $template.find('label').length>0){
    seekTemplate = true;
    cssTemplate = $template.prop('nodeName');
    if($template.prop('class').length>0){
      cssTemplate += '.'+$template.attr('class').split(' ').join('.');
    }
  }
  let wpcf7Value = '';
  const $modal = $('#cf7sg-field-edit'), $tagModal = $('#cf7sg-tag-list'), $customModal = $('#cf7sg-custom-html'), $customTagModal = $('#cf7sg-custom-tag');
  $.modal.defaults.modalClass= "cf7sg-modal modal";
	
	$(document).ready( function(){
    /** @since 5.0 scan tag with form generators  */
    document.querySelectorAll('#cf7-taggenerator-forms form.tag-generator-panel').forEach((el)=>{
      let idx = cf7TagGen.indexOf(el.dataset.id);
      if(idx>-1) cf7TagGen.splice(idx,1);
    });
    //populate the tag list modal with custom tags.
    cf7TagGen.forEach(t=>{
      $('.custom-tags', $tagModal).append(`<a href="javascript:void(0);" class="button custom-tag" data-id="${t}">${t}</a>`);
    });
    //change the form id to mimic cf7 plugin custom admin page.
    /** @since 2.11.0 full screen button*/
    let $editor = $('#cf7sg-editor');
    $colTemplt = $('<div>').html($('#grid-col').html());
    $rowTemplt = $('<div>').html($('#grid-row').html());
    $editor.css('background-color',$('body').css('background-color'));
    $('#full-screen-cf7').on('click', function(){
      $editor.toggleClass('full-screen');
      $(this).toggleClass('full-screen');
      // if($editor.is('.full-screen')) $editor.width($('#wpbody').width());
      // else $editor.width('auto');
      $('#form-editor-tabs .ui-tabs-panel', $editor).trigger('cf7sg-screen-resize');
    });
    $wpcf7Editor = $('textarea#wpcf7-form-hidden');
    $grid = $('#grid-form');
    $gridEditor = $('#cf7-editor-grid');
		// track conditional plugin on modal settings.
		if($grid.is('.cf7-conditional-group')) $.modal.defaults.modalClass += ' cf7-conditional-group';
    
    /** @since v5.0 improved cf7 tag regex pattern. */
    cf7TagRgxp = ['hidden']; //by default no hidden button.
    $('form.tag-generator-panel .insert-box input.tag').each((i,el)=>{
      cf7TagRgxp.push(el.name);
    });
    cf7TagRgxp = cf7TagRgxp.join('|');
    cf7TagRgxp = '\\[(('+cf7TagRgxp+')\\*?)(?:[\\s](.*?))?(?:[\\s](\\/))?\\]';
    /*
    Build grid from existing form------------------------- BUILD UI FORM
    */
    function buildGridForm(){
      let formhtml = $wpcf7Editor.text();
      if(0===formhtml.length){
        formhtml = '<div class="cf7sg-container"><div class="cf7sg-row"><div class="cf7sg-col full"></div></div></div>';
      }
      let $form = $('<div>').append( formhtml );
      let isGrid = true; //return value.
      $grid.html(''); //empty the grid.
      if(0===$form.children('.cf7sg-container').length){
        isGrid = false;
      }
      /** @since 5.0 track conditional groups */
      if($grid.is('.cf7-conditional-group')){
        let r = 'cf7sgGroup'+randString(6);
        $('.field:contains("[group ")', $form).trackConditionalGroups('group',r);
        $('.cf7sg-col:contains("[group "):not(:has(:contains("[group ")))', $form).trackConditionalGroups();
        $('.cf7sg-col.cf7sg-grid:contains("[group ")', $form).trackConditionalGroups();
        //check for other conditional groups, div with group but not children with group
        $('div:contains("[group "):not(:has(:contains("[group ")))', $form).trackConditionalGroups();
        //finally reinstate conditional groups within fields.
        $('.field:contains('+r+')', $form).trackConditionalGroups(r,'group');
      }
      //remove the external forms
      $('.cf7sg-external-form .cf7sg-external-form-content', $form).remove();
      //seek collapsibles for slider/accordion.
      let $collapsibles = $('.cf7sg-col .cf7sg-collapsible:not(.with-toggle):first-child', $form);
      //replace columns content with textareas
      /*--------------------------------------------------- convert columns */
      $('div.cf7sg-col', $form).each(function(){
        let $this = $(this), $area = $colTemplt.clone();

        switch(true){
          case $this.is('.cf7sg-ext-form'):
					case $this.children().is('.cf7sg-container'):
          case $this.children('.cf7sg-row').length>0: //grid.
						$('.grid-input, .cf7-field-inner, .add-field-button', $area).remove();
						break;
          case $this.is('.cf7-sg-table-footer-row > .cf7sg-col'): //table footer.
            $area = $($('#grid-table-footer-row').html()).find('.cf7sg-col'); //clone as jquery object.
            let $text = $('p.info-tip',$this);
            if($text.length>0){ 
              $text = $text.html().trim();
              $this.children().remove();
              $this.text('');
              $('.grid-input', $area).html($text);
            }else{
              $('.grid-input', $area).html($this.html().trim());
            }
            break;
          default: //move the cf7 tags to UI fields.
             $('.grid-input', $area).html($this.html().trim());
            if($this.closest('.cf7sg-col').length>0) $('.add-field-button', $area).remove();
            $this.children().remove();
            $this.text('');
            break;
        }
        $this.prepend($area.children());
        
        //set UI column size/offset menu
        $this.setColumnUIControl();
        //disable any non-valid options
        // $this.filterColumnControls(); do this when menu is opened instead.
      });
      $('div.cf7sg-row:not(.cf7-sg-table-footer-row)', $form).each(function(){
        $(this).prepend( $rowTemplt.find('.grid-ctrls').clone() );
      });
      /*--------------------------------------------------- convert collapsible sections  */
      $('div.cf7sg-container.cf7sg-collapsible', $form).each(function(){
        let $this = $(this);
        let id = $this.attr('id');
        if(typeof id == 'undefined'){
          id = randString(6);
          $this.attr('id', id); //assign a random id
        }
        let text = $this.children('.cf7sg-collapsible-title span.cf7sg-title').text();
        if(0==text.length){ //pre v1.8 title?.
          text = $this.children('.cf7sg-collapsible-title').text();
        }
        let $toggle = $('.toggle', $this.children('.cf7sg-collapsible-title'));
        if($toggle.length>0){
          $toggle = $toggle.clone();
        }
        //swap out HTML title element for UI title element with input fields.
        let $title = $this.children('.cf7sg-collapsible-title');
        $title.children().remove();
        $title.prepend( $('.cf7sg-collapsible-title',$('#grid-collapsible')).html());
        $('input', $title).not('[type="checkbox"]').val(text);
        if($toggle.length>0){
          $title.append($toggle);
          $('input[type="checkbox"]', $title ).prop('checked', true);
        }
        let $ctrl = $this.children('.cf7sg-row').children('.grid-ctrls').find('.collapsible-row-label');
        $('input', $ctrl).prop('checked', true);
        //toggle disable the sibling input
        $('input', $ctrl.siblings('.unique-mod')).prop('disabled', function(i,v){return !v;});
      });
      /*--------------------------------------------------- convert tables */
      $('div.cf7sg-container.cf7-sg-table', $form).each(function(){
        let $this = $(this);
        let id = $this.attr('id');
        if(typeof id == 'undefined'){
          id = 'cf7-sg-table-'+(new Date).getTime();
          $this.attr('id', id);
        }
        let $ctrl = $this.find('.cf7sg-row.cf7-sg-table > .grid-ctrls' ).attr('class','grid-ctrls cf7sg-table-ctrls');;
        // $('input', $ctrl).prop('checked', true);
        //set button label
        let text = $this.data('button');
        if(typeof text  == 'undefined'){
          text = 'Add Row';
          $this.attr('data-button',text);
        }
        $ctrl.next('.table-row-button').children('input').val(text);
        //toggle disable the sibling input
        $('input', $ctrl.siblings('.unique-mod')).prop('disabled', function(i,v){return !v;});
        //toggle footer row
        let $footer = $this.find('.cf7-sg-table-footer');
        if($footer){
          $('input.footer-row', $ctrl).prop('checked', true);
          $('input', $ctrl.siblings('.unique-mod')).prop('disabled', function(i,v){return !v;});
        }
      });
      //tabs
      /*--------------------------------------------------- convert tabs */
      $('ul.cf7-sg-tabs-list li', $form).each(function(){
        let $this = $(this);
        let text = $this.children('a').text();
        $this.append($('#grid-tabs').html().find('ul li label'));
        $('label input', $this).val(text);
        //setup checkbox
        let $ctrl = $this.parent().siblings('.cf7-sg-tabs-panel');
        $ctrl = $ctrl.children('.cf7sg-row').children('.grid-ctrls' ).find('.tabs-row-label');
        $('input.tabs-row', $ctrl).prop('checked', true);
        $('input', $ctrl.siblings('.unique-mod')).prop('disabled', function(i,v){return !v});
      });
      //reinsert the external forms
      $('.cf7sg-external-form', $form).each(function(){
        let $extform = $(this);
        $extform.append($( $('#grid-cf7-forms').html()).children() );
        let id = $extform.data('form');
        if($($('#grid-cf7-forms').html()).find('.cf7sg-form-select option[value="'+id+'"]').length > 0 ){
          //add controls
          //$extform.append($('#grid-cf7-forms .ext-form-controls').clone());
          $('.ext-form-controls .cf7sg-form-select', $extform).val(id);
          //check for form update.
          let data = {
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
      /** @since 3.4 enable groupings of collapsible rows */
      $('.cf7sg-accordion-rows.cf7sg-col, .cf7sg-slider-section.cf7sg-col', $form).each(function(){
        let $col = $(this),
          $control = $col.children('.grid-column').addClass('enable-grouping'); //enable checkboxes.
          /** @since 4.13.0 display auto scroll helper code */
        $('.php-icon',$control).show().attr('data-search', 'li.cf7sg-slider');
        if($col.is('.cf7sg-accordion-rows')){
          $('.accordion-rows:input', $control).prop('checked', true);
        }else{ //is .cf7sg-slider-section
          $('.slider-rows:input', $control).prop('checked', true);
          //check if next container is a slider control.
          let $ctrl = $col.closest('.cf7sg-container').next();
          if($ctrl.is('.cf7sg-container.cf7sg-slider-controls')){
            $control = $ctrl.children('.cf7sg-row').children('.grid-ctrls');
            $('.slider-control:input', $control).prop('checked', true);
          }
        }
        //remove toggle checkbox.
        $('input[type="checkbox"]', $col.children('.cf7sg-collapsible').children('.cf7sg-collapsible-title') ).hide().next('span').hide();
      });
      //check if any columns have more than 2 collapsible sections.
      $collapsibles.each(function(){
        $(this).siblings('.cf7sg-collapsible').closest('.cf7sg-col').children('.grid-column').addClass('enable-grouping');
      });
      //add the form to the grid
      if($form.children('.cf7sg-container').length >0){
        $grid.append($form.children());
      }else{ //this is not a cf7sg form.
        $grid.html($form.html());
      }
      //set the value of each textarea as inner text
      $('textarea', $grid).each(function(){
        let $this = $(this);
        $this.html($this.val());
      });
      /*--------------------------------------------- if ui mode, then convert to gui template */
      let $textareaSelected='';
      if(cf7grid.ui){
        $('.cf7sg-col', $grid).each(function(){
          let $this = $(this);
          if($this.is('.cf7sg-inner-grid')) return true;
          if($this.is('.cf7sg-grid')) return true;
          if($this.is('.cf7sg-ext-form')) return true;
          if($this.children().is('.cf7sg-container')) return true;
          if($this.children('.cf7sg-row').length > 1) $this.closest('.cf7sg-container').addClass('cf7sg-grid');
          $this.html2gui();
          if($this.is('.cf7sg-container > .cf7sg-row:first-child > .cf7sg-col')){
            $this.append($colTemplt.find('.add-field-button').clone());
          }
      });
        /** @since 5.0  add ctrl buttons after last container*/
        if($gridEditor.children('.add-row-button').length==0) $grid.after($rowTemplt.find('.add-row-button').clone());
      }else{
        //set the first textarea as our default tag consumer
        $('textarea#wpcf7-form').attr('id','');
        $textareaSelected = $('textarea', $grid).first();
        $textareaSelected.attr('id', 'wpcf7-form');
      }
      return isGrid;
    } //---------------------------------------------------------------end buildGridForm().

    /** @since 3.4.0 enable accordion class for containers having multiple collapsible rows */
    $grid.on('cf7sg-update', function(e, update){
      let $container = $(e.target);
      if(undefined !== update.add){
        switch(update.add){
          case 'collapsible-row':
            let $pc =  $container.closest('.cf7sg-col');
           if( $pc.children('.cf7sg-collapsible').length>1 ){
             //enable column accordion control.
             $pc.children('.grid-column').addClass('enable-grouping');
           }
        }
      }
      if(undefined !== update.remove){
        switch(update.remove){
          case 'collapsible-row':
            let $pc =  $container.closest('.cf7sg-col');
           if( $pc.children('.cf7sg-collapsible').length<2 ){
             //disable column accordion control.
             $pc.children('.grid-column').removeClass('enable-grouping');
           }
        }
      }
    });
    /** @since 5.0.0 modal management */    
    $('body').on('click','.jquery-modal a', (e)=>{
      let $target = $(e.target),$txaf, $uif,$txam, lbl, desc;
      
      switch(true){
        case $target.is('#cf7sg-field-edit .cf7sg-edit-shortcode'):
          $tagModal.modal({
            closeExisting: false
          });
          break;
        case $target.is('#cf7sg-field-edit .button-primary'):
          //udpate field.
          $uif = $('#cf7sg-ui-field', $grid);
          $txaf = $('.cf7-field-type textarea', $uif);
          $txam = $('#wpcf7-form', $modal);
          lbl = $('#cf7sg-modal-label', $modal).val();
          desc = $('#cf7sg-modal-desc', $modal).val();

          $txaf.val($txam.val()).text($txam.val());

          $('.cf7-field-tip input', $uif).val(desc);
          $('.cf7-field-tip p.content', $uif).html(desc);
          $('.cf7-field-label input', $uif).val(lbl);
          $('.cf7-field-label p.content', $uif).html(lbl);
          lbl = $txaf.scanCF7Tag();
          $('.cf7-field-type p.content', $uif).html(lbl);
          //reset textarea#wpcf7-form, nad modal
          $uif.attr('id','');
          $txam.attr('id','').val('').text('');
          $txaf.attr('id','');
          $('input', $modal).val('');
          $.modal.close();
          $('textarea.grid-input', $uif).updateGridForm();
          break;
        case $target.is('#cf7sg-tag-list .button'): //tag list modal
          $.modal.close();
          switch(true){
            case $target.is('.custom-tag'):
              $customTagModal.modal();
              $('textarea', $customTagModal).text(`[${$target.data('id')} field_${randString(4)}]`)
              break;
            case $target.is('.custom-html'):
              $customModal.modal();
              break;
          }
          break;
        case $target.is('#cf7sg-custom-html .button'):
          $uif = $('#cf7sg-ui-field', $grid); 
          $txam = $('textarea', $customModal);
          if($uif.is('.cf7-sg-table-footer-row *')){
            desc = $txam.val();
            $('.cf7-field-tip input', $uif).val(desc);
            $('.cf7-field-tip p.content', $uif).html(desc);
          }else{
            //remove field and set custom html.
            $uif.addClass('custom-html');
            let $f = $uif.children('.cf7-field-inner');
            $f.last().after('<div class="cf7-field-inner cf7-field-html">');
            $f.remove();
            $('.cf7-field-inner', $uif).html($txam.val());
            $('textarea.grid-input', $uif).html($txam.val());
          }
          //reset textarea#wpcf7-form, nad modal
          $uif.attr('id','');
          $txam.attr('id','').val('').text('');
          $('input', $modal).val('');
          $.modal.close();
          $('textarea.grid-input', $uif).updateGridForm();
          break;
        case $target.is('.close-modal'): //reset modal and field.
          $('#cf7sg-ui-field', $grid).attr('id','');
          $target.closest('form').find('#wpcf7-form').attr('id','');
          break;
        case $target.is('#cf7sg-custom-tag .button'): //custom tag field.
          $('textarea#wpcf7-form').val($('textarea',$customTagModal).val());
          $('textarea',$customTagModal).val('').text('');
          $.modal.close();
          $modal.modal();
          break;
      }
      $grid.trigger('cf7sg-cf7tag-update'); //for other plugins.
    });

    //offset/size change using event delegation
    /*---------------------------------------------------------------------------- ui menus */
    $gridEditor.keypress((e)=>{
      let keycode = (e.keyCode ? e.keyCode : e.which);
      if(keycode == '13'){
        e.preventDefault();
        e.stopPropagation();
        //toggleControl(); //close menu.
      }
    });
    $gridEditor.on('change', function(event){
      let $target = $(event.target);
      if($target.is('.cf7sg-form-select')){ //-------------- external form selection
        let $container = $target.closest('.cf7sg-external-form');
        $container.attr('data-form', $target.val());
        let data = {
    			'action' : 'get_cf7_content',
          'id': $('#post_ID').val(),
          'nonce'  : $('#_wpcf7nonce').val(),
    			'cf7_key' : $target.val()
    		};
        $.post(ajaxurl, data, function(response) {
    			$('.cf7sg-external-form-content', $container).attr('id','cf7sg-form-'+$target.val()).html(response);
    		});
        return true;
      }
      /*
        Collapsible / tabs rows input changes
      */
      if($target.is('.collapsible-row-title input')){ //------- Collapsible title
        $target.closest('.cf7sg-collapsible.cf7sg-container').find('.cf7sg-collapsible-title .cf7sg-title').html($target.val());
        $target.closest('.grid-controls').siblings('.control-label').html($target.val());
        return true;
      }else if( $target.is('.cf7sg-collapsible-title label input[type="checkbox"]')){ //------- Collapsible title toggled
        let $title = $target.closest('.cf7sg-collapsible-title');
        if($target.is(':checked')){
          $title.append($('#grid-collapsible-with-toggle').html());
          $title.closest('.cf7sg-container.cf7sg-collapsible').addClass('with-toggle').attr('data-group','').attr('data-open','false');
        }else{
          $('.toggle', $title).remove();
          $title.closest('.cf7sg-container.cf7sg-collapsible').removeClass('with-toggle').removeAttr( 'data-group').removeAttr( 'data-open');
        }
        return true;
      }else if($target.is('ul.cf7-sg-tabs-list li label input[type="text"]')){ //------- Tabs title
        $target.parent().siblings('a').text($target.val());
        return true;
      }else if($target.is('label.table-row-button input')){
        $target.closest('.cf7sg-container.cf7-sg-table').attr('data-button',$target.val());
        return true;
      }
      
    }); //end $grid.on('change');

    //grid click event delegation
    $gridEditor.on('click', function(event){
      let $target = $(event.target);
      switch(true){
        case $target.is('input[type="text"]:visible'):
        case $target.is('textarea:visible'): //click on a field.
        case $target.is('select:visible'):
        case $target.is('.centred-menu .cm-item.disabled'):
          return true;
          break;
      }
      //close any open row/column controls
      // toggleControl();
      //close any column size popups
      toggleCentredMenus($target);
      /* ---------------------------------------------------------------------------FORM CONTROLS */
			if( $target.is('.dashicons-admin-generic.form-control') ){ //------------------show controls modal
				let $form = $target.closest('.cf7sg-form-ctrls'),
				  $gridModal=  $('#cf7sg-grid-modal').html($('#cf7sg-grid-modal-tpl').html()),
					$innerSection = $gridModal.children('section.cf7sg-form-ctrls'),
					$type = $('.cf7sg-switch-vertical > input', $innerSection);

				$innerSection.siblings('section').remove();
        $gridModal.modal();

				if($form.is('.multiple')){
					$type.filter('#svfmulti').get(0).checked=true;
					$('.cf7sg-multi-form',$innerSection).show();

				} 
				$innerSection.change('input', (e)=>{
					let $t = $(e.target), type='', $s;
					switch(true){
						case $t.is('#svfsingle'): //single form.
							$('.cf7sg-multi-form',$innerSection).hide();
							$form.removeClass('multiple');
							//revert to single form.
							$grid.prepend($('.cf7sg-slide >.cf7sg-row > .cf7sg-col > .cf7sg-container', $grid).remove());
							$('.cf7sg-slider.cf7sg-container', $grid).remove();
							$grid.fireGridUpdate('remove','slider-section');
							$grid.siblings('.add-item-button').removeClass('add-slide-button').addClass('add-row-button');
							break; 
						case $t.is('#svfsmulti'): //multistep form.
							$('.cf7sg-multi-form',$innerSection).show(); //show additional options.
							$form.addClass('multiple');
							//wrap existing form into a new slider container without any buttons.
							$grid.insertNewRow($grid.children().remove(), '#grid-multistep-container .cf7sg-slider', 'append');
							//change the add row button to add slide.
							$grid.siblings('.add-item-button').removeClass('add-row-button').addClass('add-slide-button');
							//add row button to first slide.
							$('.cf7sg-slide.cf7sg-container > .cf7sg-row > .cf7sg-col',$grid).append($rowTemplt.find('.add-row-button').clone());
							$s = $grid.find('.cf7sg-slider');
							$s.fireGridUpdate('add','slider-section');
							$s = $('.cf7sg-slide',$s).children('.cf7sg-row');
							/** @since 4.13.0 display auto scroll helper code */
							$s.children('.grid-ctrls').children('.php-icon').show().attr('data-search', 'li.cf7sg-slider');
							$s = $s.children('.cf7sg-col');
							$s.children('.add-item-button').removeClass('add-field-button').addClass('add-row-button')
							break;
						case $t.is('#cf7sg-uifs-dots'): //dots.
							
							break; //noting to do here.
						case $t.is('#cf7sg-uifs-next'): //next button.

							break;
						case $t.is('#cf7sg-uifs-prev'): //next button.

							break;
						case $t.is('#cf7sg-uifs-submit'): //submit button.

							break;
					}
				});
        return true;

			}else if( $target.is('.dashicons-admin-generic.row-control') ){ //------------------show controls modal
				let cl = $target.closest('.ui-grid-ctrls').attr("class"),
				  $container = $target.closest('.cf7sg-container'), //the current row modal settings.
					$gridModal=  $('#cf7sg-grid-modal').html($('#cf7sg-grid-modal-tpl').html()),
					$innerSection = $gridModal.children('section.grid-ctrls').attr('class',cl),
					$type = $('.cf7sg-switch-vertical > input', $innerSection);
				
				$innerSection.siblings('section').remove();
        $gridModal.modal();
				//populate conditional groups if any.
        if($grid.is('.cf7-conditional-group') && $container.data('conditional-group')){
          $('input#conditional-grp-name', $gridModal).val($container.data('conditional-group'));
          $('input#conditional-grp', $gridModal).get(0).checked=true;
        }
				switch(true){
					case $innerSection.is('.cf7sg-ui-row'): //default row.
						$('input#svrow', $gridModal).get(0).checked = true;
						$type.prop('disabled', false); //disable the row types.
						break;
					default: //other row type.	
						let type = $innerSection.attr('class').replace('grid-ctrls', '').trim();
						type = type.match(/cf7sg\-(.*)\-ctrls/); //tabs row.
						$type.filter('input#sv'+type[1]).get(0).checked = true;
						$type.not('#svrow').prop('disabled', true); //disable the row types.
						break;
				}

				
				$innerSection.change('input', (e)=>{
					let $t = $(e.target), type='';
					switch(true){
						case $t.is('.cf7sg-uirs-tab'): //tab change.
							break; //noting to do here.
						case $t.is('.cf7sg-uirs-rowtype'): //change row type.
							//convert row
							switch(true){
								case $t.is('#svrow'):
									$container.convertUIRow();
									$innerSection.attr('class','grid-ctrls cf7sg-row-ctrls');
									$type.filter(':disabled').prop('disabled', false); //enable the row types.
									break;
								default:
									type = $t.attr('id').replace('sv','');
									$container.convertUIRow(type);
									$innerSection.attr('class',`grid-ctrls cf7sg-${type}-ctrls`);
									$type.not('#svrow').prop('disabled', true); //disable the row types.
									break;
							}
							break;
						case $t.is('#conditional-grp') && !$t.is(':checked'): //confitional group.
							$container.removeAttr('data-conditional-group');
							$('#conditional-grp-name', $gridModal).val('');
							$grid.trigger('cf7sg-cf7tag-update'); //for other plugins.
							break;
						case $t.is('#conditional-grp-name'):
							$container.attr('data-conditional-group', $t.val());
							$grid.trigger('cf7sg-cf7tag-update'); //for other plugins.
							break;
					}
				});
        return true;
			}else if( $target.is('.dashicons-admin-generic.column-control') ){ //------------------show controls modal
        let $container = $target.closest('.cf7sg-col'), //the current row modal settings.
					$gridModal=  $('#cf7sg-grid-modal').html($('#cf7sg-grid-modal-tpl').html()),
					$innerSection = $gridModal.children('section.grid-ctrls').addClass('cf7sg-ui-col'),
					$type = $('.cf7sg-switch-vertical > input', $innerSection);
				
				$innerSection.siblings('section').remove();
        $gridModal.modal();
				$('input#cf7sg-uirs-col', $innerSection).prop('checked',true); //general col tab.
				//populate conditional groups if any.
        if($grid.is('.cf7-conditional-group') && $container.data('conditional-group')){
          $('input#conditional-grp-name', $gridModal).val($container.data('conditional-group'));
          $('input#conditional-grp', $gridModal).get(0).checked=true;
        }
				//setup the column type
				$type = $type.filter('.cf7sg-uics-ctrl input').prop('disabled',false);
				$type.filter('#svcfield').get(0).checked=true; //by dfault.
				//setup the column size and offset
				$innerSection.setColumnSettingsModal($container);
				//setup the col type.
				switch(true){
					case $container.is('.cf7sg-grid'): //alreasdy multi field grid.
					case $container.is('.cf7sg-inner-grid *'): //alreasdy inner grid.
						$type.filter('#svcgrid').prop('disabled',true);
						break;
					case $container.is('.cf7sg-inner-grid'): 
						$type.filter('input#svcgrid').get(0).checked=true;
						$type.filter('#svcform').prop('disabled',true);
						break;
					case $container.is('.cf7sg-ext-form'):
						$type.filter('input#svcform').get(0).checked=true;
						$type.filter('#svcgrid').prop('disabled',true);
						break;
				}

				//listen for changes
				let colSize =  $('#cf7sg-uisc-size',	$innerSection).val(),
					colOff = $('#cf7sg-uisc-off',	$innerSection).val();

				$innerSection.change('input', (e)=>{
					let $t = $(e.target), type='';
					switch(true){
						case $t.is('#conditional-grp') && !$t.is(':checked'): //confitional group.
							$container.removeAttr('data-conditional-group');
							$('#conditional-grp-name', $gridModal).val('');
							$grid.trigger('cf7sg-cf7tag-update'); //for other plugins.
							break;
						case $t.is('#conditional-grp-name'):
							$container.attr('data-conditional-group', $t.val());
							$grid.trigger('cf7sg-cf7tag-update'); //for other plugins.
							break;
						case $t.is('#cf7sg-uisc-size'): //column size.
							$container.changeColumnSize(colSize, $t.val());
							colSize = $t.val();
							$innerSection.setColumnSettingsModal($container);
							break;
						case $t.is('#cf7sg-uisc-off'): //column offset.
							$container.changeColumnOffset(colOff, $t.val());
							colOff = $t.val();
							$innerSection.setColumnSettingsModal($container);
							break;
						case $t.is('#svcfield'): // default column type.
							$container.convertUIColumn();
							// $innerSection.attr('class','grid-ctrls cf7sg-row-ctrls');
							$type.filter(':disabled').prop('disabled', false); //enable the col types.
							break;
						case $t.is('.cf7sg-uirs-coltype'): //other column type.
							type = $t.attr('id').replace('svc','');
							$container.convertUIColumn(type);
							// $innerSection.attr('class',`grid-ctrls cf7sg-${type}-ctrls`);
							$type.not('#svcfield',$gridModal).prop('disabled', true); //disable the col types.
						break;
					}
				});
        return true;
      }
      
      /* --------------------------------SLIDER controls ------------------*/
      if($target.is('.add-slide-button *')){ //add slide
        //add a new empty slide with a new row after the last one, with a button.
        $('.cf7sg-slide.cf7sg-container', $grid).last().insertNewRow('', '#grid-multistep-container .cf7sg-slide', 'after');
				return true;
      }
      /*
        Row controls
        ----------------------------------------------------------ROW CONTRLS
      */
      let $parentRow,$parentContainer, $parentColumn;
      if($target.is('.dashicons-trash.row-control')){ //--------TRASH
         $parentContainer = $target.closest('.cf7sg-container');
        let $parent = $parentContainer.parent();
        $parentContainer.remove();
        if( $parent.is('.cf7sg-col') ) { //verify is this is the last row being deleted
          if( 0 == $parent.children('.cf7sg-container').length ){
            //add a text area to the column
            $parent.children('.grid-column').append('<textarea class="grid-input"></textarea>');
          }
        }
        return true;
      }else if($target.is('.add-row-button *')){ //-----------ADD Row
        let $row = $target.closest('.add-row-button').prev('.cf7sg-container'),$added;
        if($target.is('.button *')) $target = $target.closest('.button');
        //default, last button
        if($row.length === 0) $row = $grid.children('.cf7sg-container').last();
        switch(true){
          case $target.is('.add-row'):
            $added = $row.insertNewRow(); //without button.
            $added.find('.add-row-button').remove();
            break;
          case $target.is('.add-collapsible'):
            $added = $row.insertNewRow('', '#grid-collapsible', 'after')
            $added.attr('id',randString(6));//.find('.add-row-button').remove();
            $added.fireGridUpdate('add','collapsible-row');
            break;
          case $target.is('.add-table'):
            $added = $row.insertNewRow(); //without button.
            $added.find('.add-row-button').remove();
            $added.find('.cf7sg-row').addClass('cf7-sg-table').find('.grid-ctrls').attr('class','grid-ctrls cf7sg-table-ctrls');
            $added.addClass('cf7-sg-table').attr('id', 'cf7-sg-table-'+(new Date).getTime() )
            $added.fireGridUpdate('add','table-row');
            break;
          case $target.is('.add-tab'):
            $added = $row.insertNewRow('','#grid-tabs', 'after');
            $added.attr('id','cf7-sg-tab-'+(new Date).getTime());

            $added.fireGridUpdate('add','tabbed-row');
            break;
        }
        return true;
      }else if( $target.is('.dashicons-no-alt.row-control') ) { //----------------hide controls
        //take care by toggleControl
        return true;
      }else if($target.is('input.slider-control')){
        $parentContainer = $target.closest('.cf7sg-container');
        $parentRow = $target.closest('.cf7sg-row');
        if($target.is(':checked')){
          $parentContainer.addClass('cf7sg-slider-controls').fireGridUpdate('add','slider-control');
          $parentRow.addClass('cf7sg-submit-controls');
          $parentRow.before('<span class="button slider-control slider-prev">Prev</span>');
          $parentRow.after('<span class="button slider-control slider-next">Next</span>');
        }else{
          $parentContainer.removeClass('cf7sg-slider-controls').fireGridUpdate('remove','slider-control');
          $('.button.slider-control',$parentContainer).remove();
          $parentRow.removeClass('cf7sg-submit-controls');
        }
        return true;
      }


      /*
        Column controls, show/hide contols, add column, and refresh select dropdowns for colum size on addition
      */

      if( $target.is('.cf7sg-col')){
        $parentColumn = $target;
      }else{
        $parentColumn = $target.closest('.cf7sg-col');
      }
      //verify which target was clicked
      if($target.is('.add-field-button *')){
        //check if the button has row siblings.
        $target = $target.closest('.add-field-button');
        if($target.siblings('.cf7sg-row').length == 0){ //convert column to grid.
          let $field = $('.cf7-field-inner, .grid-input', $parentColumn).remove(),
            $col = $('.grid-column',$colTemplt).clone();
          $parentColumn.addClass('cf7sg-grid'); //label as grid
          //cleanup new col controls.
          $('.cf7-field-inner, .grid-input',$col).remove();
          $col = $('<div class="cf7sg-col full"></div>').append($col);
          $col.children('.grid-column').append($field);
          $target.closest('.cf7sg-container').addClass('cf7sg-grid');
          $target.insertNewRow($col, '#grid-row .cf7sg-row', 'before').find('.add-item-button').remove(); //insert row without container before button, without an extra button.
        }
        let $row = $target.siblings('.cf7sg-row').last().insertNewRow('', '#grid-row .cf7sg-row', 'after');
        $row.find('.add-item-button').remove(); //insert a new row after the last one without an extra button.
        //launch the field modal.
        $row.find('.cf7-field-inner').first().showUIfield();
        return true;
        
      }else if( $target.is('.centred-menu.column-setting *') ) { //----- show column sizes
        let $menu = $target.closest('.centred-menu');
        if($menu.is('.show')){ 
          $menu.css('--cf7sg-cm-val', $target.data('cmi'));
          let validation = ['dummy'];
          if( $menu.is('.column-offset') ){
            validation = offsets;
            switch($target.data('cmi')){
              case 0:
                $menu.addClass('unset');
                break;
              default:
                $menu.removeClass('unset');
                break;
            }
          }else if( $menu.is('.column-size') ){
            validation = columnsizes;
          }
          let classList = $parentColumn.get(0).classList;
          for(let idx=0; idx<classList.length; idx++){
            if(validation.indexOf(classList.item(idx)) > -1){
              $parentColumn.removeClass(classList.item(idx));
            }
          }
          $parentColumn.addClass($target.data('cmv'));
        }else{//filter the option list before opening
          $parentColumn.filterColumnControls();
        }
        $menu.toggleClass('show'); //toggle menu.
        return true;
      }else if( $target.is('.php-icon.column-control') ) { //--------show hooks
        let $helper =$('<div class="helper-popup">').html( $('#grid-helper').html()),
          $copy = $('.copy-helper', $helper),
          field = $target.data('field'),
          tag = $target.data('tag'),
          search = $target.data('search'),
          $hooks = $(search, '#fieldhelperdiv').clone();
        $target.after($helper)
        $('.cf7sg-helper-list', $helper).append($hooks);
        $('a.helper', $helper).each(function(){
          new Clipboard($(this)[0], {
            text: function(trigger) {
              let $target = $(trigger);
              let text = $target.data('cf72post');
              //get post slug
              let key = $('#post_name').val();
              text = text.replace(/\{\$form_key\}/gi, key);
              text = text.replace(/\{\$form_key_slug\}/gi, key.replace(/\-/g,'_'));
              text = text.replace(/\{\$field_name\}/gi, field);
              text = text.replace(/\{\$field_name_slug\}/gi, field.replace(/\-/g,'_'));
              text = text.replace(/\{\$field_type\}/gi, tag);
              text = text.replace(/\[dqt\]/gi, '"');
              return text;
            }
          });
          $(this).append($copy.clone());
        });
        $helper.click('a.helper, .dashicons-no-alt', function(e){
          $(this).remove();
        });
        return true;
      }else if( $target.is('.js-icon.column-control') ) { //--------show js hooks
        let $helper =$('<div class="helper-popup">').html( $('#grid-js-helper').html()),
          $copy = $('.copy-helper', $helper),
          field = $target.data('field'),
          tag = $target.data('tag'),
          search = $target.data('search'),
          $hooks = $(search, '#fieldhelperjs').clone();
        $target.after($helper);
        $('.cf7sg-helper-list', $helper).append($hooks);
        $('a.helper', $helper).each(function(){
          new Clipboard($(this)[0], {
            text: function(trigger) {
              let $target = $(trigger);
              let text = $target.data('cf72post');
              //get post slug
              let key = $('#post_name').val();
              text = text.replace(/\{\$form_key\}/gi, key);
              text = text.replace(/\{\$form_key_slug\}/gi, key.replace(/\-/g,'_'));
              text = text.replace(/\{\$field_name\}/gi, field);
              text = text.replace(/\{\$field_name_slug\}/gi, field.replace(/\-/g,'_'));
              text = text.replace(/\{\$field_type\}/gi, tag);
              text = text.replace(/\[dqt\]/gi, '"');
              return text;
            }
          });
          $(this).append($copy.clone());
        });
        $helper.click('a.helper, .dashicons-no-alt', function(e){
          $(this).remove();
        });
        return true;
      }else if($target.is('.dashicons-editor-code.column-control') ){ //goto html code
        let $focus = $target.closest('.cf7sg-col');
        //toggle cf7sgfocus class on inner field to focus on.
        if($focus.is('.cf7sgfocus')){
          $focus.removeClass('cf7sgfocus');
        }else{
          $('.cf7sgfocus', $grid).removeClass('cf7sgfocus');
          $focus.addClass('cf7sgfocus');
        }
        //move to text editor.
        $('#form-editor-tabs').tabs('option',{ active:1});
        $('body').addClass('disable-scroll');
        return true;
      }else if($target.is('.dashicons-trash.column-control') ){ //-------------------delete column
        let $row = $parentColumn.closest('.cf7sg-row');
        if($row.is('.cf7sg-container > .cf7sg-row > .cf7sg-col > .cf7sg-row') && $parentColumn.is('.full')){
          let $sibs = $row.siblings('.cf7sg-row');
          $row.parent().fireGridUpdate('remove','column');
          $row.remove();
          //check if single siblings row.
          if(1===$sibs.length){//convert back to simple column
            $parentColumn = $sibs.parent();
            $('.grid-column',$parentColumn).append($sibs.children('.cf7sg-col').children('.grid-column').children('.cf7-field-inner'));
            $sibs.remove();
            $parentColumn.closest('.cf7sg-container').removeClass('cf7sg-grid');
            $parentColumn.removeClass('cf7sg-grid');
          }
        }else{
          $parentColumn.remove();
          $row.fireGridUpdate('remove','column');
        }
        return true;
      }else if($target.is('.external-form')){ //---------------- insert cf7 form
        //close the grid-control box
        $target.parent().hide();
        $target.parent().siblings('.dashicons-no-alt').hide();
        $target.parent().siblings('.dashicons-admin-generic').show();
        //replace container with form selector
        $target.closest('.cf7sg-container').after($('#grid-cf7-forms').html());
        $target.closest('.cf7sg-container').remove();
        return true;
      }else if( $target.is('.dashicons-menu-alt.row-control') ){ //--------------------add column
        $parentColumn = $target.parent().siblings('.cf7sg-col').last();
        $parentRow = $parentColumn.closest('.cf7sg-row');
        let classList, idx , columns, row, newSize=0, createColumn = true, total = 0;
        let sizes = [], $newColumn = $('<div class="cf7sg-col"></div>');
        //is the row filled up?
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
          let newClass = columnsizes[newSize];
          $parentRow.children('.cf7sg-col').each(function(index){
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
        $newColumn.append( $colTemplt.html() );
        if(cf7grid.ui){
          $('textarea.grid-input' ,$newColumn).hide();
        }else{
          $('div.cf7-field-inner', $newColumn).hide();
        }
        $newColumn.changeColumnSize('',columnsizes[newSize]);
        $parentColumn.after($newColumn);
        return true;
      }else if( $target.is('.accordion-rows.column-control') ){ /** @since 3.4.0 enable accordion */
        if($target.is(':checked')){
          $parentColumn.addClass('cf7sg-accordion-rows').removeClass('cf7sg-slider-section').removeAttr("data-next data-prev data-submit data-dots");
          $parentColumn.closest('.cf7sg-container').removeClass('cf7sg-slider');
          $target.parent('label').siblings('.grouping-option').children(':input').prop('checked', false);
          $target.fireGridUpdate('add','accordion-rows');
          //hide toggle checkbox.
          $('input[type="checkbox"]', $parentColumn.children('.cf7sg-collapsible').children('.cf7sg-collapsible-title') ).hide().next('span').hide();
        }else{
          $target.closest('.cf7sg-col').removeClass('cf7sg-accordion-rows');
          $target.fireGridUpdate('remove','accordion-rows');
          //show toggle checkbox.
          $('input[type="checkbox"]', $parentColumn.children('.cf7sg-collapsible').children('.cf7sg-collapsible-title') ).show().next('span').show();
        }
        return true;
      }
      /*
       Column UI fields
      */
      if(cf7grid.ui){
        //close any open ui fields
        closeAlluiFields();
        if($target.is('.cf7-field-inner p.content')){ //show modal
          $target.parent().showUIfield();
          return true;
        }else if($target.is('.cf7-field-inner')){
          $target.showUIfield();
          return true;
        }else if($target.is('.cf7-field-inner span.dashicons')){
          //field will be closed by closeAlluiFields
        }else if('none'!==$('#wpcf7-form').css('display') && !$target.is('#wpcf7-form')){
          changeTextarea();
        }
      }
    });//end $grid.on('click').

    // capture tab and move to the next field.
    if(cf7grid.ui){
      $grid.keydown('div.cf7-field-inner', function(e){
        if(9 !== (e.which || e.keyCode) ) return; //check for tab.
        let $target = $(e.target);
        if($target.is('div.cf7-field-inner :input')){
          $target.closeUIfield();
          $target = $target.parent(); //switch to parent.
          let found = false, stopSearch = false, $next = $grid.find('div.cf7-field-inner').filter(function(i,el){
            if(stopSearch) return false;
            if(found) return (stopSearch = true) ;
            else if($(el).is($target) ) found = true;
          });
          if($next){
            $next.showUIfield();
            e.preventDefault(); //stop tab focus on this element.
          }
        }
      });

    }
    //general inputs into the textareas will trigger form change event
    $grid.on('input selectionchange propertychange', 'textarea', function(event){
      let $target = $(event.target);
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
        let $txta = $('textarea#wpcf7-form');
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
        let $focus = $('.cf7sgfocus', $grid);
        if($focus.length>0){
          let scrollPos = $focus.offset().top - $(window).height()/2 + $focus.height()/2;
          //console.log(scrollPos);
          $(window).scrollTop(scrollPos);
          $focus.removeClass('cf7sgfocus');
        }
      }

    });
    //make columns sortable
    sortableRows();
    //make rows sortable
    $('.cf7sg-col', $grid).sortable({
      //placeholder: "ui-state-highlight",
      handle:'.grid-ctrls > .dashicons-move',
      axis: 'y',
      //containment:'parent',
      items: '> .cf7sg-container', //.cf7sg-col.cf7-sg-tabs > .cf7sg-row',
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
    let text = '';
    let possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    for(let i=0; i < n; i++){
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
  // function toggleControl($ctrl){
  //   if(typeof $ctrl == 'undefined') $ctrl = $('.grid-controls:visible', $gridEditor);
  //   $ctrl.each(function(){
  //     let $this = $(this);
  //     if($this.is(':visible')){ //close
  //       $this.hide();
  //       $this.siblings('.dashicons-no-alt').hide();
  //       $this.siblings('.dashicons-admin-generic').show();
  //     }else{ //open
  //       $this.show();
  //       $this.siblings('.dashicons-no-alt').show();
  //       $this.siblings('.dashicons-admin-generic').hide();
  //     }
  //   });
  //   //close helper popups.
  //   $('.column-control+.helper-popup').remove();
  //   //close conditional group
  //   $ctrl = $('span.cf7-conditional-group:visible', $gridEditor).hide();
  // }
  //close any column size popups
  function toggleCentredMenus($t){
    let $menus = $('.centred-menu.show', $gridEditor);
    if($t.length>0 && $t.is('.centred-menu *')) $menus = $menus.not($t.closest('.centred-menu'));
    $menus.toggleClass('show');
  }
  //trigger a change on the textarea#wpcf7-form field if its value has changed
  function changeTextarea(finalise = false){
    if(cf7grid.ui){
      let $lastTA = $('#wpcf7-form');
      if($lastTA.length >0 && wpcf7Value !== $lastTA.val()){
        wpcf7Value = '';
        $lastTA.trigger('change');
      }else if(finalise){
        $grid.trigger('cf7grid-form-ready'); //codemirror initialisation
      }
    }
  }
  function sortableRows( $newRow='' ){
    if($newRow.length>0 && !$newRow.is('.cf7sg-slider')){
      $('.cf7sg-row.ui-sortable', $grid).sortable('destroy');
    }
    $('.cf7sg-row', $grid).sortable({
      //placeholder: "ui-state-highlight",
      //containment:'.cf7sg-row',
      handle:'.grid-column > .dashicons-move',
      connectWith: '.cf7sg-row',
      helper:'clone',
      items: '> .cf7sg-col',
      receive: function( event, ui ) {
        //ui.item validate column size relative to new row columns.
        //ui.sender original row. cnacel if column does not fit in new row.
        //$targetRow has more than 12 columns then cancel.
        let $targetRow = $(event.target);//receiving row
        let row = $targetRow.getRowSize();

        if( row.length > 12){
          ui.sender.sortable('cancel');
          let $warning = $('<span class="cf7sg-warning">Not enough space in this row to add column</span>');
          $targetRow.after($warning);
          $warning.delay(2000).fadeOut('slow', function(){
            $(this).remove();
          });
        }else{
          //make sure the row controls is at the end.
          let control = $targetRow.children('.grid-ctrls').remove();
          $targetRow.append(control);
        }
      }
    });
  }
  /* some function definitions...*/
	$.fn.convertUIColumn = function(type){
		let $col = this, id='', $added=null;
		if(!$col.is('.cf7sg-col')) return false;
		if('undefined' == typeof type){ //convert back to a normal column.
			switch(true){
				case $col.is('.cf7sg-inner-grid'):
					$col.removeClass('cf7sg-inner-grid');
					let $inner = $col.children().not('.grid-column').remove();
					$inner = $('.cf7sg-col',$inner).first().find('.grid-column');
					if($inner.is('.field-set')){
						$col.children('.grid-column').addClass('field-set');
					}
					$inner = $inner.find('.cf7-field-inner, .grid-input');
					$col.children('.grid-column').append($inner).after($('.add-field-button', $colTemplt).clone());
					break;
				case $col.is('.cf7sg-ext-form'):
					$('.cf7sg-external-form', $col).remove();
					$col.removeClass('cf7sg-ext-form');
					$col.children('.grid-column').append($('.cf7-field-inner,.grid-input', $colTemplt).clone());
					$col.append($('.add-field-button', $colTemplt).clone());
					break;
			}
		}else{
			switch(type){
				case 'grid': //conver to inner grid.
					$col.addClass('cf7sg-inner-grid');
					//create a new column for the inner row.
					let $c = $('.grid-column',$colTemplt).clone();
					if($col.children('.grid-column').is('.field-set')){
						$c.addClass('field-set');
						$col.children('.grid-column').removeClass('field-set');
					}
          $('.cf7-field-inner, .grid-input',$c).remove(); //remove the controls, and replace with target column entries.
          $c = $('<div class="cf7sg-col full"></div>').append($c);
          $c.children('.grid-column').append($('.cf7-field-inner, .grid-input', $col).remove()).after($('.add-item-button', $col).remove());
          $col.insertNewRow($c, '#grid-row', 'append');
					break;
				case 'form':
					$col.addClass('cf7sg-ext-form');
					if($col.children('.grid-column').is('.field-set')) $col.children('.grid-column').removeClass('field-set');
					$('.cf7-field-inner, .grid-input, .add-item-button', $col).remove();
					$col.insertNewRow('', '#grid-cf7-forms', 'append');
					break;
			}
		}
		return $col;
	}
	$.fn.convertUIRow = function(type){
		let $row = this, id='', $added=null;
		if(!$row.is('.cf7sg-container')) return false;
		if('undefined' == typeof type){ //convert back to a normal row.
			switch(true){
				case $row.is('.cf7-sg-table'): //convert back to a row.
					$row.find('.cf7sg-row.cf7-sg-table').removeClass('cf7-sg-table');
					$row.removeClass('cf7-sg-table').removeAttr('id').removeAttr('data-button').fireGridUpdate('remove','table-row');
					if($row.is('.cf7-sg-table-footer')){
						$row.find('.cf7-sg-table-footer-row').remove(); 
						$row.removeClass('cf7-sg-table-footer');
					}
					break;
				case $row.is('.cf7sg-collapsible'):
					let $col = $row.children('.cf7sg-row').children('.cf7sg-col').children('.cf7sg-container').remove();
					$row.after($col);
					$row.fireGridUpdate('remove','collapsible-row');
					$row.remove();
					$row = $col;
					break;
				case $row.is('.cf7-sg-tabs'):
					let $panel = $row.find('.cf7-sg-panel').children('.cf7sg-container').remove();
					$row.after($panel);
					$row.fireGridUpdate('remove','tabbed-row');
					$row.remove();
					$row = $panel;
					break;					
			}
			$row.find('.grid-ctrls').first().attr('class','grid-ctrls cf7sg-ui-row'); //identify settings 
		}else{
			switch(type){
				case 'table':
					id = 'cf7-sg-table-'+(new Date).getTime();
					$row.children('.cf7sg-row').addClass('cf7-sg-table');
					$row.addClass('cf7-sg-table').attr('id',id).fireGridUpdate('add','table-row');
					$row.find('.grid-ctrls').first().attr('class','grid-ctrls cf7sg-table-ctrls');
					break;
				case 'coll':
					$added = $row.insertNewRow($row, '#grid-collapsible', 'after');
					$added.attr('id',randString(6));//.find('.add-row-button').remove();
					$added.fireGridUpdate('add','collapsible-row');
					$row = $added;
        	break;
				case 'tabs':
					$added = $row.insertNewRow($row, '#grid-tabs', 'after');

          id = 'cf7-sg-tab-'+(new Date).getTime();
          $added.attr('id',id);
          // $row.before($('#grid-tabs').html());
          // $row.closest('.cf7sg-col').addClass('cf7-sg-tabs');
          // $('li a', $row.siblings('ul.cf7-sg-tabs-list')).attr('href','#'+id);
          $added.fireGridUpdate('add','tabbed-row');
					$row = $added;
					break;
			}
		}
		return $row;
	}
  $.fn.trackConditionalGroups = function(s,r){
    if('undefined' == typeof s) s='group';
    if('undefined' == typeof r) r='';
    $(this).each((i,d)=>{
      if(d.classList.contains('conditional-group') && d.innerHTML.indexOf('['+s+' ')>-1){ //replace with temp group name
        d.innerHTML = d.innerHTML.replaceAll(s,r);
        return; //field condition.
      }
      let g = d.textContent.match(/\[group\s(.*)\]/);
      if(g && d.textContent.trim().indexOf('[group')<3 && g.length>1){ //conditional div content
        d.setAttribute('data-conditional-group', g[1]);
        //replace the [group ... as well as the last matching [/group].
        d.innerHTML = d.innerHTML.replace(new RegExp('\\['+s+'\\s(.*)\\]'), '').replace(new RegExp('(\\[\\/'+s+'\\])(?!.*\\1)'), '');
      }
    });
  }
  $.fn.closeUIfield = function(){
    let obj = this;
    if(!Array.isArray(this)) obj=[this];
    $.each(obj,function(idx, item){
      let $this = $(item);
      if(!$this.is('.cf7-field-inner :input:visible')){
        return $this;
      }
      if($this.parent().is('.cf7-field-type')) changeTextarea();
      $this.hide().attr('id', '');
      $this.siblings('.dashicons-no-alt').hide();
      $this.siblings('.content').show();
    });
    return true;
  }
  $.fn.showUIfield = function(){
    let $this = $(this), $field;
    if(!$this.is('.cf7-field-inner')){
      return $this;
    }
    switch(true){
      case $this.is('.cf7-sg-table-footer-row *'):
        $customModal.modal();
        $field = $this.closest('.grid-column-tip').attr('id', 'cf7sg-ui-field');
        $field = $('textarea.table-footer-tip', $field);
        $('textarea', $customModal).val($field.val());
        break;
      case $this.is('.cf7-field-html'):
        // $field = $this.closest('.grid-column');
        /** @since 5.0.0 use a modal */
        $customModal.modal();
        $this.closest('.grid-column').attr('id', 'cf7sg-ui-field');
        $('textarea#wpcf7-form').attr('id','');
        let txt = html_beautify($this.html(),{
          'indent_size': 2,
          'wrap_line_length': 0
        });
        $('textarea',$customModal).attr('id','wpcf7-form').val(txt); 
        break;
      default: //inner field
        $field = $this.closest('.grid-column').attr('id', 'cf7sg-ui-field');
        /** @since 5.0.0 use a modal */
        $modal.modal();
        if(!$field.is('.field-set')){
          $tagModal.modal({
            closeExisting: false
          });
        } 

        $('textarea#wpcf7-form').attr('id','');
        $('textarea',$modal).attr('id','wpcf7-form').val($('.cf7-field-type textarea', $field).val()); 
        //check if the field has values.
        $('#cf7sg-modal-label', $modal).val($('.cf7-field-label input', $field).val());
        $('#cf7sg-modal-desc', $modal).val($('.cf7-field-tip input', $field).val());
        wpcf7Value = $('.cf7-field-type textarea', $field).val();
          // changeTextarea();
        break;
    }
    return $this;
  }
  $.fn.html2gui = function(html){
    let $this = $(this);
    if(typeof html === 'undefined') html ='';
    if(html.length === 0){
      //get the fields from the textarea
      html = $('textarea.grid-input', $this.children('.grid-column')).text();
      if(html.length === 0){
        if($this.is('.cf7-sg-table-footer-row > .cf7sg-col')){ //footer row.
          html = $('textarea.grid-input', $this).text();
          $('.cf7-field-tip input', $this).val(html);
          $('p.content', $this).html(html);
        }
        $('textarea.grid-input', $this.children('.grid-column')).hide();
        return $this;
      }
    }
    let singleField = true;
    let search = $('<div>').append(html);
    if(seekTemplate && 1==search.children(cssTemplate).length){
      let lines = html.split(/\r\n|\r|\n/g);
      search = '';
      for(let i=0; i<lines.length; i++){
       search += lines[i].trim();
      }
      let match = templateRegex.exec(search);
      if(null !== match){
        //populate the fields
        let $field = $('div.cf7-field-label', $this);
        $('input', $field).val(match[1]);
        $('p.content', $field).html(match[1]);
        $field = $('div.cf7-field-type', $this);
        let tag = $('textarea', $field).val(match[5]).scanCF7Tag();
        $('p.content', $field).html(tag);
        $field = $('div.cf7-field-tip', $this);
        $('input', $field).val(match[6]);
        $('p.content', $field).html(match[6]);
        //hide the textarea
        $('textarea.grid-input', $this).hide();
        //reset global regex
        templateRegex.lastIndex = 0;
        return $this;
      } 
    }
    //else this html does not match our templates, treat as custom html.
    let $f = $('div.cf7-field-inner', $this);
    $f.last().after('<div class="cf7-field-inner cf7-field-html">');
    $f.remove();
    $('.cf7-field-html', $this).html(html);
    return $this;
  }

  $.fn.scanCF7Tag = function(){
    let $this = $(this);
    if(!$this.is('textarea')){
      return '';
    }
    let $parent = $this.parent(), //.cf7-field-type.
      $helper = $parent.siblings('.php-icon'),
      $jshelper = $parent.siblings('.js-icon');
    $helper.each(function(index){
      if(index>0){
        $(this).remove();
        return;
      }
      $(this).removeAttr('data-field').removeAttr('data-tag').removeAttr('data-search');
    });
    //reset helper.
    $helper = $parent.siblings('.php-icon');
    //handle jshleper
    $jshelper.each(function(index){
      if(index>0){
        $(this).remove();
        return;
      }
      $(this).removeAttr('data-field').removeAttr('data-tag').removeAttr('data-search');
    });
    //reset jshelper.
    $jshelper = $parent.siblings('.js-icon');
    //
    //kitchen-facilities limit class:cf7sg-hybriddd "slug:category:tree"
    let search = $this.val(), fMatch, match,
      label='',
      tagRegex = new RegExp(cf7TagRgxp, 'igm'),
      isRequired = false,
      type = [],
      fields = [],
      hooks = [],jshooks=[],
      tag='',
      isSubmit = false,
      count =0, counth = 0,
      field = '',
      isField = false,
      classes = $colTemplt.find('div.cf7-field-type').attr('class');

    while ( (match = tagRegex.exec(search)) !== null) {
      isRequired = (match[2]+'*' === match[1]);
      count++;
      tag = match[2];
      field='';
      if(match.length>3){
        fMatch = (new RegExp(cf7FieldRgxp)).exec(match[3]);
        if(fMatch && fMatch[1]){
          field = fMatch[1];
          isField = true;
        }
      }
      let helpers = ['cf7sg-tag-all'], jsHelpers = ['cf7sg-tag-all'];
      helpers[helpers.length] = 'cf7sg-tag-'+tag;
      jsHelpers[jsHelpers.length] = 'cf7sg-tag-'+tag;
      switch(true){
        case 'submit'==tag:
        case 'save'==tag:
          tag +='-button';
          isSubmit = true;
          break;
        case 'textarea'==tag:
          if( match[0].search(/\s[0-9]{0,3}x[0-9]{1,3}\s?/ig) <0){
            let cf7sc = match[0].replace(match[3],match[3]+' x5'); //textarea prefill.
            cf7sc = search.replace(match[0], cf7sc);
            $this.val(cf7sc);
          }
          break;
        case 'hidden'==tag: /** @since 3.2.1 fix hidden field class */
          tag+='-input';
          counth++;
          break;
        case 'group'==tag: /** @since 4.4.3 fix conditional groups within */
          isField = false;
          classes += " conditional-group";
          counth++; //don't count as field.
          break;
        case cf7grid.dynamicTags.indexOf(tag)>=0:
          let source ='';
          if(fMatch[5]) source = fMatch[6];
          else if(fMatch[8]) source = 'taxonomy';

          if(match[3].indexOf('class:tags')>-1){
            helpers[helpers.length] = 'cf7sg-tag-dynamic_select-tags';
            if(source.length>0){
              helpers[helpers.length] = 'cf7sg-tag-dynamic_select-'+source+'-tags';
            }
          }

          helpers[helpers.length] = 'cf7sg-tag-dynamic_list';
          helpers[helpers.length] = 'cf7sg-tag-dynamic_list-'+source;
          break;
        default: //tags with no field names nor irrelevant fields.
          classes += ` ${tag}`;
          break;
      }
      /** @since 3.3.0 add extension classes & include 3rd party plugins helper codes */
      if(undefined !== cf7sgCustomHelperModule[tag]){
        let ch = cf7sgCustomHelperModule[tag](search);
        if(Array.isArray(ch.php) && ch.php.length>0) helpers = helpers.concat(ch.php);
        if(Array.isArray(ch.js) && ch.js.length>0) jsHelpers = jsHelpers.concat(ch.js);
      }
      if(isField){
        type.push(tag);
        fields.push(field);
        hooks.push(helpers);
        jshooks.push(jsHelpers);
      }

      label+='['+tag+ (isRequired?'*':'') + (isField?' '+field:'')+']'; //display label.
    }
    classes += " "+ type.join(' ') + (isRequired ? ' required':'');
    field = fields.join(' ');
    let $parentColumn = $parent.closest('.cf7sg-col');
    if($parentColumn.is('[class*="cf7-tags-"]')){
      $parentColumn.removeClass(function (index, className) {
        return (className.match (/(^|\s)cf7-tags-\S+/g) || []).join(' ');
      });
    }
    if( (count-counth) >1 ){ /** @since 3.2.1 don't count hidden fields */
      classes += ' cf7-tags-'+count;
      $parentColumn.addClass('cf7-tags-'+count);
    }
    $parent.attr('class',classes);
    $parent.closest('.grid-column').addClass('field-set'); //flag for modal sequence.
    /**@since 2.0.0
    * setup fields for tag specific filters/actions.
    */
    //for each tag get corresponding set of filters.
    let helperUsed = false, jsHelperUsed=false,len = type.length, jlen=0;
    search = '';
    // console.log(hooks);
    for (let i = 0; i < len; i++) {
      for (let j=0, jlen = hooks[i].length; j<jlen; j++){
        search += 'li.'+hooks[i][j]+',';
      }
      search = search.slice(0,-1); //remove last ','
      if($( search ,$('#fieldhelperdiv')).length>0){
        //this tag has some filters.
        if(helperUsed){
          let $clone = $helper.clone();
          $helper.after($clone);
          $helper = $clone;
        }
        helperUsed = true;
        $helper.attr('data-field', fields[i]);
        $helper.attr('data-tag', type[i]);
        $helper.attr('data-search', search);
        $helper.show();
      }
      //js helpers.
      search='', jlen = jshooks[i].length;
      for (let j=0; j<jlen; j++){
        search += 'li.'+jshooks[i][j]+',';
      }
      search = search.slice(0,-1); //remove last ','
      if($( search ,$('#fieldhelperjs')).length>0){
        //this tag has some filters.
        if(jsHelperUsed){
          let $clone = $jshelper.clone();
          $jshelper.after($clone);
          $jshelper = $clone;
        }
        jsHelperUsed = true;
        $jshelper.attr('data-field', fields[i]);
        $jshelper.attr('data-tag', type[i]);
        $jshelper.attr('data-search', search);
        $jshelper.show();
      }
    }
    if(isSubmit){
      $parent.parent().addClass('submit-field');
    }

    return label;
  }
  $.fn.updateGridForm = function(){
    let $this = $(this);
    if(!$this.is('textarea.grid-input')){
      return $this;
    }
    if($this.is('.custom-html *')){
      return $this;
    }
    //extract field components to contruct html markup.
    let tip = $this.siblings('div.cf7-field-tip').find(':input').val();
    if($this.is('.table-footer-tip')){
      $this.html(tip);
      return $this;
    }
    let $label = $this.siblings('div.cf7-field-label').find(':input'),
      label = $label.val(), //label
      field = $this.siblings('div.cf7-field-type'),
      classes = field.attr('class').replace('cf7-field-type','').replace('cf7-field-inner', '').trim(),
      idx = 0;
    field = field.find('textarea.field-entry').val(); //field
    if(cf7grid.requiredHTML.length>0) idx=label.indexOf(cf7grid.requiredHTML)
    if($this.siblings('div.cf7-field-type').is('.required')){
      /** @since 2.10.4 fix for custom manual labels, allow replacement with empty span*/
      if(idx<0) idx = label.search(/<span>[\w\W]*<\/span>/g);
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
    let $cell = $('<div>').append( cf7grid.preHTML + field + cf7grid.postHTML );
    $('label', $cell).html(label);
    $('.info-tip', $cell).html(tip);
    $('.field',$cell).addClass(classes);
    //update grid input and trigger change to udpate form
    if(cf7grid.ui) $this.html($cell.html()+'\n').trigger('change');
    else $this.val($cell.html()).trigger('change');
    return $this;
  };

  $.fn.toggleSiblingUIFields = function(){
    let $this = $(this);
    if(!$this.is('div.cf7-field-inner')){
      return $this;
    }
    $this.siblings('div.cf7-field-inner').each(function(){
      let $this = $(this);
      $('p.content', $this).show();
      $(':input', $this).hide().attr('id','');
      $('span.dashicons', $this).hide();
    });
  }
  $.fn.getRowSize = function(){
    let size, off, idx, foundSize, classList,total = 0;
    let sizes = [0];
    $(this).children('.cf7sg-col').each(function(index){
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
  /** get the column size/offset, expect UI menu to be set */
  $.fn.getColumnTotalSize = function(){
    let $this = $(this);
    if(! $this.is('.cf7sg-col')){
      return 0;
    }
    let size = 0,total = 0;
    // , classList = $this.attr('class').split(/\s+/);
    let $sizes = $this.children('.grid-column').find('.column-size'), 
      $offsets = $this.children('.grid-column').find('.column-offset');
    size = $sizes.get(0).style.getPropertyValue('--cf7sg-cm-val')*1.0;
    total = $offsets.get(0).style.getPropertyValue('--cf7sg-cm-val')*1.0 + size +1;
    return {'length':total, 'size':size};
  }
  /* add new rows
	* areaCode, either HTML string or jQuery object to populate the new row.
	* type, a template id for the new row.
	* action, either after|before|append to determine where the new row is to be inserted.
	*/
  $.fn.insertNewRow = function(areaCode, type, action){
    let $this = $(this), $newRow = $rowTemplt.clone().find('.cf7sg-container');
    if(typeof areaCode === 'undefined') areaCode ='';
    if(typeof type === 'string' && type.trim().length > 0){ //redefine new row object.
      let selectors = type.split(' ');
      $newRow = $($(selectors[0]).html());
      if(selectors.length>1){ 
        selectors.shift();// remove first element.
        selectors = selectors.join(' ');
        if(!$newRow.is(selectors)) $newRow = $newRow.find(selectors); 
      }
    }
    if(typeof action === 'undefined'){ 
      action = 'after';
      if($this.is('.cf7sg-col') || $this.is('.cf7sg-row')) action = 'append';
    }
    
    //append the column controls and textarea
    if($.fn[action]) $this[action]($newRow);
    else{
      //unknown action, maybe an error.
      return $this;
    }
    //fill in any template requirements.
    $newRow = $newRow.fillCF7SGTemplate(areaCode);
    //make new row's columns sortable.
    sortableRows($newRow);
    $newRow.fireGridUpdate('add','row');
    return $newRow;
  }
  /** @since 5.0 function to fill inner templates if any */ 
  $.fn.fillCF7SGTemplate = function(inner){
    let $row = $(this),
      $tplt = $row.find('.inner-template'), 
			tplID = $tplt.html();
    //either fill with template for with inner content.
    if(typeof inner === 'undefined' || inner.length===0){ 
      if($tplt.length >0 ){ //fill template is any.
        $tplt.before($(tplID).html());
        $tplt.remove();
        //check for additional templates in newly inserted content.
        $row = $row.fillCF7SGTemplate();
      }
      return $row;
    }
    // $('.cf7sg-col', $row).last().prepend( $($('#grid-col').html()) ); //format grid column.
    //is areaCode text or jQuery object?
    if(inner instanceof jQuery){
			//remove if part of dom.
			if(inner instanceof HTMLCollection) inner = inner.remove();
			switch(tplID){
				case '#grid-row':
					$tplt.before(inner);	
					break;
				case '#grid-col':
				default: //in case no inner template for collumns.
					// $('.cf7-field-inner, .grid-input', $row).remove();
					if(inner.is('.cf7sg-col')) inner = inner.children();
					$('.cf7sg-col', $row).last().append(inner);
					break;
			}
    }else{
      //add the code to the textarea
      if(cf7grid.ui){
        $('textarea.grid-input',$row).html(inner);//.trigger('change');
        $row.html2gui(inner);
      }else{
        $('textarea.grid-input',$row).val(inner);//.trigger('change');
        $('div.cf7-field-inner', $row).hide();
      }
    }
    $tplt.remove(); //if any
    return $row;
  }
  //refresh controls select
  $.fn.changeColumnSize = function(oldSize, newSize){
		this. changeColumnDim(oldSize, newSize,'size');
  }
	$.fn.changeColumnOffset = function(oldSize, newSize){
		this. changeColumnDim(oldSize, newSize,'offset');
  }
	$.fn.changeColumnDim = function(oldSize, newSize, menu){
		if(!this.is('.cf7sg-col')) return false;
		if(['offset','size'].indexOf(menu) === -1) return false;

    if(oldSize.length > 0) this.removeClass(oldSize);
    if(newSize.length > 0) this.addClass(newSize);
    let $sizeItem = this.children('.grid-column').find(`.column-${menu} .cm-item[data-cmv="${newSize}"]`);
    $sizeItem.closest('.centred-menu').css('--cf7sg-cm-val',$sizeItem.data('cmi'));
  }
  /** Setup column size/offet in UI menu */
  $.fn.setColumnUIControl = function(){
    if(!this.is('.cf7sg-col') ) return false;

    let $col = $(this), classes = $col.get(0).classList,
      $cmSize = $col.children('.grid-column').find('.column-size'),
      $cmOffset = $col.children('.grid-column').find('.column-offset');
    
    $cmSize.css('--cf7sg-cm-val',0);//default for no classes
    $cmOffset.css('--cf7sg-cm-val',0);//default for no classes
    for(let idx = 0; idx<classes.length; idx++){
      let c = classes.item(idx), size=0;
      if('cf7sg-col'==c) continue;
      size = columnsizes.indexOf(c);
      if(size>-1) $cmSize.css('--cf7sg-cm-val',size);
      else{
        size = offsets.indexOf(c);
        if(size>-1) $cmOffset.css('--cf7sg-cm-val',size);
      }
    }
  }
	/** funtion to filter column settings size/offset dropdown list. */
	$.fn.setColumnSettingsModal = function($c){
		if(!this.is('.cf7sg-ui-col')) return false;
		if(!$c.is('.cf7sg-col')) return false;
		let $r = $c.closest('.cf7sg-row'), 
			cl = $c.get(0).classList,
      rowSize = $r.getRowSize(),
      colSize = $c.getColumnTotalSize();
		//enable all options
    $('.cf7sg-uics-ctrl option', this ).prop('disabled', false);
    
    let idx, start, free = 0;
    if(rowSize.length < 12) free = (12 - rowSize.length);
    for(idx = start = colSize.size+1; idx < columnsizes.length; idx++){
      if( idx > (free + start - 1) ){
        $(`#cf7sg-uisc-size > option[value=${columnsizes[idx]}]`, this ).prop('disabled', true);
      }
    }
    for(idx = start = colSize.length - colSize.size - 1 ;idx<= offsets.length; idx++){
      if(0===idx) continue; //0 is unset.
      if( idx > (free + start) ){
        $(`#cf7sg-uisc-off > option[value=${offsets[(idx-1)]}]`, this ).prop('disabled', true);
      }
    }
		//set size/offset
		for(idx=0; idx< cl.length; idx++ ){
			if(columnsizes.indexOf(cl.item(idx)) > -1){
				$(`#cf7sg-uisc-size > option[value=${cl.item(idx)}]`, this ).prop('selected', true);
				continue;
			}
			if(offsets.indexOf(cl.item(idx)) > -1){
				$(`#cf7sg-uisc-off > option[value=${cl.item(idx)}]`, this ).prop('selected', true);
			}
		}
		return this;
	}
  /** Functions to disable options on the column size/offset list */
  $.fn.filterColumnControls = function(){
    if( !this.is('.cf7sg-col') ) return false;

    let $col = $(this), 
      $ctrl = $col.children('.grid-column'),
      $parentRow = $col.closest('.cf7sg-row'), 
      rowSize = $parentRow.getRowSize(),
      colSize = $col.getColumnTotalSize();
    //enable all options
    $('.cm-item', $ctrl ).removeClass('disabled');
    
    let idx, start, free = 0;
    if(rowSize.length < 12) free = (12 - rowSize.length);
    for(idx = start = colSize.size+1; idx < columnsizes.length; idx++){
      if( idx > (free + start - 1) ){
        $(`.column-size .cm-item[data-cmi=${idx}]`, $ctrl ).addClass('disabled');
      }
    }
    for(idx = start = colSize.length - colSize.size - 1 ;idx<= offsets.length; idx++){
      if(0===idx) continue; //0 is unset.
      if( idx > (free + start) ){
        $(`.column-offset .cm-item[data-cmi=${idx}]`, $ctrl ).addClass('disabled');
      }
    }
    return $col;
  }
  /** @since 3.4.0 fire grid ui update events.*/
  $.fn.fireGridUpdate = function(action, element){
    let e={
      bubbles: true,
      cancelable: true,
    };
    e[action]=element;
    $(this).trigger('cf7sg-update',e);
  }

})( jQuery );

/** @since 3.3.0 custom helpers */
var cf7sgCustomHelperModule = (function (cch) {
  return cch;
}(cf7sgCustomHelperModule || {}));
