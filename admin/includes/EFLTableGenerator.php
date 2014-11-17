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
	 * Values of the feature list
	 *
	 * @access   private
	 */
	private $values;

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
		$features = get_post_meta($list_id, 'efl-list-features', true);
		$values = get_post_meta($post_id, 'efl-list-values', true);

		if ($features == null || $features == ""   || $values == null || $values == "" ) {
			return false;
		}

		$features = json_decode($features, true);
		$values = json_decode($values, true);

		// Each line is of form "Feature Group: Feature 1, Feature 2, Feature 3".
 		foreach ($features as $group_id => $group) {
 			$group_id = intval($group_id);
 			foreach ($features[$group_id]['features'] as $feature_id => $feature) {
 				$feature_id = intval($feature_id);
 				// Merge the value data into the feature list structure array.
 				$features[$group_id]['features'][$feature_id]['value'] = $values[$group_id][$feature_id];
 			}
 		}

 		return $features;
	}

	/**
	 * Creates and returns the feature list <table> markup.
	 *
	 * @var 	array 		$structure 			Array that represents the feature list structure.
	 * @return 	string  						The markup of the feature list <table> created.
	 */
	private function create_table($structure) {
		$output = "<table class='efl-feature-list-table'>";

		foreach ($structure as $group) {
			$output .= "<tr>";
			$output .= $this->create_group_name_cell($group['name']);
			$output .= $this->create_group_features_cell($group['features']);
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
		$output .= "<label class='efl-feature-name-label'>" . $feature['name'] . "</label></td>";
		$output .= "<td class='efl-feature-cell efl-cell-wide-" . $this->max_col . "'>";

		if ($feature['type'] == 'Text') {
			$output .= "<span class='efl-feature-text'>" . $feature['value'] . "</span>";
		} else if ($feature['type'] == 'Checkbox') {
			$output .= "<input disabled=true class='efl-feature-check' type='checkbox' " . $feature['value'] . "/>";
		}

		$output .= "</td>";
		return $output;
	}
}