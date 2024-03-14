<?php
/**
 * Exit if accessed directly.
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Displays an admin notice if the OpenAI API key is not configured.
 *
 * This function checks if the OpenAI API key is set in the plugin settings.
 * If not, it displays a warning notice in the WordPress admin dashboard,
 * reminding the user to configure the key.
 */
function wp_t2s_check_api_key() {
    $OPENAIKEY = get_option('wpt2s-openai-apikey');

    /**
     * Check if the OpenAI API key is empty and display a warning notice.
     */
    if (empty($OPENAIKEY)) {
        ?>
        <div class="notice notice-warning is-dismissible">
            <p><?php _e('Please make sure that you have configured the OPENAI API key in the Text2Speech settings.', 'wp42-text2speech'); ?></p>
        </div>
        <?php
    }
}

/**
 * Hook into admin_notices to display the notice.
 */
add_action('admin_notices', 'wp_t2s_check_api_key');
