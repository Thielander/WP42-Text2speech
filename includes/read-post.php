<?php 
/**
 * Exit if accessed directly.
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Checks if an audio file exists for a given post.
 *
 * @param int $post_id The ID of the post.
 * @return bool True if the audio file exists, false otherwise.
 */
function wp_t2s_exists_audiofile($post_id) {
    $file_path = SOUNDFILE_UPLOAD_DIR . intval($post_id) . '.mp3';
    return file_exists($file_path);
}

/**
 * Adds an audio button to the post content if an audio file exists.
 *
 * This function checks if there is an audio file for the post and,
 * if so, adds a button to the post content to play the audio.
 * The position of the button (before, after, or both) is based on plugin settings.
 *
 * @param string $content The content of the post.
 * @return string The modified content with the audio button.
 */
function add_audio_button_to_content($content) {
    global $post;

    /**
     * Check if audio file exists for the post
     */
    if (wp_t2s_exists_audiofile($post->ID)) {
        $post_id = intval($post->ID);
        $audio_url = SOUNDFILE_URL_DIR . $post_id . '.mp3';

       
        /**
         * Rewriting the URL to HTTPS
         */
        $audio_url = str_replace("http://", "https://", $audio_url);

        
        /**
         * Unique IDs for audio and buttons
         */
        $audioId = 'audio' . $post_id;

        /**
         * Button Text
         */
        $button_text = __('Listen to post:', 'wp42-text2speech');
      
        /**
         * Audio Player
         */
        $button_html  = '<div class="my-audio-button-container">';
        $button_html .= '<div class="audio-header">';
        $button_html .= '<b>' . $button_text . '</b>';
        $button_html .= '</div>'; // Ende der audio-header div
        $button_html .= '<audio controls volume="0.1" id="' . $audioId . '" class="audio-example" style="width: auto;">';
        $button_html .= '<source src="' . $audio_url . '" type="audio/mpeg">';
        $button_html .= 'Your browser does not support the audio element.';
        $button_html .= '</audio>';
        $button_html .= '</div>'; // Ende der my-audio-button-container div
        

        /**
         *  Insert button HTML based on settings
         */
        $dropdown_before_after_post = get_option('wpt2s-before_after_post_dropdown');

        switch ($dropdown_before_after_post) {
            case 'before':
                $content = str_replace('[text2speech]', '', $content);
                $content = $button_html . $content;
                break;
            case 'after':
                $content = str_replace('[text2speech]', '', $content);
                $content .= $button_html;
                break;
            case 'both':
                $content = str_replace('[text2speech]', '', $content);
                $content = $button_html . $content . $button_html;
                break;
            case 'shortcode':
                $content = str_replace('[text2speech]', $button_html, $content);
                break;
        }

        return $content;

    }
    return $content;
}

/**
 * Hook into 'the_content' to add the audio button
 */
add_filter('the_content', 'add_audio_button_to_content');