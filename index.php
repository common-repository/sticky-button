<?php
/*
Plugin Name: Sticky Floating Button (Book Now, Contact, Call To Action...)
Description: The button can be centered at the bottom of the page or on the left/right sides. Display the button on the entire website or on specific pages.
Author: Digimax
Version: 1.0
Author URI: https://profiles.wordpress.org/digimaxme/
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

add_action( 'admin_menu', 'stbtn_book_now_menu' );

function stbtn_book_now_menu() {
	add_menu_page( 'Sticky Button', 'Sticky Button', 'manage_options', 'sticky-button-page.php', 'stbtn_book_now_page', plugin_dir_url( __FILE__ ) . 'assets/icon.png', 6  );
add_action( 'admin_init', 'stbtn_register_book_now_settings' );


}

function stbtn_register_book_now_settings() {
	//register our settings
        register_setting( 'sticky-button-settings-group', 'stbtn_book_enable' );
	    register_setting( 'sticky-button-settings-group', 'stbtn_book_text' );
	    register_setting( 'sticky-button-settings-group', 'stbtn_book_url' );
	    register_setting( 'sticky-button-settings-group', 'stbtn_left_right' );
        register_setting( 'sticky-button-settings-group', 'stbtn_book_color' );
        register_setting( 'sticky-button-settings-group', 'stbtn_text_color' );
        register_setting( 'sticky-button-settings-group', 'stbtn_book_bottom' );
        register_setting( 'sticky-button-settings-group', 'stbtn_page_id' );
        register_setting( 'sticky-button-settings-group', 'stbtn_target' );
        register_setting( 'sticky-button-settings-group', 'stbtn_width' );
		register_setting( 'sticky-button-settings-group', 'stbtn_font_size' );
		register_setting( 'sticky-button-settings-group', 'stbtn_font_size_m' );
		register_setting( 'sticky-button-settings-group', 'stbtn_btn_pad' );
		register_setting( 'sticky-button-settings-group', 'stbtn_btn_pad_m' );
		register_setting( 'sticky-button-settings-group', 'stbtn_font_family' );
		register_setting( 'sticky-button-settings-group', 'show_on_desktop' );
		
		//For colorpicker scripts load
		
		// Css rules for Color Picker
		wp_enqueue_style( 'wp-color-picker' );
		// Register javascript
		add_action('admin_enqueue_scripts', 'stbtn_enqueue_admin_js');
}


// Add Options to post types
add_action( 'add_meta_boxes', 'stbtn_meta_box_add');
function stbtn_meta_box_add() {
    $post_types = get_post_types( array('public' => true) );
	foreach($post_types as $post_typess) {
		add_meta_box( 'my-meta-box-id', 'Sticky Button Settings For This Page', 'stbtn_meta_box_bn', $post_typess, 'advanced', 'high' );
	}
}
function stbtn_meta_box_bn( $post ) {
    $values = get_post_custom( $post->ID );
	$enable_global_settings=1;
    $enable_global_settings = isset( $values['stbtn_meta_box_enable_global_settings'] ) ? esc_attr( $values['stbtn_meta_box_enable_global_settings'][0] ) : 1;
	 // Check and get a post meta

	/* if (isset( $values['stbtn_meta_box_enable_global_settings'] )) {
		$meta_value = get_post_meta( $post->ID, '_meta_key', true );
	} */
	
    $enable = isset( $values['stbtn_meta_box_enable'] ) ? esc_attr( $values['stbtn_meta_box_enable'][0] ) : '';
    $disable = isset( $values['stbtn_meta_box_disable'] ) ? esc_attr( $values['stbtn_meta_box_disable'][0] ) : '';
	$text = isset( $values['stbtn_meta_box_text'] ) ? esc_attr( $values['stbtn_meta_box_text'][0] ) : '';
	$url = isset( $values['stbtn_meta_box_url'] ) ? esc_attr( $values['stbtn_meta_box_url'][0] ) : '';
	$show_on_desktop = isset( $values['stbtn_meta_box_show_on_desktop'] ) ? esc_attr( $values['stbtn_meta_box_show_on_desktop'][0] ) : '';
	$stbtn_meta_box_left_right = isset( $values['stbtn_meta_box_left_right'] ) ? esc_attr( $values['stbtn_meta_box_left_right'][0] ) : '';
	$stbtn_meta_box_font_family = isset( $values['stbtn_meta_box_font_family'] ) ? esc_attr( $values['stbtn_meta_box_font_family'][0] ) : 'Arial';
	$show_on_mobile = isset( $values['stbtn_meta_box_show_on_mobile'] ) ? esc_attr( $values['stbtn_meta_box_show_on_mobile'][0] ) : '';
	$bg = isset( $values['stbtn_meta_box_bg'] ) ? esc_attr( $values['stbtn_meta_box_bg'][0] ) : '';
	$txtcolor = isset( $values['stbtn_meta_box_txtcolor'] ) ? esc_attr( $values['stbtn_meta_box_txtcolor'][0] ) : '';
	$target = isset( $values['stbtn_meta_box_target'] ) ? esc_attr( $values['stbtn_meta_box_target'][0] ) : '';
	$width = isset( $values['stbtn_meta_box_width'] ) ? esc_attr( $values['stbtn_meta_box_width'][0] ) : '';
	$fontsize = isset( $values['stbtn_meta_box_font_size'] ) ? esc_attr( $values['stbtn_meta_box_font_size'][0] ) : '16px';
	$fontsizem = isset( $values['stbtn_meta_box_font_size_m'] ) ? esc_attr( $values['stbtn_meta_box_font_size_m'][0] ) : '16px';
	$btnpad = isset( $values['stbtn_meta_box_btn_pad'] ) ? esc_attr( $values['stbtn_meta_box_btn_pad'][0] ) : '10px';
	$btnpadm = isset( $values['stbtn_meta_box_btn_pad_m'] ) ? esc_attr( $values['stbtn_meta_box_btn_pad_m'][0] ) : '10px';
    wp_nonce_field( 'stbtn_meta_box_nonce', 'meta_box_nonce' );
    ?>
	<table class="form-table">
        <tr valign="top">
        <th scope="row"><a target="_blank" href="<?php echo get_site_url() ?>/wp-admin/admin.php?page=sticky-button-page.php">Same As Global Settings</a></th>
        <td>
			 <input class="stbtn_page_checkboxes" name="stbtn_meta_box_enable_global_settings" id="stbtn_meta_box_enable_global_settings" type="checkbox" value="1" <?php checked( '1', $enable_global_settings ); ?> />
		</td>
		
        </tr>
		<tr valign="top">
        <th scope="row">Enable (Different Settings)</th>
        <td>
			 <input class="stbtn_page_checkboxes" name="stbtn_meta_box_enable" id="stbtn_meta_box_enable" type="checkbox" value="1" <?php checked( '1', $enable ); ?> />
		</td>
		
        </tr>
		<tr valign="top">
        <th scope="row">Disable</th>
        <td>
			 <input class="stbtn_page_checkboxes" name="stbtn_meta_box_disable" id="stbtn_meta_box_disable" type="checkbox" value="1" <?php checked( '1', $disable ); ?> />
		</td>
		
        </tr>
        <tr valign="top" class="stbtn_meta_field">
        <th scope="row">Button Text</th>
        <td>
			<input type="text" placeholder="Book Now, Buy Now…" name="stbtn_meta_box_text" id="stbtn_meta_box_text" value="<?php echo esc_attr($text); ?>" size="18"/>
		</td>
        </tr>

        <tr valign="top" class="stbtn_meta_field">
        <th scope="row">Button Link</th>
        <td>
			<input type="text" name="stbtn_meta_box_url" id="stbtn_meta_box_url" value="<?php echo esc_attr($url); ?>" size="18"/>
			<br /><br />
			<button type="button" class="button_link_1"> <span class="tip_plus">+</span> Tip 1 (Whatsapp Chat)</button><br />
			
			<div id="button_link_1">
				<br />Create  a click to chat on Whatsapp link by using https://wa.me/[number] where the [number] is a full phone number in international format. <br/>
				Omit any zeroes, brackets, or dashes when adding the phone number in international format.<br/>
				Example, if your number is:+155555555<br/>
				Use: https://wa.me/155555555<br/>
				Don't use: https://wa.me/+001-55555555<br/>

			</div><br />
			
			<button type="button" class="button_link_2"> <span class="tip_plus">+</span> Tip 2 (Click to call)</button><br />
			
			<div id="button_link_2">
				<br />Create a click to call link by adding "tel:" followed by your number with the international code, example: <br/> tel:+155555555

			</div>
		</td>
        </tr>

        <tr valign="top" class="stbtn_meta_field">
        <th scope="row">Link Target</th>
        <td>
		<select name="stbtn_meta_box_target" id="stbtn_meta_box_target" style="width:100%">
			<option <?php if($target == '_blank') { echo 'selected';} ?> value="_blank">New window (_blank)</option>
			<option <?php if($target == '_self') { echo 'selected';} ?> value="_self">Same window (_self)</option>
			<option <?php if($target == '_parent') { echo 'selected';} ?> value="_parent">Parent frame (_parent)</option>
			<option <?php if($target == '_top') { echo 'selected';} ?> value="_top">Opens the linked document in the full body of the window
	 (_top)</option>
       </select>
