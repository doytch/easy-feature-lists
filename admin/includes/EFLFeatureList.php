<?php

/**
 * The feature list custom post type
 *
 * Defines the feature list custom post types and handles all related actions.
 *
 * @package    Easy_Feature_Lists
 * @subpackage Easy_Feature_Lists/includes
 * @author     Mark Hurst Deutsch <admin@qedev.com>
 */
class EFLFeatureList {

	/**
	 * The ID of this plugin.
	 *
	 * @access   private
	 * @var      string    $name    The ID of this plugin.
	 */
	private $name;

	/**
	 * The version of this plugin.
	 *
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @var      string    $name       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $name, $version ) {
		$this->name = $name;
		$this->version = $version;

		$this->register_hooks();
		$this->register_metabox();
	}

	/**
	 * Register the action/filter hooks for the class.
	 *
	 * @since 		1.0.0
	 */
 	private function register_hooks() {
		add_action('init', array($this, 'register_post_type'));
		add_action('admin_print_styles-post-new.php', array($this, 'remove_fl_links'));
		add_action('admin_print_styles-post.php', array($this, 'remove_fl_links'));
	}

	/**
	 * Create the Feature List Metabox box.
	 *
	 * @since 		1.0.0
	 */
	private function register_metabox() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/EFLFeatureListMetabox.php';
		new EFLFeatureListMetabox($name, $version);
	}

	/**
	 * Create the feature list custom post.
	 *
	 * @since 		1.0.0
	 */
	public function register_post_type() {
		register_post_type('feature_list', array(
			'labels' => array(
				'name' => 'Feature Lists',
				'singular_name' => 'Feature List',
				'add_new' => 'Add New Feature List',
				'add_new_item' => 'Add New Feature List',
				'edit_item' => 'Edit Feature List',
				'new_item' => 'New Feature List',
				'view_item' => 'View Feature List',
				'search_items' => 'Search Feature Lists',
				'not_found' => 'No Feature Lists Found',
				'not_found_in_trash' => 'No Feature Lists Found In Trash'
			),
			'supports' => array(
				'title',
				'custom_fields'
			),
			'public' => false,
			'show_in_menu' => true,
			'show_ui' => true,
			'has_archive' => false
		));
	}

	/**
	 * Remove the view new/updated post links from the post editor.
	 *
	 * @since 		1.0.0
	 */
 	public function remove_fl_links() {
		global $post_type;
		if ($post_type == 'feature_list') {
			echo '<style type="text/css">#edit-slug-box, #view-post-btn, #post-preview, .updated p a{display: none;}</style>';
		}
	}
}
