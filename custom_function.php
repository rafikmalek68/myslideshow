<?php


/**
 * Enqueue scripts and styles.
 */

function wp_slide_add_scripts() {
        
        wp_register_style( 'slick', plugins_url( '/lib/slick/css/slick.css', __FILE__ ), array(), '20120208', 'all' );
	wp_enqueue_style( 'slick' );
	wp_register_style( 'slick-theme', plugins_url( '/lib/slick/css/slick-theme.css', __FILE__ ), array(), '20120208', 'all' );
	wp_enqueue_style( 'slick-theme' );
	
	wp_register_script( 'slick-jquery', plugins_url( '/lib/slick/js/jquery-2.2.0.min.js', __FILE__ ) );
        wp_enqueue_script( 'slick-jquery' );
	wp_register_script( 'slick', plugins_url( '/lib/slick/js/slick.js', __FILE__ ) );
	wp_enqueue_script( 'slick' );
        
}

add_action( 'wp_enqueue_scripts', 'wp_slide_add_scripts' );

/**
 * function for shortcode creating
 */

function get_slider( $atts ) {

	global $post;
		extract( shortcode_atts( array(
		'id' => '',
	), $atts ) );
	
        //Get settings from meta data        
	$opt_val_slides_to_show         = get_post_meta( $id, 'sld_slides_to_show', true );
	$opt_val_slides_to_scroll       = get_post_meta( $id, 'sld_slides_to_scroll', true );
	$opt_val_dot                    = get_post_meta( $id, 'sld_dot', true );
	$opt_val_infinite               = get_post_meta( $id, 'sld_infinite', true );
	$opt_val_sld_center_mode        = get_post_meta( $id, 'sld_center_mode', true );
	$opt_val_sld_variable_width     = get_post_meta( $id, 'sld_variable_width', true );
	$opt_val_sld_arrow              = get_post_meta( $id, 'sld_arrow', true );
	$opt_val_sld_fade               = get_post_meta( $id, 'sld_fade', true );
	$opt_val_sld_autoplay           = get_post_meta( $id, 'sld_autoplay', true );
	$opt_val_sld_autoplay_speed     = get_post_meta( $id, 'sld_autoplay_speed', true );
	$opt_val_sld_speeds             = get_post_meta( $id, 'sld_speeds', true );

        //Set Default setting if not set yet
	$opt_val_slides_to_show     = ( '' == $opt_val_slides_to_show )? 1 : $opt_val_slides_to_show;
	$opt_val_slides_to_scroll   = ( '' == $opt_val_slides_to_scroll )? 1 : $opt_val_slides_to_scroll;
	$opt_val_dot                = ( '' == $opt_val_dot )? 'true' : $opt_val_dot;
	$opt_val_infinite           = ( '' == $opt_val_infinite )? 'true' : $opt_val_infinite;
	$opt_val_sld_variable_width = ( '' == $opt_val_sld_variable_width )? 'false' : $opt_val_sld_variable_width;
	$opt_val_sld_center_mode    = ( '' == $opt_val_sld_center_mode )? 'false' : $opt_val_sld_center_mode;
	$opt_val_sld_arrow          = ( '' == $opt_val_sld_arrow )? 'true' : $opt_val_sld_arrow;
	$opt_val_sld_fade           = ( '' == $opt_val_sld_fade )? 'false' : $opt_val_sld_fade;
	$opt_val_sld_autoplay       = ( '' == $opt_val_sld_autoplay )? 'false' : $opt_val_sld_autoplay;
	$opt_val_sld_autoplay_speed = ( '' == $opt_val_sld_autoplay_speed )? '1000' : $opt_val_sld_autoplay_speed;
	$opt_val_sld_speeds         = ( '' == $opt_val_sld_speeds )? '1000' : $opt_val_sld_speeds;

	$args = array( 'post_type' => 'myslideshow', 'p' => $id );
	$myposts = NEW WP_Query( $args );
	if ( $myposts->have_posts() ) {
                //Custom Post type myslideshow loop 
		while ( $myposts->have_posts() ) {
			$myposts->the_post();
			$image_string = get_post_meta( $post->ID, '_ImageIds', true );
			$image_array  = json_decode( $image_string );
			$section_id   = 'slider-' . $id;
                        
                        //Set HTML for short code
			
                        if( !empty($image_array) ) {
                            
                                $output .= '<section class="regular slider" id="' . $section_id . '">';
                                foreach ( $image_array as $slidevalue ){
                                        $image_id          = $slidevalue->ImageIds;
                                        $slide_title       = $slidevalue->slide_title;
                                        $slide_description = $slidevalue->slide_description;

                                        $old_content_width = $content_width;
                                        $content_width     = 1000;
                                        $thumbnail_html    = wp_get_attachment_image( $image_id, array( $content_width, $content_width ) );
                                        $output            .= '<div class="slider-img-container  custom-con">';
                                        if ( '' !== $slide_title ) {
                                                $output .= '<h2 class="slide-title">' . $slide_title . '</h2>';
                                        }
                                        if ( '' !== $slide_description ) {
                                                $output .= '<p class="slide-content">' . $slide_description . '</p>';
                                        }
                                        $output .= $thumbnail_html;
                                        $output .= '</div>';
                                }


                            $output .= '</section>';

                            //Adding slider initialization script 
                            $output .= '<script>$(function () {
                                                    $("#' . $section_id . '").slick({
                                                            dots: ' . $opt_val_dot . ',
                                                            infinite: ' . $opt_val_infinite . ',
                                                            centerMode: ' . $opt_val_sld_center_mode . ',    
                                                            slidesToShow: ' . $opt_val_slides_to_show . ',
                                                            slidesToScroll: ' . $opt_val_slides_to_scroll . ',
                                                            variableWidth: ' . $opt_val_sld_variable_width . ',
                                                            arrows: ' . $opt_val_sld_arrow . ',    
                                                            fade: ' . $opt_val_sld_fade . ',
                                                            speed: ' . $opt_val_sld_speeds . ',
                                                            autoplay: ' . $opt_val_sld_autoplay . ',
                                                            autoplaySpeed: ' . $opt_val_sld_autoplay_speed . ',
                                                      });
                                                    });
                                                    </script>';
                       } // End if().
		} // End while().
		return $output; // Return output
	} // End if().
}

// Register shortcode
add_shortcode( 'myslideshow', 'get_slider' );
