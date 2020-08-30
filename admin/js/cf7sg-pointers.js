jQuery(document).ready( function($) {
  function createPointer(id,pointer){
    var $point,
      content=pointer[0], arrow=pointer[1],
      valign=pointer[2],selector=pointer[3];
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
      default:
        $point = $(selector);
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
  if(pids.length>0){
    pidx=0;
    id = pids[pidx];
    pobj=createPointer(id, pointers[id]);
    if(!pobj && pidx<pids.length){ //try the next one.
      pidx++;
      id = pids[pidx];
      pobj=createPointer(id, pointers[id]);
    }
    $('body').on('click', 'a.cf7sg-next', function(e){
      // var $pointer = $(e.target);
      pobj.pointer('close');
      if(pidx<pids.length){
        pidx++;
        id = pids[pidx];
        pobj=createPointer( id, pointers[id]);
        pobj.pointer('open');
      }
    });
  }

});
