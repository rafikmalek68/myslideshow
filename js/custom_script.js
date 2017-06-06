jQuery(document).ready(function ($) {

    // Uploading files
    var file_frame;

    jQuery.fn.upload_listing_image = function (button) {
        var button_id = button.attr('id');
        var field_id = button_id.replace('_button', '');

        // If the media frame already exists, reopen it.
        if (file_frame) {
            file_frame.open();
            return;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: jQuery(this).data('uploader_title'),
            button: {
                text: jQuery(this).data('uploader_button_text'),
            },
            multiple: true
        });

        // When an image is selected, run a callback.
        file_frame.on('select', function () {

            //var attachment = file_frame.state().get('selection').first().toJSON();
            var attachment = file_frame.state().get('selection').toJSON();

            var images_html = '';
            for (var i = 0; i < attachment.length; i++) {

                images_html += '<li class="ui-state-default" id="imageid_' + attachment[i].id + '">';
                images_html += '<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>';
                images_html += '<img src="' + attachment[i].url + '" style="width:150px;hight:auto;border:0;display:none;" />';
                images_html += '<input type="hidden" id="hidden_imgid_' + attachment[i].id + '" name="ImageIds[]" value="' + attachment[i].id + '" />';
                images_html += '<p class="hide-if-no-js"><a title="" class="remove_img" href="javascript:;"  id="remove_' + attachment[i].id + '" >Remove</a></p>';
                images_html += '</li>';
            }
            jQuery("#slideimage_contenar").append(images_html);
            jQuery('#listingimagediv img').show();
        });

        // Finally, open the modal
        file_frame.open();
    };

    jQuery('#listingimagediv').on('click', '#upload_listing_image_button', function (event) {
        event.preventDefault();
        jQuery.fn.upload_listing_image(jQuery(this));
    });

    jQuery('#listingimagediv').on('click', '.remove_img', function (event) {
        event.preventDefault();
        jQuery(this).parent().parent().remove();
    });



});

jQuery(function () {
    jQuery("#slideimage_contenar").sortable();
    jQuery("#slideimage_contenar").disableSelection();

});


