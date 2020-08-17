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
 * Plugin URI:        https://github.com/chandrapatel/wp-custom-settings
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

require_once __DIR__ . '/class-wp-custom-settings.php';
require_once __DIR__ . '/class-wp-custom-settings-section.php';
require_once __DIR__ . '/class-wp-custom-settings-field.php';
