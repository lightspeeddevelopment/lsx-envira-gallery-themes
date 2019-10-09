<?php
namespace lsx\envira_gallery\themes\classes;

/**
 * This class loads the other classes and function files
 *
 * @package lsx-envira-gallery-themes
 */
class Core {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\envira_gallery\themes\classes\Core()
	 */
	protected static $instance = null;

	/**
	 * @var object \lsx\envira_gallery\themes\classes\Admin();
	 */
	public $admin;

	/**
	 * @var object \lsx\envira_gallery\themes\classes\Frontend();
	 */
	public $frontend;

	/**
	 * Contructor
	 */
	public function __construct() {
		$this->load_includes();
		$this->load_classes();
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\envira_gallery\themes\classes\Core()    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Loads the variable classes and the static classes.
	 */
	private function load_classes() {

		require_once LSX_EGT_PATH . 'classes/class-admin.php';
		$this->admin = Admin::get_instance();

		require_once LSX_EGT_PATH . 'classes/class-frontend.php';
		$this->frontend = Frontend::get_instance();
	}

	/**
	 * Loads the plugin functions.
	 */
	private function load_includes() {
		require_once LSX_EGT_PATH . '/includes/functions.php';
	}
}
