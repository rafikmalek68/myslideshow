<?php
/*
  Plugin Name: My Slideshow
  Description: Simple slideshow plugin for wordpress
  Author: Rafik Malek
  Version: 1.0
 */

function myslideshow_init() {
    $args = array(
        'public' => true,
        'label' => 'My Slideshow',
        'supports' => array(
            'title',
            'thumbnail',
        ),
    );
    register_post_type( 'myslideshow', $args );
}

add_action('init', 'myslideshow_init');

function wp_slide_add_scripts() {
    wp_register_script( 'slider-jquery', plugins_url('/lib/responsiveslides/js/jquery.min.js', __FILE__) );
    wp_register_script( 'slider-responsiveslides', plugins_url('/lib/responsiveslides/js/responsiveslides.min.js', __FILE__) );
    wp_register_script( 'slider-custom', plugins_url('/lib/responsiveslides/js/slider-custom.js', __FILE__) );
    wp_enqueue_script( 'slider-jquery' );
    wp_enqueue_script( 'slider-responsiveslides' );
    wp_enqueue_script( 'slider-custom' );
}

add_action( 'wp_enqueue_scripts', 'wp_slide_add_scripts' );

function wp_slide_add_style() {
    wp_register_style( 'slider-style', plugins_url('/lib/responsiveslides/css/responsiveslides.css', __FILE__), array(), '20120208', 'all' );
    wp_enqueue_style( 'slider-style' );
   
}

add_action( 'wp_enqueue_scripts', 'wp_slide_add_style' );

function add_admin_scripts( $hook ) {
    global $post;
    if ( 'post-new.php' == $hook || 'post.php' == $hook) {
        if ( 'myslideshow' === $post->post_type ) {
            wp_enqueue_script( 'myslideshow', plugins_url('/js/custom_script.js', __FILE__), '', '', true );
            wp_enqueue_style( 'myslideshow', plugins_url('/css/custom_css.css', __FILE__) );
        }
    }
}

add_action( 'admin_enqueue_scripts', 'add_admin_scripts', 10, 1 );
add_action( 'add_meta_boxes', 'listing_image_add_metabox' );

