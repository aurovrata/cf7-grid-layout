(function($){
	$('#dynamic-checkbox-tag-generator').change(':input',function(e){
		let $target = $(e.target), $form = $(this);
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
				if(e.target.checked){
					let $style = $('input[name="dynamic_checkbox-style[]"]', $form);
					switch($style.val()){
						case 'hybriddd':
						case 'treeview':
							$('#dynamic_checkbox-imagehdd',$form).prop('checked',true);
							break;
					}
				}
				break;
			case $target.is('#dynamic_checkbox-limit'): //enable limit selection
			let $l = $target.next('.max-selection');
				if(e.target.checked){
					$l.prop('disabled',false);
					$l.next('input.data-attribute').val('maxcheck:'+$l.val()).trigger('change'); //hidden field
				}else{
					$l.next('input.data-attribute').val('').trigger('change'); //empty hidden field
					$l.prop('disabled',true);
				}
				break;
			case $target.is('.max-selection'): //update hidden value.
				$target.next('input.data-attribute').val('maxcheck:'+e.target.value).trigger('change');
				break;
			case $target.is('#dynamic_checkbox-imagehdd'): //update hidden value.
			case $target.is('#dynamic_checkbox-imagegrid'): //update hidden value.
				// let $form = $target.closest('.cf7sg-dynamic-list-tag-manager'),
	      let $tab = $('input[name="sections"]:checked', $form);
				if( $tab.is('.taxonomy-tab') && e.target.checked) $('p.filter-hook', $tab.closest('section')).show();
				else $('p.filter-hook', $tab.closest('section')).hide();
				break;
		}
	});
})(jQuery);
