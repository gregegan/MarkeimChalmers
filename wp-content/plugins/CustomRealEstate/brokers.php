<?php


	
	/*
 * Brokers
 */
 
 
 
 
// BROKERS on BROKERS custom post type
function brokers_admin( $post ) {
    add_meta_box( 'display_brokers_meta_box',
        'Broker Information',
        'display_brokers_meta_box',
        'brokers', 'normal', 'default'
    );
}

function display_brokers_meta_box($post) {
	wp_enqueue_media();
	wp_register_script('googlemaps', 'http://maps.googleapis.com/maps/api/js?key=AIzaSyBd2CrZ5PmJ-q7F4GwaBIsOUHX-H6XhkT8&sensor=false', false, '3');
	wp_enqueue_script('googlemaps');
	wp_register_script('alljs', plugin_dir_url(__FILE__) . '/all.js', false, '3');
	wp_enqueue_script('alljs');
	
	global $post;
	$broker_title = esc_html( get_post_meta( $post->ID, 'broker_title', true ) );
	$broker_address = esc_html( get_post_meta( $post->ID, 'broker_address', true ) );
	$broker_city = esc_html( get_post_meta( $post->ID, 'broker_city', true ) );
	$broker_state = esc_html( get_post_meta( $post->ID, 'broker_state', true ) );
	$broker_zip = esc_html( get_post_meta( $post->ID, 'broker_zip', true ) );
	$broker_phone = esc_html( get_post_meta( $post->ID, 'broker_phone', true ) );
	$broker_email = esc_html( get_post_meta( $post->ID, 'broker_email', true ) );
	$broker_linkedin = esc_html( get_post_meta( $post->ID, 'broker_linkedin', true ) );
	$broker_vcard = esc_html( get_post_meta( $post->ID, 'broker_vcard', true ) );
	$broker_fax = esc_html( get_post_meta( $post->ID, 'broker_fax', true ) );
	$broker_direct_phone = esc_html( get_post_meta( $post->ID, 'broker_direct_phone', true ) );
	?>
	<div style="overflow: hidden;">
	    <div style="float:left;width:46%;">
			<table>
				<tbody>
					<tr>
				        <th>Title</th>
				        <td><input type="text" name="broker_title" value="<?php echo $broker_title; ?>" /></td>
				    </tr>
				    <tr>
				        <th>Address</th>
				        <td><input type="text" name="broker_address" value="<?php echo $broker_address; ?>" /></td>
				    </tr>
				    <tr>
				        <th>City</th>
				        <td><input type="text" name="broker_city" value="<?php echo $broker_city; ?>" /></td>
				    </tr>
				    <tr>
				        <th>State</th>
				        <td><input type="text" name="broker_state" value="<?php echo $broker_state; ?>" /></td>
				    </tr>
				    <tr>
				        <th>Zip</th>
				        <td><input type="text" name="broker_zip" value="<?php echo $broker_zip; ?>" /></td>
				    </tr>
				    
				</tbody>
			</table>
		</div>
    	<div style="float:left;width:49%;margin-left:10px;">
    		<table>
    			<tbody>
    				<tr>
				        <th>Phone</th>
				        <td><input type="text" name="broker_phone" value="<?php echo $broker_phone; ?>" /></td>
				    </tr>
				    <tr>
				        <th>Direct Phone</th>
				        <td><input type="text" name="broker_direct_phone" value="<?php echo $broker_direct_phone; ?>" /></td>
				    </tr>
				    <tr>
				        <th>Fax</th>
				        <td><input type="text" name="broker_fax" value="<?php echo $broker_fax; ?>" /></td>
				    </tr>
				    <tr>
				        <th>Email</th>
				        <td><input type="text" name="broker_email" value="<?php echo $broker_email; ?>" /></td>
				    </tr>
				    <tr>
				        <th>LinkedIn</th>
				        <td><input type="text" name="broker_linkedin" value="<?php echo $broker_linkedin; ?>" /></td>
				    </tr>
					<tr>
						<th><label for="broker_vcard">Vcard</label></th>
						<td>
							<input id="broker_vcard" class="upload_image_field" type="text" size="36" name="broker_vcard" value="<?php echo $broker_vcard; ?>" /> 
							<input class="button upload_image_button" type="button" value="Upload Vcard" />
							<br />Enter a URL or upload a Vcard
						</td>
					</tr>
    			</tbody>
    		</table>
    	</div>	
	</div>
	<?php
}

