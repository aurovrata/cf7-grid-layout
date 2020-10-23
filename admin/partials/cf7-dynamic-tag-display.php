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
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div id="dynamic-select-tag-generator" class="control-box cf7-dynamic-select">
  <fieldset>
    <legend><?= __('Dynamic Select Dropdown field','cf7-grid-layout')?></legend>
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
            <div>
              <label for="html-select">
                <input name="select-style[]" id="html-select" class=" select-type "  type="radio" value="select" checked="checked"/>
                <?=__('HTML Select field','cf7-grid-layout')?>
              </label>
            </div>
            <div>
              <label for="nice-select">
                <input id="nice-select" name="select-style[]" class=" select-type "  type="radio" value="nice" />
                <a target="_blank" href="http://hernansartorio.com/jquery-nice-select/"><?=__('jQuery Nice Select','cf7-grid-layout')?></a>
              </label>
            </div>
            <div>
              <label for="select2-select">
                <input id="select2-select" name="select-style[]" class=" select-type "  type="radio" value="select2" />
                <a target="_blank" href="https://select2.org/getting-started/basic-usage"><?=__('jQuery Select2','cf7-grid-layout')?></a>
              </label>
              <label for="select2-tags" class="display-none">
                <input name="select2-tags" id="select2-tags" type="checkbox" disabled value="select2tags"/>
                <a target="_blank" href="https://select2.org/tagging"><?=__('Enable user options','cf7-grid-layout')?></a>
              </label>
            </div>
          </td>
        </tr>
        <tr>
          <th scope="row"><?=__('Mutliple attribute','cf7-grid-layout')?></th>
          <td>
            <input name="select-multiple" id="select-multiple" type="checkbox" value="multiple"/>
            <a target="_blank" href="https://www.w3schools.com/tags/att_select_multiple.asp"><?=__('Enable multiple selection','cf7-grid-layout')?></a>
          </td>
        </tr>
      </tbody>
    </table>
    <div id="dynamic-dropdown-sources" class="tabordion">
      <section id="taxonomy-source">
        <input type="radio" name="sections" id="taxonomy-tab" checked>
        <label for="taxonomy-tab"><?=__('Taxonomy','cf7-grid-layout')?></label>
        <article>
          <h4><?=__('Taxonomy source','cf7-grid-layout')?></h4>
          <select class="taxonomy-list">
            <option value="" data-name="" ><?=__('Choose a Taxonomy','cf7-grid-layout')?></option>
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
              echo '<option selected data-name="' . $taxonomy['singular'] . '" value="'. $taxonomy['slug'] . '" class="cf7sg-taxonomy">' . $taxonomy['plural'] . '</option>';
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
      <section id="post-source">
        <input type="radio" name="sections" id="post-tab">
        <label for="post-tab"><?=__('Post','cf7-grid-layout')?></label>
        <article class="">
          <h4><?=__('Post source','cf7-grid-layout')?></h4>
          <select class="post-list">
            <option value=""><?=__('Select a post','cf7-grid-layout')?></option>
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
                $taxonomy_lists[$type] .= '<optgroup label="'.$taxonomy->label.'">'.PHP_EOL;
                $taxonomy_lists[$type] .= cf7sg_terms_to_options($taxonomy->name, $taxonomy->hierarchical,0);
                $taxonomy_lists[$type] .= '</optgroup>'.PHP_EOL;
              }
            }
            $taxonomies = get_object_taxonomies( 'post', 'objects' );
            $taxonomy_lists['post'] = '';
            foreach($taxonomies as $taxonomy){
              $taxonomy_lists['post'] .= '<optgroup label="'.$taxonomy->label.'">'.PHP_EOL;
              $taxonomy_lists['post'] .= cf7sg_terms_to_options($taxonomy->name, $taxonomy->hierarchical,0);
              $taxonomy_lists['post'] .= '</optgroup>'.PHP_EOL;
            }
            $taxonomies = get_object_taxonomies( 'page', 'objects' );
            $taxonomy_lists['page'] = '';
            foreach($taxonomies as $taxonomy){
              $taxonomy_lists['page'] .= '<optgroup label="'.$taxonomy->label.'">'.PHP_EOL;
              $taxonomy_lists['page'] .= cf7sg_terms_to_options($taxonomy->name, $taxonomy->hierarchical,0);
              $taxonomy_lists['page'] .= '</optgroup>'.PHP_EOL;
            }
            ?>
            <option value="post"><?=__('Posts','cf7-grid-layout')?></option>
            <option value="page"><?=__('Pages','cf7-grid-layout')?></option>
          </select>
          <label><input type="checkbox" id="include-post-links"/><?= __('Include post links','cf7-grid-layout')?></label>

  <?php foreach($taxonomy_lists as $type=>$list ):
          if(empty($list)) continue;
    ?>
          <div id="<?php echo $type ?>" class="post-taxonomies hidden">
            <select multiple class="select2">
              <?php echo $list?>
            </select>
          </div>
  <?php endforeach;  ?>
        </article>
      </section>
      <section id="custom-source">
        <input type="radio" name="sections" id="custom-tab">
        <label for="custom-tab"><?=__('Custom','cf7-grid-layout')?></label>
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
<div class="insert-box">
  <input type="hidden" name="values" value="" />
  <input type="text" name="dynamic-select" class="tag code" readonly="readonly" onfocus="this.select()" />

  <div class="submitbox">
      <input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __cf7sg( 'Insert Tag' ) ); ?>" />
  </div>

  <br class="clear" />
</div>
<?php
/*
Added functionality
*/
function cf7sg_terms_to_options($taxonomy, $is_hierarchical, $parent=0){
  $args = array('hide_empty' => 0);
  if($is_hierarchical){
    $args['parent'] = $parent;
  }
  //check the WP version
  global $wp_version;
  if ( $wp_version >= 4.5 ) {
    $args['taxonomy'] = $taxonomy;
    $terms = get_terms($args); //WP>= 4.5 the get_terms does not take a taxonomy slug field
  }else{
    $terms = get_terms($taxonomy, $args);
  }
  if( is_wp_error( $terms ) ){
    debug_msg('Taxonomy '.$taxonomy.' does not exist');
    return '';
  }else if( empty($terms) ){
    return'';
  }
  if(0==$parent) $class = 'parent';
  else $class = 'child';
  $script = '';
  foreach($terms as $term){
    $script .='<option value="taxonomy:' . $taxonomy . ':' . $term->slug . '" >' . $term->name . '</option>' . PHP_EOL;
    if($is_hierarchical){
      $script .= cf7sg_terms_to_options($taxonomy, $is_hierarchical, $term->term_id);
    }
  }
  return $script;
}

?>
