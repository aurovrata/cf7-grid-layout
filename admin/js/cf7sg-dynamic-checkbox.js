(function($){
	$('#dynamic-checkbox-tag-generator').change(':input',function(e){
		let $target = $(e.target);
		switch(true){
			case $target.is('.post-tab'):
				if($('#dynamic-checkbox-post-images').is(':checked')) $('#image-grid').show();
				break; //nothing to do.
			case $target.is('.custom-tab'):
				$('#image-grid').show();
				//enable image filter.
				break; //nothing to do.
			case $target.is('.source-tab'): //alternative source.
				$('#image-grid').hide().find(':input').prop('checked', false);
				break;
			case $target.is('#dynamic-checkbox-post-images'):
				if(e.target.checked) $('#image-grid').show();
				else $('#image-grid').hide().find(':input').prop('checked', false);
				break;
			case $target.is('#checkbox-maxcheck'):
				if(e.target.checked) $('.cf7sg-se-maxcheck .max-selection').prop('disabled',false);
				else $('.cf7sg-se-maxcheck .max-selection').prop('disabled',true);
				break;
			case $target.is('.list-style.dynamic_checkbox'):
				if(e.target.checked) $target.next('span').show();
				break;
			case $target.is('.max-selection'): //update hidden value.
				$('.cf7sg-se-maxcheck input.data-attribute').val('maxcheck:'+e.target.value);
				break;
		}
	});
})(jQuery);
