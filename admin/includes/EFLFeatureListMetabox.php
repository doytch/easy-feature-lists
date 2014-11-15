<?php

/**
 * Define the metabox functionality.
 *
 * Creates and saves metaboxes for the boat CPT.
 *
 * @package    Easy_Feature_Lists
 * @subpackage Easy_Feature_Lists/includes
 * @author     Mark Hurst Deutsch <admin@qedev.com>
 */
class EFLFeatureListMetabox {
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

		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
		add_action( 'save_post', array( $this, 'save_metabox'));
	}

	/**
	 * Register the metabox
	 *
	 */
	public function add_metabox($post_type) {
		add_meta_box(
			'efl_fl_mb',
			__('Configuration', $this->name),
			array($this, 'create_metabox'),
			'feature_list',
			'normal',
			'high'
		);
	}

	/**
	 * Create the metabox
	 *
	 */
	public function create_metabox($post) {
		wp_nonce_field('efl_fl_mb', 'efl_fl_mb_nonce');
		include_once(plugin_dir_path( dirname(dirname( __FILE__ )) ) . 'admin/partials/feature-list-metabox.php');
	}

	/**
	 * Saves the metabox contents as post metadata.
	 *
	 * @since 		1.0.0
	 * @var 		string 		$post_id 		The ID of the post we're saving.
	 */
	public function save_metabox($post_id) {
		// Verify the nonce to ensure we're getting called from the right spot.
		if (!isset($_POST['efl_fl_mb_nonce'])) {
			return $post_id;
		}
		$nonce = $_POST['efl_fl_mb_nonce'];
		if (!wp_verify_nonce($nonce, 'efl_fl_mb')) {
			return $post_id;
		}

		if (isset($_POST['efl-list-markup'])) {
			update_post_meta($post_id, 'efl-list-markup', wp_strip_all_tags($_POST['efl-list-markup']));
		}
		if (isset($_POST['efl-list-cols'])) {
			update_post_meta($post_id, 'efl-list-cols', intval($_POST['efl-list-cols']));
		}
	}
}
