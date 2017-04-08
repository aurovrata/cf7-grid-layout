//tag generator ?
$('a[href*="tag-generator-panel-smart-grid"].button').on('click',function(){

  function updateTag(){
      var thisForm = $('form.tag-generator-panel[data-id="smart-grid"]');
      var rows = $('input[name="number_of_rows"]', $('form')).val();
      var row_column = [];
      var idxr, idxc, cols, idx;
      for(idxr=0;idxr<rows;idxr++){
        cols = "input[name='number_of_columns_"+(idxr+1)+"']";
        row_column[idxr] = $(cols, thisForm).val();
      }

      var value = '<div class="smart-grid container">\r\n';
      for(idxr=0;idxr<rows;idxr++){
        value = '<div class="row" data-row="'+(idxr+1)+'">\n';
        for(idxc=0;idxc<row_column[idxr];idxc++){
          value += ' <div class="columns four" data-column="'+(idxc+1)+'">\n';
          value += ' <!-- Row:'+(idxr+1)+' Column:'+(idxc+1)+' put your fields here-->\n';
          value += ' \n';
          value += ' </div><!-- end-column-'+(idxc+1)+' -->\n';
        }
        value += '</div><!-- end-row-'+(idxr+1)+' -->';
      }
      value += '</div><!-- end-container -->';
      $('.control-box.cf7-grid-layout + .insert-box input[name="values"]', thisForm).val( value );
      $('.control-box.cf7-grid-layout + .insert-box input.tag', thisForm).val( value );
  }
  updateTag();

});
