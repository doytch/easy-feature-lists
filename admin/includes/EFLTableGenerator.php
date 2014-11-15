<?php

/**
 * The feature list table markup generator.
 *
 * Creates the HTML of the feature list tables.
 *
 * @package    Easy_Feature_Lists
 * @subpackage Easy_Feature_Lists/admin
 * @author     Mark Hurst Deutsch <admin@qedev.com>
 */
class EFLTableGenerator {
	/**
	 * Raw checklist info of the feature list
	 *
	 * @access   private
	 */
	private $checklist;

	/**
	 * The width of each feature cell as a percentage of table width.
	 *
	 * @access   private
	 */
	private $max_col;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct() {
	}

	/**
	 * Top-level function that orchestrates creation of the feature list and returns the markup.
	 *
	 * @var     string 		$post_id 			The ID of the post we're creating the feature list in.
	 * @return 	string 							The HTML markup of the feature list table.
	 */
	public function create_feature_list_table( $post_id ) {
		$list_id = get_post_meta($post_id, 'efl-list-sel', true);
		$this->max_col = get_post_meta($list_id, 'efl-list-cols', true);
 		
 		$structure = $this->create_structure($post_id, $list_id);
		if ($structure == false) {
			return "";
		}

		$output = $this->create_table($structure);
		return $output;
	}

	/**
	 * Creates a PHP array of the structure of the feature list from the markup and checklist.
	 *
	 * @var     string 		$post_id 			The ID of the post we're creating the feature list in.
	 * @var     string 		$list_id 			The ID of the feature list we're creating.
	 * @return 	array, boolean 					Array of the structure if no errors, else false.
	 */
	private function create_structure($post_id, $list_id) {
		$markup = get_post_meta($list_id, 'efl-list-markup', true);
		$this->checklist = get_post_meta($post_id, 'efl-list-checklist', true);
		if ($markup == null || $markup == ""   || $this->checklist == null || $this->checklist == "" ) {
			return false;
		}

		// Each line is of form "Feature Group: Feature 1, Feature 2, Feature 3".
		$structure = array();
 		foreach (explode("\n", $markup) as $group_id => $group_markup) {
 			// Split each line into the group name and the features.
 			$tmp = explode(":", $group_markup);
 			if (count($tmp) != 2) {
 				return false;
 			}
 			
 			$group = array();
 			foreach (explode(",", $tmp[1]) as $feature_id => $feature_markup) {
 				// Construct an array for each feature and add it to the group array.
 				$group[] = array(
 					'id' => "{$group_id}-{$feature_id}",
 					'name' => trim($feature_markup),
 					'checked' => $this->feature_is_checked($group_id, $feature_id)
				);
 			}
 			$structure[trim($tmp[0])] = $group;
 		}

 		return $structure;
	}

	/**
	 * Creates a table that contains the feature list w/ appropriate items checked.
	 *
	 * @var     string 		$group_id 			The ID of the group the feature's in.
	 * @var     string 		$feature_id			The ID of the feature itself.
	 * @return 	boolean 						True/false whether the feature is checked.
	 */
	private function feature_is_checked($group_id, $feature_id) {
		return strpos($this->checklist, "{$group_id}-{$feature_id}") !== false;
	}

	/**
	 * Creates and returns the feature list <table> markup.
	 *
	 * @var 	array 		$structure 			Array that represents the feature list structure.
	 * @return 	string  						The markup of the feature list <table> created.
	 */
	private function create_table($structure) {
		$output = "<table class='efl-feature-list-table'>";

		foreach ($structure as $group_name => $features) {
			$output .= "<tr>";
			$output .= $this->create_group_name_cell($group_name);
			$output .= $this->create_group_features_cell($features);
			$output .= "</tr>";
		}

		$output .= "</table>";
		return $output;
	}

	/**
	 * Creates and returns the header cell for the feature group.
	 *
	 * @var     string 		$name   			The name of the feature group.
	 * @return 	string  						The feature group header cell markup.
	 */
	private function create_group_name_cell($name) {
		return "<td class='efl-group-name-cell'><strong>{$name}</strong></td>";
	}

	/**
	 * Creates and returns the cell for the feature group.
	 *
	 * @var     array 		$features   		Array of feature structure arrays.
	 * @return 	string  						The feature group list cell markup with complete <table>.
	 */
	private function create_group_features_cell($features) {
		$output = "<td class='efl-group-features-cell'><table class='efl-group-table'>";
		
		$col = 0;
		$in_row = false;
		foreach ($features as $feature) {
			if ($col == 0) {
				$output .= "<tr>";
				$in_row = true;
			}

			$output .= $this->create_feature_cell($feature);
			
			$col += 1;
			if ($col == $this->max_col) {
				$col = 0;
				$output .= "</tr>";
				$in_row = false;
			}
		}

		if ($in_row) {
			while ($col != $this->max_col) {
				$output .= "<td></td>";
				$col += 1;
			}
			$output .= "</tr>";
		}

		$output .= "</table></td>";
		return $output;
	}

	/**
	 * Creates and returns the cell for the feature.
	 *
	 * @var     array 		$name   			The feature array.
	 * @return 	string  						The feature cell markup with checkbox and label.
	 */
	private function create_feature_cell($feature) {
		$output  = "<td class='efl-feature-cell efl-cell-wide-" . $this->max_col . "'>";
		$output .= "<input class='efl-feature-checkbox' disabled=true type='checkbox' ";
		$output .= "id='efl-chk-" . $feature['id'] . "'";
		$output .= $feature['checked'] ? ' checked ' : ' ';
		$output .= "/>";
		$output .= "<label for='efl-chk-" . $feature['id'] . "'>";
		$output .= "&nbsp;" . $feature['name'] . "</label></td>";
		return $output;
	}
}