add_action( 'save_post', 'broker_metabox_save', 10, 2 );
function broker_metabox_save( $post_id ) {
	global $post;
	// check autosave
  	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
 
  	// check permissions
  	if (!current_user_can('edit_post', $post_id))
    	return;
    
    if ( $post->post_type == 'brokers' ) {
        if ( isset( $_POST['broker_title'] ) && $_POST['broker_title'] != '' ) {
            update_post_meta( $post->ID, 'broker_title', $_POST['broker_title'] );
        }
        if ( isset( $_POST['broker_address'] ) && $_POST['broker_address'] != '' ) {
            update_post_meta( $post->ID, 'broker_address', $_POST['broker_address'] );
        }
        if ( isset( $_POST['broker_city'] ) && $_POST['broker_city'] != '' ) {
            update_post_meta( $post->ID, 'broker_city', $_POST['broker_city'] );
        }
        if ( isset( $_POST['broker_state'] ) && $_POST['broker_state'] != '' ) {
            update_post_meta( $post->ID, 'broker_state', $_POST['broker_state'] );
        }
        if ( isset( $_POST['broker_zip'] ) && $_POST['broker_zip'] != '' ) {
            update_post_meta( $post->ID, 'broker_zip', $_POST['broker_zip'] );
        }
        if ( isset( $_POST['broker_phone'] ) && $_POST['broker_phone'] != '' ) {
            update_post_meta( $post->ID, 'broker_phone', $_POST['broker_phone'] );
        }
        if ( isset( $_POST['broker_email'] ) && $_POST['broker_email'] != '' ) {
            update_post_meta( $post->ID, 'broker_email', $_POST['broker_email'] );
        }
        if ( isset( $_POST['broker_linkedin'] ) && $_POST['broker_linkedin'] != '' ) {
            update_post_meta( $post->ID, 'broker_linkedin', $_POST['broker_linkedin'] );
        }
		if ( isset( $_POST['broker_vcard'] ) && $_POST['broker_vcard'] != '' ) {
			update_post_meta( $post_id, 'broker_vcard', $_POST['broker_vcard'] );
		} 
		if ( isset( $_POST['broker_fax'] ) && $_POST['broker_fax'] != '' ) {
			update_post_meta( $post_id, 'broker_fax', $_POST['broker_fax'] );
		} 
		if ( isset( $_POST['broker_direct_phone'] ) && $_POST['broker_direct_phone'] != '' ) {
			update_post_meta( $post_id, 'broker_direct_phone', $_POST['broker_direct_phone'] );
		} 
    }
}
 
// BROKERS on PROPERTIES custom post type
function brokers_properties_admin( $post ) {
    add_meta_box( 'display_brokers_properties_meta_box',
        'Brokers',
        'display_brokers_properties_meta_box',
        'properties', 'side', 'default'
    );
}

function display_brokers_properties_meta_box( $post ) {
	global $post;
    wp_nonce_field( plugin_basename( __FILE__ ), 'broker_properties_metabox_nonce' );
	$brokers = get_post_meta( $post->ID, 'broker_ids', true );
	$broker_ids = explode( ',', $brokers);
    $parents = get_posts(
        array(
            'post_type'   => 'brokers', 
            'orderby'     => 'title', 
            'order'       => 'ASC', 
            'numberposts' => -1 
        )
    );

    if ( !empty( $parents ) ) {
        foreach ( $parents as $parent ) {
			echo '<input type="checkbox" name="broker_ids[]" value="'.$parent->ID.'"';               
            if ( in_array($parent->ID, $broker_ids) )
                echo ' checked="checked"';
            echo ' /> '. esc_html( $parent->post_title ) .'<br />';
	    }
    }
}

add_action('save_post', 'broker_properties_metabox_save');
function broker_properties_metabox_save($post_id) {
  // verify nonce
  if ( !wp_verify_nonce( $_POST['broker_properties_metabox_nonce'], plugin_basename( __FILE__ ) ) )
        return;
 
  // check autosave
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
 
  // check permissions
  if (!current_user_can('edit_post', $post_id))
    return;
 
    $old['checkboxes'] = get_post_meta( $post_id, 'broker_ids', true );
	$new['checkboxes'] = $_POST['broker_ids'];

    if ( $new['checkboxes'] && $new['checkboxes'] != $old['checkboxes'] ) {
		$new['checkboxes'] = implode( ',', $new['checkboxes'] );
      update_post_meta($post_id, 'broker_ids', $new['checkboxes']);
    } elseif ( '' == $new['checkboxes'] && $old['checkboxes'] ) {
      delete_post_meta($post_id, 'broker_ids', $old['checkboxes']);
    }
}
?>