<?php

/*
 * Plugin Name: My Slideshow
 * Description: Simple slideshow plugin for wordpress
 * Plugin URI: https://github.com/rafikmalek68/myslideshow
 * Author: Rafik Malek
 * Version: 1.0
 * Text Domain: myslideshow
 */

include( plugin_dir_path( __FILE__ ) . 'custom_function.php' );

/**
 * Register custom post type as myslideshow
 */

function myslideshow_init() {
	$args = array(
		'labels' => array(
			'name'          => esc_html( 'Slideshows', 'myslideshow' ),
			'singular_name' => esc_html( 'Slideshow', 'myslideshow' ),
			'add_new'       => esc_html( 'Add New', 'myslideshow' ),
			'add_new_item'  => esc_html( 'Add New Slideshow', 'myslideshow' ),
			'edit_item'     => esc_html( 'Edit Slideshow', 'myslideshow' ),
			'new_item'      => esc_html( 'New Slideshow', 'myslideshow' ),
			'view_item'     => esc_html( 'View Slideshow', 'myslideshow' ),
		),
		'public'   => true,
		'supports' => array(
			'title',
			'thumbnail',
		),
	);
	register_post_type( 'myslideshow', $args );
}

add_action( 'init', 'myslideshow_init' );

/*
 * Admin Enqueue scripts and styles.
 */

function add_admin_scripts( $hook ) {
	global $post;
	wp_enqueue_style( 'myslideshow', plugins_url( '/css/custom_css.css', __FILE__ ) );
	if ( 'post-new.php' == $hook || 'post.php' == $hook ) {
		if ( 'myslideshow' === $post->post_type ) {
			wp_enqueue_script( 'myslideshow', plugins_url( '/js/custom_script.js', __FILE__ ), '', '', true );
		}
	}
}

add_action( 'admin_enqueue_scripts', 'add_admin_scripts', 10, 1 );

/*
 * Metabox added for slide add and remove opertion,Setting,shortcode display
 */

function listing_image_add_metabox() {
	add_meta_box( 'listingimagediv', esc_html( 'Slides', 'myslideshow' ), 'listing_image_metabox', 'myslideshow', 'advanced', 'low' );
	add_meta_box( 'Settings', esc_html( 'Slide Settings', 'myslideshow' ), 'this_slide_settings', 'myslideshow', 'advanced', 'low' );
	add_meta_box( 'Shortcode', esc_html( 'Copy Shortcode', 'myslideshow' ), 'copy_shortcode', 'myslideshow', 'advanced', 'low' );
}
add_action( 'add_meta_boxes', 'listing_image_add_metabox' );

/*
 * Function for adding metabox to display shortcode 
 */

function copy_shortcode( $post ) {
	echo $content='<input type="text" readonly="readonly" onClick="this.select();" value="[myslideshow id='."'$post->ID'".'] " />';
}

/*
 * function for slider settings display
 */

