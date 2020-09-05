<?php
/**
 * Custom Settings example by using WP Settings API Wrapper.
 *
 * @package wp-custom-settings
 */

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
						__( 'Field Title 1', 'wp-custom-settings' ),
						[
							'description' => 'Description of textarea field.',
							'label_for'   => 'wp_custom_settings_textarea_field',
							'class'       => 'large-text',
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
				]
			),
		]
	);

}
add_action( 'init', 'wp_register_custom_settings' );
