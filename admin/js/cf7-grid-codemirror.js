(function( $ ) {

	$(document).ready( function(){
    var $codemirror = $('#cf7-codemirror');
    var $wpcf7Editor = $('textarea#wpcf7-form-hidden');
    var codemirrorUpdated = false;
    var $grid = $('#grid-form');
    var gridTab = '#cf7-editor-grid'; //default at load time.

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
      var cmEditor = CodeMirror.fromTextArea( $wpcf7Editor.get(0), {
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

      cmEditor.on('changes', function(){
        codemirrorUpdated = true;
      });

      //create tabs
      $('#form-editor-tabs').tabs({
        beforeActivate: function (event, ui){
          //update the codemirror panel
          if('#cf7-codemirror' == ui.newPanel.selector){
            var $txta = $('textarea#wpcf7-form');
            $txta.html($txta.val()+'\n');
            var code = html_beautify(
              $grid.CF7FormHTML(),
              {
                'indent_size': 2,
                'wrap_line_length': 0
              }
            );
            cmEditor.setValue(code);
            //reset the codemirror change flag
            codemirrorUpdated = false;
            //remove id from textarea
            $('textarea#wpcf7-form').attr('id', '');
            //change the wpcf7 textarea
            $('textarea.codemirror-cf7-update', $codemirror).attr('id', 'wpcf7-form');
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


    /*
     Save the form TODO: need to move wpcf7-form textarea outside the grid tabs to ensure it is updated properly, this means the codemirror needs to be set by value rather than using the textarea intially.
    */
    $('form#post').submit(function(event) {
      $( window ).off( 'beforeunload' ); //remove event to stop cf7 script from warning on save
      event.preventDefault(); //this will prevent the default submit
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
      }
      $(this).unbind('submit').submit(); // continue the submit unbind preventDefault
   });

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
      text = text.replace(/^(?:[\t ]*(?:\r?\n|\r))+/gm, "");
      return text;
    }
  });

})( jQuery );