function this_slide_settings( $post ) { 
        
	$hidden_field_name               = 'sld_submit_hidden';
	$opt_sld_slides_to_show          = 'sld_slides_to_show';
	$data_field_sld_slides_to_show   = 'sld_slides_to_show';
	$opt_sld_slides_to_scroll        = 'sld_slides_to_scroll';
	$data_field_sld_slides_to_scroll = 'sld_slides_to_scroll';
	$opt_sld_dot                     = 'sld_dot';
	$data_field_sld_dot              = 'sld_dot';
	$opt_sld_infinite                = 'sld_infinite';
	$data_field_sld_infinite         = 'sld_infinite';
	$opt_sld_center_mode             = 'sld_center_mode';
	$data_field_sld_center_mode      = 'sld_center_mode';
	$opt_sld_variable_width          = 'sld_variable_width';
	$data_field_sld_variable_width   = 'sld_variable_width';
	$opt_sld_arrow                   = 'sld_arrow';
	$data_field_sld_arrow            = 'sld_arrow';
	$opt_sld_fade                    = 'sld_fade';
	$data_field_sld_fade             = 'sld_fade';
	$opt_sld_speeds                  = 'sld_speeds';
	$data_field_sld_speeds           = 'sld_speeds';
	$opt_sld_autoplay                = 'sld_autoplay';
	$data_field_sld_autoplay         = 'sld_autoplay';
	$opt_sld_autoplay_speed          = 'sld_autoplay_speed';
	$data_field_sld_autoplay_speed   = 'sld_autoplay_speed';
        
        //Get seetings from meta data
	$opt_val_slides_to_show          = get_post_meta( $post->ID, $opt_sld_slides_to_show, true );
	$opt_val_slides_to_scroll        = get_post_meta( $post->ID, $opt_sld_slides_to_scroll, true );
	$opt_val_dot                     = get_post_meta( $post->ID, $opt_sld_dot, true );
	$opt_val_infinite                = get_post_meta( $post->ID, $opt_sld_infinite, true );
	$opt_val_center_mode             = get_post_meta( $post->ID, $opt_sld_center_mode, true );
	$opt_val_variable_width          = get_post_meta( $post->ID, $opt_sld_variable_width, true );
	$opt_val_arrow                   = get_post_meta( $post->ID, $opt_sld_arrow, true );
	$opt_val_fade                    = get_post_meta( $post->ID, $opt_sld_fade, true );
	$opt_val_autoplay                = get_post_meta( $post->ID, $opt_sld_autoplay, true );
	$opt_val_autoplay_speed          = get_post_meta( $post->ID, $opt_sld_autoplay_speed, true );
	$opt_val_speeds                  = get_post_meta( $post->ID, $opt_sld_speeds, true );
	?>

<div id="slide-settings">
		<input type="hidden" name="<?php echo esc_attr( $hidden_field_name ); ?>" value="Y">

		<p>
			<span><?php esc_html_e( 'Dots Show', 'myslideshow' ); ?></span>
			<input type="radio" <?php echo ( 'true' == $opt_val_dot ) ? 'checked' : ''; ?>  name="<?php echo esc_attr( $data_field_sld_dot ); ?>" value="true" > Yes 
			<input type="radio" <?php echo ( 'false' == $opt_val_dot ) ? 'checked' : ''; ?> name="<?php echo esc_attr( $data_field_sld_dot ); ?>" value="false" > No 
		</p>
		<hr />
		<p>
			<span><?php esc_html_e( 'Arrows Show', 'myslideshow' ); ?></span>
			<input type="radio" <?php echo ( 'true' == $opt_val_arrow ) ? 'checked' : ''; ?>  name="<?php echo esc_attr( $data_field_sld_arrow ); ?>" value="true" > Yes 
			<input type="radio" <?php echo ( 'false' == $opt_val_arrow ) ? 'checked' : ''; ?> name="<?php echo esc_attr( $data_field_sld_arrow ); ?>" value="false" > No 
		</p>
		<hr />
		<p>
			<span><?php esc_html_e( 'Fade', 'myslideshow' ); ?></span>
			<input type="radio" <?php echo ( 'true' == $opt_val_fade ) ? 'checked' : ''; ?>  name="<?php echo esc_attr( $data_field_sld_fade ); ?>" value="true" > Yes 
			<input type="radio" <?php echo ( 'false' == $opt_val_fade ) ? 'checked' : ''; ?> name="<?php echo esc_attr( $data_field_sld_fade ); ?>" value="false" > No 
		</p>
		<hr />
		<p>
			<span><?php esc_html_e( 'Autoplay', 'myslideshow' ); ?></span>
			<input type="radio" <?php echo ( 'true' == $opt_val_autoplay ) ? 'checked' : ''; ?>  name="<?php echo esc_attr( $data_field_sld_autoplay ); ?>" value="true" > Yes 
			<input type="radio" <?php echo ( 'false' == $opt_val_autoplay ) ? 'checked' : ''; ?> name="<?php echo esc_attr( $data_field_sld_autoplay ); ?>" value="false" > No 
		</p>
		<hr />
		<p>
			<span><?php esc_html_e( 'Autoplay Speed', 'myslideshow' ); ?> </span>
			<input type="number" min="100" name="<?php echo esc_attr( $data_field_sld_autoplay_speed ); ?>" value="<?php echo esc_attr( $opt_val_autoplay_speed ); ?>" size="10">
		</p>
		<hr />
		<p>
			<span><?php esc_html_e( 'Slide Speed', 'myslideshow' ); ?> </span>
			<input type="number" min="100" name="<?php echo esc_attr( $data_field_sld_speeds ); ?>" value="<?php echo esc_attr( $opt_val_speeds ); ?>" size="10">
		</p>
		<hr />
		<p>
			<span><?php esc_html_e( 'Infinite', 'myslideshow' ); ?> </span>
			<input type="radio" <?php echo ( 'true' == $opt_val_infinite ) ? 'checked' : ''; ?>  name="<?php echo esc_attr( $data_field_sld_infinite ); ?>" value="true" > Yes 
			<input type="radio" <?php echo ( 'false' == $opt_val_infinite ) ? 'checked' : ''; ?> name="<?php echo esc_attr( $data_field_sld_infinite ); ?>" value="false" > No 
		</p>
		<hr />
		<p>
			<span><?php esc_html_e( 'Center Mode', 'myslideshow' ); ?> </span>
			<input type="radio" <?php echo ( 'true' == $opt_val_center_mode ) ? 'checked' : ''; ?>  name="<?php echo esc_attr( $data_field_sld_center_mode ); ?>" value="true" > Yes 
			<input type="radio" <?php echo ( 'false' == $opt_val_center_mode ) ? 'checked' : ''; ?> name="<?php echo esc_attr( $data_field_sld_center_mode ); ?>" value="false" > No 
		</p>
		<hr />
		<p>
			<span><?php esc_html_e( 'Variable Width', 'myslideshow' ); ?> </span>
			<input type="radio" <?php echo ( 'true' == $opt_val_variable_width ) ? 'checked' : ''; ?>  name="<?php echo esc_attr( $data_field_sld_variable_width ); ?>" value="true" > Yes
			<input type="radio" <?php echo ( 'false' == $opt_val_variable_width ) ? 'checked' : ''; ?> name="<?php echo esc_attr( $data_field_sld_variable_width ); ?>" value="false" > No
		</p>
		<hr />
		<p>
			<span><?php esc_html_e( 'Slides To Show', 'myslideshow' ); ?> </span>
			<input type="number" min="1" name="<?php echo esc_attr( $data_field_sld_slides_to_show ); ?>" value="<?php echo esc_attr( $opt_val_slides_to_show ); ?>" size="10">
		</p>
		<hr />
		<p>
			<span><?php esc_html_e( 'Slides To Scroll', 'myslideshow' ); ?> </span>
			<input type="number" min="1" name="<?php echo esc_attr( $data_field_sld_slides_to_scroll ); ?>" value="<?php echo esc_attr( $opt_val_slides_to_scroll ); ?>" size="10">
		</p>
		<hr />
</div>
<?php }