</td>
        </tr>
		<tr valign="top" class="stbtn_meta_field">
        <th scope="row">Show On Desktop</th>
        <td>
			<input name="stbtn_meta_box_show_on_desktop" id="stbtn_meta_box_show_on_desktop" type="checkbox" value="1" <?php checked( '1', $show_on_desktop ); ?> />
		</td>
        </tr>
        <tr valign="top" class="stbtn_meta_box_position_of_button stbtn_meta_field">
        <th scope="row">Position of Button</th>
			<td>
				<select name="stbtn_meta_box_left_right" style="width:100%">
					<option <?php if($stbtn_meta_box_left_right == '') { echo 'selected';} ?> value="">Select</option>
					 <option <?php if($stbtn_meta_box_left_right == 'left') { echo 'selected';} ?> value="left">Extreme Left</option>
					<option <?php if($stbtn_meta_box_left_right == 'right') { echo 'selected';} ?> value="right">Extreme Right</option>
					<option <?php if($stbtn_meta_box_left_right == 'center') { echo 'selected';} ?> value="center">Center</option>
					<option <?php if($stbtn_meta_box_left_right == 'fullwidth') { echo 'selected';} ?> value="fullwidth">Full Width</option>
				</select>
			</td>
        </tr>
		<tr valign="top" class="stbtn_meta_box_custom_button_width stbtn_meta_field">
        <th scope="row">Custom Button Width</th>
        <td><input type="text" name="stbtn_meta_box_width" placeholder="E.g. 200px" value="<?php echo esc_attr($width); ?>" size="18"/></td>
        </tr>
        <tr valign="top" class="stbtn_meta_field">
        <th scope="row">Show On Mobile</th>
        <td>
			<input name="stbtn_meta_box_show_on_mobile" id="stbtn_meta_box_show_on_mobile" type="checkbox" value="1" size="18" <?php checked( '1', $show_on_mobile ); ?> />
		</td>
        </tr>
        <tr valign="top" class="stbtn_meta_field">
        <th scope="row">Background Color</th>
        <td>
			<input type="text" class="background_color" name="stbtn_meta_box_bg" id="stbtn_meta_box_bg" value="<?php echo esc_attr($bg); ?>" size="18"/>
		</td>
        </tr>
        <tr valign="top" class="stbtn_meta_field">
        <th scope="row">Text Color</th>
        <td><input type="text" class="background_color" name="stbtn_meta_box_txtcolor" id="stbtn_meta_box_txtcolor" value="<?php echo esc_attr($txtcolor); ?>" size="18"/></td>
        </tr>
		<tr valign="top" class="stbtn_meta_field">
        <th scope="row">Font Family</th>
        <td>
			<select name="stbtn_meta_box_font_family" id="stbtn_meta_box_font_family" style="width:100%">
				<option <?php if($stbtn_meta_box_font_family == 'Arial') { echo 'selected';} ?> value="Arial">Arial</option>
				<option <?php if($stbtn_meta_box_font_family == 'Verdana') { echo 'selected';} ?> value="Verdana">Verdana</option>
				<option <?php if($stbtn_meta_box_font_family == 'Helvetica') { echo 'selected';} ?> value="Helvetica">Helvetica</option>
				<option <?php if($stbtn_meta_box_font_family == 'Tahoma') { echo 'selected';} ?> value="Tahoma">Tahoma</option>
				<option <?php if($stbtn_meta_box_font_family == 'Trebuchet MS') { echo 'selected';} ?> value="Trebuchet MS">Trebuchet MS</option>
				<option <?php if($stbtn_meta_box_font_family == 'Times New Roman') { echo 'selected';} ?> value="Times New Roman">Times New Roman</option>
				<option <?php if($stbtn_meta_box_font_family == 'Georgia') { echo 'selected';} ?> value="Georgia">Georgia</option>
				<option <?php if($stbtn_meta_box_font_family == 'Courier New') { echo 'selected';} ?> value="Courier New">Courier New</option>
				<option <?php if($stbtn_meta_box_font_family == 'Brush Script MT') { echo 'selected';} ?> value="Brush Script MT">Brush Script MT</option>
				
				<option <?php if($stbtn_meta_box_font_family == 'Garamond') { echo 'selected';} ?> value="Garamond">Garamond</option>
				</option>
		   </select>
		</td>
        </tr>
		<tr valign="top" class="stbtn_meta_field">
        <th scope="row">Font Size Desktop</th>
        <td>
			<input type="text" placeholder="E.g. 16px" name="stbtn_meta_box_font_size" id="stbtn_meta_box_font_size" value="<?php echo esc_attr($fontsize); ?>" size="18"/>
		</td>
        </tr>
		<tr valign="top" class="stbtn_meta_field">
        <th scope="row">Font Size Mobile</th>
        <td><input type="text" placeholder="E.g. 16px" name="stbtn_meta_box_font_size_m" id="stbtn_meta_box_font_size_m" value="<?php echo esc_attr($fontsizem); ?>" size="18"/></td>
        </tr>
		<tr valign="top" class="stbtn_meta_field">
        <th scope="row">Button Padding Desktop</th>
        <td><input placeholder="E.g. 10px" type="text" name="stbtn_meta_box_btn_pad" id="stbtn_meta_box_btn_pad" value="<?php echo esc_attr($btnpad); ?>" size="18"/></td>
        </tr>
		<tr valign="top" class="stbtn_meta_field">
        <th scope="row">Button Padding Mobile</th>
        <td><input type="text" name="stbtn_meta_box_btn_pad_m" placeholder="E.g. 10px" id="stbtn_meta_box_btn_pad_m" value="<?php echo esc_attr($btnpadm); ?>" size="18"/></td>
        </tr>
    </table>
	<div><h4>Note: </strong>Make sure to clear your cache, if after saving you didn't notice any changes.</h4>
    <?php
}
add_action( 'save_post', 'stbtn_meta_box_save' );
function stbtn_meta_box_save( $post_id ) {
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'stbtn_meta_box_nonce' ) ) return;
    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post', $post_id ) ) return;
    // now we can actually save the data

    // Probably a good idea to make sure your data is set
	
	//if( isset( $_POST['stbtn_meta_box_enable_global_settings'] ) )
	update_post_meta( $post_id, 'stbtn_meta_box_enable_global_settings', sanitize_text_field($_POST['stbtn_meta_box_enable_global_settings']));
	update_post_meta( $post_id, 'stbtn_meta_box_enable', sanitize_text_field($_POST['stbtn_meta_box_enable']));
	update_post_meta( $post_id, 'stbtn_meta_box_disable', sanitize_text_field($_POST['stbtn_meta_box_disable']));
		
	if( isset( $_POST['stbtn_meta_box_text'] ) )
        update_post_meta($post_id, 'stbtn_meta_box_text', sanitize_text_field($_POST['stbtn_meta_box_text']) );
	if( isset( $_POST['stbtn_meta_box_url'] ) )
        update_post_meta($post_id, 'stbtn_meta_box_url', sanitize_text_field( $_POST['stbtn_meta_box_url']));
	//if( isset( $_POST['stbtn_meta_box_show_on_desktop'] ) )
        update_post_meta($post_id, 'stbtn_meta_box_show_on_desktop', sanitize_text_field( $_POST['stbtn_meta_box_show_on_desktop']));
	if( isset( $_POST['stbtn_meta_box_left_right'] ) )
        update_post_meta( $post_id, 'stbtn_meta_box_left_right', sanitize_text_field( $_POST['stbtn_meta_box_left_right']) );
	if( isset( $_POST['stbtn_meta_box_font_family'] ) )
        update_post_meta( $post_id, 'stbtn_meta_box_font_family', sanitize_text_field( $_POST['stbtn_meta_box_font_family']) );
	//if( isset( $_POST['stbtn_meta_box_show_on_mobile'] ) )
        update_post_meta( $post_id, 'stbtn_meta_box_show_on_mobile', sanitize_text_field( $_POST['stbtn_meta_box_show_on_mobile']) );
	if( isset( $_POST['stbtn_meta_box_bg'] ) )
        update_post_meta($post_id, 'stbtn_meta_box_bg', sanitize_text_field($_POST['stbtn_meta_box_bg']));
	if( isset( $_POST['stbtn_meta_box_txtcolor'] ) )
        update_post_meta($post_id, 'stbtn_meta_box_txtcolor', sanitize_text_field($_POST['stbtn_meta_box_txtcolor']));
	if( isset( $_POST['stbtn_meta_box_font_size'] ) )
        update_post_meta($post_id, 'stbtn_meta_box_font_size', sanitize_text_field( $_POST['stbtn_meta_box_font_size']));
	if( isset( $_POST['stbtn_meta_box_font_size_m'] ) )
        update_post_meta($post_id, 'stbtn_meta_box_font_size_m', sanitize_text_field( $_POST['stbtn_meta_box_font_size_m']));
	if( isset( $_POST['stbtn_meta_box_btn_pad'] ) )
        update_post_meta($post_id, 'stbtn_meta_box_btn_pad', sanitize_text_field( $_POST['stbtn_meta_box_btn_pad']));
	if( isset( $_POST['stbtn_meta_box_btn_pad_m'] ) )
        update_post_meta($post_id, 'stbtn_meta_box_btn_pad_m', sanitize_text_field( $_POST['stbtn_meta_box_btn_pad_m']));
	if( isset( $_POST['stbtn_meta_box_target'] ) )
        update_post_meta($post_id, 'stbtn_meta_box_target', sanitize_text_field( $_POST['stbtn_meta_box_target']));
	if( isset( $_POST['stbtn_meta_box_width'] ) )
        update_post_meta($post_id, 'stbtn_meta_box_width', sanitize_text_field( $_POST['stbtn_meta_box_width']));
}


