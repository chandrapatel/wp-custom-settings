<?php
/**
 * WP Custom Settings
 *
 * @package           wp-custom-settings
 * @author            Chandra Patel
 * @license           GPL-2.0-or-later
 */

/**
 * Manage to add menu page, register setting, settings section, and fields.
 */
class WP_Custom_Settings {

	/**
	 * An array of arguments to add the menu page.
	 *
	 * @var array
	 */
	private $page;

	/**
	 * An array of arguments to register setting.
	 *
	 * @var array
	 */
	private $setting;

	/**
	 * An array of settings sections and it's fields.
	 *
	 * @var array
	 */
	private $sections;

	/**
	 * Initialize class properties and add action to register setting and menu page.
	 *
	 * @param array $page     An array of arguments to add the menu page.
	 * @param array $setting  An array of arguments to register setting.
	 * @param array $sections An array of settings sections and it's fields.
	 */
	public function __construct( array $page, array $setting, array $sections ) {

		$this->page = wp_parse_args(
			$page,
			[
				'page_title' => __( 'Custom Settings', 'wp-custom-settings' ),
				'menu_title' => __( 'Custom Settings', 'wp-custom-settings' ),
				'capability' => 'manage_options',
				'menu_slug'  => 'wp-custom-settings-page',
				'icon_url'   => '',
				'position'   => null,
			]
		);

		$this->setting = wp_parse_args(
			$setting,
			[
				'option_group' => 'wp_custom_setting_group',
				'option_name'  => 'wp_custom_setting_options',
				'args'         => [],
			]
		);

		$this->sections = $sections;

		add_action( 'admin_init', array( $this, 'register_setting' ) );
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );

	}

	/**
	 * Register setting, add sections and fields.
	 *
	 * @return void
	 */
	public function register_setting() {

		register_setting( $this->setting['option_group'], $this->setting['option_name'], $this->setting['args'] );

		if ( empty( $this->sections ) || ! is_array( $this->sections ) ) {
			return;
		}

		foreach ( $this->sections as $section ) {

			if ( ! $section instanceof WP_Custom_Settings_Section ) {
				continue;
			}

			add_settings_section( $section->id, $section->title, [ $section, 'display' ], $this->page['menu_slug'] );

			$fields = $section->fields;

			if ( empty( $fields ) || ! is_array( $fields ) ) {
				continue;
			}

			foreach ( $fields as $field ) {

				if ( ! is_object( $field ) ) {
					continue;
				}

				// Set option name used to save all settings field value in a single option name in the options table.
				// Couldn't find a better way to get option name in WP_Custom_Settings_Field class so setting it through the method.
				$field->set_option_name( $this->setting['option_name'] );

				add_settings_field( $field->id, $field->title, [ $field, 'display' ], $this->page['menu_slug'], $section->id, $field->args );

			}
		}

	}

	/**
	 * Register menu page.
	 *
	 * @return void
	 */
	public function add_menu_page() {

		add_menu_page(
			$this->page['page_title'],
			$this->page['menu_title'],
			$this->page['capability'],
			$this->page['menu_slug'],
			[ $this, 'display_menu_page' ],
			$this->page['icon_url'],
			$this->page['position']
		);

	}

	/**
	 * Display menu page.
	 *
	 * @return void
	 */
	public function display_menu_page() {

		// Check user capabilities.
		if ( ! current_user_can( $this->page['capability'] ) ) {
			return;
		}

		// Check if the user has submitted the settings.
		// WordPress will add the "settings-updated" $_GET parameter to the URL.
		if ( isset( $_GET['settings-updated'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			// Add settings saved message with the class of "updated".
			add_settings_error(
				"{$this->page['menu_slug']}_messages",
				"{$this->page['menu_slug']}_message",
				__( 'Settings Saved', 'wp-custom-settings' ),
				'updated'
			);

		}

		// Show error/update messages.
		settings_errors( "{$this->page['menu_slug']}_messages" );

		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
			<?php
			// Output security fields for the registered setting.
			settings_fields( $this->setting['option_group'] );

			// Output setting sections and their fields.
			// Sections are registered for registered setting, each field is registered to a specific section.
			do_settings_sections( $this->page['menu_slug'] );

			// Output save settings button.
			submit_button( __( 'Save Settings', 'wp-custom-settings' ) );
			?>
			</form>
		</div>
		<?php
	}
}
