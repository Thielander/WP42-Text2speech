<?php
/**
 * Exit if accessed directly.
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register the admin menu for the wp42-text2speech plugin.
 *
 * This function adds a new menu item to the WordPress admin area
 * under the 'Settings' menu. It allows users with 'manage_options' capability
 * to access the settings page of the wp42-text2speech plugin.
 */
function wp_t2s_admin_menu() {
    add_options_page(
        __('wp42-text2speech Settings', 'wp42-text2speech'), 
        __('Text2Speech', 'wp42-text2speech'),           
        'manage_options',                       
        'wp42-text2speech',                       
        'wp_t2s_text2speech_settings'                 
    );
}

/**
 * Hook into the 'admin_menu' action to add the menu item.
 */
add_action('admin_menu', 'wp_t2s_admin_menu');