function stbtn_book_now_page() {
?>
<div class="wrap">
    <!--<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/sticky-button-logo.png'; ?>" alt="Sticky Button" width="200"/>-->
		<h2>Sticky Button Settings</h2>

<form method="post" action="options.php" class="stbtn_settings_form">
    <?php settings_fields( 'sticky-button-settings-group' ); ?>
    <?php do_settings_sections( 'sticky-button-settings-group' ); ?>
	<?php //echo get_option( 'stbtn_book_enable' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Enable/Disable</th>
        <td>
			<label class="stbtn_switch">
				<input name="stbtn_book_enable" type="checkbox" value="1" <?php checked( '1', get_option( 'stbtn_book_enable' ) ); ?> />
				<span class="stbtn_slider stbtn_round"></span>
			</label>
			<div>By enabling the plugin you will add a sticky button to all your website pages, you can also enable/disable the Sticky Button for a specific page,  from the page itself.</div>
		</td>
		
        </tr>

        <tr valign="top">
        <th scope="row">Button Text</th>
        <td><input type="text" placeholder="Book Now, Buy Now…" name="stbtn_book_text" value="<?php echo esc_attr( get_option('stbtn_book_text', 'Sticky Button') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Button Link</th>
        <td>
			<input type="text" name="stbtn_book_url" value="<?php echo esc_attr( get_option('stbtn_book_url', 'http://example.com') ); ?>" /><br /><br />
			<button type="button" class="button_link_1"> <span class="tip_plus">+</span> Tip 1 (Whatsapp Chat)</button><br />
			
			<div id="button_link_1">
				<br />Create  a click to chat on Whatsapp link by using https://wa.me/[number] where the [number] is a full phone number in international format. <br/>
				Omit any zeroes, brackets, or dashes when adding the phone number in international format.<br/>
				Example, if your number is:+155555555<br/>
				Use: https://wa.me/155555555<br/>
				Don't use: https://wa.me/+001-55555555<br/>

			</div><br />
			
			<button type="button" class="button_link_2"> <span class="tip_plus">+</span> Tip 2 (Click to call)</button><br />
			
			<div id="button_link_2">
				<br />Create a click to call link by adding "tel:" followed by your number with the international code, example: <br/> tel:+155555555

			</div>
		</td>
        </tr>

        <tr valign="top">
        <th scope="row">Link Target</th>
        <td>
<select name="stbtn_target">
         <option <?php if(get_option('stbtn_target') == '_blank') { echo 'selected';} ?> value="_blank">New window (_blank)</option>
        <option <?php if(get_option('stbtn_target') == '_self') { echo 'selected';} ?> value="_self">Same window (_self)</option>
        <option <?php if(get_option('stbtn_target') == '_parent') { echo 'selected';} ?> value="_parent">Parent frame (_parent)</option>
        <option <?php if(get_option('stbtn_target') == '_top') { echo 'selected';} ?> value="_top">Opens the linked document in the full body of the window
 (_top)</option>
       </select>
</td>
        </tr>
		<tr valign="top">
        <th scope="row">Show On Desktop</th>
        <td>
			<label class="stbtn_switch">
				<input name="show_on_desktop" type="checkbox" value="1" <?php checked( '1', get_option( 'show_on_desktop' ) ); ?> />
				<span class="stbtn_slider stbtn_round"></span>
			</label>
		</td>
        </tr>
        <tr valign="top" class="position_of_button">
        <th scope="row">Position of Button</th>
			<td>
				<select name="stbtn_left_right">
					 <option <?php if(get_option('stbtn_left_right') == 'left') { echo 'selected';} ?> value="left">Extreme Left</option>
					<option <?php if(get_option('stbtn_left_right') == 'right') { echo 'selected';} ?> value="right">Extreme Right</option>
					<option <?php if(get_option('stbtn_left_right') == 'center') { echo 'selected';} ?> value="center">Center</option>
					<option <?php if(get_option('stbtn_left_right') == 'fullwidth') { echo 'selected';} ?> value="fullwidth">Full Width</option>
				</select>
			</td>
        </tr>
		<tr valign="top" class="custom_button_width">
        <th scope="row">Custom Button Width</th>
        <td><input type="text" name="stbtn_width" placeholder="E.g. 200px" value="<?php echo esc_attr( get_option('stbtn_width', '200px') ); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Show On Mobile</th>
        <td>
			<label class="stbtn_switch">
				<input name="stbtn_book_bottom" type="checkbox" value="1" <?php checked( '1', get_option( 'stbtn_book_bottom' ) ); ?> />
				<span class="stbtn_slider stbtn_round"></span>
			</label>
		</td>
        </tr>
        <tr valign="top">
        <th scope="row">Background Color</th>
        <td><input type="text" name="stbtn_book_color" class="background_color" value="<?php echo esc_attr( get_option('stbtn_book_color', '#000000') ); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Text Color</th>
        <td><input type="text" class="background_color" name="stbtn_text_color" value="<?php echo esc_attr( get_option('stbtn_text_color', '#FFFFFF') ); ?>" /></td>
        </tr>
				<tr valign="top">
        <th scope="row">Font Family</th>
        <td>
			<select name="stbtn_font_family" id="stbtn_font_family" value="<?php echo esc_attr( get_option('stbtn_font_family', 'Arial') ); ?>">
				<option <?php if(get_option('stbtn_font_family') == 'Arial') { echo 'selected';} ?> value="Arial">Arial</option>
				<option <?php if(get_option('stbtn_font_family') == 'Verdana') { echo 'selected';} ?> value="Verdana">Verdana</option>
				<option <?php if(get_option('stbtn_font_family') == 'Helvetica') { echo 'selected';} ?> value="Helvetica">Helvetica</option>
				<option <?php if(get_option('stbtn_font_family') == 'Tahoma') { echo 'selected';} ?> value="Tahoma">Tahoma</option>
				<option <?php if(get_option('stbtn_font_family') == 'Trebuchet MS') { echo 'selected';} ?> value="Trebuchet MS">Trebuchet MS</option>
				<option <?php if(get_option('stbtn_font_family') == 'Times New Roman') { echo 'selected';} ?> value="Times New Roman">Times New Roman</option>
				<option <?php if(get_option('stbtn_font_family') == 'Georgia') { echo 'selected';} ?> value="Georgia">Georgia</option>
				<option <?php if(get_option('stbtn_font_family') == 'Courier New') { echo 'selected';} ?> value="Courier New">Courier New</option>
				<option <?php if(get_option('stbtn_font_family') == 'Brush Script MT') { echo 'selected';} ?> value="Brush Script MT">Brush Script MT</option>
				
				<option <?php if(get_option('stbtn_font_family') == 'Garamond') { echo 'selected';} ?> value="Garamond">Garamond</option>
				</option>
		   </select>
		</td>
        </tr>
		<tr valign="top">
        <th scope="row">Font Size Desktop</th>
        <td><input type="text" name="stbtn_font_size" placeholder="E.g. 16px" value="<?php echo esc_attr( get_option('stbtn_font_size', '16px') ); ?>" /></td>
        </tr>
		<tr valign="top">
        <th scope="row">Font Size Mobile</th>
        <td><input type="text" name="stbtn_font_size_m" placeholder="E.g. 16px" value="<?php echo esc_attr( get_option('stbtn_font_size_m', '16px') ); ?>" /></td>
        </tr>
		<tr valign="top">
        <th scope="row">Button Padding Desktop</th>
        <td><input type="text" name="stbtn_btn_pad" placeholder="E.g. 10px" value="<?php echo esc_attr( get_option('stbtn_btn_pad', '10px') ); ?>" /></td>
        </tr>
		<tr valign="top">
        <th scope="row">Button Padding Mobile</th>
        <td><input type="text" name="stbtn_btn_pad_m" placeholder="E.g. 10px" value="<?php echo esc_attr( get_option('stbtn_btn_pad_m', '10px') ); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Exclude Pages/Posts, Comma Separated For Multiple Values</th>
        <td>
			<input type="text" name="stbtn_page_id" value="<?php echo esc_attr( get_option('stbtn_page_id', '') ); ?>" />
			
			
			<div>
				<br />
				<a target="_blank" href='https://athemes.com/tutorials/wordpress-page-id-and-post-id/'>How to find page/post id</a>
			</div>
			<div>
				<br /><strong>Note:</strong> Alternatively you can disable the plugin for a specific page, from the page itself.
			</div>
			
		</td>
        </tr>
    </table>
	<div>
		<br /><strong>Clear Cache:</strong> If you didn't notice any changes after saving then make sure to clear your cache.
	</div>
    <?php submit_button(); ?>
	<div><strong>Support us:</strong><br />
	<span>If you like our plugin, please support us by leaving a 5 stars review.</div>

</form>
</div>
<?php }


