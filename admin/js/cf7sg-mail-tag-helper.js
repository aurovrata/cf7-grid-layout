
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
            var tag = $(trigger).data('tag'), ftag = tag.replace(/\-/g,'_');
            var addTable = false;
            if(0==tag.indexOf('cf7sg-form-')){
              //check if table or tab exists.
              let $form = $('<div>').html($('#grid-form').CF7FormHTML());
              if( $('div.container.cf7-sg-table', $form).length > 0) addTable=true;
              if( $('div.container.cf7-sg-tabs-panel', $form).length > 0) addTable=true;
            }
            var text = "add_filter( 'cf7sg_mailtag_"+tag+"', 'filter_cf7_mailtag_"+ftag+"', 10, 3);\n";
            text += "function filter_cf7_mailtag_"+ftag+"($tag_replace, $submitted, $cf7_key){\n";
            text += "  /*the $tag_replace string to change*/\n";
            text += "  /*the $submitted an array containing all submitted fields*/\n";
            text += "  /*the $cf7_key is a unique string key to identify your form, which you can find in your form table in the dashboard.*/\n"
            text += "  if('"+form+"'==$cf7_key ){\n";
            if(addTable){
              text += "    $style = 'style=[qtm]padding:0 3px;border-collapse:collapse;border-bottom:1px solid black[qtm]';\n";
              text += "    $tag_replace ='\n";
              text += "    <table>\n";
              text += "      <thead><tr><th '.$style.'>Guest Name</th><th '.$style.'>Senior</th></tr></thead>\n";
              text += "      <tbody>';\n";
              text += "    if(!empty($submitted['guest-name'])){\n";
              text += "      $style = 'style=[qtm]background-color:#e3e3e3[qtm]';\n";
              text += "      $row=1;\n";
              text += "      foreach($submitted['guest-name'] as $idx=>$guest){\n";
              text += "        $tag_replace .='  <tr><td '.($row%2==0?$style:'').'>'.$guest.'</td><td '.($row%2==0?$style:'').'>'. (empty($submitted['senior-guest'][$idx]) ? '' : 'yes') . '</td></tr>'.PHP_EOL;\n";
              text += "        $row++;\n";
              text += "      }\n";
              text += "    }\n";
              text += "    $tag_replace .='\n";
              text += "      </tbody>\n";
              text += "    </table>\n";
              text += "    ';\n";
            }else{
              text += "    $tag_replace = ... //change the "+tag+".\n";
            }
            text += "  }\n";
            text += "  return $tag_replace;\n";
            text += "}\n";
            return text.replace(/\[qtm\]/,'"');
          }
      });
      var helper = mailTagHelper.filter.replace('%s', '<span class="tag-colour">'+tag+'</span>');
      $help.after('<span class="helper">'+helper+', '+mailTagHelper.msg+'</span>');
    })
  })
})( jQuery );
