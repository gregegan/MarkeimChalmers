<?php
/*
 * PROPERTIES
 */
 
 
 
 
 
 
 
 
 
function properties_admin() {
    add_meta_box( 'properties_meta_box',
        'Property Details',
        'display_property_meta_box',
        'properties', 'normal', 'high'
    );
}

function display_property_meta_box( $property ) {
	wp_enqueue_media();
	wp_register_script('googlemaps', 'http://maps.googleapis.com/maps/api/js?key=AIzaSyBd2CrZ5PmJ-q7F4GwaBIsOUHX-H6XhkT8&sensor=false', false, '3');
	wp_enqueue_script('googlemaps');
	wp_register_script('alljs', plugin_dir_url(__FILE__) . '/all.js', false, '3');
	wp_enqueue_script('alljs');

    $property_type_investment = esc_html( get_post_meta( $property->ID, 'property_type_investment', true ) );
    $property_type_office = esc_html( get_post_meta( $property->ID, 'property_type_office', true ) );
    $property_type_rental = esc_html( get_post_meta( $property->ID, 'property_type_rental', true ) );
    $property_type_industrial = esc_html( get_post_meta( $property->ID, 'property_type_industrial', true ) );
    $property_type_land = esc_html( get_post_meta( $property->ID, 'property_type_land', true ) );
    $property_type_mixed = esc_html( get_post_meta( $property->ID, 'property_type_mixed', true ) );
    $property_space_requirement = esc_html( get_post_meta( $property->ID, 'property_space_requirement', true ) );
    $property_sort = esc_html( get_post_meta( $property->ID, 'property_sort', true ) );
	$property_head = esc_html( get_post_meta( $property->ID, 'property_head', true ) );
    $property_address = esc_html( get_post_meta( $property->ID, 'property_address', true ) );
    $property_address2 = esc_html( get_post_meta( $property->ID, 'property_address2', true ) );
    $property_city = esc_html( get_post_meta( $property->ID, 'property_city', true ) );
    $property_state = esc_html( get_post_meta( $property->ID, 'property_state', true ) );
    $property_zip = esc_html( get_post_meta( $property->ID, 'property_zip', true ) );
    $property_acreage = esc_html( get_post_meta( $property->ID, 'property_acreage', true ) );
    $property_available_space = esc_html( get_post_meta( $property->ID, 'property_available_space', true ) );
    $property_lease_text = esc_html( get_post_meta( $property->ID, 'property_lease_text', true ) );
    $property_sale_text = esc_html( get_post_meta( $property->ID, 'property_sale_text', true ) );
    $property_sale_type_sale = esc_html( get_post_meta( $property->ID, 'property_sale_type_sale', true ) );
    $property_sale_type_lease = esc_html( get_post_meta( $property->ID, 'property_sale_type_lease', true ) );
    $property_lease_price = esc_html( get_post_meta( $property->ID, 'property_lease_price', true ) );
    $property_sale_price = esc_html( get_post_meta( $property->ID, 'property_sale_price', true ) );
    $property_cap_rate = esc_html( get_post_meta( $property->ID, 'property_cap_rate', true ) );
    $property_year_built = esc_html( get_post_meta( $property->ID, 'property_year_built', true ) );
    $property_status = esc_html( get_post_meta( $property->ID, 'property_status', true ) );
    //$property_location = esc_html( get_post_meta( $property->ID, 'property_location', true ) );
    $property_zoning = esc_html( get_post_meta( $property->ID, 'property_zoning', true ) );
    $property_traffic_count = esc_html( get_post_meta( $property->ID, 'property_traffic_count', true ) );
    $property_retaxes = esc_html( get_post_meta( $property->ID, 'property_retaxes', true ) );
    $property_lat = esc_html( get_post_meta( $property->ID, 'property_lat', true ) );
    $property_long = esc_html( get_post_meta( $property->ID, 'property_long', true ) );
    $property_comments = esc_html(get_post_meta($property->ID, 'property_comments', true));
    
    ?>
    <div style="overflow: hidden;">
	    <div style="float:left;width:46%;">
		    <table>
		    	<tbody>
		    		<tr>
		    			<th>Property Type</th>
		    			<td>
		    				<input type="checkbox" id="property_type_investment" name="property_type_investment" value="true"<?php if($property_type_investment == "true"){ echo ' checked="checked"'; } ?>/>
			        		Investment<br />
			        		
			        		<input type="checkbox" id="property_type_office" name="property_type_office" value="true"<?php if($property_type_office == "true"){ echo ' checked="checked"'; } ?>/>
			        		Office<br />
			        		
			        		<input type="checkbox" id="property_type_rental" name="property_type_rental" value="true"<?php if($property_type_rental == "true"){ echo ' checked="checked"'; } ?>/>
			        		Retail<br />
			        		
			        		<input type="checkbox" id="property_type_land" name="property_type_land" value="true"<?php if($property_type_land == "true"){ echo ' checked="checked"'; } ?>/>
			        		Land<br />
			        		
			        		<input type="checkbox" id="property_type_industrial" name="property_type_industrial" value="true"<?php if($property_type_industrial == "true"){ echo ' checked="checked"'; } ?>/>
			        		Industrial<br />
			        		
			        		<input type="checkbox" id="property_type_mixed" name="property_type_mixed" value="true"<?php if($property_type_mixed == "true"){ echo ' checked="checked"'; } ?>/>
			        		Mixed Use<br />
		    			</td>
		    		</tr>
			        <!--<tr>
			            <th>Space Requirements</th>
			            <td>
			            	<select name="property_space_requirement">
			                    <option value="1000" <?php echo selected( "1000", $property_space_requirement ); ?>>1000-5000
								<option value="5000" <?php echo selected( "5000", $property_space_requirement ); ?>>5000-10000
								<option value="10000" <?php echo selected( "10000", $property_space_requirement ); ?>>10000-25000
								<option value="25000" <?php echo selected( "25000", $property_space_requirement ); ?>>25000+
			                </select>
			            </td>
			        </tr>-->
			        <tr>
			            <th>Acreage</th>
			            <td><input type="text" name="property_acreage" value="<?php echo $property_acreage; ?>" /></td>
			        </tr>
			        <tr>
			            <th>Available Space(sq.ft.)</th>
			            <td><input type="text" name="property_available_space" value="<?php echo $property_available_space; ?>" /></td>
			        </tr>
			        <tr>
			        	<th>Property Sale Type</th>
			        	<td>
			        		<input type="checkbox" id="property_sale_type_lease" name="property_sale_type_lease" value="true"<?php if($property_sale_type_lease == "true"){ echo ' checked="checked"'; } ?>/>
			        		Lease<br />
			        		<input type="checkbox" id="property_sale_type_sale" name="property_sale_type_sale" value="true"<?php if($property_sale_type_sale == "true"){ echo ' checked="checked"'; } ?>/>
			        		Sale<br />
			        	</td>
			        </tr>
			        <tr id="property-lease-price">
			            <th>Lease Price</th>
			            <td><input type="text" name="property_lease_price" value="<?php echo $property_lease_price; ?>" /></td>
			        </tr>
			        <tr>
			            <th>Lease Alternate Text</th>
			            <td><input type="text" name="property_lease_text" value="<?php echo $property_lease_text; ?>" /></td>
			        </tr>
			        <tr id="property-sale-price">
			            <th>Sale Price</th>
			            <td><input type="text" name="property_sale_price" value="<?php echo $property_sale_price; ?>" /></td>
			        </tr>
			        <tr>
			            <th>Sale Alternate Text</th>
			            <td><input type="text" name="property_sale_text" value="<?php echo $property_sale_text; ?>" /></td>
			        </tr>
			        <tr>
			            <th>Cap Rate</th>
			            <td><input type="text" name="property_cap_rate" value="<?php echo $property_cap_rate; ?>" /></td>
			        </tr>
			        <tr>
			            <th>Year Built</th>
			            <td><input type="text" name="property_year_built" value="<?php echo $property_year_built; ?>" /></td>
			        </tr>
			        <tr>
			            <th>Zoning</th>
			            <td><input type="text" name="property_zoning" value="<?php echo $property_zoning; ?>" /></td>
			        </tr>
			        <tr>
			            <th>Traffic Count</th>
			            <td><input type="text" name="property_traffic_count" value="<?php echo $property_traffic_count; ?>" /></td>
			        </tr>
			        <tr>
			            <th>RE Taxes</th>
			            <td><input type="text" name="property_retaxes" value="<?php echo $property_retaxes; ?>" /></td>
			        </tr>
			        <tr>
			            <th>Status</th>
			            <td>
			            	<!--<select name="property_status">
			                    <option value="Available" <?php echo selected( "Available", $property_status ); ?>>Available
			                    <option value="Pending" <?php echo selected( "Pending", $property_status ); ?>>Pending
			                    <option value="Sold" <?php echo selected( "Sold", $property_status ); ?>>Sold
			                </select>-->
			                <input type="text" name="property_status" value="<?php echo $property_status; ?>" />
			            </td>
			        </tr>
		        </tbody>
		    </table>
	    </div>
    	<div style="float:left;width:49%;margin-left:10px;">
	    	<table>
		    	<tbody>
		    		<tr>
			            <th>Sort #</th>
			            <td><input type="text" id="property_sort" name="property_sort" value="<?php if(isset($property_sort) && $property_sort != ''){echo $property_sort;}else{echo "99999";} ?>" /></td>
			        </tr>
					<tr>
			            <th>Property Heading</th>
			            <td><input type="text" id="property_head" name="property_head" value="<?php echo $property_head; ?>" /></td>
			        </tr>
			        <tr>
			            <th>Address</th>
			            <td><input type="text" id="property_address" name="property_address" value="<?php echo $property_address; ?>" /></td>
			        </tr>
			        <tr>
			            <th>Address 2</th>
			            <td><input type="text" id="property_address2" name="property_address2" value="<?php echo $property_address2; ?>" /></td>
			        </tr>
			        <tr>
			            <th>City</th>
			            <td><input type="text" id="property_city" name="property_city" value="<?php echo $property_city; ?>" /></td>
			        </tr>
			        <tr>
			            <th>State</th>
			            <td><input type="text" id="property_state" name="property_state" value="<?php echo $property_state ?>" /></td>
			        </tr>
			        <tr>
			            <th>Zip</th>
			            <td><input type="text" id="property_zip" name="property_zip" value="<?php echo $property_zip; ?>" /></td>
			        </tr>
			        <tr>
			        	<style>
			        		#getGeo { padding:5px;text-align:center;font-size:14px;background-color:#e0e0e0; }
			        		#getGeo:hover { background-color:#f0f0f0;cursor:pointer;}
			        	</style>
			        	<td colspan="2" id="getGeo">Get Google Maps Geolocation</td>
			        </tr>
			        <tr>
			            <th>Lat</th>
			            <td><input type="text" id="property_lat" name="property_lat" value="<?php echo $property_lat; ?>" /></td>
			        </tr>
			        <tr>
			            <th>Long</th>
			            <td><input type="text" id="property_long" name="property_long" value="<?php echo $property_long; ?>" /></td>
			        </tr>
		        </tbody>
		    </table>
    	</div>
    	<div style="clear:both;">
    		<table>
    			<tbody>
    				<tr>
    					<th>Comments</th>
    					<td>
    						<textarea cols="80" rows="5" name="property_comments" id="property_comments"><?php if ( isset ( $property_comments ) ) echo $property_comments ?></textarea>
						</td>
    				</tr>
    			</tbody>
    		</table>
    	</div>
    </div>
    <div style="clear:both;margin:15px 0;">
    	<div style="width:800px; height:400px;margin:0 auto;" id="map-canvas"></div>
    </div>
	<?
}

