/**
 Javascript to handle Codemirror editor
 Event 'cf7sg-form-change' fired on #contact-form-editor element when codemirror changes occur
*/
(function( $ ) {

	$(document).ready( function(){
    var $codemirror = $('#cf7-codemirror');
    var $wpcf7Editor = $('textarea#wpcf7-form-hidden');
    var codemirrorUpdated = false;
    var $grid = $('#grid-form');
    var gridTab = '#cf7-editor-grid'; //default at load time.
    var cmEditor ;

    $wpcf7Editor.on('grid-ready', function(){ //------ setup the codemirror editor
      //codemirror editor
      CodeMirror.defineMode("shortcode", function(config, parserConfig) {
        var cf7Overlay = {
          token: function(stream, state) {
            var ch;
            if (stream.match(/^\[([a-zA-Z0-9_]+)\*?\s?/)) {
              while ((ch = stream.next()) != null)
                if (ch == "]" ) {
                  //stream.eat("]");
                  return "shortcode";
                }
            }
            while (stream.next() != null && !stream.match(/^\[([a-zA-Z0-9_]+)\*?\s?/, false)) {}
            return null;
          }
        };
        return CodeMirror.overlayMode(CodeMirror.getMode(config, parserConfig.backdrop || "htmlmixed"), cf7Overlay);
      });
      cmEditor = CodeMirror( $codemirror.get(0), {
        value: $wpcf7Editor.text(),
        extraKeys: {"Ctrl-Space": "autocomplete"},
        mode:  "shortcode",
        lineNumbers: true,
        styleActiveLine: true,
        matchBrackets: true,
        theme: "paraiso-light",
        tabSize:2,
        lineWrapping: true,
        addModeClass: true,
        foldGutter: true,
        autofocus:false,
        gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"]
      });

      /*  TODO: enable shortcode edit at a future date
      $('.cm-shortcode',$codemirror).each(function(){
        $(this).append('<span class="dashicons dashicons-edit"></span>');
      });
      $('.dashicons-edit', $codemirror).on('click', function(){
        alert('shortcode '+ $(this).parent().text());
      });
      */
      $.fn.beautify = function(){
        cmEditor.setSelection({
          'line':cmEditor.firstLine(),
          'ch':0,
          'sticky':null
        },{
          'line':cmEditor.lastLine(),
          'ch':0,
          'sticky':null
        },
        {scroll: false});
        cmEditor.indentSelection("smart");
        cmEditor.setCursor(cmEditor.firstLine(),0);
      }
      $codemirror.beautify();

      //var cur = cmEditor.getCursor();
      //cmEditor.setCursor(99, cur.ch);

      cmEditor.on('changes', function(){
        codemirrorUpdated = true;
        var disabled = $('#form-editor-tabs').tabs('option','disabled');

        if(true===disabled){
          var changes = $('<div>').append(cmEditor.getValue());
          if(0===changes.children().length || changes.children('.container').length>0){
            $('#form-editor-tabs').tabs('option',{disabled:false});
            /**
            * @since 1.2.3 disable cf7sg styling/js for non-cf7sg forms.
            */
            $('#is-cf7sg-form').val('true');
          }
        }

        $('#contact-form-editor').trigger('cf7sg-form-change');
      });

      //create tabs
      $('#form-editor-tabs').tabs({
        beforeActivate: function (event, ui){
          //update the codemirror panel
          if('#cf7-codemirror' == ui.newPanel.selector){
						//finalise any changes in the grid form editor
            $grid.on('cf7grid-form-ready', function(){
                var code = $grid.CF7FormHTML();
                if($grid.children('.container').length > 0){ //beautify.
	              code = html_beautify(code ,
    	              {
    	                'indent_size': 2,
    	                'wrap_line_length': 0
    	              });
                }
	            cmEditor.setValue(code);
	            //reset the codemirror change flag
	            codemirrorUpdated = false;
	            //remove id from textarea
	            $('textarea#wpcf7-form').attr('id', '');
	            //change the wpcf7 textarea
	            $('textarea.codemirror-cf7-update', $codemirror).attr('id', 'wpcf7-form');
	            //setup the form code in the hidden textarea
	            $wpcf7Editor.html(code);
						});
						$grid.trigger('cf7grid-form-finalise');
          }else{
            //remove id from textarea
            $('textarea#wpcf7-form').attr('id', '');
          }
        },
        activate: function( event, ui ) {
          gridTab = ui.newPanel.selector;
          if('#cf7-editor-grid' == ui.newPanel.selector  && codemirrorUpdated){
            //update the hidden textarea
            $wpcf7Editor.text(cmEditor.getValue());
            //trigger rebuild grid event
            $grid.trigger('build-grid');
          }else if('#cf7-codemirror' == ui.newPanel.selector){
            $codemirror.beautify();
          }
        }
      });
      /*@since 1.1.1 disable grid editor for existing cf7 forms*/
      if(0==$grid.children('.container').length){
        $('#form-editor-tabs').tabs('option',{ active:1, disabled:true});
        /**
        * @since 1.2.3 disable cf7sg styling/js for non-cf7sg forms.
        */
        $('#is-cf7sg-form').val('false');
      }

      //update the codemirror when tags are inserted
      $('form.tag-generator-panel .button.insert-tag').on('click', function(){
        var $textarea = $('textarea#wpcf7-form');
        if($textarea.is('.codemirror-cf7-update')){
          var tag = $textarea.delay(100).val();
          cmEditor.replaceSelection(tag);
          //update codemirror.
          $textarea.val(''); //clear.
        }
      });
    }); //-----------end codemirror editor setup

    $('form#post').submit(function(event) {
      $( window ).off( 'beforeunload' ); //remove event to stop cf7 script from warning on save
      event.preventDefault(); //this will prevent the default submit
      var $embdedForms = '';
      var $formNoEmbeds = '';
      if('#cf7-editor-grid' == gridTab){ //set up the code in the cf7 textarea
        var $txta = $('textarea#wpcf7-form');
        $txta.html($txta.val()+'\n');
        var code = html_beautify(
          $grid.CF7FormHTML(),
          {
            'indent_size': 2,
            'wrap_line_length': 0
          }
        );
        $('textarea#wpcf7-form-hidden').html(code);
        $formNoEmbeds = $('<div>').append(code);
      }else{//we are in text mode.
        $('textarea#wpcf7-form-hidden').html(cmEditor.getValue());
        $formNoEmbeds = $('<div>').append(cmEditor.getValue());
      }
      $embdedForms = $formNoEmbeds.find('.cf7sg-external-form').remove();

      //setup sub-forms hidden field.
      var embeds = [];
      var hasTables = false;
      var hasTabs = false;
      if($embdedForms.length>0){
        $embdedForms.each(function(){
          embeds[embeds.length] = $(this).data('form');
        });
      }
      $('#cf7sg-embeded-forms').val(JSON.stringify(embeds));
      //scan and submit tabs & tables fields.
      var tableFields = [];
      var cf7TagRegexp = /\[(.[^\s]*)\s*(.[^\s]*)(|\s*(.[^\[]*))\]/img;
      $('.row.cf7-sg-table', $formNoEmbeds).each(function(){
        var search = $(this).html();
        var match = cf7TagRegexp.exec(search);
        //console.log('search:'+search);
        while (match != null) {
          //ttFields[ match[2] ] = match[1];
          tableFields[tableFields.length] = match[2];
          //console.log('match'+match[2]);
          match = cf7TagRegexp.exec(search); //get the next match.
        }
        hasTables = true;
      });
      //var cf7TagRegexp = /\[(.[^\s]*)\s*(.[^\s]*)\s*(.[^\[]*)\]/img;
      var tabFields = [];
      $('.container.cf7-sg-tabs-panel', $formNoEmbeds).each(function(){
        var search = $(this).html();
        var match = cf7TagRegexp.exec(search);
        while (match != null) {
          //if( -1 === tableFields.indexOf(match[2]) ) /*removed as now want to idenify fields which are both tabs and table fields*/
          tabFields[tabFields.length] = match[2];
          //ttFields[match[2]] = match[1];
          match = cf7TagRegexp.exec(search); //get the next match.
        }
        hasTabs = true;
      });
      //append hidden fields
      $(this).append('<input type="hidden" name="cf7sg-has-tabs" value="'+hasTabs+'" /> ');
      $(this).append('<input type="hidden" name="cf7sg-has-tables" value="'+hasTables+'" /> ');
      var disabled = $('#form-editor-tabs').tabs('option','disabled');
      $(this).append('<input type="hidden" name="cf7sg-has-grid" value="'+disabled+'" /> ');
      /*
      TODO: check for nice-select/select2, effects, toggles, accordion, validation to reduce script loads on front end.
      */
      $('#cf7sg-tabs-fields').val(JSON.stringify(tabFields));
      $('#cf7sg-table-fields').val(JSON.stringify(tableFields));
      //alert(ttFields);
      // continue the submit unbind preventDefault.
      $(this).unbind('submit').submit();
   });
   /*
   Function to convert the UI form into its html final form for editing in the codemirror and/or saving to the CF7 plugin.
   */
    $.fn.CF7FormHTML = function(){
      if( !$(this).is('#grid-form') ){
        return '';
      }
      var $form = $('<div>').append(  $(this).html() );
      var text='';
      //remove the external forms
      var external = {};
      $('.cf7sg-external-form', $form).each(function(){
        var id = $(this).data('form');
        external[id] = $(this).children('.cf7sg-external-form-content').remove();
        $(this).children('.form-controls').remove();
      });
      //remove the row controls
      $('.row', $form).removeClass('ui-sortable').children('.row-controls').remove();
      //remove the collapsible input
      $('.container.cf7sg-collapsible', $form).each(function(){
        var $title = $(this).children('.cf7sg-collapsible-title');
        text = $title.children('label').children('input[type="hidden"]').val();
        $title.children('label').remove();
        //text = $('input', $title).val();
        $title.html(text + $title.html());
        //TODO check if we have a toggle
      });
      //remove tabs inputs
      $('ul.cf7-sg-tabs-list li label', $form).remove();
      //remove texarea and embed its content
      $('.columns', $form).each(function(){
        $(this).removeClass('ui-sortable');
        var $gridCol = $(this).children('.grid-column');
        var $text = $('textarea.grid-input', $gridCol);
        if($text.length>0){
          text = $text.text();
          $(this).html('\n'+text);
        }//else this column is a grid.
        $gridCol.remove();
      });
      //reinsert the external forms
      $('.cf7sg-external-form', $form).each(function(){
        var id = $(this).data('form');
        $(this).append( external[id] );
      });
      text = $form.html();
      if($grid.children('.container').length > 0){ //strip tabs/newlines
          text = text.replace(/^(?:[\t ]*(?:\r?\n|\r))+/gm, "");
      }
      return text;
    }
});//dcoument ready end

})( jQuery );
