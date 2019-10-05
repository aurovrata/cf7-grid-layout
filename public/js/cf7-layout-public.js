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
	        }
	      }
	    })();
	    
  $(document).on('ready', function(){
    cf7Form_niceSelect.ready();
    cf7Form_select2.ready();
  });  

})( jQuery );
