<?php
/**
 * WP Custom Settings
 *
 * @package           wp-custom-settings
 * @author            Chandra Patel
 * @license           GPL-2.0-or-later
 */

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
