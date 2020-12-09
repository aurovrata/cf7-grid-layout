<?php
/**
* js event helper links for code snipets, displayed on the js codemirror tab.
* @since 4.0.0
*/
?>
<ul id="js-tags" class="display-none">
  <li id="form-events" class="show-events"><?=__('Form','cf7-grid-layout')?><span>&gt;</span>
    <ul>
      <li><?= __('Event: ','cf7-grid-layout')?>
        <a class="helper" data-cf72post="$('#cf7sg-form-{$cf7_key} form.wpcf7-form').on( 'cf7SmartGridReady', function(e){
/* event fired once the form has been initialised, click to insert helper code into your js file. */
let $form = $(this); //$jquery form object.
});" href="javascript:void(0);"><?=__('form ready','cf7-grid-layout')?></a>
      </li>
    </ul>
  </li>
  <li id="table-events" class="display-none"><?=__('Tables','cf7-grid-layout')?><span>&gt;</span>
    <ul>
      <li><?= __('Event: ','cf7-grid-layout')?>
        <a class="helper" data-cf72post="$('#cf7sg-form-{$cf7_key} form.wpcf7-form').on( 'sgTableReady', '.container.cf7-sg-table', function(e){
/* event fired once a table has been initialised, click to insert helper code into your js file. */
let $form = $(e.delegateTarget), $table = $(e.target);
});" href="javascript:void(0);"><?=__('table ready','cf7-grid-layout')?></a>
      </li>
      <li><?= __('Event: ','cf7-grid-layout')?>
        <a class="helper" data-cf72post="$('#cf7sg-form-{$cf7_key} form.wpcf7-form').on( 'sgRowAdded', '.container.cf7-sg-table', function(e){
/* event fired when a table row has been added, click to insert helper code into your js file. */
//$form current form jquery object.
//$table table jquery object to which the new row was added.
//$row newly added row jquery object.
//rIdx row index (zero based).
let $form = $(e.delegateTarget), $table = $(e.target), rIdx = e['row'], $row= $table.find('.row[data-row='+rIdx+']');
});" href="javascript:void(0);"><?=__('row added','cf7-grid-layout')?></a>
      </li>
      <li><?= __('Event: ','cf7-grid-layout')?>
        <a class="helper" data-cf72post="/* event fired when a table row has been deleted, click to insert helper code into your js file. */
$('#cf7sg-form-{$cf7_key} form.wpcf7-form').on( 'sgRowDeleted', '.container.cf7-sg-table', function(e){
  //$form current form jquery object.
  //$table table jquery object.
  let $form = $(e.delegateTarget), $table = $(e.target);
});" href="javascript:void(0);"><?=__('row removed','cf7-grid-layout')?></a>
      </li>
      <li><?= __('Function: ','cf7-grid-layout')?>
        <a class="helper" data-cf72post="//call this function once the table is ready.
//$table is the table section jquery object.
//adds an extra row, and fires a row added event.
$table.cf7sgCloneRow(); /* function to programmatically add a row to a table if fields. */" href="javascript:void(0);"><?=__('add a row','cf7-grid-layout')?></a>
      </li>
      <li><?= __('Function: ','cf7-grid-layout')?>
        <a class="helper" data-cf72post="//call this function once the table is ready.
//$table is the table section jquery object.
//pass false to hide the button, true to enable the button.
$table.toggleCF7sgTableRowAddition(false); /* hide/show row addition button. */" href="javascript:void(0);"><?=__('toggle add button','cf7-grid-layout')?></a>
      </li>
      <li><?= __('Function: ','cf7-grid-layout')?>
        <a class="helper" data-cf72post="//call this function once the table is ready.
//$table is the table section jquery object.
//pass false to hide the button, true to enable the button.
$table.toggleCF7sgTableRowDeletion(false); /* hide/show row deletion button. */" href="javascript:void(0);"><?=__('toggle delete button','cf7-grid-layout')?></a>
      </li>
      <li><?= __('Function: ','cf7-grid-layout')?>
        <a class="helper" data-cf72post="//call this function once the table is ready.
