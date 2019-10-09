<?php
namespace lsx\envira_gallery\themes\classes;

/**
 * Frontend Class.
 *
 * @package lsx-envira-gallery-themes
 */
class Frontend {

	/**
	 * Holds class instance
	 *
	 * @var object \lsx\envira_gallery\themes\classes\Frontend()
	 */
	protected static $instance = null;

	/**
	 * Holds them array of available themes
	 *
	 * @var array
	 */
	public $themes = array();

	/**
	 * Holds the slug of the currently selectec theme.
	 *
	 * @var string
	 */
	public $current_theme = '';

	/**
	 * Holds a counter for use when running through the gallery items.
	 *
	 * @var int
	 */
	public $item_counter = 1;

	/**
	 * Contructor
	 */
	public function __construct() {
		add_filter( 'envira_load_gallery_theme_url', array( $this, 'assets' ), 10, 2 );
		add_action( 'init', array( $this, 'init' ), 10 );
		add_filter( 'envira_images_pre_data', array( $this, 'check_gallery_theme' ), 10, 2 );
		//add_filter( 'envira_gallery_output_item_data', array( $this, 'gallery_output_item_data' ), 10, 4 );
		add_filter( 'envira_gallery_output_item_classes', array( $this, 'gallery_output_item_classes' ), 10, 4 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\envira_gallery\themes\classes\Frontend()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Runs after the WP query, but before wp_head
	 *
	 * @return void
	 */
	public function init() {
		$this->themes = \lsx\envira_gallery\themes\functions\get_themes();
	}

	/**
	 * Registers the plugin frontend assets
	 *
	 * @return string
	 */
	public function assets( $path, $theme ) {
		if ( array_key_exists( $theme, $this->themes ) ) {
			$path = LSX_EGT_URL . 'assets/css/lsx-envira-gallery-themes.css';
		}
		return $path;
	}

	/**
	 * Checks the theme to see if one of ours has been selected.
	 *
	 * @param  array $data
	 * @param  string $gallery_id
	 * @return void
	 */
	public function check_gallery_theme ( $data, $gallery_id ) {
		if ( isset( $data['config'] ) && isset( $data['config']['gallery_theme'] ) && '' !== $data['config']['gallery_theme'] && array_key_exists( $data['config']['gallery_theme'], $this->themes ) ) {
			$this->current_theme = $data['config']['gallery_theme'];
			$this->saved_data    = $data;
		}

		return $data;
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $item
	 * @param [type] $id
	 * @param [type] $data
	 * @param [type] $i
	 * @return void
	 */
	public function gallery_output_item_data ( $item, $id, $data, $i ) {
		if ( '' !== $this->current_theme ) {
			switch ( $this->current_theme ) {
				case 'lsx-staggered-columns':
					$item = $this->staggered_columns_filter( $item, $id, $data, $i );
				break;

				default:
				break;
			}

			$this->item_counter++;
		}
		return $item;
	}

	/**
	 * Adds in the classes to each gallery item.
	 *
	 * @param [type] $classes
	 * @param [type] $item
	 * @param [type] $i
	 * @param [type] $data
	 * @return void
	 */
	public function gallery_output_item_classes ( $classes, $item, $i, $data ) {
		if ( '' !== $this->current_theme ) {
			switch ( $this->current_theme ) {
				case 'lsx-staggered-columns':
					$classes = $this->staggered_columns_classes( $classes, $item, $i, $data );
				break;

				default:
				break;
			}

			$this->item_counter++;
		}
		return $classes;
	}


	public function staggered_columns_filter ( $item, $id, $data, $i ) {
		return $item;
	}

	public function staggered_columns_classes ( $classes, $item, $i, $data ) {
		if ( 1 === $this->item_counter || 2 === $this->item_counter || 3 === $this->item_counter ) {
			$classes[] = 'staggered-column-3';
		} else if ( 4 === $this->item_counter || 5 === $this->item_counter ) {
			$classes[] = 'staggered-column-2';
			if ( 5 === $this->item_counter ) {
				$this->item_counter = 0;
			}
		}
		return $classes;
	}
}
