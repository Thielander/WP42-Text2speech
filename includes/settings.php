<?php
/**
 * Exit if accessed directly.
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register settings for wp42-text2speech plugin.
 *
 * This function registers various settings used by the plugin
 * to store API keys and other configuration options.
 */
function wp_t2s_register_settings() {
    register_setting('wp42-text2speech-settings', 'wpt2s-openai-apikey');
    register_setting('wp42-text2speech-settings', 'wpt2s-GPT_voice_dropdown');
    register_setting('wp42-text2speech-settings', 'wpt2s-before_after_post_dropdown');
}

/**
 * Render the settings page for wp42-text2speech plugin.
 *
 * This function outputs the HTML for the plugin's settings page,
 * allowing users to configure their API keys and other settings.
 */
function wp_t2s_text2speech_settings() {

    $OPENAIKEY = get_option('wpt2s-openai-apikey');
    $dropdown = get_option('wpt2s-GPT_voice_dropdown');
    $dropdown_before_after_post = get_option('wpt2s-before_after_post_dropdown');

    // Check OpenAI API key validity and update option
    $is_valid = is_openai_key_valid($OPENAIKEY);
    update_option('wpt2s-check_api', $is_valid ? 1 : 0);
    $check_api = get_option('wpt2s-check_api');

    ?>
    <div class="wrap">
        <h1><?=  __('wp42-text2speech settings', 'wp42-text2speech') ?></h1>
        <p><?=  __('Settings for the Text2speech plugin', 'wp42-text2speech') ?></p>

        <form method="post" action="options.php">
            <?php settings_fields('wp42-text2speech-settings'); ?>
            <?php do_settings_sections('wp42-text2speech-settings'); ?>

            <div class="settings-section">
                <div class="settings-row">
                    <label for="wpt2s-openai-apikey"><?=  __('Your Openai API KEY:', 'wp42-text2speech') ?> <?php echo ($check_api == 1) ? '<span class="api-check-indicator-green"></span>' : '<span class="api-check-indicator-red"></span>'; ?></label>
                    <input type="text" id="wpt2s-openai-apikey" name="wpt2s-openai-apikey" value="<?php echo esc_attr($OPENAIKEY); ?>" style="width: 50%;" />
                    <p><?=  __('Enter your OpenAI API key to create sound files.', 'wp42-text2speech') ?></p>
                </div>

                <div class="settings-row">
                    <label for="GPT_voice_dropdown"><?=  __('Voice:', 'wp42-text2speech') ?></label>
                    <select name="wpt2s-GPT_voice_dropdown">
                        <option value="alloy" 	<?php selected($dropdown, 'alloy'); 	?>>Alloy</option>
                        <option value="echo" 	<?php selected($dropdown, 'echo'); 		?>>Echo</option>
                        <option value="fable" 	<?php selected($dropdown, 'fable'); 	?>>Fable</option>
                        <option value="onyx" 	<?php selected($dropdown, 'onyx'); 		?>>Onyx</option>
                        <option value="nova" 	<?php selected($dropdown, 'nova'); 		?>>Nova</option>
                        <option value="shimmer" <?php selected($dropdown, 'shimmer'); 	?>>Shimmer</option>
                    </select>
                    <p><?=  __('Voice examples:', 'wp42-text2speech') ?> <a href="https://platform.openai.com/docs/guides/text-to-speech/voice-options" target="_blank"><?=  __('Check here', 'wp42-text2speech') ?></a></p>
                </div>

                <div class="settings-row">
                    <label for="wpt2s-before_after_post_dropdown"><?=  __('Position:', 'wp42-text2speech') ?></label>
                    <select name="wpt2s-before_after_post_dropdown">
                        <option value="before" 	    <?php selected($dropdown_before_after_post, 'before'); 	    ?>><?=  __('Before post', 'wp42-text2speech') ?></option>
                        <option value="after" 	    <?php selected($dropdown_before_after_post, 'after'); 	    ?>><?=  __('After post', 'wp42-text2speech') ?></option>
                        <option value="both" 	    <?php selected($dropdown_before_after_post, 'both'); 	    ?>><?=  __('Before & after post', 'wp42-text2speech') ?></option>
                        <option value="shortcode" 	<?php selected($dropdown_before_after_post, 'shortcode'); 	?>><?=  __('Just with Shortcode', 'wp42-text2speech') ?></option>
                       
                    </select>
                    <p><?=  __('Where should the play button be displayed?', 'wp42-text2speech') ?></p>
                </div>
            
            </div>
            <?php submit_button(); ?>
        </form>
    </div>
<?php
}

/**
 * Hook into admin_init to register settings
 */
add_action('admin_init', 'wp_t2s_register_settings');