//$table is the table section jquery object.
//returns number of rows in table.
$table.cf7sgCountRows(); /* count table rows. */" href="javascript:void(0);"><?=__('row count','cf7-grid-layout')?></a>
      </li>
      <li><?= __('Function: ','cf7-grid-layout')?>
        <a class="helper" data-cf72post="//call this function once the table is ready.
//$table is the table section jquery object.
//returns number of rows in table.
$table.cf7sgRemoveRow(); /* remove last row. */" href="javascript:void(0);"><?=__('remove last row','cf7-grid-layout')?></a>
      </li>
    </ul>
  </li>
  <li id="tab-events" class="display-none"><?=__('Tabs','cf7-grid-layout')?><span>&gt;</span>
    <ul>
      <li><?= __('Event: ','cf7-grid-layout')?>
        <a class="helper" data-cf72post="/* event fired once the tabs has been initialised, click to insert helper code into your js file. */
$('#cf7sg-form-{$cf7_key} form.wpcf7-form').on( 'sgTabsReady', function(e){
  //$form current form jquery object.
  //$tabs tabs jquery object.
  let $form = $(this), $tabs = $(e.target);
});" href="javascript:void(0);"><?=__('tabs ready','cf7-grid-layout')?></a>
      </li>
      <li><?= __('Event: ','cf7-grid-layout')?>
        <a class="helper" data-cf72post="/* event fired when a new tab is added, click to insert helper code into your js file. */
$('#cf7sg-form-{$cf7_key} form.wpcf7-form').on( 'sgTabAdded', '.cf7-sg-tabs-panel', function(e){
  //$form current form jquery object.
  //$panel newly added panel jquery object.
  //$panel.attr('id') == <initial-id>-<tIdx> where tab index (tIdx) is greater than 0.
  let $form = $(this), $panel = $(e.target), tIdx = e['tab-index'];
});" href="javascript:void(0);"><?=__('tab added','cf7-grid-layout')?></a>
      </li>
      <li><?= __('Event: ','cf7-grid-layout')?>
        <a class="helper" data-cf72post="/* event fired when a new tab is removed, click to insert helper code into your js file. */
$('#cf7sg-form-{$cf7_key} form.wpcf7-form').on( 'sgTabRemoved', '.cf7-sg-tabs', function(e){
  //$form current form jquery object.
  //$tabs tab jquery object from which the panel was removed.
  let $form = $(this), $tabs = $(e.target);
});" href="javascript:void(0);"><?=__('tab removed','cf7-grid-layout')?></a>
      </li>
      <li><?= __('Function: ','cf7-grid-layout')?>
        <a class="helper" data-cf72post="
//call this function once the tabs are ready.
//adds an extra tab, and fires a tab added event.
$tabs.cf7sgCloneTab(); /* function to programmatically add a tab. */" href="javascript:void(0);"><?=__('add a tab','cf7-grid-layout')?></a>
      </li>
      <li><?= __('Function: ','cf7-grid-layout')?>
        <a class="helper" data-cf72post="
//call this function once the tabs are ready.
//removes the last tab and its corresponding panel.
$tabs.cf7sgRemoveTab(); /* remove tab and panel. */" href="javascript:void(0);"><?=__('remove tab','cf7-grid-layout')?></a>
      </li>
      <li><?= __('Function: ','cf7-grid-layout')?>
        <a class="helper" data-cf72post="
//call this function once the tabs are ready.
//to disable the tab addition pass false, to enable it pass true.
$tabs.toggleCF7sgTabAddition(false); /* disable/enable tab addition. */" href="javascript:void(0);"><?=__('toggle add button','cf7-grid-layout')?></a>
      </li>
      <li><?= __('Function: ','cf7-grid-layout')?>
        <a class="helper" data-cf72post="
//call this function once the tabs are ready.
//to disable the tab deletion pass false, to enable it pass true.
$tabs.toggleCF7sgTabDeletion(false); /* disable/enable tab deletion. */" href="javascript:void(0);"><?=__('toggle delete button','cf7-grid-layout')?></a>
      </li>
      <li><?= __('Function: ','cf7-grid-layout')?>
        <a class="helper" data-cf72post="
