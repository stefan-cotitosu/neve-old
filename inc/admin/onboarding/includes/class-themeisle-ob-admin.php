<?php
/**
 * Handles admin logic for the onboarding.
 *
 * Author:  Andrei Baicus <andrei@themeisle.com>
 * On:      21/06/2018
 *
 * @package    themeisle-onboarding
 * @soundtrack Smell the Roses - Roger Waters
 */

/**
 * Class Themeisle_OB_Admin
 *
 * @package ThemeIsle
 */
class Themeisle_OB_Admin {

	/**
	 * Initialize the Admin.
	 */
	public function init() {
		add_filter( 'query_vars', array( $this, 'add_onboarding_query_var' ) );
		add_action( 'ti_about_page_after_tabs', array( $this, 'add_demo_import_tab' ) );
		add_action( 'ti_about_page_after_tabs_content', array( $this, 'add_demo_import_tab_content' ) );
	}

	/**
	 * Add our onboarding query var.
	 *
	 * @param array $vars_array the registered query vars.
	 *
	 * @return array
	 */
	public function add_onboarding_query_var( $vars_array ) {
		array_push( $vars_array, 'onboarding' );

		return $vars_array;
	}

	/**
	 * Add about page tab list item.
	 */
	public function add_demo_import_tab() {
		?>
		<li style="margin-bottom: 0;" data-tab-id="<?php echo esc_attr( 'demo-import' ); ?>"><a class="nav-tab"
					href="#<?php echo esc_attr( 'demo-import' ); ?>"><?php echo __( 'Sites Library', 'hestia-pro' ); ?></a>
		</li>
		<?php
	}

	/**
	 * Add about page tab content.
	 */
	public function add_demo_import_tab_content() {
		?>
		<div id="<?php echo esc_attr( 'demo-import' ); ?>">
			<?php $this->render_site_library(); ?>
		</div>
		<?php
	}

	/**
	 * Render the sites library.
	 */
	public function render_site_library() {
		$this->enqueue();
		?>
		<div class="ti-sites-lib__wrap">
			<h3 class="wp-heading-inline"><?php echo __( 'Sites Library', 'hestia-pro' ); ?></h3>
			<hr class="wp-header-end">
			<div id="ti-sites-library">
				<app></app>
			</div>
		</div>
		<?php
	}

	/**
	 * Enqueue script and styles.
	 */
	public function enqueue() {
		wp_register_script( 'themeisle-site-lib', Themeisle_Onboarding::get_dir() . '/assets/js/bundle.min.js', array(), Themeisle_Onboarding::VERSION, true );

		wp_localize_script( 'themeisle-site-lib', 'themeisleSitesLibApi', $this->localize_sites_library() );

		wp_enqueue_script( 'themeisle-site-lib' );

		wp_enqueue_style( 'themeisle-site-lib', Themeisle_Onboarding::get_dir() . '/assets/css/style.css', array(), Themeisle_Onboarding::VERSION );
	}

	/**
	 * Localize the sites library.
	 *
	 * @return array
	 */
	private function localize_sites_library() {
		$api = array(
			'root'       => esc_url_raw( rest_url( Themeisle_Onboarding::API_ROOT ) ),
			'nonce'      => wp_create_nonce( 'wp_rest' ),
			'homeUrl'    => home_url(),
			'i18ln'      => $this->get_strings(),
			'onboarding' => 'no',
		);

		$is_onboarding = isset( $_GET['onboarding'] ) && $_GET['onboarding'] === 'yes';
		if ( $is_onboarding ) {
			$api['onboarding'] = 'yes';
		}

		return $api;
	}

	/**
	 * Get strings.
	 *
	 * @return array
	 */
	private function get_strings() {
		return array(
			'preview_btn'         => __( 'Preview', 'hestia-pro' ),
			'import_btn'          => __( 'Import', 'hestia-pro' ),
			'cancel_btn'          => __( 'Cancel', 'hestia-pro' ),
			'loading'             => __( 'Loading', 'hestia-pro' ),
			'go_to_site'          => __( 'View Website', 'hestia-pro' ),
			'back'                => __( 'Back to Sites Library', 'hestia-pro' ),
			'note'                => __( 'Note', 'hestia-pro' ),
			'advanced_options'    => __( 'Advanced Options', 'hestia-pro' ),
			'plugins'             => __( 'Plugins', 'hestia-pro' ),
			'general'             => __( 'General', 'hestia-pro' ),
			'later'               => __( 'Not right now.', 'hestia-pro' ),
			'onboard_header'      => __( 'Get started here', 'hestia-pro' ),
			'onboard_description' => __( 'This process will set up your website, install required plugins, import demo content (pages, posts, media) and set up the customizer options.', 'hestia-pro' ),
			'content'             => __( 'Content', 'hestia-pro' ),
			'customizer'          => __( 'Customizer', 'hestia-pro' ),
			'widgets'             => __( 'Widgets', 'hestia-pro' ),
			'import_steps'        => array(
				'plugins'    => __( 'Installing Plugins', 'hestia-pro' ),
				'content'    => __( 'Importing Content', 'hestia-pro' ),
				'theme_mods' => __( 'Setting Up Customizer', 'hestia-pro' ),
				'widgets'    => __( 'Importing Widgets', 'hestia-pro' ),
			),
			'import_disclaimer'   => __( 'We recommend you backup your website content before attempting a full site import.', 'hestia-pro' ),
			'import_done'         => __( 'Content was successfully imported. Enjoy your new site!', 'hestia-pro' ),
		);
	}
}
