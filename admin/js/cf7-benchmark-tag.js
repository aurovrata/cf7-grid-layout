
(function( $ ) {
  'use strict';
  var $tag = $('input.tag' , $('#benchmark-tag-generator').closest('.control-box').siblings('.insert-box'));
  var $name = $('#benchmark-tag-generator input[name="name"]');
  var $id = $('#benchmark-tag-generator input[name="id"]');
  var $cl = $('#benchmark-tag-generator input[name="class"]');
  var $req = $('#benchmark-tag-generator input[name="required"]');
  var $eventName = $('#event-name');
  var $hidden = $('#benchmark-tag-generator input[name="hidden"]');


  $('#benchmark-tag-generator').on('change',':input', function(event){
    var $target = $(event.target);
    var $tab = $('input[name="bsections"]:checked');
    var source = 'taxonomy';
    $eventName.text($name.val());

    /* which source ? */
    if($tab.is('#above-tab')){
      source = 'above';
    }else if($tab.is('#below-tab')){
      source = 'below';
    }else if($tab.is('#between-tab')){
      source = 'between';
    }
    updateCF7Tag(source);
  });


  function updateCF7Tag(source='above') {
    var id=$id.val();
    if(id.length > 0) id =' id:'+id;

    var classes = $cl.val();
    if(classes.length > 0){
      var classArr = classes.split(',');
      var idx;
      classes='';
      for(idx=0; idx<classArr.length; idx++){
        classes += " class:" + classArr[idx].trim() + " ";
      }
    }
    var values = ''
    switch(source){
      case 'above':
        var limit = $('#benchmark-above').val();
        var msg = $('#warning-above').val();
        if(limit.length > 0){
          values = ' "above:'+ limit+'" "warn:'+msg+'"';
        }
        break;
      case 'below':
        var limit = $('#benchmark-below').val();
        var msg = $('#warning-below').val();
        if(limit.length > 0){
          values = ' "below:'+limit+'" "warn:'+msg+'"';
        }
        break;
      case 'between':
        var max = $('#benchmark-max').val();
        var min = $('#benchmark-min').val();
        var msg = $('#warning-between').val();
        if(max.length > 0 && min .length>0){
          values = ' "between:'+min+':'+max+'" "warn:'+msg+'"';
        }
        break;
    }

    var type = 'benchmark ';
    if($req.is(':checked')) type = 'benchmark* ';
    if($hidden.is(':checked')) values += ' "hidden:true"';
    $tag.val('[' + type + $name.val() + id + classes + values +']');
  }
  })( jQuery );
