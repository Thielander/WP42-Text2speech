<?php
/**
 * Exit if accessed directly.
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * The chatGPT class handles the text-to-speech conversion using OpenAI API.
 */
class chatGPT {

    /**
     * The API key for OpenAI.
     *
     * @var string
     */
    private $apiKey;

    /**
     * The selected voice model for text-to-speech conversion.
     *
     * @var string
     */
    private $voice;

    /**
     * Constructor for the chatGPT class.
     *
     * @param string $APIKEY The API key for OpenAI.
     * @param string $voice  The selected voice model.
     */
    function __construct($APIKEY, $voice) {
        $this->apiKey = $APIKEY;
        $this->voice = $voice;
    }

    /**
     * Generates a sound file from the given text using OpenAI's text-to-speech API.
     *
     * @param string $text    The text to convert to speech.
     * @param int    $post_id The ID of the post for which the audio is generated.
     * @return void
     */
    public function getSoundfile($text, $post_id) {
        $file_path = SOUNDFILE_UPLOAD_DIR . intval($post_id) . '.mp3';

        // Ensure the upload directory exists
        if (!file_exists(SOUNDFILE_UPLOAD_DIR)) {
            wp_mkdir_p(SOUNDFILE_UPLOAD_DIR);
        }

        $apiUrl = "https://api.openai.com/v1/audio/speech";

        $data = [
            "model" => "tts-1",
            "input" => $text,
            "voice" => $this->voice
        ];

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $this->apiKey",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            // Instead of echoing, consider throwing an exception
            throw new Exception('Curl error: ' . curl_error($ch));
        } else {
            file_put_contents($file_path, $result);
        }

        curl_close($ch);
    }
}