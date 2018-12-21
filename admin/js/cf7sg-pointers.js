jQuery(document).ready( function($) {
  function createPointer(content, id, arrow, valign){
    var $point;
    switch(id){
      case 'update_forms_pointer':
        $point = $('tbody#the-list tr .cf7-form-key[data-update="cf7sg-update"]');
        if($point){
          $point = $point.first().closest('tr').find('a.row-title');
        }else return null;
        break;
      case 'cf7sg_shortcodes':
        $point = $('tbody#the-list tr .cf7-2-post-shortcode').first();
        break;
    }
    var pointer =  $point.pointer({
      content: content,
      position:{edge:	arrow, align:	valign},
      pointerWidth:	350,
      close:function() {$.post( ajaxurl, {pointer: id, action: 'dismiss-wp-pointer'});}
    }).pointer('open');
    $('.wp-pointer-content .cf7sg-pointer').parent().find('a.close').after('<a href="#" data-id="'+id+'" class="button-primary cf7sg-next">'+cf7sg_pointers.next+'</a>');
    return pointer;
  }
  var pobj, pids, id, pidx, pointers = cf7sg_pointers.pointers;
  pids = Object.keys(pointers);
  if(pids.length>1){
    pidx=0;
    id = pids[pidx];
    pobj=createPointer(pointers[id][0], id, pointers[id][1],pointers[id][2]);
    if(!pobj && pidx<pids.length){ //try the next one.
      pidx++;
      id = pids[pidx];
      pobj=createPointer(pointers[id][0], id, pointers[id][1],pointers[id][2]);
    }
    $('body').on('click', 'a.cf7sg-next', function(e){
      // var $pointer = $(e.target);
      pobj.pointer('close');
      if(pidx<pids.length){
        pidx++;
        id = pids[pidx];
        pobj=createPointer(pointers[id][0], id, pointers[id][1],pointers[id][2]);
        pobj.pointer('open');
      }
    });
  }

});