function stbtn_book_now() {
	if (get_post_meta(get_the_ID(), 'stbtn_meta_box_enable', true)) {
		
		$mytrueglobalsettings = __(get_post_meta(get_the_ID(), 'stbtn_meta_box_enable_global_settings', true));
		$mytrue = __(get_post_meta(get_the_ID(), 'stbtn_meta_box_enable', true));
		$mytruedisable = __(get_post_meta(get_the_ID(), 'stbtn_meta_box_disable', true));
		$mytext = __(get_post_meta(get_the_ID(), 'stbtn_meta_box_text', true));
		$myurl = __(get_post_meta(get_the_ID(), 'stbtn_meta_box_url', true));
		$show_on_desktop = __(get_post_meta(get_the_ID(), 'stbtn_meta_box_show_on_desktop', true));
		$mymobile = __(get_post_meta(get_the_ID(), 'stbtn_meta_box_show_on_mobile', true));
		$lor = __(get_post_meta(get_the_ID(), 'stbtn_meta_box_left_right', true));
		$target = __(get_post_meta(get_the_ID(), 'stbtn_meta_box_target', true));
		
		
		
	} else {
		
		$mytrue = get_option( 'stbtn_book_enable' );
		$mytext = __(get_option('stbtn_book_text'));
		$myurl = __(get_option('stbtn_book_url'));
		$show_on_desktop = get_option('show_on_desktop');
		$mymobile = get_option( 'stbtn_book_bottom' );
		$lor = get_option('stbtn_left_right');
		$target = get_option( 'stbtn_target' );
		$pageid = get_option( 'stbtn_page_id' );
		
		// Exclude/Hide button of page -  if status disable

		if (get_post_meta(get_the_ID(), 'stbtn_meta_box_disable', true)) {
			$truepgid = explode(',',get_the_ID());
		} else if(!empty($pageid)) {
			$truepgid = explode(',',$pageid);
		} else {
			$truepgid = "";
		}
	}

	if(!is_admin() && $mytrue === '1') {
		if(!empty($truepgid)) {
			if(!is_page($truepgid) && !is_single($truepgid)) {
			echo wp_kses_post("<a href='$myurl' class='link_option' target='$target'><div id='rg-book'>$mytext</div></a>");
			}
		} else {
			echo wp_kses_post("<a href='$myurl' class='link_option' target='$target'> <div id='rg-book'>$mytext </div></a>");
		}
	}
}

