<?php
/**
 * Custom Settings example by using WP Settings API Wrapper.
 *
 * @package wp-custom-settings
 */

use WP_Custom_Settings\WP_Custom_Settings;
use WP_Custom_Settings\WP_Custom_Settings_Section;
use WP_Custom_Settings\WP_Custom_Settings_Field;

/**
 * An example of adding a custom settings page.
 *
 * @return void
 */
function wp_register_custom_settings() {

	$custom_settings = new WP_Custom_Settings(
		// Arguments to add menu page. Following arguments are same as add_menu_page() function arguments.
		// Callback argument does not needed.
		[
			'page_title' => __( 'Custom Settings', 'wp-custom-settings' ),
			'menu_title' => __( 'Custom Settings', 'wp-custom-settings' ),
			'capability' => 'manage_options',
			'menu_slug'  => 'wp-custom-settings-page',
			'icon_url'   => '',
			'position'   => null,
		],
		// Arguments to register setting. Following arguments are same as register_setting() function arguments.
		[
			'option_group' => 'wp_custom_settings_group',
			'option_name'  => 'wp_custom_settings_options',
			'args'         => array(
				'type'              => 'array',
				'description'       => 'Description of Custom Settings.',
				'show_in_rest'      => true,
				'default'           => array(),
				'sanitize_callback' => null,
			),
		],
		// Arguments to add sections and fields.
		[
			new WP_Custom_Settings_Section(
				'wp_custom_settings_form_elements_section', // ID.
				__( 'HTML Form Elements', 'wp-custom-settings' ), // Title.
				__( 'All the HTML form elements.', 'wp-custom-settings' ), // Description.
				[
					new WP_Custom_Settings_Field(
						'text', // Field type.
						'wp_custom_settings_text_field', // ID. Also, it will used for "name" attribute.
						__( 'Text Input', 'wp-custom-settings' ), // Title.
						[ // Pass additional arguments.
							'description' => 'Description of text field.',
							'label_for'   => 'wp_custom_settings_text_field',
							'class'       => 'regular-text',
						]
					),
					new WP_Custom_Settings_Field(
						'select', // Field type.
						'wp_custom_settings_select_field', // ID. Also, it will used for "name" attribute.
						__( 'Select Input', 'wp-custom-settings' ), // Title.
						[ // Pass additional arguments.
							'options'     => [
								''         => 'Select Option',
								'option-1' => 'Option 1',
								'option-2' => 'Option 2',
								'option-3' => 'Option 3',
							],
							'description' => 'Description of select field.',
							'label_for'   => 'wp_custom_settings_select_field',
							'class'       => 'regular-text',
						]
					),
					new WP_Custom_Settings_Field(
						'textarea',
						'wp_custom_settings_textarea_field',
						__( 'Textarea Input', 'wp-custom-settings' ),
						[
							'description' => 'Description of textarea field.',
							'label_for'   => 'wp_custom_settings_textarea_field',
							'class'       => 'regular-text',
						]
					),
				]
			),
			new WP_Custom_Settings_Section(
				'wp_custom_settings_input_types_section',
				__( 'Input Types.', 'wp-custom-settings' ),
				__( 'All the input types.', 'wp-custom-settings' ),
				[
					new WP_Custom_Settings_Field(
						'checkbox', // Field type.
						'wp_custom_settings_checkbox_field', // ID. Also, it will used for "name" attribute.
						__( 'Checkbox Input Type', 'wp-custom-settings' ), // Title.
						[ // Pass additional arguments.
							'value'       => '1',
							'label'       => 'Checkbox label',
							'description' => 'Description of checkbox input.',
						]
					),
					new WP_Custom_Settings_Field(
						'radio', // Field type.
						'wp_custom_settings_radio_field', // ID. Also, it will used for "name" attribute.
						__( 'Radio Input Type', 'wp-custom-settings' ), // Title.
						[ // Pass additional arguments.
							'options'     => [
								'yes' => 'Yes',
								'no'  => 'No',
							],
							'description' => 'Description of radio field.',
							'label_for'   => 'wp_custom_settings_radio_field',
						]
					),
					new WP_Custom_Settings_Field(
						'password', // Field type.
						'wp_custom_settings_password_field', // ID. Also, it will used for "name" attribute.
						__( 'Password Input Type', 'wp-custom-settings' ), // Title.
						[ // Pass additional arguments.
							'description' => 'Description of password input type.',
							'label_for'   => 'wp_custom_settings_password_field',
							'class'       => 'regular-text',
						]
					),
					new WP_Custom_Settings_Field(
						'email', // Field type.
						'wp_custom_settings_email_field', // ID. Also, it will used for "name" attribute.
						__( 'Email Input Type', 'wp-custom-settings' ), // Title.
						[ // Pass additional arguments.
							'description' => 'Description of email input type.',
							'label_for'   => 'wp_custom_settings_email_field',
							'class'       => 'regular-text',
						]
					),
					new WP_Custom_Settings_Field(
						'url', // Field type.
						'wp_custom_settings_url_field', // ID. Also, it will used for "name" attribute.
						__( 'URL Input Type', 'wp-custom-settings' ), // Title.
						[ // Pass additional arguments.
							'description' => 'Description of url input type.',
							'label_for'   => 'wp_custom_settings_url_field',
							'class'       => 'regular-text',
						]
					),
					new WP_Custom_Settings_Field(
						'tel', // Field type.
						'wp_custom_settings_tel_field', // ID. Also, it will used for "name" attribute.
						__( 'Tel Input Type', 'wp-custom-settings' ), // Title.
						[ // Pass additional arguments.
							'description' => 'Description of Tel input type.',
							'label_for'   => 'wp_custom_settings_tel_field',
							'class'       => 'regular-text',
						]
					),
					new WP_Custom_Settings_Field(
						'number', // Field type.
						'wp_custom_settings_number_field', // ID. Also, it will used for "name" attribute.
						__( 'Number Input Type', 'wp-custom-settings' ), // Title.
						[ // Pass additional arguments.
							'description' => 'Description of number input type.',
							'label_for'   => 'wp_custom_settings_number_field',
							'class'       => 'small-text',
						]
					),
					new WP_Custom_Settings_Field(
						'color', // Field type.
						'wp_custom_settings_color_field', // ID. Also, it will used for "name" attribute.
						__( 'Color Input Type', 'wp-custom-settings' ), // Title.
						[ // Pass additional arguments.
							'description' => 'Description of color input type.',
							'label_for'   => 'wp_custom_settings_color_field',
							'class'       => 'small-text',
						]
					),
					new WP_Custom_Settings_Field(
						'date', // Field type.
						'wp_custom_settings_date_field', // ID. Also, it will used for "name" attribute.
						__( 'Date Input Type', 'wp-custom-settings' ), // Title.
						[ // Pass additional arguments.
							'description' => 'Description of date input type.',
							'label_for'   => 'wp_custom_settings_date_field',
							'class'       => 'regualr-text',
						]
					),
					new WP_Custom_Settings_Field(
						'datetime-local', // Field type.
						'wp_custom_settings_datetime_local_field', // ID. Also, it will used for "name" attribute.
						__( 'Datetime-local Input Type', 'wp-custom-settings' ), // Title.
						[ // Pass additional arguments.
							'description' => 'Description of Datetime-local input type.',
							'label_for'   => 'wp_custom_settings_datetime_local_field',
							'class'       => 'regular-text',
						]
					),
					new WP_Custom_Settings_Field(
						'month', // Field type.
						'wp_custom_settings_month_field', // ID. Also, it will used for "name" attribute.
						__( 'Month Input Type', 'wp-custom-settings' ), // Title.
						[ // Pass additional arguments.
							'description' => 'Description of month input type.',
							'label_for'   => 'wp_custom_settings_month_field',
							'class'       => 'regualr-text',
						]
					),
					new WP_Custom_Settings_Field(
						'week', // Field type.
						'wp_custom_settings_week_field', // ID. Also, it will used for "name" attribute.
						__( 'Week Input Type', 'wp-custom-settings' ), // Title.
						[ // Pass additional arguments.
							'description' => 'Description of week input type.',
							'label_for'   => 'wp_custom_settings_week_field',
							'class'       => 'regualr-text',
						]
					),
					new WP_Custom_Settings_Field(
						'time', // Field type.
						'wp_custom_settings_time_field', // ID. Also, it will used for "name" attribute.
						__( 'Time Input Type', 'wp-custom-settings' ), // Title.
						[ // Pass additional arguments.
							'description' => 'Description of time input type.',
							'label_for'   => 'wp_custom_settings_time_field',
							'class'       => 'regualr-text',
						]
					),
				]
			),
		]
	);

}
add_action( 'init', 'wp_register_custom_settings' );
