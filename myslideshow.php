<?php
/*
  Plugin Name: My Slideshow
  Description: Simple assignment of a slideshow into WordPress
  Author: Rafik Malek
  Version: 1.0
 */

function myslideshow_init() {
    $args = array(
        'public' => true,
        'label' => 'My Slideshow',
        'supports' => array(
            'title',
            'thumbnail'
        )
    );
    register_post_type('myslideshow', $args);
}

add_action('init', 'myslideshow_init');

/* Fire our meta box setup function on the post editor screen. */
add_action('load-post.php', 'smashing_post_meta_boxes_setup');
add_action('load-post-new.php', 'smashing_post_meta_boxes_setup');

function smashing_add_post_meta_boxes() {
    add_meta_box(
            'smashing-post-class', // Unique ID
            esc_html__('Post Class', 'example'), // Title
            'smashing_post_class_meta_box', // Callback function
            'myslideshow', // Admin page (or post type)
            'advanced', // Context
            'default'         // Priority
    );
}

/* Display the post meta box. */

function smashing_post_class_meta_box($post) {
    ?>

    <?php wp_nonce_field(basename(__FILE__), 'smashing_post_class_nonce'); ?>

    <p>
        <label for="smashing-post-class"><?php _e("Add a custom CSS class, which will be applied to WordPress' post class.", 'example'); ?></label>
        <br />
        <input class="widefat" type="text" name="smashing-post-class" id="smashing-post-class" value="<?php echo esc_attr(get_post_meta($post->ID, 'smashing_post_class', true)); ?>" size="30" />
    </p>
<?php
}

/* Meta box setup function. */

function smashing_post_meta_boxes_setup() {

    /* Add meta boxes on the 'add_meta_boxes' hook. */
    add_action('add_meta_boxes', 'smashing_add_post_meta_boxes');

    /* Save post meta on the 'save_post' hook. */
    add_action('save_post', 'smashing_save_post_class_meta', 10, 2);
}

/* Save the meta box's post metadata. */

function smashing_save_post_class_meta($post_id, $post) {

    /* Verify the nonce before proceeding. */
    if (!isset($_POST['smashing_post_class_nonce']) || !wp_verify_nonce($_POST['smashing_post_class_nonce'], basename(__FILE__)))
        return $post_id;

    /* Get the post type object. */
    $post_type = get_post_type_object($post->post_type);

    /* Check if the current user has permission to edit the post. */
    if (!current_user_can($post_type->cap->edit_post, $post_id))
        return $post_id;

    /* Get the posted data and sanitize it for use as an HTML class. */
    $new_meta_value = ( isset($_POST['smashing-post-class']) ? sanitize_html_class($_POST['smashing-post-class']) : '' );

    /* Get the meta key. */
    $meta_key = 'smashing_post_class';

    /* Get the meta value of the custom field key. */
    $meta_value = get_post_meta($post_id, $meta_key, true);

    /* If a new meta value was added and there was no previous value, add it. */
    if ($new_meta_value && '' == $meta_value)
        add_post_meta($post_id, $meta_key, $new_meta_value, true);

    /* If the new meta value does not match the old value, update it. */
    elseif ($new_meta_value && $new_meta_value != $meta_value)
        update_post_meta($post_id, $meta_key, $new_meta_value);

    /* If there is no new meta value but an old value exists, delete it. */
    elseif ('' == $new_meta_value && $meta_value)
        delete_post_meta($post_id, $meta_key, $meta_value);
}

///======================

add_action('add_meta_boxes', 'listing_image_add_metabox');

function listing_image_add_metabox() {
    add_meta_box('listingimagediv', __('Slideshow Images', 'text-domain'), 'listing_image_metabox', 'myslideshow', 'advanced', 'low');
}

function listing_image_metabox($post) {

    global $content_width, $_wp_additional_image_sizes;

    $image_string = get_post_meta($post->ID, '_ImageIds', true);
    $image_array = json_decode($image_string);
    
    
    if(!empty($image_array)){
    
    $content .='<div id="slideimage_contenar">';
    for ($i = 0; $i < count($image_array); $i++) {
        $image_id = $image_array[$i];
        $old_content_width = $content_width;
        $content_width = 254;

        if ($image_id && get_post($image_id)) {

            if (!isset($_wp_additional_image_sizes['post-thumbnail'])) {
                $thumbnail_html = wp_get_attachment_image($image_id, array($content_width, $content_width));
            } else {
                $thumbnail_html = wp_get_attachment_image($image_id, 'post-thumbnail');
            }

            if (!empty($thumbnail_html)) {

                //$content .= $thumbnail_html;
                //$content .= '<p class="hide-if-no-js"><a href="javascript:;" id="remove_listing_image_button" >' . esc_html__('Remove listing image', 'text-domain') . '</a></p>';
                //$content .= '<input type="hidden" id="upload_listing_image" name="ImageIds" value="' . esc_attr($image_id) . '" />';
                
                      $content .='<div id="imageid_'.esc_attr($image_id).'">';
                      $content .=$thumbnail_html;
                      $content .='<input type="hidden" id="hidden_imgid_'.esc_attr($image_id).'" name="ImageIds[]" value="'.esc_attr($image_id).'" />';
                      $content .='<p class="hide-if-no-js"><a title="" class="remove_img" href="javascript:;"  id="remove_'.esc_attr($image_id).'" >Remove</a></p>';
                      $content .='</div>';
     
                
            }

            //$content_width = $old_content_width;
        } 
    }
    $content .='</div>';
    $content .= '<p class="hide-if-no-js"><a title="' . esc_attr__('Set listing image', 'text-domain') . '" href="javascript:;" id="upload_listing_image_button" id="set-listing-image" data-uploader_title="' . esc_attr__('Choose an image', 'text-domain') . '" data-uploader_button_text="' . esc_attr__('Add Slide', 'text-domain') . '">' . esc_html__('Add Slide', 'text-domain') . '</a></p>';
    }else {

            $content .='<div id="slideimage_contenar"></div>';
            $content .= '<p class="hide-if-no-js"><a title="' . esc_attr__('Set listing image', 'text-domain') . '" href="javascript:;" id="upload_listing_image_button" id="set-listing-image" data-uploader_title="' . esc_attr__('Choose an image', 'text-domain') . '" data-uploader_button_text="' . esc_attr__('Add Slide', 'text-domain') . '">' . esc_html__('Add Slide', 'text-domain') . '</a></p>';
        }

    echo $content;
}

add_action('save_post', 'listing_image_save', 10, 1);

function listing_image_save($post_id) {
    if (isset($_POST['ImageIds'])) {
        $image_id = $_POST['ImageIds'];
        $image_id = json_encode($image_id);
        update_post_meta($post_id, '_ImageIds', $image_id);
    }
}

function add_admin_scripts($hook) {
    global $post;
    if ($hook == 'post-new.php' || $hook == 'post.php') {
        if ('myslideshow' === $post->post_type) {
            wp_enqueue_script('myslideshow', plugins_url('/js/custom_script.js', __FILE__), '', '', true); // "TRUE" - ADDS JS TO FOOTER
        }
    }
}

add_action('admin_enqueue_scripts', 'add_admin_scripts', 10, 1);