/*
 * Function for listing slides 
 */

function listing_image_metabox( $post ) {

	global $content_width, $_wp_additional_image_sizes;
	$image_string = get_post_meta( $post->ID, '_ImageIds', true );
	$image_array = json_decode( $image_string );

	if ( ! empty( $image_array ) ) {
            
                //Adding slide HTML to Add slide page
		$content .= '<ul id="slideimage_contenar">';
                foreach ( $image_array as $slidevalue ){
			$image_id          = $slidevalue->ImageIds;
			$slide_title       = $slidevalue->slide_title;
			$slide_description = $slidevalue->slide_description;
                        
			$old_content_width = $content_width;
			$content_width     = 150;
			$attachment_title  = get_the_title( $image_id );

			if ( $image_id && get_post( $image_id ) ) {

				if ( ! isset( $_wp_additional_image_sizes['post-thumbnail'] ) ) {
					$thumbnail_html = wp_get_attachment_image( $image_id, array( $content_width, $content_width ) );
				} else {
					$thumbnail_html = wp_get_attachment_image( $image_id, 'post-thumbnail' );
				}
				if ( ! empty( $thumbnail_html ) ) {
					$content .= '<li class="ui-state-default" id="imageid_' . esc_attr( $image_id ) . '">';
					$content .= '<p class="draging">' . esc_html( $attachment_title ) . '</p>';
					$content .= $thumbnail_html;
					$content .= '<div class="image_data">';
					$content .= '<input class="slide-input" type="text" placeholder="Title" value="' . esc_attr( $slide_title ) . '" name="slide_title[]"  />';
					$content .= '<textarea class="slide-input input-content" placeholder="Description" name="slide_description[]">' . esc_attr( $slide_description ) . '</textarea>';
					$content .= '</div>';
					$content .= '<input type="hidden" id="hidden_imgid_' . esc_attr( $image_id ) . '" name="ImageIds[]" value="' . esc_attr( $image_id ) . '" />';
					$content .= '<p class="hide-if-no-js"><a title="" class="remove-img" href="javascript:;"   id="remove_' . esc_attr( $image_id ) . '" >Delete slide</a></p>';
					$content .= '</li>';
				}
			}
		}
                
		$content .= '</ul>';
		$content .= '<p class="hide-if-no-js"><a class="button slideshow-insert-image-slide" title="Add Slide" href="javascript:void(0)" id="upload_listing_image_button"  data-uploader_title="Choose an image" data-uploader_button_text="Add Slide">Add Slide<a></p>';
                
	} else {
            
		$content .= '<ul id="slideimage_contenar"></ul>';
		$content .= '<p class="hide-if-no-js"><a class="button slideshow-insert-image-slide" title="Add Slide" href="javascript:void(0)" id="upload_listing_image_button"  data-uploader_title="Choose an image" data-uploader_button_text="Add Slide">Add Slide<a></p>';
                
	} // End if().
	echo $content;
}

