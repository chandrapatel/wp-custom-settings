<?php
/**
 * WP Custom Settings
 *
 * @package           wp-custom-settings
 * @author            Chandra Patel
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       WP Custom Settings
 * Plugin URI:        https://chandrapatel.in
 * Description:       Allows developers to create a custom admin menu page with settings using Settings API without registering callbacks to every settings section and field.
 * Version:           0.1
 * Requires at least: 5.0
 * Requires PHP:      7.0
 * Author:            Chandra Patel
 * Author URI:        https://chandrapatel.in
 * Text Domain:       wp-custom-settings
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
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

/**
 * Manage settings section properties and rendering.
 */
class WP_Custom_Settings_Section {

	/**
	 * Section ID.
	 *
	 * @var string
	 */
	private $id;

	/**
	 * Section title.
	 *
	 * @var string
	 */
	private $title;

	/**
	 * Section description.
	 *
	 * @var string
	 */
	private $description;

	/**
	 * An array of Section's fields.
	 *
	 * @var array
	 */
	private $fields;

	/**
	 * Initialize class properties required to add settings section.
	 *
	 * @param string $id          Section ID.
	 * @param string $title       Section title.
	 * @param string $description Section description.
	 * @param array  $fields      An array of Section's fields.
	 */
	public function __construct( $id, $title, $description, $fields ) {

		$this->id          = $id;
		$this->title       = $title;
		$this->description = $description;
		$this->fields      = $fields;

	}

	/**
	 * Return value of requested property.
	 *
	 * @param string $property_name Property Name.
	 *
	 * @return mixed
	 */
	public function __get( $property_name ) {

		return $this->$property_name;

	}

	/**
	 * Settings section display callback.
	 *
	 * @param array $args Display arguments.
	 *
	 * @return void
	 */
	public function display( $args ) {

		printf(
			'<p id="%1$s">%2$s</p>',
			esc_attr( $this->id ),
			esc_html( $this->description )
		);

	}

}

/**
 * Manage settings field properties and rendering.
 */
class WP_Custom_Settings_Field {

	/**
	 * Field type.
	 *
	 * @var string
	 */
	private $type;

	/**
	 * Field ID.
	 *
	 * @var string
	 */
	private $id;

	/**
	 * Field title.
	 *
	 * @var string
	 */
	private $title;

	/**
	 * An array of additional field args.
	 *
	 * @var array
	 */
	private $args;

	/**
	 * Option Name. Used to save all settings field value in the single option name in the options table.
	 *
	 * @var string
	 */
	private $option_name;

	/**
	 * Initialize class properties required to add settings field.
	 *
	 * @param string $type  Field Type.
	 * @param string $id    Field ID.
	 * @param string $title Field title.
	 * @param array  $args  An array of addtional field args.
	 */
	public function __construct( string $type, string $id, string $title, array $args = [] ) {

		$this->type  = $type;
		$this->id    = $id;
		$this->title = $title;
		$this->args  = $args;

	}

	/**
	 * Return value of requested property.
	 *
	 * @param string $property_name Property Name.
	 *
	 * @return mixed
	 */
	public function __get( $property_name ) {

		return $this->$property_name;

	}

	/**
	 * Set option name used to save all settings field value in a single option name in the options table.
	 *
	 * @param string $option_name Option Name.
	 *
	 * @return void
	 */
	public function set_option_name( $option_name ) {

		$this->option_name = $option_name;

	}

	/**
	 * Settings section display callback.
	 *
	 * @param array $args Display arguments.
	 *
	 * @return void
	 */
	public function display( $args ) {

		$options = get_option( $this->option_name );

		$field_value = ( isset( $options[ $this->id ] ) ) ? $options[ $this->id ] : '';

		switch ( $this->type ) {
			case 'textarea':
				$this->display_textarea_field( $field_value );
				break;

			case 'text':
			default:
				$this->display_text_field( $field_value );
				break;
		}

		if ( ! empty( $args['description'] ) ) {
			printf(
				'<p class="description">%s</p>',
				esc_html( $args['description'] )
			);
		}

	}

	/**
	 * Display text field.
	 *
	 * @param string $field_value Field value.
	 *
	 * @return void
	 */
	private function display_text_field( $field_value ) {

		$class = isset( $this->args['class'] ) ? $this->args['class'] : 'regular-text';

		printf(
			'<input type="text" name="%1$s[%2$s]" id="%2$s" value="%3$s" class="%4$s" />',
			esc_attr( $this->option_name ),
			esc_attr( $this->id ),
			esc_attr( $field_value ),
			esc_attr( $class )
		);

	}

	/**
	 * Display textarea field.
	 *
	 * @param string $field_value Field value.
	 *
	 * @return void
	 */
	private function display_textarea_field( $field_value ) {

		$class = isset( $this->args['class'] ) ? $this->args['class'] : 'regular-text';

		printf(
			'<textarea name="%1$s[%2$s]" id="%2$s" class="%4$s">%3$s</textarea>',
			esc_attr( $this->option_name ),
			esc_attr( $this->id ),
			esc_textarea( $field_value ),
			esc_attr( $class )
		);

	}

}
