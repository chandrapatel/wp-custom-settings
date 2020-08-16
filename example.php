<?php
/**
 * Custom Settings example by using WP Settings API Wrapper.
 *
 * @package wp-settings-api-wrapper
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
				'wp_custom_settings_section', // ID.
				__( 'Section Title.', 'wp-custom-settings' ), // Title.
				__( 'Section Description.', 'wp-custom-settings' ), // Description.
				[
					new WP_Custom_Settings_Field(
						'text', // Field type.
						'wp_custom_settings_field', // ID. Also, it will used for "name" attribute.
						__( 'Field Title', 'wp-settings-api-wrapper' ), // Title.
						[ // Pass additional arguments.
							'description' => 'Description of Custom Settings.',
							'label_for'   => 'wp_custom_settings_field',
							'class'       => 'regular-text',
						]
					),
				]
			),
			new WP_Custom_Settings_Section(
				'wp_custom_settings_section_1',
				__( 'Section Title 1.', 'wp-custom-settings' ),
				__( 'Section Description 1.', 'wp-custom-settings' ),
				[
					new WP_Custom_Settings_Field(
						'textarea',
						'wp_custom_settings_field_1',
						__( 'Field Title 1', 'wp-custom-settings' ),
						[
							'description' => 'Description of Custom Settings Field 1.',
							'label_for'   => 'wp_custom_settings_field_2',
							'class'       => 'large-text',
						]
					),
				]
			),
		]
	);

}
add_action( 'init', 'wp_register_custom_settings' );
