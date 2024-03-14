(function (wp) {
    var registerBlockType = wp.blocks.registerBlockType;
    var el = wp.element.createElement;
    var Fragment = wp.element.Fragment;
    var BlockControls = wp.blockEditor.BlockControls;
    var Toolbar = wp.components.Toolbar;
    var IconButton = wp.components.Button;

    registerBlockType('wp-text2speech/text2speech-shortcode', {
        title: 'Text2Speech',
        icon: 'media-document',
        category: 'common',
        edit: function (props) {
            // Function that inserts the shortcode
            var insertShortcode = function () {
                props.setAttributes({ inserted: true });
            };

            // Check whether the shortcode has already been inserted
            if (!props.attributes.inserted) {
                insertShortcode();
                return el(Fragment, {},
                    el('p', {}, 'Text2Speech shortcode has been added.')
                );
            }

            // Display in the editor if the shortcode has already been inserted
            return el(Fragment, {},
                el('p', {}, '[text2speech]')
            );
        },
        save: function () {
            // The shortcode is output in the frontend
            return '[text2speech]';
        }
    });
})(window.wp);
