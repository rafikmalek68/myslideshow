jQuery(document).ready(function ( $ ) {
    var file_frame;
    jQuery.fn.upload_listing_image = function ( button ) {
        var button_id = button.attr('id');
        var field_id = button_id.replace( '_button', '' );
        if ( file_frame ) {
            file_frame.open();
            return;
        }
        
        file_frame = wp.media.frames.file_frame = wp.media({
            title: jQuery(this).data('uploader_title'),
            button: {
                text: jQuery(this).data('uploader_button_text'),
            },
            multiple: true
        });
        
        file_frame.on('select', function () {
            var attachment = file_frame.state().get('selection').toJSON();
            var images_html = '';
            for ( var i = 0; i < attachment.length; i++ ) {
                images_html += '<li class="ui-state-default" id="imageid_' + attachment[i].id + '">';
                images_html += '<p class="draging">'+attachment[i].title+'</p>';
                images_html += '<input type="hidden" id="hidden_imgid_' + attachment[i].id + '" name="ImageIds[]" value="' + attachment[i].id + '" />';
                images_html += '<img  src="' + attachment[i].sizes.thumbnail.url + '" width="'+ attachment[i].sizes.thumbnail.width+'" height="'+ attachment[i].sizes.thumbnail.height+'" />';
                images_html += '<p class="hide-if-no-js"><a  title="" class="remove-img" href="javascript:;"  id="remove_' + attachment[i].id + '" >Delete slide</a></p>';
                images_html += '</li>';
            }
            jQuery("#slideimage_contenar").append(images_html);
            jQuery('#listingimagediv img').show();
        });
        file_frame.open();
    };
    jQuery('#listingimagediv').on('click', '#upload_listing_image_button', function (event) {
        event.preventDefault();
        jQuery.fn.upload_listing_image(jQuery(this));
    });
    jQuery('#listingimagediv').on('click', '.remove-img', function (event) {
        
        if (confirm("Are you sure you want to delete this slide?") == true) {
        jQuery(this).parent().parent().remove();
        } 
    });
});
jQuery(function () {
    jQuery("#slideimage_contenar").sortable();
    jQuery("#slideimage_contenar").disableSelection();
});