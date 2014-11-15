<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Easy_Feature_Lists
 * @subpackage Easy_Feature_Lists/admin
 * @author     Mark Hurst Deutsch <admin@qedev.com>
 */
class EFLAdmin {

	/**
	 * The ID of this plugin.
	 *
	 * @access   private
	 */
	private $name;

	/**
	 * The version of this plugin.
	 *
	 * @access   private
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

		require_once plugin_dir_path( __FILE__ ) . '/includes/EFLFeatureList.php';
		new EFLFeatureList($name, $version);

		require_once plugin_dir_path( __FILE__ ) . '/includes/EFLPostMetabox.php';
		new EFLPostMetabox($name, $version);

		require_once plugin_dir_path( __FILE__ ) . '/includes/EFLShortcode.php';
		new EFLShortcode();

	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'thickbox');
	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 */
	public function enqueue_scripts($hook) {
		if ($hook == 'post.php' || $hook == 'post-new.php') {
			wp_enqueue_script( $this->name, plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), null, false );
			$protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
			wp_localize_script($this->name, 'EFL_AJAX_URL', admin_url('admin-ajax.php', $protocol));
		}
	}

}