//call this function once the tabs are ready.
//returns the total count of current tabs.
$tabs.cf7sgCountTabs(); /* tab count. */" href="javascript:void(0);"><?=__('tab count','cf7-grid-layout')?></a>
      </li>
    </ul>
  </li>
  <li id="collapsible-events" class="display-none"><?=__('Collapsible rows','cf7-grid-layout')?><span>&gt;</span>
    <ul>
      <li><?= __('Event: ','cf7-grid-layout')?>
        <a class="helper" data-cf72post="/* event fired once the collapsible rows (accordion/toggle/sections) have been initialised */
$('#cf7sg-form-{$cf7_key} form.wpcf7-form').on( 'sgCollapsibleRowsReady', function(e){
  let $form = $(this), $section = $(e.target);
});" href="javascript:void(0);"><?=__('section ready','cf7-grid-layout')?></a>
      </li>
      <li><?= __('Event: ','cf7-grid-layout')?>
        <a class="helper" data-cf72post="/* event fired when a collapsible section (accordion/toggle/section) is activated, click to insert helper code into your js file. */
$('#cf7sg-form-{$cf7_key} form.wpcf7-form').on( 'accordionactivate', function(e, ui){
  //this event is fired if the section is either closed or opened.
  //for single collapsed sections,
  //  when openned, ui.oldPanel is empty and ui.newPanel is the current opened panel.
  //  when closed, ui.oldPanel is the currently closed panel and ui.newPanel is empty.
  //for accordion with multile rows,
  //  ui.oldPanel is the previous panel (else empty if first actiation), ui.newPanel is the current opened panel.
  //see https://api.jqueryui.com/accordion/#event-activate for more details.
  let $form = $(this), $section = $(e.target);
  switch(true){
    case $section.is('.with-toggle'):
      //this is a toggled section, identify it by its CSS id $this.attr('id').
      break;
    case $section.is('.cf7sg-accordion-rows'):
      //this is an accordion with multiple collapsible sections, identify it by its CSS id $this.attr('id')
      break;
    default: //this is a single collapsible section.
      //identify it by its CSS id $this.attr('id')
      break;
    }
});" href="javascript:void(0);"><?=__('section activated','cf7-grid-layout')?></a>
      </li>
      <li><?= __('Function: ','cf7-grid-layout')?>
        <a class="helper all-fields" data-cf72post="
  // $section is the collapsible section jquery object.
  // you can either pass true (open, activate), or false (close) to the function.
  $section.activateCF7sgCollapsibleSection(true);/* toggle open/close a collapsible section. */" href="javascript:void(0);"><?=__('open/close section','cf7-grid-layout')?></a>
      </li>
    </ul>
  </li>
  <li id="slider-events" class="display-none"><?=__('Slides','cf7-grid-layout')?><span>&gt;</span>
    <ul>
      <li><?= __('Event: ','cf7-grid-layout')?>
        <a class="helper" data-cf72post="/* event fired once the slider has been initialised, click to insert helper code into your js file. */
$('#cf7sg-form-{$cf7_key} form.wpcf7-form').on( 'sgSliderReady','.cf7sg-slider-section', function(e){
  // $form is the form jquery object.
  //the $slider is the div.cf7sg-slider-section element jquery object.
  //slides is the total number of slides.
  let $form = $(e.delegateTarget), $slider = $(this), slides = e.total;
});" href="javascript:void(0);"><?=__('slider ready','cf7-grid-layout')?></a>
      </li>
      <li><?= __('Event: ','cf7-grid-layout')?>
        <a class="helper" data-cf72post="/* event fired when a new slide is active, click to insert helper code into your js file. */
