(function($){
	let $imgFilter = $('#fieldhelperdiv .cf7sg_filter_taxonomy_images a.helper');
	$imgFilter = $('#dynamic_checkbox-cf7sg-imagehdd, #dynamic_checkbox-cf7sg-imagegrid').siblings('.cf7sg-se-option').children('a').attr('data-cf7sghelp', $imgFilter.attr('data-cf72post')).addClass('helper');
	//enable the filter helper code.
	$imgFilter.each(function(){
		new Clipboard(this, {
			text: function(trigger) {
				let $target = $(trigger),
				  text = $target.data('cf7sghelp'),
				  key = $('#post_name').val(), //get post slug
					$form = $target.closest('.cf7sg-dynamic-list-tag-manager'),
				  field = $('input.tg-name',$form).val();
				text = text.replace(/\{\$form_key\}/gi, key);
				text = text.replace(/\{\$form_key_slug\}/gi, key.replace(/\-/g,'_'));
				text = text.replace(/\{\$field_name\}/gi, field);
				text = text.replace(/\{\$field_name_slug\}/gi, field.replace(/\-/g,'_'));
				text = text.replace(/\{\$field_type\}/gi, 'dynamic_checkbox');
				text = text.replace(/\[dqt\]/gi, '"');
				return text;
			}
		})
	})
	$('#dynamic-checkbox-tag-generator').change(':input',function(e){
		let $target = $(e.target),
			$form = $(this),
			$l = $('#dynamic_checkbox-limit', $form).next('.max-selection'), //limit selection
			$t = $('#dynamic-checkbox-post-images', $form), //post thumbnails
			$style = $('input[name="dynamic_checkbox-style[]"]', $form),//styles.
			$source = $('input[name="sections"]', $form); //source of data

		switch(true){
			case $target.is('.source-tab'):
				if($t.is(':checked')){ //post thumbnail option
					$t.prop('checked',false);
					$style.filter('#dynamic_checkbox-cf7sg-hybriddd, #dynamic_checkbox-cf7sg-treeview').attr('disabled',false);
				}
				$imgFilter.closest('.cf7sg-se-option').hide(); //hide all filters
				if($target.is('.taxonomy-tab') && ['cf7sg-imagehdd','cf7sg-imagegrid'].includes($style.filter(':checked').val())){
					//shoe the image filter option.
					$style.filter(':checked').siblings('.cf7sg-se-option').show();
				}
				break;
			case $target.is('#dynamic-checkbox-post-images'):
				if(e.target.checked){
					switch($style.filter(':checked').val()){
						case 'cf7sg-hybriddd':
						case 'cf7sg-treeview':
							$style.filter('#dynamic_checkbox-cf7sg-imagehdd').prop('checked',true);
							break;
					}
				}
				$style.filter('#dynamic_checkbox-cf7sg-hybriddd, #dynamic_checkbox-cf7sg-treeview').attr('disabled',e.target.checked);

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
			case $target.is('.list-style'):
				if($source.filter(':checked').is('.taxonomy-tab')){
					$target.siblings('.cf7sg-se-option').show(); //show the current one.
				}
				break;
		}
	});
})(jQuery);
