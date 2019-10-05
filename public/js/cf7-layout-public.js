/**
 * Enhancements for default CF7 Forms
 * Support for CF7 Smart Grid elemtents that are available in default CF7 Forms
 */
(function( $ ) {
	'use strict';
	
	var selectors = {
	      niceSelect: 'select.nice-select',
	      select2: 'select.select2',
	      tags: '.tags'
	    },
	    // Support for nice-select fields
	    cf7Form_niceSelect = (function(){
	      var $niceSelects;
	      
	      return {
	        ready: function(){
	          $niceSelects = $(selectors.niceSelect);
	          $niceSelects.niceSelect();
	        },
	        setup: function(){
	          return typeof $.fn.niceSelect !== 'undefined';
	        }
	      }
	    })(),
	    // Support for select2 fields
	    cf7Form_select2 = (function(){
	      var $select2s;
	      
	      return {
	        ready: function(){
	          $select2s = $(selectors.select2);	          
	          $select2s.each(function(index){
	            var $select2 = $(this);
	            
	            $select2.select2({
                tags: $select2.is(selectors.tags)
              });
	          })
	        },
	        setup: function(){
	          return typeof $.fn.select2 !== 'undefined';
	        }
	      }
	    })();
	    
  $(document).on('ready', function(){
    if ( cf7Form_niceSelect.setup() ) {
      cf7Form_niceSelect.ready();
    }
    if ( cf7Form_select2.setup() ) {
      cf7Form_select2.ready();
    }
  });  

})( jQuery );
