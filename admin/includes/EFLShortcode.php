<?php

/**
 * The shortcode functionality of the plugin.
 *
 * Defines the shortcode and handles replacement of them
 * in posts.
 *
 * @package    Easy_Feature_Lists
 * @subpackage Easy_Feature_Lists/admin
 * @author     Mark Hurst Deutsch <admin@qedev.com>
 */
class EFLShortcode {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @var      string    $name       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct() {
		$this->add_shortcodes();
	}

	/**
	 * Set up a few shortcodes for widest compatibility.
	 *
	 * @since 		1.0.0
	 */
	private function add_shortcodes() {
		add_shortcode('efl_feature_list', array($this, 'found_shortcode'));
		add_shortcode('feature_list', array($this, 'found_shortcode'));
		add_shortcode('fl', array($this, 'found_shortcode'));
	}

	/**
	 * Called by WP when the shortcode is found.
	 *
	 * @var      array     $attr       The attributes of the shortcode
	 * @var      string    $content    Content enclosed by shortcode.
	 */
 	public function found_shortcode($attr, $content) {
 		global $id;

		require_once plugin_dir_path(  __FILE__ ) . '/EFLTableGenerator.php';
		$tableGenerator = new EFLTableGenerator();
 		$output = $tableGenerator->create_feature_list_table($id);

 		if ($output != "") {
	 		wp_enqueue_style('table', plugin_dir_url( dirname(__FILE__) ) . 'css/table.css');
	 		wp_enqueue_style('bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css');
 		}

 		return $output;
 	}
}