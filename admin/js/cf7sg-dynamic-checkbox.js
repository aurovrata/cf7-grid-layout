(function($){
	$('#dynamic-checkbox-tag-generator').change(':input',function(e){
		let $target = $(e.target),
			$form = $(this),
			$l = $('#dynamic_checkbox-limit', $form).next('.max-selection'), //limit selection
			$t = $('#dynamic-checkbox-post-images', $form), //post thumbnails
			$style = $('input[name="dynamic_checkbox-style[]"]', $form),//styles.
			$source = $('input[name="sections[]"]', $form); //source of data

		switch(true){
			case $target.is('.source-tab'):
				if($t.is(':checked')){
					$t.prop('checked',false);
					$style.filter('#dynamic_checkbox-hybriddd, #dynamic_checkbox-treeview').attr('disabled',false);
				}
				if(['imagehdd','imagegrid'].includes($style.filter(':checked').val()){
					//display filter for images
					$source.filter(':checked').is('.custom-tab');
				}
				break;
			case $target.is('.custom-tab'):
				$('#image-grid').show();
				//enable image filter.
				break; //nothing to do.
			case $target.is('.source-tab'): //alternative source.
				$('#image-grid').hide().find(':input').prop('checked', false);
				break;
			case $target.is('#dynamic-checkbox-post-images'):
				if(e.target.checked){
					switch($style.val()){
						case 'hybriddd':
						case 'treeview':
							$style.filter('#dynamic_checkbox-imagehdd').prop('checked',true);
							break;
					}
					$style.filter('#dynamic_checkbox-hybriddd, #dynamic_checkbox-treeview').attr('disabled',true);
				}
				break;
			case $target.is('#dynamic_checkbox-limit'): //enable limit selection
				if(e.target.checked){
					$l.prop('disabled',false);
					$l.next('input.data-attribute').val('maxcheck:'+$l.val()).trigger('change'); //hidden field
				}
				break;
			case $target.is('#dynamic_checkbox-nolimit'): //enable limit selection
				if(e.target.checked){
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
