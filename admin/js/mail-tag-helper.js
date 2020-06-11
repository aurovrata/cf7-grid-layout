
(function( $ ) {
  'use strict';
  var $mailtags
  $(document).ready(function(){

    $('span.mailtag.code', '#wpcf7-mail').each(function(){
      var $this = $(this);
      var tag = $this.text().replace(/\[(.+)\]/g,'$1');
      var $help = $('<a class="helper" data-tag="'+tag+'" href="javascript:void(0);"><span class="dashicons dashicons-info"></span></a>');
      $(this).after($help);
      new Clipboard($help[0], {
          text: function(trigger) {
            var form = $('#post_name').val();
            if(!form) form = 'my-form';

            var ftag = $(trigger).data('tag').replace('-','_');
            var text = "add_filter( 'cf7sg_mailtag_"+$(trigger).data('tag')+"', 'filter_cf7_mailtag_"+ftag+"', 10, 3);\n";
            text += "function filter_cf7_mailtag_"+ftag+"($tag_replace, $submitted, $cf7_key){\n";
            text += "  /*the $tag_replace string to change*/\n";
            text += "  /*the $submitted an array containing all submitted fields*/\n";
            text += "  /*the $cf7_key is a unique string key to identify your form, which you can find in your form table in the dashboard.*/\n"
            text += "  if('"+form+"'==$cf7_key ){\n";
            text += "    $tag_replace = ... //change the "+$(trigger).data('tag')+".\n";
            text += "  }\n";
            text += "return $tag_replace;\n";
            text += "}\n";
            return text;
          }
      });
      var helper = mailTagHelper.filter.replace('%s', '<span class="tag-colour">'+tag+'</span>');
      $help.after('<span class="helper">'+helper+', '+mailTagHelper.msg+'</span>');
    })
  })
})( jQuery );
