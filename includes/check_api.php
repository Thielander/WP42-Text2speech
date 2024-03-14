<?php
/**
 * Exit if accessed directly.
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Checks the validity of the OpenAI API key.
 *
 * Sends a request to the OpenAI API to validate the provided API key.
 * 
 * @param string $openaiKey The OpenAI API key to validate.
 * @return bool True if the API key is valid, false otherwise.
 */
function is_openai_key_valid($openaiKey) {
    $api_url = "https://api.openai.com/v1/chat/completions";

    $headers = array(
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $openaiKey,
    );

    /**
     * Updated data structure for the Chat API Endpoint
     */
    $api_data = array(
        'body' => json_encode(array(
            "model" => "gpt-3.5-turbo",
            "messages" => array(
                array(
                    "role" => "system",
                    "content" => "You are a helpful assistant."
                ),
                array(
                    "role" => "user",
                    "content" => "Test"
                )
            )
        )),
        'headers' => $headers,
        'method' => 'POST',
        'data_format' => 'body'
    );

    $response = wp_remote_post($api_url, $api_data);

    /**
     * There was an error with the enquiry
     */
    if (is_wp_error($response)) {
        return false; 
    }

    $status_code = wp_remote_retrieve_response_code($response);

    /**
     * Returns true if the status code is 200, otherwise falses
     */
    return $status_code == 200;  
}


 