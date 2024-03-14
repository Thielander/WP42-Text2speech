<?php
/**
 * Exit if accessed directly.
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Adds a custom column to the post list table.
 *
 * @param array $columns Existing columns.
 * @return array Updated columns.
 */
function wp_t2s_add_columns($columns) {
    $columns['text2speech'] = '<span class="dashicons dashicons-format-audio"></span>'; 
    return $columns;
}

/**
 * Checks if an audio file exists for a given post.
 *
 * @param int $post_id Post ID.
 * @return bool True if the audio file exists, false otherwise.
 */
function does_audio_file_exist($post_id) {
    $file_path = SOUNDFILE_UPLOAD_DIR . $post_id . '.mp3';
    return file_exists($file_path);
}

/**
 * Custom column content for the 'text2speech' column.
 *
 * @param string $column  The name of the column.
 * @param int    $post_id The post ID.
 */
function wp_t2s_custom_column($column, $post_id) {
    
    switch ($column) {
        case 'text2speech':
            if (get_post_status($post_id) === 'publish' || get_post_status($post_id) === 'future') {
                   
                if (does_audio_file_exist($post_id)) {
                    /**
                     * Display check icon and delete icon
                     */
                    echo '<span class="dashicons dashicons-yes"></span>';
                    echo '<a href="#" class="text2speech-delete" data-postid="' . $post_id . '"><span class="dashicons dashicons-trash"></span></a>';
                } else { 
                    /**
                     * Display plus icon
                     */
                    echo '<a href="#" class="text2speech-generate" data-postid="' . $post_id . '"><span class="dashicons dashicons-plus"></span></a>';
                }
            } else {
                echo '<span class="dashicons dashicons-no"></span>';
            }
            break;
    }
}
 
/**
 * Add custom column only if API key is valid
 */
$openai_check_api = get_option('wpt2s-check_api');

if ($openai_check_api) {
    add_filter('manage_posts_columns', 'wp_t2s_add_columns');
    add_action('manage_posts_custom_column', 'wp_t2s_custom_column', 10, 2);
}