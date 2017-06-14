<?php
/*
  Plugin Name: My Slideshow
  Description: Simple slideshow plugin for wordpress
  Author: Rafik Malek
  Version: 1.0
 */

include( plugin_dir_path( __FILE__ ) . 'custom_function.php');
include( plugin_dir_path( __FILE__ ) . 'admin/settings.php');

function myslideshow_init() {
    $args = array(
        'labels' => array(
            'name' => __( 'Slideshows' ),
            'singular_name' => __( 'Slideshow' ),
            'add_new' => __('Add New', 'Slideshow'),
            'add_new_item' => __('Add New Slideshow'),
            'edit_item' => __('Edit Slideshow'),
            'new_item' => __('New Slideshow'),
            'view_item' => __('View Slideshow'),
        ),
        'public' => true,
        'supports' => array(
            'title',
            'thumbnail',
        ),
    );
    register_post_type( 'myslideshow', $args );
}

add_action( 'init', 'myslideshow_init' );


function add_admin_scripts( $hook ) {
    global $post;
    wp_enqueue_style( 'myslideshow', plugins_url('/css/custom_css.css', __FILE__) );
    if ( 'post-new.php' == $hook || 'post.php' == $hook ) {
        if ( 'myslideshow' === $post->post_type ) {
            wp_enqueue_script( 'myslideshow', plugins_url('/js/custom_script.js', __FILE__), '', '', true );
            
        }
    }
}

add_action( 'admin_enqueue_scripts', 'add_admin_scripts', 10, 1 );
add_action( 'add_meta_boxes', 'listing_image_add_metabox' );

function listing_image_add_metabox() {
    add_meta_box( 'listingimagediv', __('Slides', 'text-domain'), 'listing_image_metabox', 'myslideshow', 'advanced', 'low' );
    add_meta_box( 'Shortcode', __('Copy Shortcode', 'text-domain'), 'copy_shortcode', 'myslideshow', 'advanced', 'low' );
}

function copy_shortcode( $post ) {
    echo $content="<p>[myslideshow id='".$post->ID."']</p>";
}

function listing_image_metabox( $post ) {
    
    global $content_width, $_wp_additional_image_sizes;
    $image_string = get_post_meta( $post->ID, '_ImageIds', true );
    $image_array = json_decode( $image_string );
    
    if ( !empty($image_array) ) {
        
        $content .='<ul id="slideimage_contenar">';
                    
        for ( $i = 0; $i < count($image_array); $i++ ) {
            
            $image_id = $image_array[$i]->ImageIds;
            $slide_title = $image_array[$i]->slide_title;
            $slide_description = $image_array[$i]->slide_description;
            $old_content_width = $content_width;
            $content_width = 150;   
            $attachment_title = get_the_title($image_id);
            
            if ( $image_id && get_post($image_id) ) {

                if ( !isset($_wp_additional_image_sizes['post-thumbnail']) ) {
                    $thumbnail_html = wp_get_attachment_image( $image_id, array($content_width, $content_width) );
                } else {
                    $thumbnail_html = wp_get_attachment_image( $image_id, 'post-thumbnail' );
                }
                if ( !empty($thumbnail_html) ) {
                    $content .='<li class="ui-state-default" id="imageid_' . esc_attr($image_id) . '">';
                    $content .='<p class="draging">'.$attachment_title.'</p>';
                    $content .=$thumbnail_html;
                    $content .= '<div class="image_data">';
                    $content .= '<input class="slide-input" type="text" placeholder="Title" value="'.esc_attr($slide_title).'"  name="slide_title[]"  />';
                    $content .= '<textarea class="slide-input input-content" placeholder="Description"  name="slide_description[]">'.esc_attr($slide_description).'</textarea>';
                    $content .= '</div>';
                    $content .='<input type="hidden" id="hidden_imgid_' . esc_attr($image_id) . '" name="ImageIds[]" value="' . esc_attr($image_id) . '" />';
                    $content .='<p class="hide-if-no-js"><a title="" class="remove-img" href="javascript:;"   id="remove_' . esc_attr($image_id) . '" >Delete slide</a></p>';
                    $content .='</li>';
                }
            }
        }
        $content .='</ul>';
        $content .= '<p class="hide-if-no-js"><a class="button slideshow-insert-image-slide" title="' . esc_attr__('Set listing image', 'text-domain') . '" href="javascript:;" id="upload_listing_image_button" id="set-listing-image" data-uploader_title="' . esc_attr__('Choose an image', 'text-domain') . '" data-uploader_button_text="' . esc_attr__('Add Slide', 'text-domain') . '">' . esc_html__('Add Slide', 'text-domain') . '</a></p>';
    } else {
        $content .='<ul id="slideimage_contenar"></ul>';
        $content .= '<p class="hide-if-no-js"><a class="button slideshow-insert-image-slide" title="' . esc_attr__('Set listing image', 'text-domain') . '" href="javascript:;" id="upload_listing_image_button" id="set-listing-image" data-uploader_title="' . esc_attr__('Choose an image', 'text-domain') . '" data-uploader_button_text="' . esc_attr__('Add Slide', 'text-domain') . '">' . esc_html__('Add Slide', 'text-domain') . '</a></p>';
    }
    echo $content;
}

add_action( 'save_post', 'listing_image_save', 10, 1 );

function listing_image_save($post_id) {
    if ( isset( $_POST['ImageIds'] ) ) {
        for( $i=0; $i < count($_POST['ImageIds']); $i++ ){
            $idata[$i]['ImageIds']=$_POST['ImageIds'][$i];
            $idata[$i]['slide_title']=$_POST['slide_title'][$i];
            $idata[$i]['slide_description']=$_POST['slide_description'][$i];
        }
            $image_id = json_encode($idata);
            update_post_meta($post_id, '_ImageIds', $image_id);
    } else {
            update_post_meta($post_id, '_ImageIds', '');
    }
}
?>