// Now we set that function up to execute when the admin_notices action is called
add_action( 'wp_footer', 'stbtn_book_now' );

// We need some CSS to position the paragraph
function stbtn_book_css() {
	if (get_post_meta(get_the_ID(), 'stbtn_meta_box_enable', true)) {
		
		$mytrueglobalsettings = __(get_post_meta(get_the_ID(), 'stbtn_meta_box_enable_global_settings', true));
		$mytrue = __(get_post_meta(get_the_ID(), 'stbtn_meta_box_enable', true));
		$mytruedisable = __(get_post_meta(get_the_ID(), 'stbtn_meta_box_disable', true));
		$mytext = __(get_post_meta(get_the_ID(), 'stbtn_meta_box_text', true));
		$myurl = __(get_post_meta(get_the_ID(), 'stbtn_meta_box_url', true));
		$show_on_desktop = __(get_post_meta(get_the_ID(), 'stbtn_meta_box_show_on_desktop', true));
		$mymobile = __(get_post_meta(get_the_ID(), 'stbtn_meta_box_show_on_mobile', true));
		$lor = __(get_post_meta(get_the_ID(), 'stbtn_meta_box_left_right', true));
		 $target = __(get_post_meta(get_the_ID(), 'stbtn_meta_box_target', true));
		 $bgcolor = __(get_post_meta(get_the_ID(), 'stbtn_meta_box_bg', true));
		 $txtcolor = __(get_post_meta(get_the_ID(), 'stbtn_meta_box_txtcolor', true));
		 $fontfamily = __(get_post_meta(get_the_ID(), 'stbtn_meta_box_font_family', true));
		 $fontsize = __(get_post_meta(get_the_ID(), 'stbtn_meta_box_font_size', true));
		 
		  $fontsizem = __(get_post_meta(get_the_ID(), 'stbtn_meta_box_font_size_m', true));
		  $btnpad = __(get_post_meta(get_the_ID(), 'stbtn_meta_box_btn_pad', true));
		  $btnpadm = __(get_post_meta(get_the_ID(), 'stbtn_meta_box_btn_pad_m', true));
		  $customwidth = __(get_post_meta(get_the_ID(), 'stbtn_meta_box_width', true));
		
	} else {
		
		$mytrue = get_option( 'stbtn_book_enable' );
		$mytext = __(get_option('stbtn_book_text'));
		$myurl = __(get_option('stbtn_book_url'));
		$show_on_desktop = get_option('show_on_desktop');
		$mymobile = get_option( 'stbtn_book_bottom' );
		$lor = get_option('stbtn_left_right');
		$target = get_option( 'stbtn_target' );
		$bgcolor = get_option('stbtn_book_color');
		$pageid = get_option( 'stbtn_page_id' );
		$txtcolor = get_option('stbtn_text_color');
		$fontfamily = get_option( 'stbtn_font_family' );
		$fontsize = get_option('stbtn_font_size');
		$fontsizem = get_option('stbtn_font_size_m');
		$btnpad = get_option('stbtn_btn_pad');
		$btnpadm = get_option('stbtn_btn_pad_m');
		$customwidth = get_option('stbtn_width');
		if(!empty($pageid)) {
			$truepgid = explode(',',$pageid);
		} else {
			$truepgid = "";
		}
	}
	

        $numberofchars = strlen(get_option('stbtn_book_text'));
		
        $totalW = 25 * $numberofchars;

        $distance = 10 * $numberofchars;

        $deg = '0deg';
		$bottom='0%';
		$lor_val='';
        if($lor == 'right') {
			  //$deg = '-90deg';
			 $lor = 'right';
			 $lor_val='0px';
        } else if($lor == 'center') {
			$customwidth="20%";
			$lor = 'right';
			$lor_val='40%';
        } else if($lor == 'fullwidth') {
			$customwidth="100%";
			$lor = 'right';
			$lor_val='0px';
        } else {
           //$deg = '90deg';
		   $lor = 'left';
		   $lor_val='0px';
        }
if( $mytrue == 1 ) {
	echo '
	<style type="text/css">
	#rg-book {
           position: fixed;
           transform: rotate('.$deg.');
           '.$lor.': '.$lor_val.';
           width: '.$customwidth.';
           height: auto;
           text-align: center;
           padding:'.$btnpad.';
           border-top-left-radius: 10px;
           border-top-right-radius: 10px;
           z-index: 9999999;
           bottom: '.$bottom.';
           background: '.$bgcolor.';
           color: '.$txtcolor.'!important;
           box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);

	}

      .link_option{
          color: '.$txtcolor.';
          text-transform:uppercase;
          font-size:'.$fontsize.';
          font-weight:700;
					font-family:'.$fontfamily.';
        }
