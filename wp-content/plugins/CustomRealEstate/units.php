<?php
/*
 * UNITS
 */

function units_admin() {
    add_meta_box( 'units_meta_box',
        'Property Units',
        'units_meta_box',
        'properties', 'normal', 'high'
    );
}

function units_meta_box() {
    global $post;
	wp_nonce_field( plugin_basename( __FILE__ ), 'units_metabox_nonce' );
	for($x = 0; $x < 20; $x++) {
		${'unit_active'.$x} = esc_html( get_post_meta( $post->ID, 'unit_active'.$x, true ) );
		${'unit_title'.$x} = esc_html( get_post_meta( $post->ID, 'unit_title'.$x, true ) );
		${'unit_space'.$x} = esc_html( get_post_meta( $post->ID, 'unit_space'.$x, true ) );
		//${'unit_upload'.$x} = esc_html( get_post_meta( $post->ID, 'unit_upload'.$x, true ) );
	}
    //$file_upload1 = esc_html( get_post_meta( $post->ID, 'file_upload1', true ) );

?>
<input type="hidden" name="fwds_slider_box_nonce" value="<?php wp_create_nonce(basename(__FILE__)) ?>" />
<table class="form-table">
<tbody>
	<?php

	for($x = 0; $x < 20; $x++) {
	?>
    <tr>
		<td>
			Active: <input type="checkbox" name="unit_active<?php echo $x; ?>" value="true"<?php if(${'unit_active'.$x} == "true"){ echo ' checked="checked"'; } ?>/>
		</td>
		<td><input type="text" placeholder="Unit Title" id="unit_title<?php echo $x;?>" name="unit_title<?php echo $x;?>" value="<?php echo ${'unit_title'.$x}; ?>" /></td>
		<td>
			<!--<select name="unit_space<?php echo $x;?>">
				<option value="1000" <?php echo selected( "1000", ${'unit_space'.$x} ); ?>>1000-5000
				<option value="5000" <?php echo selected( "5000", ${'unit_space'.$x} ); ?>>5000-10000
				<option value="10000" <?php echo selected( "10000", ${'unit_space'.$x} ); ?>>10000-25000
				<option value="25000" <?php echo selected( "25000", ${'unit_space'.$x} ); ?>>25000+
			</select>-->
			<input type="text" placeholder="Unit Space Available" id="unit_space<?php echo $x;?>" name="unit_space<?php echo $x;?>" value="<?php echo ${'unit_space'.$x}; ?>" />
		</td>

    	<!--<td>
		    <input id="unit_upload<?php echo $x;?>" class="upload_image_field" type="text" size="36" name="unit_upload<?php echo $x;?>" value="<?php echo $unit_upload; ?>" /> 
		    <input class="button upload_image_button" type="button" value="Upload PDF" />
		    <br />Enter a URL or upload an PDF
    	</td>-->
    </tr>
	<?php 
	}
	?>
</tbody>
</table>
<?php
}



add_action('save_post', 'units_metabox_save');
function units_metabox_save($post_id) {
  // verify nonce
  if ( !wp_verify_nonce( $_POST['units_metabox_nonce'], plugin_basename( __FILE__ ) ) )
        return;
 
  // check autosave
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
 
  // check permissions
  if (!current_user_can('edit_post', $post_id))
    return;
	
	$has1000 = false;
	$has5000 = false;
	$has10000 = false;
	$has25000 = false;
	for($x = 0; $x < 20; $x++) {
		$isActive = false;
		if ( isset( $_POST['unit_active'.$x] ) && $_POST['unit_active'.$x] != '' ) {
			$isActive = true;
			update_post_meta( $post_id, 'unit_active'.$x, $_POST['unit_active'.$x] );
		} else {
			update_post_meta( $post_id, 'unit_active'.$x, 'false' );
		}
		if ( isset( $_POST['unit_title'.$x] ) && $_POST['unit_title'.$x] != '' ) {
			update_post_meta( $post_id, 'unit_title'.$x, $_POST['unit_title'.$x] );
		} else {
			update_post_meta( $post_id, 'unit_title'.$x, '' );
		}
		if ( isset( $_POST['unit_space'.$x] ) && $_POST['unit_space'.$x] != '' ) {
			$unit_space = $_POST['unit_space'.$x];
			if($isActive) { 
				if((float)$unit_space >= 1000 && (float)$unit_space <= 5000) { $has1000 = true; }
				if((float)$unit_space >= 5000 && (float)$unit_space <= 10000) { $has5000 = true; }
				if((float)$unit_space >= 10000 && (float)$unit_space <= 25000) { $has10000 = true; }
				if((float)$unit_space >= 25000) { $has25000 = true; }
			}
			update_post_meta( $post_id, 'unit_space'.$x, $unit_space );
		} else {
			update_post_meta( $post_id, 'unit_space'.$x, '' );
		}
		if ( isset( $_POST['unit_upload'.$x] ) && $_POST['unit_upload'.$x] != '' ) {
			update_post_meta( $post_id, 'unit_upload'.$x, $_POST['unit_upload'.$x] );
		}
	}
	
	$property_available_space = $_POST['property_available_space'];
	if(isset($property_available_space) && $property_available_space != '') {
		if((float)$property_available_space >= 1000 && (float)$property_available_space <= 5000) { $has1000 = true; }
		if((float)$property_available_space >= 5000 && (float)$property_available_space <= 10000) { $has5000 = true; }
		if((float)$property_available_space >= 10000 && (float)$property_available_space <= 25000) { $has10000 = true; }
		if((float)$property_available_space >= 25000) { $has25000 = true; }
	}
	
	if($has1000) {
		update_post_meta( $post_id, 'has1000', 'true' );
	} else {
		update_post_meta( $post_id, 'has1000', 'false' );
	}
	
	if($has5000) {
		update_post_meta( $post_id, 'has5000', 'true' );
	} else {
		update_post_meta( $post_id, 'has5000', 'false' );
	}
	
	if($has10000) {
		update_post_meta( $post_id, 'has10000', 'true' );
	} else {
		update_post_meta( $post_id, 'has10000', 'false' );
	}
	
	if($has25000) {
		update_post_meta( $post_id, 'has25000', 'true' );
	} else {
		update_post_meta( $post_id, 'has25000', 'false' );
	}

}

?>