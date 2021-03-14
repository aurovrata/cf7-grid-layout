(function( $, cme, jscme, csscme ) {
  'use strict';
  /** @since 4.2.0 manage editor loading errors */
  const $codemirror = $('#cf7-codemirror'),
    $formEditor = $('#contact-form-editor'),
    $wpcf7Editor = $('textarea#wpcf7-form-hidden'),
    $editorTabs = $('#form-editor-tabs'),
    $optionals= $('#optional-editors'),
    $topTags =$('#top-tags'), $bottomTags =$('#bottom-tags');

  let eh = $formEditor.height(), ew =$formEditor.width();
  $('.loading-screen',$formEditor).css('padding','250px 5px '+(eh-100)+'px 5px');
  $('#publish').prop('disabled', true);

  window.onerror= function(msg, source, lineno, colno, error){
    let $loadScreen = $('.loading-screen',$formEditor);
    if($loadScreen.is(':visible')){
      $loadScreen.html('<div class="js-error"><p><span class="message">'+cf7sgeditor.jserror+'</span><br/><span class="error">'+msg+'</span><br/>(file: '+source+', line: <strong>'+lineno+'</strong><br/>'+error+')</p></div>');

      if($codemirror.children('.CodeMirror')){
        let load = window.confirm(cf7sgeditor.fixhtmlform);
        if(load){
          $loadScreen.hide();
          $editorTabs.children('ul').hide();
          $optionals.hide();
          $topTags.hide();
          $bottomTags.hide();
          $('#publish').prop('disabled', false);
        }
      }
    }
  }

  CodeMirror.defineMode("shortcode", function(config, parserConfig) {
    let scOverlay = {
      token: function(stream, state) {
        let ch;
        if (stream.match(/^\[([a-zA-Z0-9_]+)\*?\s?/)) {
          while ((ch = stream.next()) != null){
            if (ch == "]" ) {
              //stream.eat("]");
              return "shortcode";
            }
          }
        }else if(stream.match(/data-([a-z0-9_]+)/)){
          while ((ch = stream.next()) != null){
            if (ch == '=' ) {
              //stream.eat("]");
              return "cf7sg-attr";
            }
          }
        }
        while (stream.next() != null && !stream.match(/^\[([a-zA-Z0-9_]+)\*?\s?/, false) && !stream.match(/data-([a-z0-9_]+)/,false) ) {}
        return null;
      }
    };
    return CodeMirror.overlayMode(CodeMirror.getMode(config, parserConfig.backdrop || "htmlmixed"), scOverlay);
  });

  if(cf7sgeditor.mode.length>0) cme.setOption('mode',cf7sgeditor.mode);
  if(cf7sgeditor.theme.user.length>0) cme.setOption('theme',cf7sgeditor.theme.user);
  /** @since 4.4.4 enable tag matching in text editor, set after addon script is loaded */
  cme.setOption('matchTags', {bothTags: true});
  /** @since 4.0.0 manage user theme pref */
  let $themeRadio = $('.codemirror-theme', $codemirror);
  if($(':input:checked', $themeRadio).length>0){
    $themeRadio.on('change',':input',function(e){
      cme.setOption("theme",cf7sgeditor.theme[e.target.value]);
    });
  }else $(':input',$themeRadio).prop('disabled',true);

  $(document).ready( function(){
    let codemirrorUpdated = false, formFields={},
      $grid = $('#grid-form'),
      $jsTags = $('#js-tags'),
      gridTab = '#cf7-editor-grid', //default at load time.
      $jsCodemirror = $('#cf7-js-codemirror'),
      $cssCodemirror = $('#cf7-css-codemirror'),
      $jstext = $('textarea#cf7-form-js'),
      $csstext = $('textarea#cf7-form-css'),
      jscmUpdated = false, jsInsertAtLine = false,
      csscmUpdated = false, cssInsertAtLine = false,
      $jsThemeRadio = $('.codemirror-theme', $jsCodemirror),
      $cssThemeRadio = $('.codemirror-theme', $cssCodemirror);

    const $cf7key = $('#post_name');
    $.fn.beautify = function(cursor){
      let cm = cme, $this=$(this);
      if($this.is('#cf7-js-codemirror')) cm = jscme;
      else if($this.is('#cf7-css-codemirror')) cm = csscme;
      cm.setSelection({
        'line':cm.firstLine(),
        'ch':0,
        'sticky':null
      },{
        'line':cm.lastLine(),
        'ch':0,
        'sticky':null
      },
      {scroll: false});
      cm.indentSelection("smart");
      cm.setCursor(cm.firstLine(),0,{scroll: false});
      if('undefined' != typeof cursor && cursor.find(false)){
        const from = cursor.from(), to = cursor.to();
        cm.setSelection(CodeMirror.Pos(from.line, 0), to);
        cm.scrollIntoView({from: from, to: CodeMirror.Pos(to.line + 10, 0)});
      }
    }
    //set codemirror editor value;
    cme.setValue($wpcf7Editor.text());
    cme.setSize("100%");
    // cme.setOption('viewportMargin',Infinity);


    $wpcf7Editor.on('grid-ready', function(){ //------ setup the codemirror editor
      //codemirror editor
      $codemirror.beautify();

      cme.on('changes', function(){
        codemirrorUpdated = true;
        const disabled = $editorTabs.tabs('option','disabled');

        if(true===disabled){
          const changes = $('<div>').append(cme.getValue());
          if(0===changes.children().length || changes.children('.container').length>0){
            $editorTabs.tabs('option',{disabled:false});
            /**
            * @since 1.2.3 disable cf7sg styling/js for non-cf7sg forms.
            */
            $('#is-cf7sg-form').val('true');
          }
        }

        $formEditor.trigger('cf7sg-form-change');
      });
      //toogle body scroll off/on
      function disableBodyScroll(){
        let $post = $('form#post');
        $post.css('width',$post.width()+'px');
        $('body').addClass('disable-scroll')
      }
      function enableBodyScroll(){
        $('form#post').css('width','auto');
        $('body').removeClass('disable-scroll')
      }
      //create tabs
      $editorTabs.tabs({
        beforeActivate: function (event, ui){
          enableBodyScroll();
          //update the codemirror panel
          let panel = '';
          if(ui.newPanel instanceof jQuery) panel = '#'+ui.newPanel.attr('id');
          else if('undefined' != typeof ui.newPanel.selector) panel = ui.newPanel.selector;
          else console.log('CF7 GRID EDITOR: unknown tab ui panel object');

          if('#cf7-codemirror' == panel){
						//finalise any changes in the grid form editor
            $grid.on('cf7grid-form-ready', function(){
                let code = $grid.CF7FormHTML();
                if($grid.children('.container').length > 0){ //beautify.
	              code = html_beautify(code ,
    	              {
    	                'indent_size': 2,
    	                'wrap_line_length': 0
    	              });
                }
	            cme.setValue(code);
              // cme.refresh();
	            //reset the codemirror change flag
	            codemirrorUpdated = false;
	            //remove id from textarea
	            $('textarea#wpcf7-form').attr('id', '');
	            //change the wpcf7 textarea
	            $('textarea.codemirror-cf7-update', $codemirror).attr('id', 'wpcf7-form');
	            //setup the form code in the hidden textarea
	            $wpcf7Editor.html(code);
						});
            /** @since 2.8.3 clear the codemirror textarea##wpcf7-form */
            $('textarea.codemirror-cf7-update', $codemirror).val('');
						$grid.trigger('cf7grid-form-finalise');
          }else{
            //remove id from textarea
            $('textarea#wpcf7-form').attr('id', '');
          }
        },
        activate: function( event, ui ) {
          if(ui.newPanel instanceof jQuery) gridTab = '#'+ui.newPanel.attr('id');
          else if('undefined' != typeof ui.newPanel.selector) gridTab = ui.newPanel.selector;
          else console.log('CF7 GRID EDITOR: unknown tab ui panel object');

          $topTags.show();
          $bottomTags.show();
          $jsTags.hide();
          $optionals.hide();
          $(window).scrollTop($('#form-panel').offset().top);

          switch(gridTab){
            case '#cf7-editor-grid': //grid editor.
              $optionals.show();
              if(codemirrorUpdated){
                //update the hidden textarea
                $wpcf7Editor.text(cme.getValue());
                //trigger rebuild grid event
                $grid.trigger('build-grid');
              }else{ //try to set the focus on the 'cf7sgfocus element'
                const $focus = $('.cf7sgfocus', $grid);
                if($focus.length>0){
                  const scrollPos = $focus.offset().top - $(window).height()/2 + $focus.height()/2;
                  //console.log(scrollPos);
                  $(window).scrollTop(scrollPos);
                  $focus.removeClass('cf7sgfocus');
                }
              }
              break;
            case '#cf7-codemirror': //HTML editor.
              const cursor = cme.getSearchCursor('cf7sgfocus', CodeMirror.Pos(cme.firstLine(), 0), {caseFold: true, multiline: true});

              $codemirror.beautify(cursor);
              cme.refresh();
              break;
            case '#cf7-js-codemirror': //js editor.
              $topTags.hide();
              $bottomTags.hide();
              $jsTags.show();
              let $form = $('<div>').html($grid.CF7FormHTML());
              $('.display-none', $jsTags).removeClass('show-events');
              if( $('div.container.cf7-sg-table', $form).length > 0){
                $('#table-events', $jsTags).addClass('show-events');
              }
              if( $('.container.cf7sg-collapsible', $form).not('.cf7sg-slider-section > .cf7sg-collapsible').length > 0){
                $('#collapsible-events', $jsTags).addClass('show-events');
              }
              if( $('div.container.cf7-sg-tabs-panel', $form).length > 0){
                $('#tab-events', $jsTags).addClass('show-events');
              }
              if( $('.cf7sg-slider-section', $form).length > 0){
                $('#slider-events', $jsTags).addClass('show-events');
              }

              //scan all fields.
              let cf7TagRegexp = /\[(.[^\s]*)\s*(.[^\s\]]*)[\s\[]*(.[^\[]*\"source:([^\s]*)\"[\s^\[]*|[.^\[]*(?!\"source:)[^\[]*)\]/img;
              //reset form fields.
              formFields={};
              $('.field', $form).each(function(){
                let $field = $(this), search = $field.text(), match = cf7TagRegexp.exec(search);
                while(null != match && match.length>2){
                  switch(match[1].replace('*','')){
                    case 'recaptcha':
                    case 'recaptch':
                    case 'acceptance':
                    case 'submit':
                    case 'save':
                      match=null;
                      break;//tags with no fields of interest.
                    default:
                      formFields[match[2]] = match[2];
                      if($field.is('.cf7-sg-tabs .field')) formFields[match[2]] += '_tab';
                      if($field.is('.container.cf7-sg-table .field')) formFields[match[2]] += '_row';
                      match = cf7TagRegexp.exec(search); //search next.
                      break;
                  }
                }
              });
              break;
            case '#cf7-css-codemirror': //css editor.
              $topTags.hide();
              $bottomTags.hide();
              break;
          }
        },
        create: function(e){
          //console.info("Created tab");
          $('.ui-tabs-panel:not(#cf7-editor-grid)',$editorTabs).hover(disableBodyScroll, enableBodyScroll);
          $('.loading-screen',$formEditor).hide();
          $('#publish').prop('disabled', false);
        }
      });
      /** @since 4.0 js cm editor */
      $.fn.createNewCMEditor = function(activate=false){
        let $this = $(this);
        $this.each(function(activate){
          let $this = $(this), ref, $theme, $text, cm, theme, $cm, mode,regex;
          if(!$this.is('a.button.cf7sg-cmtab')) return $this;

          ref = '#'+$this.next('div.display-none').attr('id');
          $this.attr('href',ref);
          switch(ref){
            case '#cf7-js-codemirror':
              $theme = $jsThemeRadio;
              $text = $jstext;
              cm = jscme;
              theme = cf7sgeditor.jstheme;
              $cm = $jsCodemirror;
              mode={name: "javascript", json: true};
              $this.text('JS');
              //enable helper popups.
              // regex = /(?<=\/\*)(.*?)(?=\s?\*\/)/im;
              regex = /^.*(\/\*)(.*?)(?=\s?\*\/)/im;
              $('a.helper', $jsTags).each(function(){
                let $helper = $(this), text = regex.exec($helper.data('cf72post'));
                if('undefined' != typeof text[2]){
                  $helper.parent().append('<span style="position:relative"><span class="display-none">'+text[2]+'</span></span>');
                }
              });
              break;
            case '#cf7-css-codemirror':
              $theme = $cssThemeRadio;
              $text = $csstext;
              cm = csscme;
              theme = cf7sgeditor.csstheme;
              $cm = $cssCodemirror;
              mode = "css";
              $this.text('CSS');
              break;
          }
          $theme.append('<span class="file">'+$text.data('file')+'</span>');
          //convert to css editor.
          $editorTabs.append($this.next('div.display-none').remove());
          $text.data('form', $cf7key.val());
          $this = $this.remove().wrap('<li></li>');
          $editorTabs.children('ul').append($this.closest('li'));
          //set codemirror editor value;
          cm.setValue($text.text());
          cm.setOption("mode", mode);
          cm.setSize("100%");
          $cm.on('cf7sg-screen-resize',function(){cm.refresh()});
          $cm.hover(disableBodyScroll, enableBodyScroll);
          $cm.removeClass('display-none').beautify();

          if(theme.user.length>0) cm.setOption('theme',theme.user);
          if($(':input:checked', $theme).length>0){
            $theme.on('change',':input',function(e){
              cm.setOption("theme",theme[e.target.value]);
            });
          }else $(':input',$theme).prop('disabled',true);
          $editorTabs.tabs('refresh');
          // if(activate) $this.delay(300).click();
        });
        return $this;
      }
      //add requried cm editors.
      $('a.cf7sg-cmtab.required', $optionals).createNewCMEditor();
      //else listen for user requirement.
      $optionals.click('a.cf7sg-cmtab',function(e){ $(e.target).createNewCMEditor(true) });

      jscme.on('changes', function(e, changes){
        jsInsertAtLine = false;
        let last = jscme.getLine(changes[changes.length-1].to.line);
        if(isEmpty(last) || ""==last.trim()) jsInsertAtLine = true;

        jscmUpdated = false;
        if(jscme.getValue().length>0){
          jscmUpdated = true;
          $jstext.attr('data-form', $cf7key.val()); //set current form slug.
        }
      });
      csscme.on('changes', function(e, changes){
        cssInsertAtLine = false;
        let last = csscme.getLine(changes[changes.length-1].to.line);
        if(isEmpty(last) || ""==last.trim()) cssInsertAtLine = true;

        csscmUpdated = false;
        if(csscme.getValue().length>0){
          csscmUpdated = true;
          $csstext.attr('data-form', $cf7key.val()); //set current form slug.
        }
      });
      $cf7key.on('change',function(e){
        let oldkey = $jstext.data('form'),
          newkey = $(this).val(), filepath;
        if( oldkey.length > 0 && oldkey != $cf7key.val() ){
          /* update editor js */
          jscme.setValue( jscme.getValue().replace('#'+oldkey, '#'+$cf7key.val()) );
          $jstext.attr('data-form', newkey);
          jscmUpdated = true;
        }
        //update the file name.
        oldkey =   $('.file',$jsThemeRadio).text();
        oldkey = oldkey.substring(oldkey.indexOf('js/')+3, oldkey.indexOf('.js'));
        $('.file',$jsThemeRadio).text( $jstext.data('file').replace(oldkey, newkey) );
        filepath = $jstext.data('file');
        filepath = filepath.substring(filepath.indexOf('>>')+3);
        $('.prev-file:input', $jsThemeRadio).val(filepath);
        //css cm update.
        $('.file',$cssThemeRadio).text( $csstext.data('file').replace(oldkey, newkey) );
        filepath = $csstext.data('file');
        filepath = filepath.substring(filepath.indexOf('>>')+3);
        $('.prev-file:input', $cssThemeRadio).val(filepath);

        oldkey = $csstext.data('form');
        if( oldkey.length > 0 && oldkey != $cf7key.val() ){
          /* update editor js */
          csscme.setValue( csscme.getValue().replace('#'+oldkey, '#'+$cf7key.val()) );
          $csstext.data('form', newkey);
          csscmUpdated = true;
        }
      });
      $('#js-tags').on('click','a.helper',function(e){
        let helper = $(this).data('cf72post').replace('{$cf7_key}', $cf7key.val() ), enableArrayFields=false;
        if(!$('#cf7sg-jstags-comments').is(':checked')){
          helper = helper.replace(/^\n?\s*?\/\/.*\n?/gmi,'');
        }
        if($(this).is('.all-fields')){
          let fieldsText = '\n';
          $.each(formFields, function(field, fidx){
            fieldsText += "  case '"+field+"': //"+field+" updated";
            switch(fidx){
              case field+'_tab':
                fieldsText +=", tIdx is tab index.\n";
                enableArrayFields=true;
                break;
              case field+'_row':
                fieldsText +=", rIdx is row index.\n";
                enableArrayFields=true;
                break;
              case field+'_tab_row':
                fieldsText +=", tIdx is tab index / rIdx is row index.\n";
                enableArrayFields=true;
                break;
              default:
                fieldsText +=".\n    //do something\n";
                break;
            }
            fieldsText +="    break;\n"
          });
          let arrayFields = '';
          if(enableArrayFields){
            arrayFields =  "//-----code to extract field name and tab/row index -----------\n";
            arrayFields += "let search='', tIdx=0, rIdx=0;\n";
            arrayFields += "if( $field.is('.cf7sgtab-field') || $field.is('.cf7sgrow-field') ){\n";
            arrayFields += "  $.each($field.attr('class').split(/\\s+/), function(idx, clss){\n";
            arrayFields += "    if(0==clss.indexOf('cf7sg-')){\n";
            arrayFields += "      clss = clss.replace('cf7sg-','');\n";
            arrayFields += "      search = new RegExp( '(?<='+clss+')(_tab-(\\\\d))?(_row-(\\\\d))?','gi' ).exec(fieldName);\n";
            arrayFields += "      switch(true){\n";
            arrayFields += "        case /\\d+/.test(search[2]*search[4]): //table within a tab.\n";
            arrayFields += "          tIdx = parseInt(search[2]);\n";
            arrayFields += "          rIdx = parseInt(search[4]);\n";
            arrayFields += "          break;\n";
            arrayFields += "        case /\\d+/.test(search[2]): //tab.\n";
            arrayFields += "          tIdx = parseInt(search[2]);\n";
            arrayFields += "          break;\n";
            arrayFields += "        case /\\d+/.test(search[4]): //row.\n";
            arrayFields += "          rIdx = parseInt(search[4]);\n";
            arrayFields += "          break;\n";
            arrayFields += "      }\n";
            arrayFields += "      fieldName = clss;\n";
            arrayFields += "      return false; //break out of each loop.\n";
            arrayFields += "    }\n  });\n";
            arrayFields += "}\n//------ end of code for field extraction ---------\n";
          }
          helper = helper.replace('{$array_field_extraction}', arrayFields);
          helper = helper.replace('{$list_of_fields}', fieldsText);
        }
        let line = jscme.getCursor().line;
        // if(!jsInsertAtLine) line = 0;
        switch(line){
          case 0:
            let il = jscme.lastLine();
            if(il>1 && jscme.getLine(il).indexOf('(jQuery)')<0){
              if(jscme.getLine(il-1).indexOf('(jQuery)')>0) il-=1;
            }
            jscme.setCursor({'line':il,'ch':0});
            helper += "\n";
            break;
          default:
            helper += "\n";
            break;
        }
        jscme.replaceSelection(helper);
        line = jscme.getCursor().line;
        $jsCodemirror.beautify();
        jscme.setCursor({'line':line,'ch':line.length});
      });
      /*@since 1.1.1 disable grid editor for existing cf7 forms*/
      if(0==$grid.children('.container').length){
        $editorTabs.tabs('option',{ active:1, disabled:[0]});
        /** @since 1.2.3 disable cf7sg styling/js for non-cf7sg forms.*/
        $('#is-cf7sg-form').val('false');
      }
      //update the codemirror when tags are inserted
      $('form.tag-generator-panel .button.insert-tag').on('click', function(){
        const $textarea = $('textarea#wpcf7-form');
        if($textarea.is('.codemirror-cf7-update')){
          const tag = $textarea.delay(100).val();
          cme.replaceSelection(tag);
          $textarea.val(''); //clear.
        }
      });
      /** @since 4.8.1 ensure latest HTML form is available to other tabs */
      $grid.on('cf7sg-cf7tag-update', function(e){
        //update HTML code to ensure latest UI form changes available to other tabs.
        $wpcf7Editor.html($grid.CF7FormHTML());
      })
    }); //-----------end codemirror editor setup
    $('form#post').submit(function(event) {
      const $this = $(this);
      $( window ).off( 'beforeunload' ); //remove event to stop cf7 script from warning on save
      event.preventDefault(); //this will prevent the default submit
      //close any open UI fields.
      $('.cf7-field-inner :input:visible').closeUIfield();
      let $embdedForms = '',$formNoEmbeds = '', codeMirror ='';
      if('#cf7-editor-grid' == gridTab){ //set up the code in the cf7 textarea
        const $txta = $('textarea#wpcf7-form');
        $txta.html($txta.val()+'\n');
        codeMirror = html_beautify(
          $grid.CF7FormHTML(),
          {
            'indent_size': 2,
            'wrap_line_length': 0
          }
        );
        $('textarea#wpcf7-form-hidden').html(codeMirror);
        $formNoEmbeds = $('<div>').append(codeMirror);
      }else{//we are in text mode.
        codeMirror = cme.getValue();
        $('textarea#wpcf7-form-hidden').html(codeMirror).val(codeMirror);
        $formNoEmbeds = $('<div>').append(codeMirror);
      }
      //since 1.8 remove cf7sgfocus class if present.
      $('.cf7sgfocus', $formNoEmbeds).removeClass('cf7sgfocus');
      $embdedForms = $formNoEmbeds.find('.cf7sg-external-form').remove();
      //setup sub-forms hidden field.
      const embeds = [];
      let hasTables = false, hasTabs = false, hasToggles=false;
      if($embdedForms.length>0){
        $embdedForms.each(function(){
          embeds[embeds.length] = $(this).data('form');
        });
      }
      $('#cf7sg-embeded-forms').val(JSON.stringify(embeds));
      const cf7TagRegexp = /\[(.[^\s]*)\s*(.[^\s]*)(|\s*(.[^\[]*))\]/img;
      /** @since 3.0.0 determine scripts required */
      let scriptClass ="";
      if(codeMirror.indexOf("class:sgv-")>0) scriptClass += "has-validation,";
      if(codeMirror.indexOf("class:select2")>0) scriptClass += "has-select2,";
      if(codeMirror.indexOf("class:nice-select")>0) scriptClass += "has-nice-select,";
      /** @since 4.0 enable grouping of collapsible sections as slider */
      if($('.cf7sg-collapsible', $formNoEmbeds).not('.cf7sg-slider-section > .cf7sg-collapsible').length>0){
        scriptClass += "has-accordion,";
      }
      if($('.cf7sg-slider-section', $formNoEmbeds).length>0) scriptClass += "has-slider,";
      if(codeMirror.indexOf("[benchmark")>0) scriptClass += "has-benchmark,";
      if(codeMirror.indexOf("[date")>0 || 0<codeMirror.search(/\[text([^\]]+?)class:datepicker/ig)) scriptClass += "has-date,";

      //scan and submit tabs & tables fields.
      const tableFields = [];
      $('.row.cf7-sg-table', $formNoEmbeds).each(function(){
        /**@since 2.4.2 track each tables with unique ids and their fields*/
        const unique = $(this).closest('.container.cf7-sg-table').attr('id'),
          fields = {}, search = $(this).html();
        fields[unique]=[];
        let match = cf7TagRegexp.exec(search);
        //console.log('search:'+search);
        while (match != null) {
          //ttFields[ match[2] ] = match[1];
          fields[unique][fields[unique].length] = match[2];
          //console.log('match'+match[2]);
          match = cf7TagRegexp.exec(search); //get the next match.
        }
        tableFields[tableFields.length] = fields;
        hasTables = true;
      });

      const tabFields = [];
      $('.container.cf7-sg-tabs-panel', $formNoEmbeds).each(function(){
        /**@since 2.4.2 track each tables with unique ids and their fields*/
        const unique = $(this).attr('id'),fields = {}, search = $(this).html();
        fields[unique]=[];

        let match = cf7TagRegexp.exec(search);
        while (match != null) {
          //if( -1 === tableFields.indexOf(match[2]) ) /*removed as now want to idenify fields which are both tabs and table fields*/
          fields[unique][fields[unique].length] = match[2];
          //ttFields[match[2]] = match[1];
          match = cf7TagRegexp.exec(search); //get the next match.
        }
        tabFields[tabFields.length] = fields;
        hasTabs = true;
      });
      /**
      * Track toggled fields to see if they are submitted or not.
      * @since 2.5 */

      const toggledFields = [], tabbedToggles=[], groupedToggles={};
      $('.container.cf7sg-collapsible.with-toggle', $formNoEmbeds).each(function(){
        /**@since 2.4.2 track each tables with unique ids and their fields*/
        const $toggle = $(this), unique = $toggle.attr('id'), group = $toggle.data('group'),
          fields = {}, search = $toggle.html();
        fields[unique]=[];
        if(group.length>0){
          if('undefined' == typeof groupedToggles[group] ) groupedToggles[group] = [];
          groupedToggles[group].push(unique);
        }
        let match = cf7TagRegexp.exec(search);
        while (match != null) {
          fields[unique].push(match[2]);
          match = cf7TagRegexp.exec(search); //get the next match.
        }
        toggledFields.push(fields);
        /** @since 4.0.0 differentiate toggles in tabed sections.*/
        if($toggle.is('.cf7-sg-tabs .cf7sg-collapsible')) tabbedToggles.push(unique);
        hasToggles = true;
      });
      //append hidden fields
      $this.append('<input type="hidden" name="cf7sg-has-tabs" value="'+hasTabs+'" /> ');
      $this.append('<input type="hidden" name="cf7sg-has-tables" value="'+hasTables+'" /> ');
      $this.append('<input type="hidden" name="cf7sg-has-toggles" value="'+hasToggles+'" /> ');
      const disabled = $editorTabs.tabs('option','disabled');
      $this.append('<input type="hidden" name="cf7sg-has-grid" value="'+disabled+'" /> ');
      //update script classes since v3.
      if(hasTabs) scriptClass+="has-tabs,";
      if(hasTables) scriptClass+="has-table,";
      if(hasToggles) scriptClass+="has-toggles,";
      if(!disabled) scriptClass+="has-grid,";
      $this.append('<input type="hidden" name="cf7sg-script-classes" value="'+scriptClass+'" />');

      $('#cf7sg-tabs-fields').val(JSON.stringify(tabFields));
      $('#cf7sg-table-fields').val(JSON.stringify(tableFields));
      $('#cf7sg-toggle-fields').val(JSON.stringify(toggledFields));
      $('#cf7sg-tabbed-toggles').val(JSON.stringify(tabbedToggles));
      $('#cf7sg-grouped-toggles').val(JSON.stringify(groupedToggles));
      /** @since 4.0 enable js/css */
      $jstext.text('');//empty.
      codeMirror = jscme.getValue();
      if(jscmUpdated){
        if(codeMirror.length>2) $jstext.html(codeMirror);
      }else if(codeMirror.length>1) $jstext.prop('disabled',true);
      $csstext.text('');//empty.
      codeMirror = csscme.getValue();
      if(csscmUpdated){
        if(codeMirror.length>2) $csstext.html(csscme.getValue());
      }else if(codeMirror.length>1) $csstext.prop('disabled',true);
      // continue the submit unbind preventDefault.
      /** @since 4.4.3 conditional cf7 plugin hack.*/
      if ('undefined' != typeof wpcf7cf && wpcf7cf.currentMode == 'normal' && wpcf7cf.getnumberOfFieldEntries() > 0) {
        wpcf7cf.copyFieldsToText();
      }
      $this.unbind('submit').submit();
   });
   /*
   Function to convert the UI form into its html final form for editing in the codemirror and/or saving to the CF7 plugin.
   */
    $.fn.CF7FormHTML = function(){
      const $this = $(this);
      if( !$this.is('#grid-form') ){
        return '';
      }
      const $form = $('<div>').append(  $this.html() );
      let text='';
      //remove the external forms
      const external = {};
      $('.cf7sg-external-form', $form).each(function(){
        const $exform = $(this), id = $exform.data('form');
        external[id] = $exform.children('.cf7sg-external-form-content').remove();
        $exform.children('.form-controls').remove();
      });
      //remove the row controls
      $('.row', $form).removeClass('ui-sortable').children('.row-controls').remove();

      //remove the collapsible input
      $('.container.cf7sg-collapsible', $form).each(function(){
        const $this = $(this),cid = $this.attr('id'), $title = $this.children('.cf7sg-collapsible-title');
        let text = $title.children('label').children('input[type="hidden"]').val();
        $title.children('label').remove();
        let toggle = '';
        if($this.is('.with-toggle')){
          toggle=' toggled';
        }
        text = '<span class="cf7sg-title'+toggle+'">'+text+'</span>';
        $title.html(text + $title.html());
      });
      //remove tabs inputs
      $('ul.cf7-sg-tabs-list li label', $form).remove();

      const cf7TagRegexp = /\[(.[^\s]*)\s*(.[^\s]*)(|\s*(.[^\[]*))\]/img,
        cf7sgToggleRegex = /class:cf7sg-toggle-(.[^\s]+)/i;
      //remove textarea and embed its content
      $('.columns', $form).each(function(){
        const $this = $(this), $gridCol = $this.children('.grid-column'),
          $text = $('textarea.grid-input', $gridCol);
        $this.removeClass('ui-sortable');
        if($text.length>0){
          let text = $text.text();
          //verify if this column is within a toggled section.
          const $toggle = $this.closest('.container.cf7sg-collapsible.with-toggle');
          if($toggle.length>0){
            const cid = $toggle.attr('id');
            /**
            * track toggled checkbox/radio fields, because they are not submitted when not filled.
            *@since 2.1.5
            */
            const $field = $text.siblings('div.cf7-field-type');
            let isToggled = false;

            if($field.length>0){
              if($field.is('.checkbox.required') || $field.is('.radio') || $field.is('.file.required')) isToggled = true;
            }else isToggled = true; //custom column, needs checking.

            if(isToggled){
              let search = text, match = cf7TagRegexp.exec(search);
              while (match != null) {
                switch(match[1]){
                  case 'checkbox*':
                  case 'radio':
                  case 'file*':
                    let options = '';
                    if(match.length>4){
                      const tglmatch = cf7sgToggleRegex.exec(match[4]);
                      if(tglmatch != null){
                        if(tglmatch[1] == cid) break;
                        options =match[4].replace(tglmatch[0],'class:cf7sg-toggle-'+cid);
                      }else{
                        options = 'class:cf7sg-toggle-'+cid+' '+match[4];
                      }
                    }else{
                      options = 'class:cf7sg-toggle-'+cid;
                    }
                    text = text.replace(match[0], '['+match[1]+' '+match[2]+' '+options+']');
                    //hasRadios = true;
                    break;
                }
                //console.log('match'+match[2]);
                match = cf7TagRegexp.exec(search); //get the next match.
              }
            }//end isToggled.
          }
          $this.html('\n'+text);
        }//else this column is a grid.
        $gridCol.remove();
      });
      //reinsert the external forms
      $('.cf7sg-external-form', $form).each(function(){
        const $this = $(this);
        let id = $this.data('form');
        $this.append( external[id] );
      });
      text = $form.html();
      if($grid.children('.container').length > 0){ //strip tabs/newlines
          text = text.replace(/^(?:[\t ]*(?:\r?\n|\r))+/gm, "");
      }
      return text;
    }
  });//document ready end
  //empty checks for undefined, null, false, NaN, ''
  function isEmpty(v){
    if('undefined' === typeof v || null===v) return true;
    return typeof v === 'number' ? isNaN(v) : !Boolean(v);
  }
})( jQuery, codeMirror_5_32, jsCodeMirror_5_32, cssCodeMirror_5_32);