@media screen and (max-width:767px) {
 #rg-book {
    position: fixed;
    transform: none;
    right: inherit;
	left: inherit;
    width: 100%;
    margin:0 auto;
    height: auto;
    text-align: center;
    padding: '.$btnpadm.';
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    z-index: 9999999;
    bottom: 0;
    background: '.$bgcolor.';
}

.link_option {
  text-transform:uppercase;
  font-size:'.$fontsizem.';
  font-weight:700;

}
}
	</style>
	';
}
if($mymobile !=1) {
   echo '<style>
    @media screen and (max-width:767px) {
         #rg-book {
            display:none;
		 }
   }
   <style>';
}
if($show_on_desktop==0) {
	echo '<style>
    @media screen and (min-width:767px) {
         #rg-book {
            display:none;
		 }
   }
   <style>';

}
}

add_action( 'wp_footer', 'stbtn_book_css' );

add_action( 'admin_footer','stbtn_color_field_js');

function stbtn_color_field_js() {

	?>
	<style>
.stbtn_switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.stbtn_switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.stbtn_slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: red;
  -webkit-transition: .4s;
  transition: .4s;
}

.stbtn_slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

.stbtn_settings_form input:checked + .stbtn_slider {
  background-color: #70F282;
}

.stbtn_settings_form input:focus + .stbtn_slider {
  box-shadow: 0 0 1px #2196F3;
}

