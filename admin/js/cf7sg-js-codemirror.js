/*
cme is instantiated as a global variable
*/
(function( $, cme ) {
  $(document).ready( function(){
    let $codemirror = $('#cf7-js-codemirror'),
      $jstext = $('textarea#cf7-form-js'),
      codemirrorUpdated = false;
    const $cf7key = $('#post_name');
    //set codemirror editor value;
    cme.setValue($jstext.text());
    cme.setOption("mode",{name: "javascript", json: true});
    cme.setSize("100%");

    if(cf7sgJSeditor.theme.user.length>0) cme.setOption('theme',cf7sgJSeditor.theme.user);
    let $themeRadio = $('.codemirror-theme', $codemirror);
    if($(':input:checked', $themeRadio).length>0){
      $themeRadio.on('change',':input',function(e){
        cme.setOption("theme",cf7sgJSeditor.theme[e.target.value]);
      });
    }else $(':input',$themeRadio).prop('disabled',true);

    $themeRadio.append('<span class="file">'+$jstext.data('file')+'</span>')

    $('form#post').submit(function(event) {
      $( window ).off( 'beforeunload' ); //remove event to stop cf7 script from warning on save
      event.preventDefault();
      //verify that the js file content is different.
      let jsf = cme.getValue();
      if(jsf != $jstext.text()){
        $jstext.text(jsf)
      }else $jstext.text('');//empty.
      // continue the submit unbind preventDefault.
      $(this).unbind('submit').submit();
    });

    function beautifyEditor(cursor){
      cme.setSelection({
        'line':cme.firstLine(),
        'ch':0,
        'sticky':null
      },{
        'line':cme.lastLine(),
        'ch':0,
        'sticky':null
      },
      {scroll: false});
      cme.indentSelection("smart");
      cme.setCursor(cme.firstLine(),0);

      if('undefined' != typeof cursor && cursor.find(false)){
        const from = cursor.from(), to = cursor.to();
        cme.setSelection(CodeMirror.Pos(from.line, 0), to);
        cme.scrollIntoView({from: from, to: CodeMirror.Pos(to.line + 10, 0)});
      }
    }
    beautifyEditor();

    cme.on('changes', function(e){
      codemirrorUpdated = false;
      if(cme.getValue().length>0){
        codemirrorUpdated = true;
        $jstext.data('form', $cf7key.val()); //set current form slug.
      }
    });
    $cf7key.on('change',function(e){
      let oldkey = $jstext.data('form'),
        newkey = $(this).val();
      if( oldkey.length > 0 && oldkey != $cf7key.val() ){
        /* update editor js */
        cme.setValue( cme.getValue().replace('#'+oldkey, '#'+$cf7key.val()) );
        $jstext.data('form', newkey);
      }
      //update the file name.
      oldkey = $jstext.data('file');
      oldkey = oldkey.substring(oldkey.indexOf('js/')+3, oldkey.indexOf('.js'));
      oldkey = $jstext.data('file').replace(oldkey, newkey);
      $('.file',$themeRadio).text(oldkey);
    });
    $('#js-tags').on('click','a.helper',function(e){
      let helper = $(this).data('cf72post').replace('{$cf7_key}', $cf7key.val() );
      let cursor = cme.getCursor();
      switch(cursor.line){
        case 0:
          cursor = {'line':cme.lastLine()-1,'ch':0};
          cme.setCursor(cursor);
          helper += "\n";
          break;
        case cme.lastLine():
          helper = "\n"+helper;
          break;
        default:
          helper += "\n";
          break;
      }
      cme.replaceSelection(helper);
      beautifyEditor();
    });
  });
})( jQuery, jsCodeMirror_5_32);
