<?php
/*
  Plugin Name: My Slideshow
  Description: Simple slideshow plugin for wordpress
  Author: Rafik Malek
  Version: 1.0
 */

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

add_action('init', 'myslideshow_init');

function wp_slide_add_scripts() {
     
    wp_register_script( 'slick-jquery', plugins_url('/lib/slick/js/jquery-2.2.0.min.js', __FILE__) );
    wp_enqueue_script( 'slick-jquery' );
    
    wp_register_script( 'slick', plugins_url('/lib/slick/js/slick.js', __FILE__) );
    wp_enqueue_script( 'slick' );
}

add_action( 'wp_enqueue_scripts', 'wp_slide_add_scripts' );

function wp_slide_add_style() {
    
    wp_register_style( 'slick', plugins_url('/lib/slick/css/slick.css', __FILE__), array(), '20120208', 'all' );
    wp_enqueue_style( 'slick' );
    wp_register_style( 'slick-theme', plugins_url('/lib/slick/css/slick-theme.css', __FILE__), array(), '20120208', 'all' );
    wp_enqueue_style( 'slick-theme' );
}

add_action( 'wp_enqueue_scripts', 'wp_slide_add_style' );

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
            $image_id = $image_array[$i];
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
    if (isset( $_POST['ImageIds']) ) {
        $image_id = $_POST['ImageIds'];
        $image_id = json_encode($image_id);
        update_post_meta($post_id, '_ImageIds', $image_id);
    }
}

function get_slider($atts) {
    global $post;
    $opt_val_slides_to_show = get_option( 'sld_slides_to_show' );
    $opt_val_slides_to_scroll = get_option( 'sld_slides_to_scroll' );
    $opt_val_dot = get_option( 'sld_dot' );
    $opt_val_infinite = get_option( 'sld_infinite' );
    $opt_val_sld_variable_width = get_option( 'sld_variable_width' );
    $opt_val_sld_center_mode = get_option( 'sld_center_mode' );
    
    $opt_val_slides_to_show     = ( ''==$opt_val_slides_to_show )? 1 : $opt_val_slides_to_show;
    $opt_val_slides_to_scroll   = ( ''==$opt_val_slides_to_scroll )? 1 : $opt_val_slides_to_scroll;
    $opt_val_dot                = ( ''==$opt_val_dot )? 'true' : $opt_val_dot;
    $opt_val_infinite           = ( ''==$opt_val_infinite )? 'true' : $opt_val_infinite;
    $opt_val_sld_variable_width = ( ''==$opt_val_sld_variable_width )? 'false' : $opt_val_sld_variable_width;
    $opt_val_sld_center_mode    = ( ''==$opt_val_sld_center_mode )? 'false' : $opt_val_sld_center_mode;
    
    
    
    extract(shortcode_atts(array(
        'id' => ''
    ), $atts));
    $args = array( 'post_type' => 'myslideshow', 'p' => $id );
    $myposts = NEW WP_Query($args);
    if ( $myposts->have_posts() ) {
        while ( $myposts->have_posts() ) {
            $myposts->the_post();
            $image_string = get_post_meta( $post->ID, '_ImageIds', true );
            $image_array = json_decode( $image_string );
            $output.='<section class="regular slider">';
            
            for ( $i = 0; $i < count($image_array); $i++ ) {
                $image_id = $image_array[$i];
                $old_content_width = $content_width;
                $content_width = 1000;
                $thumbnail_html = wp_get_attachment_image($image_id, array($content_width, $content_width));
                $output.='<div class="slider-img-container">' . $thumbnail_html .'</div>';
            }
            
            $output.='</section>';
            $output .='<script>$(function () {
                        $(".regular").slick({
                            dots: '.$opt_val_dot.',
                            infinite: '.$opt_val_infinite.',
                            centerMode: '.$opt_val_sld_center_mode.',    
                            slidesToShow: '.$opt_val_slides_to_show.',
                            slidesToScroll: '.$opt_val_slides_to_scroll.',
                            variableWidth: '.$opt_val_sld_variable_width.',
                          });
                        });
                        </script>';
        }
        return $output;
    }
}

add_shortcode( 'myslideshow', 'get_slider' ); //[myslideshow id='1']



function slide_settings() {
    add_submenu_page( "edit.php?post_type=myslideshow", 'myslideshow settings', 'Settings', 'manage_options', "myslideshow-settings", 'show_settings_admin_page' );
}

add_action('admin_menu', 'slide_settings');

