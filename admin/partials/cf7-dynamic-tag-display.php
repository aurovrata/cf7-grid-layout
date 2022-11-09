<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://syllogic.in
 * @since      1.5.0
 *
 * @package    Cf7_2_Post
 * @subpackage Cf7_2_Post/admin/partials
 */
 //TODO: add a check box to include or not address fields
 $class = str_replace('_','-' ,$tag_id);
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div id="<?=$class?>-tag-generator" data-tag="<?=$tag_id?>" class="control-box cf7-<?=$class?> cf7sg-dynamic-list-tag-manager">
  <fieldset>
    <legend><?= sprintf(__('%s field','cf7-grid-layout'), $dlo->label)?></legend>
    <table  class="form-table">
      <tbody>
        <tr>
      	<th scope="row"><label for="<?= esc_attr( $args['content'] . '-name' ); ?>"><?= esc_html( __cf7sg( 'Name' ) ); ?></label></th>
      	<td><input type="text" name="name" class="tg-name oneline" id="<?= esc_attr( $args['content'] . '-name' ); ?>" /></td>
      	</tr>
        <tr>
        	<th scope="row"><?=__('Field type','cf7-grid-layout')?></th>
        	<td><input name="required" type="checkbox"><?=__('Required field','cf7-grid-layout')?><br /></td>
      	</tr>
        <tr>
          <th scope="row"><?=__('Id attribute','cf7-grid-layout')?></th>
          <td>
            <input name="id" class="idvalue oneline option" id="tag-generator-panel-dynamic-select-id" type="text">
          </td>
        </tr>
        <tr>
          <th scope="row"><?=__('Class attribute','cf7-grid-layout')?></th>
          <td>
            <input name="class" class="classvalue oneline option" id="tag-generator-panel-dynamic-select-class" type="text">
          </td>
        </tr>
        <tr>
          <th scope="row"><?=__('Dropdown style','cf7-grid-layout')?></th>
          <td class="cf7sg-dl-styles">
          <?php
            $styles = $dlo->get_tag_generator_styles();
            $checked = 'checked="checked"';
            $display_none = '';
            foreach($styles as $s=>$label):
              $id = $tag_id.'-'.$s;
            ?>
            <div>
              <label for="<?=$id?>">
                <input class="list-style <?=$tag_id?>" name="<?=$tag_id?>-style[]" id="<?=$id?>"  type="radio" value="<?=$s?>" <?=$checked?>/>
                <?=$label?>
                <?php
                $extras = $dlo->get_style_extras($s);
                // debug_msg($extras, "$tag_id ");
                foreach($extras as $val => $field):
                  $type = 'checkbox';
                  $label = '';
                  $attributes = 'disabled';
                  $html='';
                  if(is_array($field)){
                    if(isset($field['label'])) $label = $field['label'];
                    if(isset($field['type'])) $type = $field['type'];
                    if(isset($field['attrs'])) $attributes = $field['attrs'];
                    if(isset($field['html'])) $html = $field['html'];
                  }else{
                    $val = $s;
                    $html = $field;
                  }
                  $pre='';
                  $pst='';
                  switch($type){
                    case 'checkbox':
                      $pst=$label;
                      break;
                    case 'number':
                    case 'text':
                      $pre=$label;
                      break;
                    }
                 ?>
                 <span class="cf7sg-se-option cf7sg-se-<?=$val?><?=$display_none?>">
                   <?php if(!empty($label)):?>
                   <label for="<?=$s?>-<?=$val?>">
                     <?=$pre?><input id="<?=$s?>-<?=$val?>" type="<?=$type?>" value="<?=$val?>" <?=$attributes?>/><?=$pst?>
                   </label>
                 <?php endif;
                 if(!empty($html)):?>
                   <?=$html?>
                 <?php endif;?>
                 </span>
               <?php endforeach;?>
              </label>
            </div>
          <?php
              $checked = '';
              $display_none=' display-none';
            endforeach;?>
          </td>
        </tr>
        <tr class="others">
          <th scope="row"><?=__('Other attributes','cf7-grid-layout')?></th>
          <td>
            <?php
              $others = $dlo->get_other_extras();
              $type = $dlo->get_other_extras_type();
              $ckd = ' checked';
              foreach($others as $val=>$field):
                $label = '<em>unknown field</em>';
                $attributes = '';
                if(is_array($field)){
                  if(isset($field['label'])) $label = $field['label'];
                  if(isset($field['type'])) $type = $field['type'];
                  if(isset($field['attrs'])) $attributes = $field['attrs'];
                }else $label = $field;
                $pre='';
                $pst='';
                $name ='';
                switch($type){
                  case 'checkbox':
                    $pst=$label;
                    break;
                  case 'number':
                  case 'text':
                    $pre=$label;
                    break;
                  case 'radio':
                    $name = ' name="dl_extras[]"';
                    if(empty($val)) $name .=' checked';
                    break;
                  }
                ?>
                <div class="<?=$val?>">
                  <label for="<?=$tag_id?>-<?=$val?>">
                    <input class="select-<?=$val?>" id="<?=$tag_id?>-<?=$val?>" type="<?=$type?>" value="<?=$val?>"<?=$name?><?=$ckd?>/>
                    <?=$label?>
                  </label>
                </div>
                <?php
                $ckd='';
              endforeach;
            ?>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="tabordion cf7sg-dynamic-list-sources">
      <section class="taxonomy-source">
        <input type="radio" id="<?=$class?>-taxonomy-tab" name="sections" class="taxonomy-tab source-tab" checked>
        <label for="<?=$class?>-taxonomy-tab"><?=__('Taxonomy','cf7-grid-layout')?></label>
        <article>
          <h4><?=__('Taxonomy source','cf7-grid-layout')?></h4>
          <select class="taxonomy-list">
            <option value="" data-name="" selected="true" ><?=__('Choose a Taxonomy','cf7-grid-layout')?></option>
            <option class="cf7sg-new-taxonomy" value="new_taxonomy" data-name="New Category"><?=__('New Categories','cf7-grid-layout')?></option>
          <?php
          //get options.
          $dropdowns = get_option('_cf7sg_dynamic_dropdown_taxonomy',array());
          $slugs = array();

          foreach($dropdowns as $post_id=>$all_lists){
            foreach($all_lists as $slug => $taxonomy){
              if(isset($slugs[$slug]) ){
                continue;
              }else{
                $slugs[$slug] = $slug;
              }
              echo '<option data-name="' . $taxonomy['singular'] . '" value="'. $taxonomy['slug'] . '" class="cf7sg-taxonomy'.$taxonomy['hierarchical']?' hierarchical':''.'">' . $taxonomy['plural'] . '</option>';
            }
          }
          //inset the default post tags and category
          ?>
          <option value="post_tag" data-name="Post Tag" class="system-taxonomy"><?=__('Post Tags','cf7-grid-layout')?></option>
          <option value="category" data-name="Post Category" class="system-taxonomy hierarchical"><?=__('Post Categories','cf7-grid-layout')?></option>
          <?php
          $system_taxonomies = get_taxonomies( array('public'=>true, '_builtin' => false), 'objects' );
          foreach($system_taxonomies as $taxonomy){
            if( !empty($taxonomy_slug) && $taxonomy_slug == $taxonomy->name ) continue;
            echo '<option value="' . $taxonomy->name . '" data-name="' . $taxonomy->labels->singular_name . '" class="system-taxonomy'.$taxonomy->hierarchical? ' hierarchical':''.'">' . $taxonomy->labels->name . '</option>';
          }
          ?>
          </select>
          <?php if($dlo->has_nesting()):/*@since 4.11 enable nested lists*/?>
          <label id="enable-branches" class="display-none">
            <input type="checkbox" />
            <?= __('Include branches','cf7-grid-layout');?>
          </label>
        <?php endif;?>
          <div class="cf72post-new-taxonomy">
            <div><strong><?=__('New Taxonomy','cf7-grid-layout')?></strong></div>
            <label><?=__('Plural Name','cf7-grid-layout')?><br />
            <input disabled="true" class="cf72post-new-taxonomy" type="text" name="plural_name" value=""></label>
            <label ><?=__('Singular Name','cf7-grid-layout')?><br />
            <input disabled="true"  class="cf72post-new-taxonomy" type="text" name="singular_name" value=""></label>
            <label><?=__('Slug','cf7-grid-layout')?><br />
            <input disabled="true"  class="cf72post-new-taxonomy" type="text" name="taxonomy_slug" value="" /></label>
            <label class="hidden"><input class="cf72post-new-taxonomy" type="checkbox" name="is_hierarchical" /><?=__('hierarchical','cf7-grid-layout')?></label>
          </div>
          <?php do_action('cf7sg_dynamic_tag_manager_taxonomy_source', $tag_id); ?>
        </article>
      </section>
      <section class="post-source">
        <input type="radio" id="<?=$class?>-post-tab" name="sections" class="post-tab source-tab">
        <label for="<?=$class?>-post-tab"><?=__('Post','cf7-grid-layout')?></label>
        <article class="">
          <h4><?=__('Post source','cf7-grid-layout')?></h4>
          <select id="<?=$class?>-post-list" class="post-list" name="<?=$tag_id?>_post_list">
            <option value="" selected><?=__('Select a post','cf7-grid-layout')?></option>
            <?php
            $args = array(
               'show_ui'   => true,
               '_builtin' => false
            );
            $output = 'objects'; // names or objects, note names is the default
            $operator = 'and'; // 'and' or 'or'

            $post_types = get_post_types( $args, $output, $operator );
            foreach($post_types as $type=>$post){
              echo '<option value="'.$type.'">'.$post->labels->name.'</option>';
              $taxonomies = get_object_taxonomies( $type, 'objects' );

              $taxonomy_lists[$type] = '';
              foreach($taxonomies as $taxonomy){
                //skup cf7 dynamic list taxonomies.
                if(WPCF7_ContactForm::post_type == $type && 'wpcf7_type' != $taxonomy->name) continue;
                if(empty($taxonomy->label)) continue;
                $taxonomy_lists[$type] .= '<optgroup label="'.$taxonomy->label.'">'.PHP_EOL;
                $taxonomy_lists[$type] .= $dlo->cf7sg_terms_to_options($taxonomy->name, $taxonomy->hierarchical);
                $taxonomy_lists[$type] .= '</optgroup>'.PHP_EOL;
              }
            }
            $taxonomies = get_object_taxonomies( 'post', 'objects' );
            $taxonomy_lists['post'] = '';
            foreach($taxonomies as $taxonomy){
              if(empty($taxonomy->label)) continue;
              $taxonomy_lists['post'] .= '<optgroup label="'.$taxonomy->label.'">'.PHP_EOL;
              $taxonomy_lists['post'] .= $dlo->cf7sg_terms_to_options($taxonomy->name, $taxonomy->hierarchical);
              $taxonomy_lists['post'] .= '</optgroup>'.PHP_EOL;
            }
            $taxonomies = get_object_taxonomies( 'page', 'objects' );
            $taxonomy_lists['page'] = '';
            foreach($taxonomies as $taxonomy){
              if(empty($taxonomy->label)) continue;
              $taxonomy_lists['page'] .= '<optgroup label="'.$taxonomy->label.'">'.PHP_EOL;
              $taxonomy_lists['page'] .= $dlo->cf7sg_terms_to_options($taxonomy->name, $taxonomy->hierarchical);
              $taxonomy_lists['page'] .= '</optgroup>'.PHP_EOL;
            }
            ?>
            <option value="post"><?=__('Posts','cf7-grid-layout')?></option>
            <option value="page"><?=__('Pages','cf7-grid-layout')?></option>
          </select>
          <div class="<?=$class?>-post-options">
            <label for="<?=$class?>-post-links" class="<?=$class?> include-links">
              <input id="<?=$class?>-post-links" value="include_links" name="<?=$tag_id?>_post_links" type="checkbox" class="include-post-links"/><?= __('Include post links','cf7-grid-layout')?>
            </label>
            <label for="<?=$class?>-post-images" class="<?=$class?> include-images">
              <input id="<?=$class?>-post-images" value="include_imgs" name="<?=$tag_id?>_post_imgs" type="checkbox" class="include-post-images"/><?= __('Include post thumbnails','cf7-grid-layout')?>
            </label>
          </div>

  <?php foreach($taxonomy_lists as $type=>$list ):
          if(empty($list)) continue;
    ?>
          <div id="" class="post-taxonomies cf7sg-dynamic-tag hidden <?= $type ?>">
            <select id="<?=$class?>-<?=$type?>" multiple class="select2" name="<?=$tag_id?>_<?=$type?>">
              <option value=""><?=__('Filter by terms', 'cf7-grid-layout')?></option>
              <?= $list?>
            </select>
          </div>
  <?php endforeach;  ?>
  <?php do_action('cf7sg_dynamic_tag_manager_post_source', $tag_id); ?>
        </article>
      </section>
      <section class="custom-source">
        <input type="radio" id="<?=$class?>-custom-tab" name="sections" class="custom-tab source-tab">
        <label for="<?=$class?>-custom-tab"><?=__('Custom','cf7-grid-layout')?></label>
        <article>
          <h4><?=__('Custom source','cf7-grid-layout')?></h4>
          <p class="position-relative filter-hook">
            <?php
            /** @since 4.11.0 generalise hook jelper code initialisation.
            * use the provided template to display a message to copy the filter link.
            * Insert the filter class name into the template, that matches the filter added to the list in
            * the file admin/partials/helpers/cf7sg-form-fields.php.
            */
            echo sprintf(__('Copy the following <a class="%s" href="javascript:void(0);">filter</a> to your <em>functions.php</em> file.', 'cf7-grid-layout'), 'cf7sg_filter_source');?>
          </p>
          <?php do_action('cf7sg_dynamic_tag_manager_custom_source', $tag_id); ?>
        </article>
      </section>
    </div> <!-- end-tabs-->
    <?php do_action('cf7sg_dynamic_tag_manager_end', $tag_id); ?>
  </fieldset>
</div>
<div class="insert-box cf7sg-dynamic-tag-submit">
  <input type="hidden" name="values" value="" />
  <input type="text" name="<?=$tag_id?>" class="tag code" readonly="readonly" onfocus="this.select()" />

  <div class="submitbox ">
      <input type="button" class="button button-primary insert-tag" value="<?= esc_attr( __cf7sg( 'Insert Tag' ) ); ?>" />
  </div>

  <br class="clear" />
</div>
