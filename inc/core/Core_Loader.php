<?php
/**
 * Neve Features Factory
 *
 * Author:          Andrei Baicus <andrei@themeisle.com>
 * Created on:      17/08/2018
 *
 * @package Neve\Core
 */

namespace Neve\Core;

/**
 * The core entry class.
 *
 * @package Neve\Core
 */
class Core_Loader {
	/**
	 * Features that will be loaded.
	 *
	 * @access   protected
	 * @var array $features Features that will be loaded.
	 */
	protected $features = array();

	/**
	 * Define the core functionality of the theme.
	 *
	 * @access public
	 */
	public function __construct() {
		$this->maybe_load_addons();
		$this->define_hooks();
		$this->define_modules();
		$this->load_modules();
	}

	/**
	 * Define the features that will be loaded.
	 */
	private function define_modules() {
		$this->features = apply_filters(
			'neve_filter_main_modules', array(
				'Customizer\Loader',
				'Views\Typography',
				'Views\Top_Bar',
				'Views\Header',
				'Views\Footer',
				'Views\Template_Parts',
				'Views\Page_Header',
				'Views\Post_Header',

				'Views\Layouts\Layout_Container',
				'Views\Layouts\Layout_Sidebar',

				'Views\Partials\Post_Meta',
				'Views\Partials\Excerpt',

				'Views\Pluggable\Pagination',
				'Views\Pluggable\Masonry',
				'Views\Pluggable\Post_Meta',

				'Views\Inline\Style_Manager',

				/*'Compatibility\WooCommerce',*/
				'Compatibility\Elementor',

				'Admin\Metabox\Main',
			)
		);
	}

	/**
	 * Check Features and register them.
	 *
	 * @access  private
	 */
	private function load_modules() {
		$factory = new Factory( $this->features );
		$factory->load_modules();
	}

	/**
	 * Check if add-ons are available and load them if necessary.
	 *
	 * @access private
	 */
	private function maybe_load_addons() {
		if ( ! class_exists( 'Neve\Addons\Main' ) ) {
			return;
		}
		$addon_manager = new \Neve\Addons\Main();
		add_filter( 'neve_filter_main_modules', array( $addon_manager, 'filter_modules' ) );
	}

	/**
	 * Register all of the hooks related to the functionality
	 * of the theme setup.
	 *
	 * @access   private
	 */
	private function define_hooks() {
		$admin = new Admin();
		add_filter( 'init', array( $admin, 'do_about_page' ) );
		add_action( 'init', array( $admin, 'load_site_import' ), 11 );

		$front_end = new Front_End();
		add_action( 'wp_enqueue_scripts', array( $front_end, 'enqueue_scripts' ) );
		add_action( 'after_setup_theme', array( $front_end, 'setup_theme' ) );
		add_action( 'widgets_init', array( $front_end, 'register_sidebars' ) );
	}
}
