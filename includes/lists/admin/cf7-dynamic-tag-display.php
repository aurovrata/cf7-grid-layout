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
 $class = str_replace('_','-' ,$this->tag_id);
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div id="<?=$class?>-tag-generator" class="control-box cf7-<?=$class?> cf7sg-dynamic-list-tag-manager">
  <fieldset>
    <legend><?= sprintf(__('%s field','cf7-grid-layout'), $this->label)?></legend>
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
          <td>
          <?php
            $styles = $this->admin_generator_tag_styles();
            $checked = 'checked="checked"';
            foreach($styles as $s=>$label):
              $id = $this->tag_id.'-'.$s;
            ?>
            <div>
              <label for="<?=$id?>">
                <input class="list-style <?=$this->tag_id?>" name="<?=$this->tag_id?>-style[]" id="<?=$id?>"  type="radio" value="<?=$s?>" <?=$checked?>/>
                <?=$label?>
                <?php do_action('cf7sg_'.$this->tag_id.'_admin_tag_style-'.$s) ?>
              </label>
            </div>
          <?php
              $checked = '';
            endforeach;?>
          </td>
        </tr>
        <tr class="others">
          <th scope="row"><?=__('Other attributes','cf7-grid-layout')?></th>
          <td>
            <span class="multiple">
              <label for="<?=$this->tag_id?>-multiple">
                <input class="select-multiple" id="<?=$this->tag_id?>-multiple" type="checkbox" value="multiple"/>
                <a target="_blank" href="https://www.w3schools.com/tags/att_select_multiple.asp"><?=__('Enable multiple selection','cf7-grid-layout')?></a>
              </label>
            </span>
            <?php do_action('cf7sg_dynamic_list_tag_manager_options', $this->tag_id);?>
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

          foreach($dropdowns as $all_lists){
            foreach($all_lists as $slug => $taxonomy){
              if(isset($slugs[$slug]) ){
                continue;
              }else{
                $slugs[$slug] = $slug;
              }
              echo '<option data-name="' . $taxonomy['singular'] . '" value="'. $taxonomy['slug'] . '" class="cf7sg-taxonomy">' . $taxonomy['plural'] . '</option>';
            }
          }
          //inset the default post tags and category
          ?>
          <option value="post_tag" data-name="Post Tag" class="system-taxonomy"><?=__('Post Tags','cf7-grid-layout')?></option>
          <option value="category" data-name="Post Category" class="system-taxonomy"><?=__('Post Categories','cf7-grid-layout')?></option>
          <?php
          $system_taxonomies = get_taxonomies( array('public'=>true, '_builtin' => false), 'objects' );
          foreach($system_taxonomies as $taxonomy){
            if( !empty($taxonomy_slug) && $taxonomy_slug == $taxonomy->name ) continue;
            echo '<option value="' . $taxonomy->name . '" data-name="' . $taxonomy->labels->singular_name . '" class="system-taxonomy">' . $taxonomy->labels->name . '</option>';
          }
          ?>
          </select>
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
        </article>
      </section>
      <section class="post-source">
        <input type="radio" id="<?=$class?>-post-tab" name="sections" class="post-tab source-tab">
        <label for="<?=$class?>-post-tab"><?=__('Post','cf7-grid-layout')?></label>
        <article class="">
          <h4><?=__('Post source','cf7-grid-layout')?></h4>
          <select id="<?=$class?>-post-list" class="post-list" name="<?=$this->tag_id?>_post_list">
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
                $taxonomy_lists[$type] .= $this->cf7sg_terms_to_options($taxonomy->name, $taxonomy->hierarchical);
                $taxonomy_lists[$type] .= '</optgroup>'.PHP_EOL;
              }
            }
            $taxonomies = get_object_taxonomies( 'post', 'objects' );
            $taxonomy_lists['post'] = '';
            foreach($taxonomies as $taxonomy){
              if(empty($taxonomy->label)) continue;
              $taxonomy_lists['post'] .= '<optgroup label="'.$taxonomy->label.'">'.PHP_EOL;
              $taxonomy_lists['post'] .= $this->cf7sg_terms_to_options($taxonomy->name, $taxonomy->hierarchical);
              $taxonomy_lists['post'] .= '</optgroup>'.PHP_EOL;
            }
            $taxonomies = get_object_taxonomies( 'page', 'objects' );
            $taxonomy_lists['page'] = '';
            foreach($taxonomies as $taxonomy){
              if(empty($taxonomy->label)) continue;
              $taxonomy_lists['page'] .= '<optgroup label="'.$taxonomy->label.'">'.PHP_EOL;
              $taxonomy_lists['page'] .= $this->cf7sg_terms_to_options($taxonomy->name, $taxonomy->hierarchical);
              $taxonomy_lists['page'] .= '</optgroup>'.PHP_EOL;
            }
            ?>
            <option value="post"><?=__('Posts','cf7-grid-layout')?></option>
            <option value="page"><?=__('Pages','cf7-grid-layout')?></option>
          </select>
          <div class="<?=$class?>-post-options">
            <label for="<?=$class?>-post-links" class="<?=$class?> include-links">
              <input id="<?=$class?>-post-links" value="include_links" name="<?=$this->tag_id?>_post_links" type="checkbox" class="include-post-links"/><?= __('Include post links','cf7-grid-layout')?>
            </label>
            <label for="<?=$class?>-post-images" class="<?=$class?> include-images">
              <input id="<?=$class?>-post-images" value="include_imgs" name="<?=$this->tag_id?>_post_imgs" type="checkbox" class="include-post-images"/><?= __('Include post thumbnails','cf7-grid-layout')?>
            </label>
          </div>



  <?php foreach($taxonomy_lists as $type=>$list ):
          if(empty($list)) continue;
    ?>
          <div id="" class="post-taxonomies cf7sg-dynamic-tag hidden <?= $type ?>">
            <select id="<?=$class?>-<?=$type?>" multiple class="select2" name="<?=$this->tag_id?>_<?=$type?>">
              <option value=""><?=__('Filter by terms', 'cf7-grid-layout')?></option>
              <?= $list?>
            </select>
          </div>
  <?php endforeach;  ?>
        </article>
      </section>
      <section class="custom-source">
        <input type="radio" id="<?=$class?>-custom-tab" name="sections" class="custom-tab source-tab">
        <label for="<?=$class?>-custom-tab"><?=__('Custom','cf7-grid-layout')?></label>
        <article>
          <h4><?=__('Custom source','cf7-grid-layout')?></h4>
          <p class="position-relative">
            <?= __('Copy the following <a href="javascript:void(0);">filter</a> to your <em>functions.php</em> file.', 'cf7-grid-layout');?>
          </p>
        </article>
      </section>
    </div> <!-- end-tabs-->

  </fieldset>
</div>
<div class="insert-box cf7sg-dynamic-tag-submit">
  <input type="hidden" name="values" value="" />
  <input type="text" name="<?=$class?>" class="tag code" readonly="readonly" onfocus="this.select()" />

  <div class="submitbox ">
      <input type="button" class="button button-primary insert-tag" value="<?= esc_attr( __cf7sg( 'Insert Tag' ) ); ?>" />
  </div>

  <br class="clear" />
</div>
