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
		include_once(plugin_dir_path( dirname( __FILE__ ) ) . '/partials/feature-list-metabox.php');
		
		wp_enqueue_style(  'feature-list-metabox', plugin_dir_url( dirname(__FILE__) ) . 'css/feature-list-metabox.css');

		wp_enqueue_script( 'feature-list-metabox', plugin_dir_url( dirname(__FILE__) ) . 'js/feature-list-metabox.js', array( 'jquery' ), null, false );
		wp_localize_script('feature-list-metabox', 'EFL_LIST_JSON', get_post_meta($post->ID, 'efl-list-features', true));		
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


		if (isset($_POST['efl-list-cols'])) {
			update_post_meta($post_id, 'efl-list-cols', intval($_POST['efl-list-cols']));
		}

		if (isset($_POST['efl-group-name']) && isset($_POST['efl-feature-name']) && isset($_POST['efl-feature-type'])) {
			$groups = array();
			foreach ($_POST['efl-group-name'] as $group_id => $group_name) {
				if (! isset($_POST['efl-feature-name'][$group_id])) {
					continue;
				}

				$group = array(
					'name' 		=> wp_strip_all_tags($group_name),
					'features' 	=> array()
				);

				foreach ($_POST['efl-feature-name'][$group_id] as $feature_id => $feature_name) {
					$feature = array(
						'name' 	=> $_POST['efl-feature-name'][$group_id][$feature_id],
						'type' 	=> $_POST['efl-feature-type'][$group_id][$feature_id]
					);

					$group['features'][] = $feature;
				}

				$groups[] = $group;
			}
			update_post_meta($post_id, 'efl-list-features', json_encode($groups));
		}
	}
}
