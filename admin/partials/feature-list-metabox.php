<?php

/**
 * Provide the view of the Feature List metabox
 * 
 *
 * @package    Easy_Feature_Lists
 * @subpackage Easy_Feature_Lists/admin/partials
 */
?>

<table id='efl-list-table' class='form-table'>
	<tr valign='top'>
		<th scope='row'>
			<label><?php _e('Features Per Row', $this->name); ?></label>
		</th>
		<?php $cols = get_post_meta(get_the_ID(), 'efl-list-cols', true); ?>
		<td>
			<select id='efl-list-cols' name='efl-list-cols'>
				<option <?php if ($cols == 1) echo 'selected'; ?> >1</option>
				<option <?php if ($cols == 2) echo 'selected'; ?> >2</option>
				<option <?php if ($cols == 3) echo 'selected'; ?> >3</option>
				<option <?php if ($cols == 4) echo 'selected'; ?> >4</option>
				<option <?php if ($cols == 5) echo 'selected'; ?> >5</option>
				<option <?php if ($cols == 6) echo 'selected'; ?> >6</option>
				<option <?php if ($cols == 7) echo 'selected'; ?> >7</option>
				<option <?php if ($cols == 8) echo 'selected'; ?> >8</option>
				<option <?php if ($cols == 9) echo 'selected'; ?> >9</option>
			</select><br/>
			<p><?php _e('The number of features in a group to list on the same row. Your page width and length of feature names determines a good number.', $this->name); ?></p>
		</td>
	</tr>
	<tr valign='top'>
		<th scope='row'>
			<button id='efl-add-feature-group-btn'><?php _e('Add Feature Group', $this->name); ?></button>
		</th>
	</tr>
</table>
