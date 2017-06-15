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
    add_meta_box( 'listingimagediv', __('Slides', 'myslideshow'), 'listing_image_metabox', 'myslideshow', 'advanced', 'low' );
    add_meta_box( 'Settings', __('Slide Settings', 'myslideshow'), 'this_slide_settings', 'myslideshow', 'advanced', 'low' );
    add_meta_box( 'Shortcode', __('Copy Shortcode', 'myslideshow'), 'copy_shortcode', 'myslideshow', 'advanced', 'low' );
}

function copy_shortcode( $post ) {
    echo $content="<p>[myslideshow id='".$post->ID."']</p>";
}

function this_slide_settings( $post ) { 
    
    $hidden_field_name = 'sld_submit_hidden';
    
    $opt_sld_slides_to_show = 'sld_slides_to_show';
    $data_field_sld_slides_to_show = 'sld_slides_to_show';
    
    $opt_sld_slides_to_scroll = 'sld_slides_to_scroll';
    $data_field_sld_slides_to_scroll = 'sld_slides_to_scroll';
    
    $opt_sld_dot = 'sld_dot';
    $data_field_sld_dot = 'sld_dot';
    
    $opt_sld_infinite = 'sld_infinite';
    $data_field_sld_infinite = 'sld_infinite';
    
    $opt_sld_center_mode = 'sld_center_mode';
    $data_field_sld_center_mode = 'sld_center_mode';
    
    $opt_sld_variable_width = 'sld_variable_width';
    $data_field_sld_variable_width = 'sld_variable_width';
    
    
    $opt_val_slides_to_show         = get_post_meta( $post->ID, $opt_sld_slides_to_show, true );
    $opt_val_slides_to_scroll       = get_post_meta( $post->ID, $opt_sld_slides_to_scroll, true );
    $opt_val_dot                    = get_post_meta( $post->ID, $opt_sld_dot, true );
    $opt_val_infinite               = get_post_meta( $post->ID, $opt_sld_infinite, true );
    $opt_val_center_mode            = get_post_meta( $post->ID, $opt_sld_center_mode, true );
    $opt_val_variable_width         = get_post_meta( $post->ID, $opt_sld_variable_width, true );
    
    ?>

<div id="slide-settings">
        <input type="hidden" name="<?php echo esc_attr($hidden_field_name); ?>" value="Y">
        <p><span><?php _e( "Dots Show", 'myslider' ); ?></span>
            <input type="radio" <?php echo ('true' == $opt_val_dot) ? 'checked' : ''; ?>  name="<?php echo esc_attr($data_field_sld_dot); ?>" value="true" > Yes 
            <input type="radio" <?php echo ('false' == $opt_val_dot) ? 'checked' : ''; ?> name="<?php echo esc_attr($data_field_sld_dot); ?>" value="false" > No 
        </p><hr />
        
        <p><span><?php _e( "Infinite", 'myslider' ); ?> </span>
            <input type="radio" <?php echo ('true' == $opt_val_infinite) ? 'checked' : ''; ?>  name="<?php echo esc_attr($data_field_sld_infinite); ?>" value="true" > Yes 
            <input type="radio" <?php echo ('false' == $opt_val_infinite) ? 'checked' : ''; ?> name="<?php echo esc_attr($data_field_sld_infinite); ?>" value="false" > No 
        </p><hr />
        
        <p><span><?php _e( "Center Mode", 'myslider' ); ?> </span>
            <input type="radio" <?php echo ('true' == $opt_val_center_mode) ? 'checked' : ''; ?>  name="<?php echo esc_attr($data_field_sld_center_mode); ?>" value="true" > Yes 
            <input type="radio" <?php echo ('false' == $opt_val_center_mode) ? 'checked' : ''; ?> name="<?php echo esc_attr($data_field_sld_center_mode); ?>" value="false" > No 
        </p><hr />

         <p><span><?php _e( "Variable Width", 'myslider' ); ?> </span>
            <input type="radio" <?php echo ('true' == $opt_val_variable_width) ? 'checked' : ''; ?>  name="<?php echo esc_attr($data_field_sld_variable_width); ?>" value="true" > Yes
            <input type="radio" <?php echo ('false' == $opt_val_variable_width) ? 'checked' : ''; ?> name="<?php echo esc_attr($data_field_sld_variable_width); ?>" value="false" > No
        </p><hr />
        
        
        <p><span><?php _e( "Slides To Show", 'myslider' ); ?> </span>
            <input type="number" name="<?php echo esc_attr($data_field_sld_slides_to_show); ?>" value="<?php echo esc_attr($opt_val_slides_to_show); ?>" size="10">
        </p><hr />
        
        <p><span><?php _e( "Slides To Scroll", 'myslider' ); ?> </span>
            <input type="number" name="<?php echo esc_attr($data_field_sld_slides_to_scroll); ?>" value="<?php echo esc_attr($opt_val_slides_to_scroll); ?>" size="10">
        </p>
</div>
<?php }


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
    
    if ( isset( $_POST['sld_submit_hidden'] ) ) {
        $data_field_sld_slides_to_show      = $_POST['sld_slides_to_show'];
        update_post_meta( $post_id, 'sld_slides_to_show', $data_field_sld_slides_to_show );
        
        $data_field_sld_slides_to_scroll    = $_POST['sld_slides_to_scroll'];
        update_post_meta( $post_id, 'sld_slides_to_scroll', $data_field_sld_slides_to_scroll );
        
        $data_field_sld_dot                 = $_POST['sld_dot'];
        update_post_meta( $post_id, 'sld_dot', $data_field_sld_dot );
        
        $data_field_sld_infinite            = $_POST['sld_infinite'];
        update_post_meta( $post_id, 'sld_infinite', $data_field_sld_infinite );
        
        $data_field_sld_center_mode         = $_POST['sld_center_mode'];
        update_post_meta( $post_id, 'sld_center_mode', $data_field_sld_center_mode );
        
        $data_field_sld_variable_width      = $_POST['sld_variable_width'];
        update_post_meta( $post_id, 'sld_variable_width', $data_field_sld_variable_width ); 
        
    }
    
    
}
?>