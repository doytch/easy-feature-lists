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
class EFLPostMetabox {
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
		add_action( 'wp_ajax_efl_ajax_get_feature_list', array($this, 'get_feature_list') );
	}

	/**
	 * Register the metabox
	 *
	 */
	public function add_metabox($post_type) {
		$screens = array( 'post', 'page');

		foreach ($screens as $screen) {
			add_meta_box(
				'efl_post_mb',
				__('Feature List Configuration', $this->name),
				array($this, 'create_metabox'),
				$screen,
				'normal',
				'high'
			);			
		}
	}

	/**
	 * Returns the markup associated with POSTed feature list ID.
	 *
	 */
	public function get_feature_list() {
		$post = intval($_POST['post_id']);
		$list = intval($_POST['feature_list_id']);
		$ret = array(
			'markup' => get_post_meta($list, 'efl-list-markup', true),
			'checklist' => get_post_meta($post, 'efl-list-checklist', true)
		);
		echo json_encode($ret);
		die();
	}

	/**
	 * Create the metabox
	 *
	 */
	public function create_metabox($post) {
		wp_nonce_field('efl_post_mb', 'efl_post_mb_nonce');
		include_once(plugin_dir_path( dirname(dirname( __FILE__ )) ) . 'admin/partials/post-metabox.php');
	}

	/**
	 * Saves the metabox contents as post metadata.
	 *
	 * @since 		1.0.0
	 * @var 		string 		$post_id 		The ID of the post we're saving.
	 */
	public function save_metabox($post_id) {
		// Verify the nonce to ensure we're getting called from the right spot.
		if (!isset($_POST['efl_post_mb_nonce'])) {
			return $post_id;
		}
		$nonce = $_POST['efl_post_mb_nonce'];
		if (!wp_verify_nonce($nonce, 'efl_post_mb')) {
			return $post_id;
		}

		if (isset($_POST['efl-list-sel'])) {
			update_post_meta($post_id, "efl-list-sel", intval($_POST['efl-list-sel']));
		}

		if (isset($_POST['efl-chk'])) {
			$group_num = 0;
			$feature_num = 0;
			$checklist = '';
			foreach($_POST['efl-chk'] as $group_idx => $group) {
				foreach ($group as $feature_idx => $feature) {	
					$checklist .= "{$group_idx}-{$feature_idx},";
					$feature_num += 1;
				}
				$feature_num = 0;
				$group_num += 1;
			}
			update_post_meta($post_id, "efl-list-checklist", $checklist);
		}
	}
}
