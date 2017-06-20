<?php

function slide_settings() {
	add_submenu_page( "edit.php?post_type=myslideshow", 'myslideshow settings', 'Settings', 'manage_options', "myslideshow-settings", 'show_settings_admin_page' );
}

add_action( 'admin_menu', 'slide_settings' );

function show_settings_admin_page() {

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html( 'You do not have sufficient permissions to access this page.', 'myslideshow' ) );
	}

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

	$opt_val_slides_to_show   = get_option( $opt_sld_slides_to_show );
	$opt_val_slides_to_scroll = get_option( $opt_sld_slides_to_scroll );
	$opt_val_dot              = get_option( $opt_sld_dot );
	$opt_val_infinite         = get_option( $opt_sld_infinite );
	$opt_val_center_mode      = get_option( $opt_sld_center_mode );
	$opt_val_variable_width   = get_option( $opt_sld_variable_width );
	$opt_val_arrow            = get_option( $opt_sld_arrow );
	$opt_val_fade             = get_option( $opt_sld_fade );
	$opt_val_speeds           = get_option( $opt_sld_speeds );
	$opt_val_autoplay         = get_option( $opt_sld_autoplay );
	$opt_val_autoplay_speed   = get_option( $opt_sld_autoplay_speed );

	if ( isset( $_POST[ $hidden_field_name ] ) && 'Y' == $_POST[ $hidden_field_name ] ) {
		$opt_val_slides_to_show   = $_POST[ $data_field_sld_slides_to_show ];
		$opt_val_slides_to_scroll = $_POST[ $data_field_sld_slides_to_scroll ];
		$opt_val_dot              = $_POST[ $data_field_sld_dot ];
		$opt_val_infinite         = $_POST[ $data_field_sld_infinite ];
		$opt_val_center_mode      = $_POST[ $data_field_sld_center_mode ];
		$opt_val_variable_width   = $_POST[ $data_field_sld_variable_width ];
		$opt_val_arrow            = $_POST[ $data_field_sld_arrow ];
		$opt_val_fade             = $_POST[ $data_field_sld_fade ];
		$opt_val_speeds           = $_POST[ $data_field_sld_speeds ];
		$opt_val_autoplay         = $_POST[ $data_field_sld_autoplay ];
		$opt_val_autoplay_speed   = $_POST[ $data_field_sld_autoplay_speed ];

		update_option( $opt_sld_slides_to_show, $opt_val_slides_to_show );
		update_option( $opt_sld_slides_to_scroll, $opt_val_slides_to_scroll );
		update_option( $opt_sld_dot, $opt_val_dot );
		update_option( $opt_sld_infinite, $opt_val_infinite );
		update_option( $opt_sld_center_mode, $opt_val_center_mode );
		update_option( $opt_sld_variable_width, $opt_val_variable_width );
		update_option( $opt_sld_arrow, $opt_val_arrow );
		update_option( $opt_sld_fade, $opt_val_fade );
		update_option( $opt_sld_speeds, $opt_val_speeds );
		update_option( $opt_sld_autoplay, $opt_val_autoplay );
		update_option( $opt_sld_autoplay_speed, $opt_val_autoplay_speed );
		?>
		<div class="updated"><p><strong><?php esc_html_e( 'settings saved.', 'myslideshow' ); ?></strong></p></div>
		<?php
	}
	?>
	<div class="wrap">
		<h2><?php esc_html_e( 'Default Settings', 'myslideshow' ); ?></h2>';
		<form name="slide-settings" id="slide-settings" method="post" action="">
			<input type="hidden" name="<?php echo esc_attr( $hidden_field_name ); ?>" value="Y">
			<p><span><?php esc_html_e( 'Dots Show', 'myslideshow' ); ?></span>
				<input type="radio" <?php echo ( 'true' == $opt_val_dot ) ? 'checked' : ''; ?>  name="<?php echo esc_attr( $data_field_sld_dot ); ?>" value="true" > Yes 
				<input type="radio" <?php echo ( 'false' == $opt_val_dot ) ? 'checked' : ''; ?> name="<?php echo esc_attr( $data_field_sld_dot ); ?>" value="false" > No 
			</p><hr />
			<p><span><?php esc_html_e( 'Arrows Show', 'myslideshow' ); ?></span>
				<input type="radio" <?php echo ( 'true' == $opt_val_arrow ) ? 'checked' : ''; ?>  name="<?php echo esc_attr( $data_field_sld_arrow ); ?>" value="true" > Yes 
				<input type="radio" <?php echo ( 'false' == $opt_val_arrow ) ? 'checked' : ''; ?> name="<?php echo esc_attr( $data_field_sld_arrow ); ?>" value="false" > No 
			</p><hr />
			<p><span><?php esc_html_e( 'Fade', 'myslideshow' ); ?></span>
				<input type="radio" <?php echo ( 'true' == $opt_val_fade ) ? 'checked' : ''; ?>  name="<?php echo esc_attr( $data_field_sld_fade ); ?>" value="true" > Yes 
				<input type="radio" <?php echo ( 'false' == $opt_val_fade ) ? 'checked' : ''; ?> name="<?php echo esc_attr( $data_field_sld_fade ); ?>" value="false" > No 
			</p><hr />
			<p><span><?php esc_html_e( 'Autoplay', 'myslideshow' ); ?></span>
				<input type="radio" <?php echo ( 'true' == $opt_val_autoplay ) ? 'checked' : ''; ?>  name="<?php echo esc_attr( $data_field_sld_autoplay ); ?>" value="true" > Yes 
				<input type="radio" <?php echo ( 'false' == $opt_val_autoplay ) ? 'checked' : ''; ?> name="<?php echo esc_attr( $data_field_sld_autoplay ); ?>" value="false" > No 
			</p><hr />
			<p><span><?php esc_html_e( 'Autoplay Speed ', 'myslideshow' ); ?> </span>
				<input type="number" min="100" name="<?php echo esc_attr( $data_field_sld_autoplay_speed ); ?>" value="<?php echo esc_attr( $opt_val_autoplay_speed ); ?>" size="10">
			</p><hr />
			<p><span><?php esc_html_e( 'Slide Speed', 'myslideshow' ); ?> </span>
				<input type="number" min="100" name="<?php echo esc_attr( $data_field_sld_speeds ); ?>" value="<?php echo esc_attr( $opt_val_speeds ); ?>" size="10">
			</p><hr />
			<p><span><?php esc_html_e( 'Infinite', 'myslideshow' ); ?> </span>
				<input type="radio" <?php echo ('true' == $opt_val_infinite) ? 'checked' : ''; ?>  name="<?php echo esc_attr( $data_field_sld_infinite ); ?>" value="true" > Yes 
				<input type="radio" <?php echo ('false' == $opt_val_infinite) ? 'checked' : ''; ?> name="<?php echo esc_attr( $data_field_sld_infinite ); ?>" value="false" > No 
			</p><hr />
			<p><span><?php esc_html_e( 'Center Mode', 'myslideshow' ); ?> </span>
				<input type="radio" <?php echo ('true' == $opt_val_center_mode) ? 'checked' : ''; ?>  name="<?php echo esc_attr( $data_field_sld_center_mode ); ?>" value="true" > Yes 
				<input type="radio" <?php echo ('false' == $opt_val_center_mode) ? 'checked' : ''; ?> name="<?php echo esc_attr( $data_field_sld_center_mode ); ?>" value="false" > No 
			</p><hr />
			 <p><span><?php esc_html_e( 'Variable Width', 'myslideshow' ); ?> </span>
				<input type="radio" <?php echo ('true' == $opt_val_variable_width) ? 'checked' : ''; ?>  name="<?php echo esc_attr( $data_field_sld_variable_width ); ?>" value="true" > Yes
				<input type="radio" <?php echo ('false' == $opt_val_variable_width) ? 'checked' : ''; ?> name="<?php echo esc_attr( $data_field_sld_variable_width ); ?>" value="false" > No
			</p><hr />
			<p><span><?php esc_html_e( 'Slides To Show', 'myslideshow' ); ?> </span>
				<input type="number" min="1" name="<?php echo esc_attr( $data_field_sld_slides_to_show ); ?>" value="<?php echo esc_attr( $opt_val_slides_to_show ); ?>" size="10">
			</p><hr />
			<p><span><?php esc_html_e( 'Slides To Scroll', 'myslideshow' ); ?> </span>
				<input type="number" min="1" name="<?php echo esc_attr( $data_field_sld_slides_to_scroll ); ?>" value="<?php echo esc_attr( $opt_val_slides_to_scroll ); ?>" size="10">
			</p><hr />
			<p class="submit">
				<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'myslideshow' ) ?>" />
			</p>
		</form>
	</div>
	<?php
}

?>