$('#cf7sg-form-{$cf7_key} form.wpcf7-form').on( 'sgSlideChange','.cf7sg-slider-section', function(e){
  //slide indexes are 0 based.
  //e['current'] holds the index of the current slide
  //e['last'] holds the index of the last slide.
  //you can add a CSS id to each div.container.cf7sg-collapsible elements
  //  whithin the div.cf7sg-slider-section element to uniquely identify each slide $slide.attr('id');
  // $form is the form jquery object.
  let $form = $(e.delegateTarget), $slider=$(this), $slide = $(e.target),
    current = e.current, last = e.last;
});" href="javascript:void(0);"><?=__('on slide change','cf7-grid-layout')?></a>
      </li>
      <li><?= __('Function: ','cf7-grid-layout')?>
        <a class="helper slider" data-cf72post="
  // $slider is the slider jquery object. Function can be chained.
  //index can either be empty/null, which will move to the next clide.
  //a positive index of an existing slide to move (slide index is 0 based) or,
  // -1 to move to the previous slide.
  $slider.sgChangeSlide(index);/* change slides. */" href="javascript:void(0);"><?=__('change slide','cf7-grid-layout')?></a>
      </li>
      <li><?= __('Function: ','cf7-grid-layout')?>
        <a class="helper slider" data-cf72post="
  // $slider is the slider jquery object.  The slide index is 0 based,
  let idx = $slider.sgCurrentSlide();/* get the current slide index. */" href="javascript:void(0);"><?=__('current slide index','cf7-grid-layout')?></a>
      </li>
    </ul>
  </li>
  <li id="field-events" class="show-events"><?=__('Form fields','cf7-grid-layout')?><span>&gt;</span>
    <ul>
      <li><?= __('Event: ','cf7-grid-layout')?>
        <a class="helper all-fields" data-cf72post="/* event fired when a field changes value, click to insert helper code into your js file. */
$('#cf7sg-form-{$cf7_key} form.wpcf7-form').on( 'change',':input', function(e){
  let $form = $(e.delegateTarget), $field=$(this), fieldName = $field.attr('name');
  {$array_field_extraction}
  // $form is the form jquery object.
  // $field is the input field jquery object.
  //
  switch(fieldName){{$list_of_fields}}
});" href="javascript:void(0);"><?=__('value change','cf7-grid-layout')?></a>
      </li>
      <li><?= __('Function: ','cf7-grid-layout')?>
        <a class="helper all-fields" data-cf72post="
  // $form is the form jquery object.
  // fieldName is the name of the field you need to retrieve.
  // you can use tab/row indexes to retrieve fields in tabbed sections and/or table structures.
  $field = $form.getCF7field(fieldName);/* function to retrieve a form field. */
  //if your field is in a tabbed/table structure OR a table within a tabbed section,
  //$form.getCF7field(fieldName, {'row':1,'tab':1}) - retrieves field on row 2 of tab 2.
  //$form.getCF7field(fieldName, {'row':0}) - field on first row OR all first row fields on all tabs.
  //$form.getCF7field(fieldName, {'tab':1}) - field in first tab OR fields of all rows on first tab.
  //$form.getCF7field(fieldName) - field in form OR all fields of all rows on all tabs." href="javascript:void(0);"><?=__('get form field','cf7-grid-layout')?></a>
      </li>
      <li><?= __('Function: ','cf7-grid-layout')?>
        <a class="helper" data-cf72post="
//call this function on a jQuery field object to display a dismissible warning/message popup.
//you can set an optional timeout in ms (1000ms = 1sec), after which the message will be automatically removed without user action.
$field.cf7sgWarning(message, timeout); /* function to display a dismissible field warning/message. */" href="javascript:void(0);"><?=__('display a message','cf7-grid-layout')?></a>
      </li>
    </ul>
  </li>
  <li id="other-events" class="display-none"><?=__('Others','cf7-grid-layout')?><span>&gt;</span>
    <ul>
      <?php do_action('cf7sg_admin_form_editor_jstags_other_items');?>
    </ul>
  </li>
  <li id="last-item">
    <label for="cf7sg-jstags-comments">
      <?php
      $checked=' checked=""';
      if(get_post_meta($post->ID, '_cf7sg_disable_jstags_comments',true)) $checked= '';
       ?>
      <input id="cf7sg-jstags-comments" type="checkbox" name="cf7sg_jstags_comments" value="true"<?=$checked?>/>
      <?=__('Show comments in helper code','cf7-grid-layout')?>
    </label>
    <?php do_action('cf7sg_admin_form_editor_jstags_last_item');?>
  </li>
</ul>
