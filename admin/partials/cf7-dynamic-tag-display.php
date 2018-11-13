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
    <legend>Dynamic Select Dropdown field</legend>
    <table  class="form-table">
      <tbody>
        <tr>
      	<th scope="row"><label for="<?= esc_attr( $args['content'] . '-name' ); ?>"><?= esc_html( __( 'Name', 'contact-form-7' ) ); ?></label></th>
      	<td><input type="text" name="name" class="tg-name oneline" id="<?= esc_attr( $args['content'] . '-name' ); ?>" /></td>
      	</tr>
        <tr>
        	<th scope="row">Field type</th>
        	<td><input name="required" type="checkbox"> Required field<br /></td>
      	</tr>
        <tr>
          <th scope="row">Id attribute</th>
          <td>
            <input name="id" class="idvalue oneline option" id="tag-generator-panel-dynamic-select-id" type="text">
          </td>
        </tr>
        <tr>
          <th scope="row">Class attribute</th>
          <td>
            <input name="class" class="classvalue oneline option" id="tag-generator-panel-dynamic-select-class" type="text">
          </td>
        </tr>
        <tr>
          <th scope="row">Dropdown style</th>
          <td>
            <div>
              <input name="select-style[]" class=" select-type "  type="radio" value="select" checked="checked"/>
              <label>HTML Select field</label>
            </div>
            <div>
              <input name="select-style[]" class=" select-type "  type="radio" value="nice" />
              <label><a target="_blank" href="http://hernansartorio.com/jquery-nice-select/">jQuery Nice Select</a></label>
            </div>
            <div>
              <input name="select-style[]" class=" select-type "  type="radio" value="select2" />
              <label><a target="_blank" href="https://select2.github.io/">jQuery Select2</a></label>
              <input name="select2-tags" id="select2-tags" type="checkbox" disabled value="select2tags"/>
              <label for="select2-tags"><a target="_blank" href="https://select2.github.io/examples.html#tags">Enable user options</a></label>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <div id="dynamic-dropdown-sources" class="tabordion">
      <section id="taxonomy-source">
        <input type="radio" name="sections" id="taxonomy-tab" checked>
        <label for="taxonomy-tab">Taxonomy</label>
        <article>
          <h4>Taxonomy source</h4>
          <select class="taxonomy-list">
            <option value="" data-name="" >Choose a Taxonomy</option>
            <option class="cf7sg-new-taxonomy" value="new_taxonomy" data-name="New Category">New Categories</option>
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
          <option value="post_tag" data-name="Post Tag" class="system-taxonomy">Post Tags</option>
          <option value="category" data-name="Post Category" class="system-taxonomy">Post Categories</option>
          <?php
          $system_taxonomies = get_taxonomies( array('public'=>true, '_builtin' => false), 'objects' );
          foreach($system_taxonomies as $taxonomy){
            if( !empty($taxonomy_slug) && $taxonomy_slug == $taxonomy->name ) continue;
            echo '<option value="' . $taxonomy->name . '" data-name="' . $taxonomy->labels->singular_name . '" class="system-taxonomy">' . $taxonomy->labels->name . '</option>';
          }
          ?>
          </select>
          <div class="cf72post-new-taxonomy">
            <div><strong>New Taxonomy</strong></div>
            <label>Plural Name<br />
            <input disabled="true" class="cf72post-new-taxonomy" type="text" name="plural_name" value=""></label>
            <label >Singular Name<br />
            <input disabled="true"  class="cf72post-new-taxonomy" type="text" name="singular_name" value=""></label>
            <label>Slug<br />
            <input disabled="true"  class="cf72post-new-taxonomy" type="text" name="taxonomy_slug" value="" /></label>
            <label class="hidden"><input class="cf72post-new-taxonomy" type="checkbox" name="is_hierarchical" />hierarchical</label>
          </div>
        </article>
      </section>
      <section id="post-source">
        <input type="radio" name="sections" id="post-tab">
        <label for="post-tab">Post</label>
        <article class="">
          <h4>Post source</h4>
          <select class="post-list">
            <option value="">Select a post</option>
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
            <option value="post">Posts</option>
            <option value="page">Pages</option>
          </select>

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
        <label for="custom-tab">Custom</label>
        <article>
          <h4>Custom source</h4>
        </article>
      </section>
    </div> <!-- end-tabs-->

  </fieldset>
</div>
<div class="insert-box">
  <input type="hidden" name="values" value="" />
  <input type="text" name="dynamic-select" class="tag code" readonly="readonly" onfocus="this.select()" />

  <div class="submitbox">
      <input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
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
