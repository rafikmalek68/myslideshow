<?php

function slide_settings() {
    add_submenu_page( "edit.php?post_type=myslideshow", 'myslideshow settings', 'Settings', 'manage_options', "myslideshow-settings", 'show_settings_admin_page' );
}

add_action('admin_menu', 'slide_settings');

function show_settings_admin_page() {

    if ( !current_user_can( 'manage_options' ) ) {
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
 
add_filter( 'manage_edit-myslideshow_columns', 'my_edit_myslideshow_columns' ) ;

function my_edit_myslideshow_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title' ),
		'shortcode' => __( 'Shortcode' ),
		'date' => __( 'Date' )
	);
	return $columns;
}

add_action( 'manage_myslideshow_posts_custom_column', 'my_manage_myslideshow_columns', 10, 2 );
function my_manage_myslideshow_columns( $column, $post_id ) {
	global $post;
	switch( $column ) {
		case 'shortcode' :
                    echo $content="<p>[myslideshow id='".$post_id."']</p>";
			break;
		default :
			break;
	}
}
?>