function listing_image_add_metabox() {
    add_meta_box( 'listingimagediv', __('Slideshow Images', 'text-domain'), 'listing_image_metabox', 'myslideshow', 'advanced', 'low' );
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

            if ( $image_id && get_post($image_id) ) {

                if ( !isset($_wp_additional_image_sizes['post-thumbnail']) ) {
                    $thumbnail_html = wp_get_attachment_image( $image_id, array($content_width, $content_width) );
                } else {
                    $thumbnail_html = wp_get_attachment_image( $image_id, 'post-thumbnail' );
                }
                if ( !empty($thumbnail_html) ) {
                    $content .='<li class="ui-state-default" id="imageid_' . esc_attr($image_id) . '">';
                    $content .='<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>';
                    $content .=$thumbnail_html;
                    $content .='<input type="hidden" id="hidden_imgid_' . esc_attr($image_id) . '" name="ImageIds[]" value="' . esc_attr($image_id) . '" />';
                    $content .='<p class="hide-if-no-js"><a title="" class="remove_img" href="javascript:;"  id="remove_' . esc_attr($image_id) . '" >Remove</a></p>';
                    $content .='</li>';
                }
            }
        }
        $content .='</ul>';
        $content .= '<p class="hide-if-no-js"><a title="' . esc_attr__('Set listing image', 'text-domain') . '" href="javascript:;" id="upload_listing_image_button" id="set-listing-image" data-uploader_title="' . esc_attr__('Choose an image', 'text-domain') . '" data-uploader_button_text="' . esc_attr__('Add Slide', 'text-domain') . '">' . esc_html__('Add Slide', 'text-domain') . '</a></p>';
    } else {
        $content .='<ul id="slideimage_contenar"></ul>';
        $content .= '<p class="hide-if-no-js"><a title="' . esc_attr__('Set listing image', 'text-domain') . '" href="javascript:;" id="upload_listing_image_button" id="set-listing-image" data-uploader_title="' . esc_attr__('Choose an image', 'text-domain') . '" data-uploader_button_text="' . esc_attr__('Add Slide', 'text-domain') . '">' . esc_html__('Add Slide', 'text-domain') . '</a></p>';
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
    $opt_val_max_width = get_option( 'sld_max_width' );
    $opt_val_speed = get_option( 'sld_speed' );
    $opt_val_auto = get_option( 'sld_auto' );
    $opt_val_nav = get_option( 'sld_nav' );
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
            $output.='<div class="callbacks_container">';
            $output.='<ul class="rslides" id="slider1">';
            for ( $i = 0; $i < count($image_array); $i++ ) {
                $image_id = $image_array[$i];
                $old_content_width = $content_width;
                $content_width = 1000;
                $thumbnail_html = wp_get_attachment_image($image_id, array($content_width, $content_width));
                $output.='<li>' . $thumbnail_html . '</li>';
            }
            $output.='</ul>';
            $output.='</div>';
            
            if( 'true' == $opt_val_nav)
             $nav='namespace: "callbacks",';
            else
             $nav='';
            
            $output .='<script>$(function () {
                        $("#slider1").responsiveSlides({
                          maxwidth: '.$opt_val_max_width.',
                          speed: '.$opt_val_speed .',
                          nav: '.$opt_val_nav.',
                          '.$nav.'
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
    $opt_sld_max_width = 'sld_max_width';
    $data_field_sld_max_width = 'sld_max_width';
    $opt_sld_speed = 'sld_speed';
    $data_field_sld_speed = 'sld_speed';
    $opt_sld_auto = 'sld_auto';
    $opt_sld_nav = 'sld_nav';
    $data_field_sld_auto = 'sld_auto';
    $data_field_sld_nav = 'sld_nav';
    
    $opt_val_max_width = get_option( $opt_sld_max_width );
    $opt_val_speed = get_option( $opt_sld_speed );
    $opt_val_auto = get_option( $opt_sld_auto );
    $opt_val_nav = get_option( $opt_sld_nav );
    
    if ( isset($_POST[$hidden_field_name]) && $_POST[$hidden_field_name] == 'Y' ) {
        $opt_val_max_width = $_POST[$data_field_sld_max_width];
        $opt_val_speed = $_POST[$data_field_sld_speed];
        $opt_val_auto = $_POST[$data_field_sld_auto];
        $opt_val_nav = $_POST[$data_field_sld_nav];

        update_option( $opt_sld_max_width, $opt_val_max_width );
        update_option( $opt_sld_speed, $opt_val_speed );
        update_option( $opt_sld_auto, $opt_val_auto );
        update_option( $opt_sld_nav, $opt_val_nav );
        ?>
        <div class="updated"><p><strong><?php _e( 'settings saved.', 'myslider' ); ?></strong></p></div>
        <?php }
        echo '<div class="wrap">';
        echo '<h2>' . __( 'General Settings', 'myslider' ) . '</h2>';
?>

    <form name="form1" method="post" action="">
        <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
        <p><?php _e( "Max Width:", 'myslider' ); ?> 
            <input type="text" name="<?php echo $data_field_sld_max_width; ?>" value="<?php echo $opt_val_max_width; ?>" size="20">
        </p>
        <p><?php _e( "Speed:", 'myslider' ); ?> 
            <input type="text" name="<?php echo $data_field_sld_speed; ?>" value="<?php echo $opt_val_speed; ?>" size="20">
        </p><hr />
        <p><?php _e( "Auto Slide:", 'myslider' ); ?> 
            <input type="radio" <?php echo ('true' == $opt_val_auto) ? 'checked' : ''; ?>  name="<?php echo $data_field_sld_auto; ?>" value="true" size="20">True
            <input type="radio" <?php echo ('false' == $opt_val_auto) ? 'checked' : ''; ?> name="<?php echo $data_field_sld_auto; ?>" value="false" size="20">False
        </p><hr />
        
        <p><?php _e( "Navigation:", 'myslider' ); ?> 
            <input type="radio" <?php echo ('true' == $opt_val_nav) ? 'checked' : ''; ?>  name="<?php echo $data_field_sld_nav; ?>" value="true" size="20">True
            <input type="radio" <?php echo ('false' == $opt_val_nav) ? 'checked' : ''; ?> name="<?php echo $data_field_sld_nav; ?>" value="false" size="20">False
        </p><hr />
        
        <p class="submit">
            <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
        </p>
    </form>
    </div>
    <?php } ?>