function show_settings_admin_page() {

    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

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
    
    $opt_val_slides_to_show = get_option( $opt_sld_slides_to_show );
    $opt_val_slides_to_scroll = get_option( $opt_sld_slides_to_scroll );
    $opt_val_dot = get_option( $opt_sld_dot );
    $opt_val_infinite = get_option( $opt_sld_infinite );
    $opt_val_variable_width = get_option( $opt_sld_variable_width );
    
    if ( isset($_POST[$hidden_field_name]) && $_POST[$hidden_field_name] == 'Y' ) {
        $opt_val_slides_to_show = $_POST[$data_field_sld_slides_to_show];
        $opt_val_slides_to_scroll = $_POST[$data_field_sld_slides_to_scroll];
        $opt_val_dot = $_POST[$data_field_sld_dot];
        $opt_val_infinite = $_POST[$data_field_sld_infinite];
        $opt_val_center_mode = $_POST[$data_field_sld_center_mode];
        $opt_val_variable_width = $_POST[$data_field_sld_variable_width];

        update_option( $opt_sld_slides_to_show, $opt_val_slides_to_show );
        update_option( $opt_sld_slides_to_scroll, $opt_val_slides_to_scroll );
        update_option( $opt_sld_dot, $opt_val_dot );
        update_option( $opt_sld_infinite, $opt_val_infinite );
        update_option( $opt_sld_center_mode, $opt_val_center_mode );
        update_option( $opt_sld_variable_width, $opt_val_variable_width );
        
        ?>

        <div class="updated"><p><strong><?php _e( 'settings saved.', 'myslider' ); ?></strong></p></div>
        <?php }
        echo '<div class="wrap">';
        echo '<h2>' . __( 'General Settings', 'myslider' ) . '</h2>';
?>

    <form name="slide-settings" id="slide-settings" method="post" action="">
        <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
        
        <p><span><?php _e( "Dots Show", 'myslider' ); ?></span>
            <input type="radio" <?php echo ('true' == $opt_val_dot) ? 'checked' : ''; ?>  name="<?php echo $data_field_sld_dot; ?>" value="true" > Yes 
            <input type="radio" <?php echo ('false' == $opt_val_dot) ? 'checked' : ''; ?> name="<?php echo $data_field_sld_dot; ?>" value="false" > No 
        </p><hr />
        
        <p><span><?php _e( "Infinite", 'myslider' ); ?> </span>
            <input type="radio" <?php echo ('true' == $opt_val_infinite) ? 'checked' : ''; ?>  name="<?php echo $data_field_sld_infinite; ?>" value="true" > Yes 
            <input type="radio" <?php echo ('false' == $opt_val_infinite) ? 'checked' : ''; ?> name="<?php echo $data_field_sld_infinite; ?>" value="false" > No 
        </p><hr />
        
        <p><span><?php _e( "Center Mode", 'myslider' ); ?> </span>
            <input type="radio" <?php echo ('true' == $opt_val_center_mode) ? 'checked' : ''; ?>  name="<?php echo $data_field_sld_center_mode; ?>" value="true" > Yes 
            <input type="radio" <?php echo ('false' == $opt_val_center_mode) ? 'checked' : ''; ?> name="<?php echo $data_field_sld_center_mode; ?>" value="false" > No 
        </p><hr />

         <p><span><?php _e( "Variable Width", 'myslider' ); ?> </span>
            <input type="radio" <?php echo ('true' == $opt_val_variable_width) ? 'checked' : ''; ?>  name="<?php echo $data_field_sld_variable_width; ?>" value="true" > Yes
            <input type="radio" <?php echo ('false' == $opt_val_variable_width) ? 'checked' : ''; ?> name="<?php echo $data_field_sld_variable_width; ?>" value="false" > No
        </p><hr />
        
        
        <p><span><?php _e( "Slides To Show", 'myslider' ); ?> </span>
            <input type="number" name="<?php echo $data_field_sld_slides_to_show; ?>" value="<?php echo $opt_val_slides_to_show; ?>" size="10">
        </p><hr />
        
        <p><span><?php _e( "Slides To Scroll", 'myslider' ); ?> </span>
            <input type="number" name="<?php echo $data_field_sld_slides_to_scroll; ?>" value="<?php echo $opt_val_slides_to_scroll; ?>" size="10">
        </p><hr />
        
        <p class="submit">
            <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
        </p>
        
    </form>
    </div>
    <?php }    
function display_posts_stickiness( $column, $post_id ) {
    if ($column == 'Shortcode'){
         echo $content="<p>[myslideshow id='".$post_id."']</p>";
    }
}
add_action( 'manage_posts_custom_column' , 'display_posts_stickiness', 10, 2 );

function add_sticky_column( $columns ) {
    return array_merge( $columns, 
        array( 'Shortcode' => __( 'Shortcode', 'myslideshow' ) ) );
}
add_filter( 'manage_myslideshow_posts_columns' , 'add_sticky_column' );    
?>
