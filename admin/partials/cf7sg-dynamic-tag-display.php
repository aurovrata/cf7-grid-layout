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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$class = str_replace( '_', '-', $tag_id );
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div id="<?php echo esc_attr( $class ); ?>-tag-generator" data-tag="<?php echo esc_attr( $tag_id ); ?>" class="control-box cf7-<?php echo esc_attr( $class ); ?> cf7sg-dynamic-list-tag-manager">
	<fieldset>
	<legend><?php /* translators: field type, either list or checkbox*/echo esc_html( sprintf( __( '%s field', 'cf7-grid-layout' ), $dlo->label ) ); ?></legend>
	<table  class="form-table">
		<tbody>
		<tr>
			<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __cf7sg( 'Name' ) ); ?></label></th>
			<td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
			</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'Field type', 'cf7-grid-layout' ); ?></th>
			<td><input name="required" type="checkbox"><?php esc_html_e( 'Required field', 'cf7-grid-layout' ); ?><br /></td>
			</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'Id attribute', 'cf7-grid-layout' ); ?></th>
			<td>
			<input name="id" class="idvalue oneline option" id="tag-generator-panel-dynamic-select-id" type="text">
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'Class attribute', 'cf7-grid-layout' ); ?></th>
			<td>
			<input name="class" class="classvalue oneline option" id="tag-generator-panel-dynamic-select-class" type="text">
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'Dropdown style', 'cf7-grid-layout' ); ?></th>
			<td class="cf7sg-dl-styles">
			<?php
			$styles       = $dlo->get_tag_generator_styles();
			$checked      = 'checked="checked"';
			$display_none = '';
			foreach ( $styles as $st => $label ) :
				$tid = $tag_id . '-' . $st;
				?>
			<div>
				<label for="<?php echo esc_attr( $tid ); ?>">
				<input class="list-style <?php echo esc_attr( $tag_id ); ?>" name="<?php echo esc_attr( $tag_id ); ?>-style[]" id="<?php echo esc_attr( $tid ); ?>"  type="radio" value="<?php echo esc_attr( $st ); ?>" <?php echo esc_attr( $checked ); ?>/>
				<?php echo wp_kses( $label, $dlo->get_allowed_html() ); ?>
				</label>
							<?php
							$extras = $dlo->get_style_extras( $st );
							foreach ( $extras as $val => $field ) :
								$tp         = 'checkbox';
								$label      = '';
								$attributes = 'disabled';
								$html       = '';
								if ( is_array( $field ) ) {
									if ( isset( $field['label'] ) ) {
										$label = $field['label'];
									}
									if ( isset( $field['type'] ) ) {
										$tp = $field['type'];
									}
									if ( isset( $field['attrs'] ) ) {
										$attributes = $field['attrs'];
									}
									if ( isset( $field['html'] ) ) {
										$html = $field['html'];
									}
								} else {
									$val  = $st;
									$html = $field;
								}
								$pre = '';
								$pst = '';
								switch ( $tp ) {
									case 'checkbox':
										$pst = $label;
										break;
									case 'number':
									case 'text':
										$pre = $label;
										break;
								}
								?>
								<span class="cf7sg-se-option cf7sg-se-<?php echo esc_attr( $val ); ?><?php echo esc_attr( $display_none ); ?>">
									<?php if ( ! empty( $label ) ) : ?>
									<label for="<?php echo esc_attr( $st ); ?>-<?php echo esc_attr( $val ); ?>">
										<?php echo wp_kses( $pre, $dlo->get_allowed_html() ); ?><input id="<?php echo esc_attr( $st ); ?>-<?php echo esc_attr( $val ); ?>" type="<?php echo esc_attr( $tp ); ?>" value="<?php echo esc_attr( $val ); ?>" <?php echo esc_attr( $attributes ); ?>/><?php echo wp_kses( $pst, $dlo->get_allowed_html() ); ?>
									</label>
										<?php
								endif;
									if ( ! empty( $html ) ) :
										?>
										<?php echo wp_kses( $html, $dlo->get_allowed_html() ); ?>
									<?php endif; ?>
								</span>
				<?php endforeach; ?>
			</div>
				<?php
				$checked      = '';
				$display_none = ' display-none';
			endforeach;
			?>
			</td>
		</tr>
		<tr class="others">
			<th scope="row"><?php esc_html_e( 'Other attributes', 'cf7-grid-layout' ); ?></th>
			<td>
			<?php
				$others = $dlo->get_other_extras();
				$tp     = $dlo->get_other_extras_type();
				$ckd    = ' checked';
			foreach ( $others as $val => $field ) :
				$label      = '<em>unknown field</em>';
				$attributes = '';
				if ( is_array( $field ) ) {
					if ( isset( $field['label'] ) ) {
						$label = $field['label'];
					}
					if ( isset( $field['type'] ) ) {
						$tp = $field['type'];
					}
					if ( isset( $field['attrs'] ) ) {
						$attributes = $field['attrs'];
					}
				} else {
					$label = $field;
				}
				$pre  = '';
				$pst  = '';
				$name = '';
				switch ( $tp ) {
					case 'checkbox':
						$pst = $label;
						break;
					case 'number':
					case 'text':
						$pre = $label;
						break;
					case 'radio':
						$name = ' name="dl_extras[]"';
						if ( empty( $val ) ) {
							$name .= ' checked';
						}
						break;
				}
				?>
				<div class="<?php echo esc_attr( $val ); ?>">
					<label for="<?php echo esc_attr( $tag_id ); ?>-<?php echo esc_attr( $val ); ?>">
					<input class="select-<?php echo esc_attr( $val ); ?>" id="<?php echo esc_attr( $tag_id ); ?>-<?php echo esc_attr( $val ); ?>" type="<?php echo esc_attr( $tp ); ?>" value="<?php echo esc_attr( $val ); ?>"<?php echo esc_attr( $name ); ?><?php echo esc_attr( $ckd ); ?>/>
					<?php echo wp_kses( $label, $dlo->get_allowed_html() ); ?>
					</label>
				</div>
				<?php
				$ckd = '';
				endforeach;
			?>
			</td>
		</tr>
		</tbody>
	</table>
	<div class="tabordion cf7sg-dynamic-list-sources">
		<section class="taxonomy-source">
		<input type="radio" id="<?php echo esc_attr( $class ); ?>-taxonomy-tab" name="sections" class="taxonomy-tab source-tab" checked>
		<label for="<?php echo esc_attr( $class ); ?>-taxonomy-tab"><?php esc_html_e( 'Taxonomy', 'cf7-grid-layout' ); ?></label>
		<article>
			<h4><?php esc_html_e( 'Taxonomy source', 'cf7-grid-layout' ); ?></h4>
			<select class="taxonomy-list">
			<option value="" data-name="" selected="true" ><?php esc_html_e( 'Choose a Taxonomy', 'cf7-grid-layout' ); ?></option>
			<option class="cf7sg-new-taxonomy" value="new_taxonomy" data-name="New Category"><?php esc_html_e( 'New Categories', 'cf7-grid-layout' ); ?></option>
			<?php
			// get options.
			$dropdowns = get_option( '_cf7sg_dynamic_dropdown_taxonomy', array() );
			$slugs     = array();

			foreach ( $dropdowns as $pid => $all_lists ) {
				foreach ( $all_lists as $slug => $taxnmy ) {
					if ( isset( $slugs[ $slug ] ) ) {
						continue;
					} else {
						$slugs[ $slug ] = $slug;
					}
					echo '<option data-name="' . esc_attr( $taxnmy['singular'] ) . '" value="' . esc_attr( $taxnmy['slug'] ) . '" class="cf7sg-taxonomy' . ( $taxnmy['hierarchical'] ? ' hierarchical' : '' ) . '">' . esc_html( $taxnmy['plural'] ) . '</option>';
				}
			}
			// inset the default post tags and category.
			?>
			<option value="post_tag" data-name="Post Tag" class="system-taxonomy"><?php esc_html_e( 'Post Tags', 'cf7-grid-layout' ); ?></option>
			<option value="category" data-name="Post Category" class="system-taxonomy hierarchical"><?php esc_html_e( 'Post Categories', 'cf7-grid-layout' ); ?></option>
			<?php
			$system_taxonomies = get_taxonomies(
				array(
					'public'   => true,
					'_builtin' => false,
				),
				'objects'
			);
			foreach ( $system_taxonomies as $taxnmy ) {
				if ( ! empty( $taxnmy_slug ) && $taxnmy_slug === $taxnmy->name ) {
					continue;
				}
				echo '<option value="' . esc_attr( $taxnmy->name ) . '" data-name="' . esc_attr( $taxnmy->labels->singular_name ) . '" class="system-taxonomy' . ( $taxnmy->hierarchical ? ' hierarchical' : '' ) . '">' . esc_html( $taxnmy->labels->name ) . '</option>';
			}
			?>
			</select>
			<?php if ( $dlo->has_nesting() ) :/*@since 4.11 enable nested lists*/ ?>
			<label id="enable-branches" class="display-none">
			<input type="checkbox" />
				<?php esc_html_e( 'Include branches', 'cf7-grid-layout' ); ?>
			</label>
		<?php endif; ?>
			<div class="cf72post-new-taxonomy">
			<div><strong><?php esc_html_e( 'New Taxonomy', 'cf7-grid-layout' ); ?></strong></div>
			<label><?php esc_html_e( 'Plural Name', 'cf7-grid-layout' ); ?><br />
			<input disabled="true" class="cf72post-new-taxonomy" type="text" name="plural_name" value=""></label>
			<label ><?php esc_html_e( 'Singular Name', 'cf7-grid-layout' ); ?><br />
			<input disabled="true"  class="cf72post-new-taxonomy" type="text" name="singular_name" value=""></label>
			<label><?php esc_html_e( 'Slug', 'cf7-grid-layout' ); ?><br />
			<input disabled="true"  class="cf72post-new-taxonomy" type="text" name="taxonomy_slug" value="" /></label>
			<label class="hidden"><input class="cf72post-new-taxonomy" type="checkbox" name="is_hierarchical" /><?php esc_html_e( 'hierarchical', 'cf7-grid-layout' ); ?></label>
			</div>
			<?php do_action( 'cf7sg_dynamic_tag_manager_taxonomy_source', $tag_id ); ?>
		</article>
		</section>
		<section class="post-source">
		<input type="radio" id="<?php echo esc_attr( $class ); ?>-post-tab" name="sections" class="post-tab source-tab">
		<label for="<?php echo esc_attr( $class ); ?>-post-tab"><?php esc_html_e( 'Post', 'cf7-grid-layout' ); ?></label>
		<article class="">
			<h4><?php esc_html_e( 'Post source', 'cf7-grid-layout' ); ?></h4>
			<select id="<?php echo esc_attr( $class ); ?>-post-list" class="post-list" name="<?php echo esc_attr( $tag_id ); ?>_post_list">
			<option value="" selected><?php esc_html_e( 'Select a post', 'cf7-grid-layout' ); ?></option>
			<?php
			$args     = array(
				'show_ui'  => true,
				'_builtin' => false,
			);
			$output   = 'objects'; // names or objects, note names is the default.
			$operator = 'and'; /** NB: 'and' or 'or' */

			$post_types = get_post_types( $args, $output, $operator );
			foreach ( $post_types as $tp => $p ) {
				echo '<option value="' . esc_attr( $tp ) . '">' . esc_html( $p->labels->name ) . '</option>';
				$taxonomies = get_object_taxonomies( $tp, 'objects' );

				$taxonomy_lists[ $tp ] = '';
				foreach ( $taxonomies as $taxnmy ) {
					// skup cf7 dynamic list taxonomies.
					if ( WPCF7_ContactForm::post_type === $tp && 'wpcf7_type' !== $taxnmy->name ) {
						continue;
					}
					if ( empty( $taxnmy->label ) ) {
						continue;
					}
					$taxonomy_lists[ $tp ] .= '<optgroup label="' . $taxnmy->label . '">' . PHP_EOL;
					$taxonomy_lists[ $tp ] .= $dlo->cf7sg_terms_to_options( $taxnmy->name, $taxnmy->hierarchical );
					$taxonomy_lists[ $tp ] .= '</optgroup>' . PHP_EOL;
				}
			}
			$taxonomies             = get_object_taxonomies( 'post', 'objects' );
			$taxonomy_lists['post'] = '';
			foreach ( $taxonomies as $taxnmy ) {
				if ( empty( $taxnmy->label ) ) {
					continue;
				}
				$taxonomy_lists['post'] .= '<optgroup label="' . $taxnmy->label . '">' . PHP_EOL;
				$taxonomy_lists['post'] .= $dlo->cf7sg_terms_to_options( $taxnmy->name, $taxnmy->hierarchical );
				$taxonomy_lists['post'] .= '</optgroup>' . PHP_EOL;
			}
			$taxonomies             = get_object_taxonomies( 'page', 'objects' );
			$taxonomy_lists['page'] = '';
			foreach ( $taxonomies as $taxnmy ) {
				if ( empty( $taxnmy->label ) ) {
					continue;
				}
				$taxonomy_lists['page'] .= '<optgroup label="' . $taxnmy->label . '">' . PHP_EOL;
				$taxonomy_lists['page'] .= $dlo->cf7sg_terms_to_options( $taxnmy->name, $taxnmy->hierarchical );
				$taxonomy_lists['page'] .= '</optgroup>' . PHP_EOL;
			}
			?>
			<option value="post"><?php esc_html_e( 'Posts', 'cf7-grid-layout' ); ?></option>
			<option value="page"><?php esc_html_e( 'Pages', 'cf7-grid-layout' ); ?></option>
			</select>
			<div class="<?php echo esc_attr( $class ); ?>-post-options">
			<label for="<?php echo esc_attr( $class ); ?>-post-links" class="<?php echo esc_attr( $class ); ?> include-links">
				<input id="<?php echo esc_attr( $class ); ?>-post-links" value="include_links" name="<?php echo esc_attr( $tag_id ); ?>_post_links" type="checkbox" class="include-post-links"/><?php esc_html_e( 'Include post links', 'cf7-grid-layout' ); ?>
			</label>
			<label for="<?php echo esc_attr( $class ); ?>-post-images" class="<?php echo esc_attr( $class ); ?> include-images">
				<input id="<?php echo esc_attr( $class ); ?>-post-images" value="include_imgs" name="<?php echo esc_attr( $tag_id ); ?>_post_imgs" type="checkbox" class="include-post-images"/><?php esc_html_e( 'Include post thumbnails', 'cf7-grid-layout' ); ?>
			</label>
			</div>

	<?php
	foreach ( $taxonomy_lists as $tp => $list ) :
		if ( empty( $list ) ) {
			continue;
		}
		?>
			<div id="" class="post-taxonomies cf7sg-dynamic-tag hidden <?php echo esc_attr( $tp ); ?>">
			<select id="<?php echo esc_attr( $class ); ?>-<?php echo esc_attr( $tp ); ?>" multiple class="select2" name="<?php echo esc_attr( $tag_id ); ?>_<?php echo esc_attr( $tp ); ?>">
				<option value=""><?php esc_html_e( 'Filter by terms', 'cf7-grid-layout' ); ?></option>
				<?php
				echo wp_kses(
					$list,
					array(
						'option'   => array(
							'value' => 1,
							'label' => 1,
							'class' => 1,
						),
						'optgroup' => array(
							'label' => 1,
						),
					)
				);
				?>
			</select>
			</div>
	<?php endforeach; ?>
	<?php do_action( 'cf7sg_dynamic_tag_manager_post_source', $tag_id ); ?>
		</article>
		</section>
		<section class="custom-source">
		<input type="radio" id="<?php echo esc_attr( $class ); ?>-custom-tab" name="sections" class="custom-tab source-tab">
		<label for="<?php echo esc_attr( $class ); ?>-custom-tab"><?php esc_html_e( 'Custom', 'cf7-grid-layout' ); ?></label>
		<article>
			<h4><?php esc_html_e( 'Custom source', 'cf7-grid-layout' ); ?></h4>
			<p class="position-relative filter-hook">
			<?php
			/** NB @since 4.11.0 generalise hook jelper code initialisation.
			 * use the provided template to display a message to copy the filter link.
			 * Insert the filter class name into the template, that matches the filter added to the list in
			 * the file admin/partials/helpers/cf7sg-form-fields.php.
			 */
			printf(
				/* translators: %1 a link to the filter, %2 the functions.php file */
				esc_html__( 'Copy the following %1$s to your %2$s file.', 'cf7-grid-layout' ),
				'<a class="cf7sg_filter_source" href="javascript:void(0);">' . esc_html__( 'filter', 'cf7-grid-layout' ) . '</a>',
				'<em>functions.php</em>'
			);
			?>
			</p>
			<?php do_action( 'cf7sg_dynamic_tag_manager_custom_source', $tag_id ); ?>
		</article>
		</section>
	</div> <!-- end-tabs-->
	<?php do_action( 'cf7sg_dynamic_tag_manager_end', $tag_id ); ?>
	</fieldset>
</div>
<div class="insert-box cf7sg-dynamic-tag-submit">
	<input type="hidden" name="values" value="" />
	<input type="text" name="<?php echo esc_attr( $tag_id ); ?>" class="tag code" readonly="readonly" onfocus="this.select()" />

	<div class="submitbox ">
		<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __cf7sg( 'Insert Tag' ) ); ?>" />
	</div>

	<br class="clear" />
</div>