add_action( 'save_post', 'add_property_fields', 10, 2 );
function add_property_fields( $property_id, $property ) {
	// check autosave
  	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
 
  	// check permissions
  	if (!current_user_can('edit_post', $post_id))
    	return;
    
    if ( $property->post_type == 'properties' ) {	    
	    if ( isset( $_POST['property_type_investment'] ) && $_POST['property_type_investment'] != '' ) {
            update_post_meta( $property_id, 'property_type_investment', $_POST['property_type_investment'] );
        } else { update_post_meta( $property_id, 'property_type_investment', 'false' ); }
        
        if ( isset( $_POST['property_type_office'] ) && $_POST['property_type_office'] != '' ) {
            update_post_meta( $property_id, 'property_type_office', $_POST['property_type_office'] );
        } else { update_post_meta( $property_id, 'property_type_office', 'false' ); }
        
        if ( isset( $_POST['property_type_rental'] ) && $_POST['property_type_rental'] != '' ) {
            update_post_meta( $property_id, 'property_type_rental', $_POST['property_type_rental'] );
        } else { update_post_meta( $property_id, 'property_type_rental', 'false' ); }
        
        if ( isset( $_POST['property_type_industrial'] ) && $_POST['property_type_industrial'] != '' ) {
            update_post_meta( $property_id, 'property_type_industrial', $_POST['property_type_industrial'] );
        } else { update_post_meta( $property_id, 'property_type_industrial', 'false' ); }
        
        if ( isset( $_POST['property_type_land'] ) && $_POST['property_type_land'] != '' ) {
            update_post_meta( $property_id, 'property_type_land', $_POST['property_type_land'] );
        } else { update_post_meta( $property_id, 'property_type_land', 'false' ); }
        
        if ( isset( $_POST['property_type_mixed'] ) && $_POST['property_type_mixed'] != '' ) {
            update_post_meta( $property_id, 'property_type_mixed', $_POST['property_type_mixed'] );
        } else { update_post_meta( $property_id, 'property_type_mixed', 'false' ); }
	    
        /*if ( isset( $_POST['property_space_requirement'] ) && $_POST['property_space_requirement'] != '' ) {
            update_post_meta( $property_id, 'property_space_requirement', $_POST['property_space_requirement'] );
        }*/
        if ( isset( $_POST['property_sort'] ) && $_POST['property_sort'] != '' ) {
            update_post_meta( $property_id, 'property_sort', $_POST['property_sort'] );
        } else { update_post_meta( $property_id, 'property_sort', '99999' ); }
        
		if ( isset( $_POST['property_head'] ) && $_POST['property_head'] != '' ) {
            update_post_meta( $property_id, 'property_head', $_POST['property_head'] );
        } else { update_post_meta( $property_id, 'property_head', '' ); }
        
        if ( isset( $_POST['property_address'] ) && $_POST['property_address'] != '' ) {
            update_post_meta( $property_id, 'property_address', $_POST['property_address'] );
        } else { update_post_meta( $property_id, 'property_address', '' ); }
        
        if ( isset( $_POST['property_address2'] ) && $_POST['property_address2'] != '' ) {
            update_post_meta( $property_id, 'property_address2', $_POST['property_address2'] );
        } else { update_post_meta( $property_id, 'property_address2', '' ); }
        
        if ( isset( $_POST['property_city'] ) && $_POST['property_city'] != '' ) {
            update_post_meta( $property_id, 'property_city', $_POST['property_city'] );
        } else { update_post_meta( $property_id, 'property_city', '' ); }
        
        if ( isset( $_POST['property_state'] ) && $_POST['property_state'] != '' ) {
            update_post_meta( $property_id, 'property_state', $_POST['property_state'] );
        } else { update_post_meta( $property_id, 'property_state', '' ); }
        
        if ( isset( $_POST['property_zip'] ) && $_POST['property_zip'] != '' ) {
            update_post_meta( $property_id, 'property_zip', $_POST['property_zip'] );
        } else { update_post_meta( $property_id, 'property_zip', '' ); }
        
        if ( isset( $_POST['property_acreage'] ) && $_POST['property_acreage'] != '' && $_POST['property_acreage'] != '0.00') {
            update_post_meta( $property_id, 'property_acreage', $_POST['property_acreage'] );
        } else { update_post_meta( $property_id, 'property_acreage', '' ); }
        
        if ( isset( $_POST['property_available_space'] ) && $_POST['property_available_space'] != '' && $_POST['property_available_space'] != '0.00') {
            update_post_meta( $property_id, 'property_available_space', $_POST['property_available_space'] );
            /*$property_available_space = $_POST['property_available_space'];
			if((float)$property_available_space >= 1000 && (float)$property_available_space <= 5000) { $has1000 = true; }
			if((float)$property_available_space >= 5000 && (float)$property_available_space <= 10000) { $has5000 = true; }
			if((float)$property_available_space >= 10000 && (float)$property_available_space <= 25000) { $has10000 = true; }
			if((float)$property_available_space >= 25000) { $has25000 = true; }
			if($has1000 == true) {
				update_post_meta( $property_id, 'has1000', 'true' );
			} else {
				update_post_meta( $property_id, 'has1000', 'false' );
			}
			
			if($has5000 == true) {
				update_post_meta( $property_id, 'has5000', 'true' );
			} else {
				update_post_meta( $property_id, 'has5000', 'false' );
			}
			
			if($has10000 == true) {
				update_post_meta( $property_id, 'has10000', 'true' );
			} else {
				update_post_meta( $property_id, 'has10000', 'false' );
			}
			
			if($has25000 == true) {
				update_post_meta( $property_id, 'has25000', 'true' );
			} else {
				update_post_meta( $property_id, 'has25000', 'false' );
			}*/
        } else { update_post_meta( $property_id, 'property_available_space', '' ); }
        
        if ( isset( $_POST['property_lease_text'] ) && $_POST['property_lease_text'] != '') {
            update_post_meta( $property_id, 'property_lease_text', $_POST['property_lease_text'] );
        } else { update_post_meta( $property_id, 'property_lease_text', '' ); }
        
        if ( isset( $_POST['property_sale_text'] ) && $_POST['property_sale_text'] != '') {
            update_post_meta( $property_id, 'property_sale_text', $_POST['property_sale_text'] );
        } else { update_post_meta( $property_id, 'property_sale_text', '' ); }
        
        if ( isset( $_POST['property_sale_type_lease'] ) && $_POST['property_sale_type_lease'] != '' ) {
            update_post_meta( $property_id, 'property_sale_type_lease', $_POST['property_sale_type_lease'] );
        } else { update_post_meta( $property_id, 'property_sale_type_lease', 'false' ); }
        
        if ( isset( $_POST['property_sale_type_sale'] ) && $_POST['property_sale_type_sale'] != '' ) {
            update_post_meta( $property_id, 'property_sale_type_sale', $_POST['property_sale_type_sale'] );
        } else { update_post_meta( $property_id, 'property_sale_type_sale', 'false' ); }
        
        if ( isset( $_POST['property_lease_price'] ) && $_POST['property_lease_price'] != '' ) {
            update_post_meta( $property_id, 'property_lease_price', $_POST['property_lease_price'] );
        } else { update_post_meta( $property_id, 'property_lease_price', '' ); }
        
        if ( isset( $_POST['property_sale_price'] ) && $_POST['property_sale_price'] != '' ) {
            update_post_meta( $property_id, 'property_sale_price', $_POST['property_sale_price'] );
        } else { update_post_meta( $property_id, 'property_sale_price', '' ); }
        
        if ( isset( $_POST['property_cap_rate'] ) && $_POST['property_cap_rate'] != '' ) {
            update_post_meta( $property_id, 'property_cap_rate', $_POST['property_cap_rate'] );
        } else { update_post_meta( $property_id, 'property_cap_rate', '' ); }
        
        if ( isset( $_POST['property_year_built'] ) && $_POST['property_year_built'] != '' ) {
            update_post_meta( $property_id, 'property_year_built', $_POST['property_year_built'] );
        } else { update_post_meta( $property_id, 'property_year_built', '' ); }
        
        if ( isset( $_POST['property_status'] ) && $_POST['property_status'] != '' ) {
            update_post_meta( $property_id, 'property_status', $_POST['property_status'] );
        } else { update_post_meta( $property_id, 'property_status', '' ); }
        
        if ( isset( $_POST['property_zoning'] ) && $_POST['property_zoning'] != '' ) {
            update_post_meta( $property_id, 'property_zoning', $_POST['property_zoning'] );
        } else { update_post_meta( $property_id, 'property_zoning', '' ); }
        
        if ( isset( $_POST['property_traffic_count'] ) && $_POST['property_traffic_count'] != '' ) {
            update_post_meta( $property_id, 'property_traffic_count', $_POST['property_traffic_count'] );
        } else { update_post_meta( $property_id, 'property_traffic_count', '' ); }

        if ( isset( $_POST['property_retaxes'] ) && $_POST['property_retaxes'] != '' ) {
            update_post_meta( $property_id, 'property_retaxes', $_POST['property_retaxes'] );
        } else { update_post_meta( $property_id, 'property_retaxes', '' ); }
        
        if ( isset( $_POST['property_lat'] ) && $_POST['property_lat'] != '' ) {
            update_post_meta( $property_id, 'property_lat', $_POST['property_lat'] );
        } else { update_post_meta( $property_id, 'property_lat', '' ); }
        
        if ( isset( $_POST['property_long'] ) && $_POST['property_long'] != '' ) {
            update_post_meta( $property_id, 'property_long', $_POST['property_long'] );
        } else { update_post_meta( $property_id, 'property_long', '' ); }
        
        if ( isset( $_POST['property_comments'] ) && $_POST['property_comments'] != '' ) {
            update_post_meta( $property_id, 'property_comments', $_POST['property_comments'] );
        } else { update_post_meta( $property_id, 'property_comments', '' ); }
    }
}

?>