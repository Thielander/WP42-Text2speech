jQuery(document).ready(function($) {
    // Handle click event for generating text-to-speech files
    $('.text2speech-generate').click(function(e) {
        e.preventDefault();
        var $this = $(this);
        var postId = $this.data('postid');

        // Replace plus icon with loading spinner
        $this.html('<span class="dashicons dashicons-update custom-spinner"></span>');

        // AJAX request to generate text-to-speech file
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'text2speech_generate',
                post_id: postId,
                security: meinText2Speech.nonce
            },
            success: function(response) {
                if (response.success) {
                    // Replace loading icon with check icon on success
                    $this.html('<span class="dashicons dashicons-yes"></span>');
                } else {
                    // Replace loading icon with plus icon and show error message
                    $this.html('<span class="dashicons dashicons-plus"></span>');
                    alert(response.data.message); // Server error message
                }
            },
            error: function(xhr, status, error) {
                // Display error message and revert to plus icon
                $this.html('<span class="dashicons dashicons-plus"></span>');
                alert('An error has occurred: ' + error);
            }
        });
    });

    // Handle click event for deleting text-to-speech files
    $('.text2speech-delete').click(function(e) {
        e.preventDefault();
        var postId = $(this).data('postid');

        // Confirm deletion
        if (confirm('Are you sure you want to delete this file?')) {
            // AJAX request to delete the file
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'text2speech_delete',
                    post_id: postId,
                    security: meinText2Speech.nonce
                },
                success: function(response) {
                    if (response.success) {
                        alert('File successfully deleted.');
                        location.reload(); // Reload the page to update the UI
                    } else {
                        alert('Error: ' + response.data);
                    }
                },
                error: function() {
                    alert('An error occurred.');
                }
            });
        }
    });
});