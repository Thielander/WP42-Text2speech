<?php
/**
 * Exit if accessed directly.
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue scripts and styles for the admin area.
 *
 * @param string $hook The current admin page.
 */
function wp_t2s_enqueue_scripts($hook) {

    /**
     * Enqueue scripts and styles for the 'edit.php' admin page
     */
    if ($hook == 'edit.php') {
        /**
         * Register and enqueue the JavaScript for admin
         */
        wp_register_script('text2speech-script', plugins_url('assets/text2speech.js', dirname(__FILE__)), array('jquery'));
        wp_enqueue_script('text2speech-script');
 
        /**
         * Localize script with data for AJAX requests
         */
        wp_localize_script('text2speech-script', 'meinText2Speech', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('text2speech_nonce')
        ));

        /**
         * Enqueue CSS for admin
         */
        wp_enqueue_style('text2speech-style', plugins_url('assets/text2speech.css', dirname(__FILE__)));

        /**
         * Add additional inline styles
         */
        wp_add_inline_style('text2speech-style', '
            .dashicons-update.spinner:before {
                animation: dashicons-spin 2s infinite linear;
            }
        ');
    }

    /**
     * Enqueue styles for the plugin settings page
     */
    if ($hook == 'settings_page_wp42-text2speech') {
        wp_register_style('settings-style', plugins_url('assets/settings.css', dirname(__FILE__)));
        wp_enqueue_style('settings-style');
    }
}

add_action('admin_enqueue_scripts', 'wp_t2s_enqueue_scripts');

/**
 * Enqueue scripts and styles for the frontend.
 *
 * This function enqueues necessary JavaScript and CSS for audio playback functionality
 * on single post pages.
 */
function wp_t2s_audio_scripts() {

    /**
     * Enqueue scripts and styles for single post pages
     */
    if (is_single()) {
        /**
         * Register and enqueue the JavaScript for the frontend
         */
        wp_register_script('audio-script', plugins_url('assets/audio.js', dirname(__FILE__)), array('jquery'), null, true);
        wp_enqueue_script('audio-script');

        /**
         * Enqueue CSS for the audio buttons on the frontend
         */
        wp_register_style('button-style', plugins_url('assets/button.css', dirname(__FILE__)));
        wp_enqueue_style('button-style');
    }
}

add_action('wp_enqueue_scripts', 'wp_t2s_audio_scripts');