add_action( 'save_post', 'listing_image_save', 10, 1 );

/*
 * Function for saving slide data and all settings
 */

function listing_image_save( $post_id ) {
	if ( isset( $_POST['ImageIds'] ) ) {
            
                        foreach( $_POST['ImageIds'] as $i => $img ){
                                $idata[ $i ]['ImageIds']          = $img;
                                $idata[ $i ]['slide_title']       = $_POST['slide_title'][ $i ];
                                $idata[ $i ]['slide_description'] = $_POST['slide_description'][ $i ];
                        }
           
                
			$image_id = json_encode( $idata );
                        
                        //Saving slide data to post meta
			update_post_meta( $post_id, '_ImageIds', $image_id );
	} else {
			update_post_meta( $post_id, '_ImageIds', '' );
	}

	if ( isset( $_POST['sld_submit_hidden'] ) ) {

		$data_field_sld_slides_to_show   = $_POST['sld_slides_to_show'];
		$data_field_sld_slides_to_scroll = $_POST['sld_slides_to_scroll'];
		$data_field_sld_dot              = $_POST['sld_dot'];
		$data_field_sld_infinite         = $_POST['sld_infinite'];
		$data_field_sld_center_mode      = $_POST['sld_center_mode'];
		$data_field_sld_variable_width   = $_POST['sld_variable_width'];
		$data_field_sld_arrow            = $_POST['sld_arrow'];
		$data_field_sld_fade             = $_POST['sld_fade'];
		$data_field_sld_autoplay         = $_POST['sld_autoplay'];
		$data_field_sld_autoplay_speed   = $_POST['sld_autoplay_speed'];
		$data_field_sld_speeds           = $_POST['sld_speeds'];
                
                //Saving setting to post meta 
		update_post_meta( $post_id, 'sld_slides_to_show', $data_field_sld_slides_to_show );
		update_post_meta( $post_id, 'sld_slides_to_scroll', $data_field_sld_slides_to_scroll );
		update_post_meta( $post_id, 'sld_dot', $data_field_sld_dot );
		update_post_meta( $post_id, 'sld_infinite', $data_field_sld_infinite );
		update_post_meta( $post_id, 'sld_center_mode', $data_field_sld_center_mode );
		update_post_meta( $post_id, 'sld_variable_width', $data_field_sld_variable_width );
		update_post_meta( $post_id, 'sld_arrow', $data_field_sld_arrow );
		update_post_meta( $post_id, 'sld_fade', $data_field_sld_fade );
		update_post_meta( $post_id, 'sld_autoplay', $data_field_sld_autoplay );
		update_post_meta( $post_id, 'sld_autoplay_speed', $data_field_sld_autoplay_speed );
		update_post_meta( $post_id, 'sld_speeds', $data_field_sld_speeds );
	}
}

/*
 * Slide listing column order change and adding shortcode column
 */

function my_edit_myslideshow_columns( $columns ) {
	$columns = array(
		'cb'        => '<input type="checkbox" />',
		'title'     => esc_html( 'Title', 'myslideshow' ),
		'shortcode' => esc_html( 'Shortcode', 'myslideshow' ),
		'date'      => esc_html( 'Date', 'myslideshow' ),
	);
	return $columns;
}

add_filter( 'manage_edit-myslideshow_columns', 'my_edit_myslideshow_columns' );

/*
 * Shortcode custom column added to slide listing page
 */
function my_manage_myslideshow_columns( $column, $post_id ) {
	global $post;
	switch ( $column ) {
		case 'shortcode' :
			echo $content='<input type="text" readonly="readonly" onClick="this.select();" value=" [myslideshow id='."'$post_id'".'] " />';
			break;
		default :
			break;
	}
}

add_action( 'manage_myslideshow_posts_custom_column', 'my_manage_myslideshow_columns', 10, 2 );
?>