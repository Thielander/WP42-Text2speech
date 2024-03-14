<?php
/*
 * Plugin Name: WP42-Text2speech
 * Plugin URI: https://www.wp42.de/text-to-speech-plugin-wordpress/
 * Description: wp42-text2speech allows WordPress posts to be read aloud in different languages with a text-to-speech feature. 
 * Version: 1.0
 * Author: Alexander Thiele
 * Author URI: https://www.linkedin.com/in/thielander/
 * Text Domain: wp42-text2speech
 * 
 * wp42-text2speech is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 * 
 * wp42-text2speech is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

/**
 * Exit if accessed directly.
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Define plugin base path.
 */
define('WP_TEXT2SPEECH_PLUGIN_PATH', plugin_dir_path(__FILE__));

/**
 * Define upload directory for sound files.
 */
define('SOUNDFILE_UPLOAD_DIR', wp_upload_dir()['basedir'] . '/wp42-text2speech/');
 
/**
 * Define URL directory for sound files.
 */
define('SOUNDFILE_URL_DIR', wp_upload_dir()['baseurl'] . '/wp42-text2speech/');

/**
 * Include necessary files.
 */
require_once WP_TEXT2SPEECH_PLUGIN_PATH . 'includes/admin-menu.php';
require_once WP_TEXT2SPEECH_PLUGIN_PATH . 'includes/check_api.php';
require_once WP_TEXT2SPEECH_PLUGIN_PATH . 'includes/settings.php';
require_once WP_TEXT2SPEECH_PLUGIN_PATH . 'includes/columns.php';
require_once WP_TEXT2SPEECH_PLUGIN_PATH . 'includes/ajax-handler.php';
require_once WP_TEXT2SPEECH_PLUGIN_PATH . 'includes/scripts.php';
require_once WP_TEXT2SPEECH_PLUGIN_PATH . 'includes/notices.php';
require_once WP_TEXT2SPEECH_PLUGIN_PATH . 'includes/chatGPT.php';
require_once WP_TEXT2SPEECH_PLUGIN_PATH . 'includes/read-post.php';



/**
 * Adds a settings link to the plugin action links.
 *
 * @param array $links Existing plugin action links.
 * @return array Updated action links with settings link.
 */
function wp_text2speech_add_settings_link($links) {
    $settings_link = '<a href="' . admin_url('options-general.php?page=wp42-text2speech') . '">' . __('Settings', 'wp42-text2speech') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}

/**
 * Add settings link to the plugin action links.
 */
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wp_text2speech_add_settings_link');

/**
 * Adds a Documentation link to the plugin action links.
 *
 * @param array $links Existing plugin action links.
 * @return array Updated action links with settings link.
 */
function wp_text2speech_add_documentation_link($links) {
    $documentation_url = 'https://www.wp42.de/text2speech-dokumentation/';
    $documentation_link = '<a href="' . esc_url($documentation_url) . '" target="_blank">' . __('Documentation', 'wp42-text2speech') . '</a>';
    array_unshift($links, $documentation_link);
    return $links;
}

/**
 * Add documentation link to the plugin action links.
 */
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wp_text2speech_add_documentation_link');


/**
 * Includes a JavaScript script in the Gutenberg block editor.
 */
function my_enqueue_block_editor_assets() {
    wp_enqueue_script(
        'my-block-js',
        plugins_url('/assets/shortcode.js', __FILE__), 
        array('wp-blocks', 'wp-dom-ready', 'wp-edit-post')
    );
}
add_action('enqueue_block_editor_assets', 'my_enqueue_block_editor_assets');

/**
 * Adds a button to the TinyMCE editor.
 */
function text2speech_plugin_add_tinymce_button() {
    if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
        return;
    }
    if ('true' == get_user_option('rich_editing')) {
        add_filter('mce_external_plugins', 'text2speech_plugin_add_tinymce_plugin');
        add_filter('mce_buttons', 'text2speech_plugin_register_tinymce_button');
    }
}
add_action('admin_head', 'text2speech_plugin_add_tinymce_button');

/**
 * Registers a new button for the TinyMCE editor.
 *
 * @param array $buttons Existing buttons in the TinyMCE editor.
 * @return array Array with added button.
 */
function text2speech_plugin_register_tinymce_button($buttons) {
    array_push($buttons, "button_text2speech");
    return $buttons;
}

/**
 * Adds the script for the custom TinyMCE button.
 *
 * @param array $plugin_array Array of TinyMCE plugins.
 * @return array Updated array with the script for the custom button.
 */
function text2speech_plugin_add_tinymce_plugin($plugin_array) {
    $plugin_array['button_text2speech'] = plugins_url('assets/shortcode.js', __FILE__); 
    return $plugin_array;
}