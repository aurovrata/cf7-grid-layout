(function($) {
  //obtained from wordpress codex: https://codex.wordpress.org/Plugin_API/Action_Reference/quick_edit_custom_box
	// we create a copy of the WP inline edit post function
	var $wp_inline_edit = inlineEditPost.edit;
	// and then we overwrite the function with our own code
	inlineEditPost.edit = function( id ) {

		// "call" the original WP edit function
		// we don't want to leave WordPress hanging
		$wp_inline_edit.apply( this, arguments );

		// now we take care of our business

		// get the post ID
		var $post_id = 0;
		if ( typeof( id ) == 'object' ) {
			$post_id = parseInt( this.getId( id ) );
		}

		if ( $post_id > 0 ) {
			// define the edit row
			var $edit_row = $( '#edit-' + $post_id );
			var $post_row = $( '#post-' + $post_id );

			// get the data
			var $form_key = $( 'span.cf7-form-key', $post_row ).text();
      var $title = $('a.row-title', $post_row ).text();
      var $slug = $('span.cf7_post_slug', $post_row ).text();
      var $mm = $( 'select[name="mm"] option:selected').val();

			// populate the data
      $( ':input[name="_smart_grid_cf7_form_key"]', $edit_row ).val( $form_key );
      $( ':input[name="post_title"]', $edit_row ).val( $title );
			$( ':input[name="post_name"]', $edit_row ).val( $slug );
      $( 'select[name="mm"] option:eq('+$mm+')').prop('selected',true);
      /*.closest('label')
      .addClass('hide-element');
      $('fieldstate.inline-edit-date').addClass('hide-element');
      $('.inline-edit-private').closest('.inline-edit-group')*/

		}
    //remove other fields
    $('.inline-edit-row .inline-edit-col-left').not('.inline-edit-cf7').addClass('hide-element');
    $('.inline-edit-row .inline-edit-col-right').addClass('hide-element');
	};

})(jQuery);
