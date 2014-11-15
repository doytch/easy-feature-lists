<?php

/**
 * Provide the view of the Feature List metabox
 * 
 *
 * @package    Easy_Feature_Lists
 * @subpackage Easy_Feature_Lists/admin/partials
 */
?>

<table id='efl-mb-table' class='form-table'>
	<tr valign='top'>
		<th scope='row'><?php _e('Feature List', $this->name); ?></th>
<?php $feature_list = get_post_meta(get_the_ID(), 'efl-list-sel', true); ?>
<?php $loop = new WP_Query( array('post_type' => 'feature_list'));?>
<?php if ($loop->found_posts > 0): ?>
		<td><select id='efl-list-sel' name='efl-list-sel'><option value='-1'><?php _e('None', $this->name); ?></option>
<?php 
		while ($loop->have_posts()) {
			$loop->the_post();
			$post_id = get_the_ID();
			echo "<option value='{$post_id}'";
			echo ($feature_list == get_the_ID() ? ' selected ' : '') . ">" . get_the_title() . "</option>";
		}
?>		
		</select></td>
<?php else: ?>
		<td><?php _e('No feature lists defined.', $this->name); ?></td>
<?php endif ?>
	</tr>
</table>