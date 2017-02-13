(function( $ ) {
	'use strict';
  $(document).ready( function(){
    var inNum = $('input[class^="sgv-"]');
    var cf7Form_validaton = $('input[class^="sgv-"]').closest('form.wpcf7-form');
  	/* Validation */
    if(cf7Form_validaton.length){
      cf7Form_validaton.change( 'input[type="number"]', function( event ) {
        var $number = $(this).val();
        switch( true ){
          case $(this).hasClass('sgv-no-zero'):
            if(0==$number){
              $("<span>Value cannot be zero</span>").dialog({
                modal: true,
                buttons: {
                  Ok: function() {
                    $( this ).dialog( "close" );
                  }
                }
              });
            }
        }
        if ( elem.is( "[href^='http']" ) ) {
            elem.attr( "target", "_blank" );
        }
      });
    }
  });
})( jQuery );
