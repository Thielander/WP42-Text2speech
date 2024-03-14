<?php
/**
 * Exit if accessed directly.
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handles AJAX request for generating text-to-speech sound files.
 *
 * This function processes the AJAX request, validates the post ID,
 * checks user permissions and the post status, and then attempts
 * to generate a text-to-speech sound file using the OpenAI API.
 */
function wp_t2s_text2speech_ajax_handler() {

    /**
     * Check nonce for security
     */
    check_ajax_referer('text2speech_nonce', 'security');

    /**
     * Check user capability
     */
    if (!current_user_can('edit_posts')) {
        wp_send_json_error(__('You do not have permission to edit posts.', 'wp42-text2speech'));
        return;
    }

    /**
     * Validate post ID
     */
    $post_id = intval($_POST['post_id']);
    if (!$post_id) {
        wp_send_json_error(__('Invalid post ID.', 'wp42-text2speech'));
        return;
    }

    /**
     * Get and validate the post object
     */
    $post = get_post($post_id);
    $post_title = get_the_title($post_id);
    if (!$post || $post->post_status !== 'publish') {
        wp_send_json_error(__('Post not found or not published.', 'wp42-text2speech'));
        return;
    }
 
    /**
     * Retrieve and process the text content of the post
     */
    $text = $post->post_content;

    /**
     *  Extract only the content within <h> and <p> tags
     */
    preg_match_all('/<h[1-6][^>]*>.*?<\/h[1-6]>|<p[^>]*>.*?<\/p>/', $text, $matches);

    /**
     * Combine all extracted content into one string
     */
    $filtered_text = implode(' ', $matches[0]);

    /**
     * Add a full stop and then two line breaks after each </h[1-6]> tag
     */
    $filtered_text = preg_replace('/(<\/h[1-6]>)/', "$1.\n\n", $filtered_text);

    /**
     * Now remove all HTML tags to get only plain text
     */
    $filtered_text = strip_tags($filtered_text);


    /**
     * Retrieve API key and voice setting
     */
    $APIKEY = get_option('wpt2s-openai-apikey');
    $voice  = get_option('wpt2s-GPT_voice_dropdown');


    /**
     * Generate the sound file
     */
    try {
        $chatGPT = new chatGPT($APIKEY, $voice);
        $chatGPT->getSoundfile( $filtered_text, $post_id);
        wp_send_json_success();
    } catch (Exception $e) {
        wp_send_json_error($e->getMessage());
    }
}

add_action('wp_ajax_text2speech_generate', 'wp_t2s_text2speech_ajax_handler');

/**
 * Handles AJAX request for deleting text-to-speech sound files.
 *
 * This function processes the AJAX request to delete a previously
 * generated sound file. It validates the post ID and checks user permissions
 * before attempting to delete the file.
 */
function wp_t2s_delete_audio_ajax_handler() {

    /**
     * Check nonce for security
     */
    check_ajax_referer('text2speech_nonce', 'security');

    /**
     *  Check user capability
     */
    if (!current_user_can('edit_posts')) {
        wp_send_json_error(__('You do not have permission to delete this file.', 'wp42-text2speech'));
        return;
    }

    /**
     * Validate post ID
     */
    $post_id = intval($_POST['post_id']);
    if (!$post_id) {
        wp_send_json_error(__('Invalid post ID.', 'wp42-text2speech'));
        return;
    }

    /**
     * Attempt to delete the file
     */
    $file_path = SOUNDFILE_UPLOAD_DIR . $post_id . '.mp3';
    if (file_exists($file_path)) {
        unlink($file_path);
        wp_send_json_success(__('File deleted.', 'wp42-text2speech'));
    } else {
        wp_send_json_error(__('File not found.', 'wp42-text2speech'));
    }
}

add_action('wp_ajax_text2speech_delete', 'wp_t2s_delete_audio_ajax_handler');