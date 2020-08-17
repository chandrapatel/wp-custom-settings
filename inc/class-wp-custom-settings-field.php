<?php
/**
 * WP Custom Settings
 *
 * @package           wp-custom-settings
 * @author            Chandra Patel
 * @license           GPL-2.0-or-later
 */

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