.stbtn_settings_form input:checked + .stbtn_slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.stbtn_slider.stbtn_round {
  border-radius: 34px;
}

.stbtn_slider.stbtn_round:before {
  border-radius: 50%;
}
</style>
	<script type="text/javascript">
		jQuery(document).ready(function($){
			  
			  //Trigger event of desktop button, trigger it before hiding stbtn_meta_fields class inputs
				if($('input[name="stbtn_meta_box_show_on_desktop"]').prop("checked") == true){
					$('.stbtn_meta_box_position_of_button').show();
					$('.stbtn_meta_box_custom_button_width').show();
					$('select[name="stbtn_meta_box_left_right"]').trigger('change');
				}
				else if($('input[name="stbtn_meta_box_show_on_desktop"]').prop("checked") == false){
					$('.stbtn_meta_box_position_of_button').hide();
					$('.stbtn_meta_box_custom_button_width').hide();
				}
				
				 $(".stbtn_meta_field").hide();
				//Default sb meta fields shown according to checkbox
				 var checkval=$('.stbtn_page_checkboxes:checked').attr('name');
				 if(checkval=='stbtn_meta_box_enable'){
					$(".stbtn_meta_field").show();
				 } else {
					 $(".stbtn_meta_field").hide();
				 }

			 
			  $('.stbtn_page_checkboxes').on('change',function(){
				 $('.stbtn_page_checkboxes').removeAttr('checked');
				 var val=$(this).attr('name');
				 if(val=='stbtn_meta_box_enable'){
					$(".stbtn_meta_field").show();
				 } else {
					 $(".stbtn_meta_field").hide();
				 }

				 $(this).prop('checked', true); // To Check ticked Checkbox
			  });
			  

			  $('.background_color').wpColorPicker();
			  $("#button_link_1").hide();
			  $("#button_link_2").hide();
			  
			  
			  
			  $(".button_link_1").click(function(){
				$("#button_link_1").toggle();
				$(this).find(".tip_plus").toggle();
			  });
			  
			  $(".button_link_2").click(function(){
				$("#button_link_2").toggle();
				$(this).find(".tip_plus").toggle();
			  });
  
			$('input[name="show_on_desktop"]').click(function(){
				if($(this).prop("checked") == true){
					$('.position_of_button').show();
					$('.custom_button_width').show();
					$('select[name="stbtn_left_right"]').trigger('change');
				}
				else if($(this).prop("checked") == false){
					$('.position_of_button').hide();
					$('.custom_button_width').hide();
				}
			});
			$('select[name="stbtn_left_right"]').change(function(){
				var value=$(this).val();
				if(value=='left' || value=='right'){
					$('.custom_button_width').show();
				} else {
					$('.custom_button_width').hide();
				}
			});
			$('select[name="stbtn_left_right"]').trigger('change');
			
			//Trigger event of desktop button
			if($('input[name="show_on_desktop"]').prop("checked") == true){
				$('.position_of_button').show();
				$('.custom_button_width').show();
				$('select[name="stbtn_left_right"]').trigger('change');
			}
			else if($('input[name="show_on_desktop"]').prop("checked") == false){
				$('.position_of_button').hide();
				$('.custom_button_width').hide();
			}
			
			
			//For page/posts itself
			
			$('input[name="stbtn_meta_box_show_on_desktop"]').click(function(){
				if($(this).prop("checked") == true){
					$('.stbtn_meta_box_position_of_button').show();
					$('.stbtn_meta_box_custom_button_width').show();
					$('select[name="stbtn_meta_box_left_right"]').trigger('change');
				}
				else if($(this).prop("checked") == false){
					$('.stbtn_meta_box_position_of_button').hide();
					$('.stbtn_meta_box_custom_button_width').hide();
				}
			});
			$('select[name="stbtn_meta_box_left_right"]').change(function(){
				var value=$(this).val();
				if(value=='left' || value=='right'){
					$('.stbtn_meta_box_custom_button_width').show();
				} else {
					$('.stbtn_meta_box_custom_button_width').hide();
				}
			});
			$('select[name="stbtn_left_right"]').trigger('change');
			
			
		});
	</script>
	<?php
}
function stbtn_enqueue_admin_js() { // Make sure to add the wp-color-picker dependecy to js file
	wp_enqueue_script( 'stbtn_custom_js', plugins_url( 'assets/jquery.custom.js', __FILE__ ), array( 'jquery', 'wp-color-picker' ), '', true  ); 
}


//Plugin redirection


register_activation_hook(__FILE__, 'stbtn_plugin_activate');
register_deactivation_hook( __FILE__, 'stbtn_plugin_deactivation' );

add_action('admin_init', 'stbtn_redirect');

function stbtn_plugin_activate() {
    add_option('stbtn_plugin_redirect', true);
}
function stbtn_plugin_deactivation() {
    
}

function stbtn_redirect() {
    if (get_option('stbtn_plugin_redirect', false)) {
        delete_option('stbtn_plugin_redirect');
        wp_redirect(get_site_url()."/wp-admin/admin.php?page=sticky-button-page.php");
    }
}
?>