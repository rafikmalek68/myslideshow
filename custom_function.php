<?php

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

function get_slider( $atts ) {
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
                
                $image_id = $image_array[$i]->ImageIds;
                $slide_title = $image_array[$i]->slide_title;
                $slide_description = $image_array[$i]->slide_description;
                $old_content_width = $content_width;
                $content_width = 1000;
                $thumbnail_html = wp_get_attachment_image($image_id, array($content_width, $content_width));
                $output.='<div class="slider-img-container  custom-con">';
                if( ''==!$slide_title ){
                    $output.='<h2 class="slide-title">'.$slide_title.'</h2>';
                }
                if( ''==!$slide_description ){
                    $output.='<p class="slide-content">'.$slide_description.'</p>';
                }
                $output.= $thumbnail_html;
                $output.='</div>';
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
