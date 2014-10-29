<?php echo $_SERVER['DOCUMENT_ROOT']; 


define( 'WP_USE_THEMES', false );  
require('/home/gregegan/public_html/markeim/wp-blog-header.php'); 


function get_postid_by_title($title, $type = 'post') {
    global $wpdb;

    $post_id = $wpdb->get_var(
        $wpdb->prepare("
            SELECT    ID
                FROM  $wpdb->posts
                WHERE post_title = %s
                  AND post_type = '%s'
        ", $title, $type)
    );
	return $post_id;  
    
}

if($_GET['type'] == "property") {

// PROPERTIES
$doc = fopen("ja0yu_listings_properties.csv", "r");

while (!feof($doc)) {
	$properties = fgetcsv($doc, 1024, ",");
	$legacyId = $properties[0];
	$title = $properties[1];
	$listing_category = $properties[2];
	$property_listing_type = $properties[4];
	$description = $properties[7];
	
	$property_address_1 = $properties[9];
	$property_address_2 = $properties[10];
	$property_city = $properties[11];
	$property_state = $properties[12];
	$property_zipcode =  $properties[13];
	$property_lat =  $properties[15];
	$property_long =  $properties[16];
	$property_broker = $properties[17];
	$property_available_space = $properties[19];
	$property_acreage = $properties[20];
	$property_zoning = $properties[22];
	$property_re_taxes = $properties[23];
	$property_k2_id = $properties[33];
	$property_published = $properties[37];
	
	//if($legacyId == 1170) {
	
		echo $property_listing_type . '<br>';
		if(strpos($property_listing_type, 'sale') !== false) {
			$property_sale_type_sale = 'true';
		} else { $property_sale_type_sale = 'false'; }
		
		if(strpos($property_listing_type, 'lease') !== false){
			$property_sale_type_lease = 'true';
		}  else { $property_sale_type_lease = 'false'; }
		
		// Create post object
		$my_post = array(
		  'post_title'    => $title,
		  'post_content'  => $description,
		  'post_status'   => 'publish',
		  'post_author'   => 1,
		  'post_type' => 'properties'
		);
		if( null == get_page_by_title( $title, OBJECT, 'properties' ) ) {
			$post_ID = wp_insert_post( $my_post );
		} else {
			$post_ID = 	get_page_by_title( $title, OBJECT, 'properties' );
		}
	
		if($post_ID != null) {
			echo $property_published;
			if($property_published == 0 || $property_published == "0") {
				$post_ID->post_status = 'draft';
	    		wp_update_post($post_ID);
			}
			
			$k2items = fopen("ja0yu_k2_items2.csv", "r");
			while (!feof($k2items)) {
				$items = fgetcsv($k2items, 1024, ";");
				$k2id = $items[0];
				$k2_published = $items[4];
				if($k2id == $property_k2_id) {
					echo '<br>published:' . $k2_published.'<br>';
					if($k2_published == 0 || $k2_published == "0") {
						$post_ID->post_status = 'draft';
			    		wp_update_post($post_ID);
					}
					break;
				}
			}
			fclose($k2items);
			
	    
			echo $post_ID->ID . ' ' . $title .  ' ' . $listing_category . '<br>';
			update_post_meta( $post_ID->ID, 'legacy_id', $legacyId );
			update_post_meta( $post_ID->ID, 'legacy_k2_id', $property_k2_id );
			update_post_meta( $post_ID->ID, 'property_address', $property_address_1 );
			update_post_meta( $post_ID->ID, 'property_address2', $property_address_2 );
			update_post_meta( $post_ID->ID, 'property_city', $property_city );
			update_post_meta( $post_ID->ID, 'property_state', strtoupper($property_state) );
			if($property_zipcode != "") {
				update_post_meta( $post_ID->ID, 'property_zip', '0'.$property_zipcode );
			} else {
				update_post_meta( $post_ID->ID, 'property_zip', "" );
			}
			update_post_meta( $post_ID->ID, 'property_lat', $property_lat );
			update_post_meta( $post_ID->ID, 'property_long', $property_long );
			update_post_meta( $post_ID->ID, 'property_acreage', $property_acreage );
			update_post_meta( $post_ID->ID, 'property_available_space', $property_available_space );
			if($property_zoning != "" && $property_zoning != "NULL") {
				update_post_meta( $post_ID->ID, 'property_zoning', $property_zoning );
			}
			update_post_meta( $post_ID->ID, 'property_sale_type_sale', $property_sale_type_sale );
			update_post_meta( $post_ID->ID, 'property_sale_type_lease', $property_sale_type_lease );
			
			if($property_re_taxes != "0.00" && $property_re_taxes != "NULL") {
				update_post_meta( $post_ID->ID, 'property_retaxes', $property_re_taxes );
			}
			if($description != "") {
				echo "XXX" . $description . "<Br><br>";
				$comments = strlen($description) > 150 ? substr($description,0,150)."..." : $description;
				echo $comments;
				update_post_meta( $post_ID->ID, 'property_comments', $comments );
			}
			
			if($property_broker != null) {
				
				/*
				scott hersh = 65 = 497
				neisser = 68 = 496
				martin = 67 = 495
				kerr = 66 = 494
				arnold = 380 = 493
				dembo 381 =491
				burns =64 =  474
				berlinsky =63= 94
				*/
				
				$str = "";
				if(strpos($property_broker, "65") !== false) {
					if($str != "") { $str .= ","; }
					$str .= "497";
				}
				
				if(strpos($property_broker, "64") !== false) {
					if($str != "") { $str .= ","; }
					$str .= "474";
				}
				
				if(strpos($property_broker, "63") !== false) {
					if($str != "") { $str .= ","; }
					$str .= "94";
				}
				
				if(strpos($property_broker, "68") !== false) {
					if($str != "") { $str .= ","; }
					$str .= "496";
				}
				
				if(strpos($property_broker, "67") !== false) {
					if($str != "") { $str .= ","; }
					$str .= "495";
				}
				
				if(strpos($property_broker, "66") !== false) {
					if($str != "") { $str .= ","; }
					$str .= "494";
				}
				
				if(strpos($property_broker, "380") !== false) {
					if($str != "") { $str .= ","; }
					$str .= "493";
				}
				if(strpos($property_broker, "381") !== false) {
					if($str != "") { $str .= ","; }
					$str .= "491";
				}
				
				if($str != "") {
					update_post_meta( $post_ID->ID, 'broker_ids', $str );
				}
				
			}
			
			/*
			Investment = 9
			Office = 10
			Retail = 11
			Industrial = 12
			Land = 18*/
			echo "<br>".$listing_category . "<br>";
			if(strpos($listing_category, "9") !== false) {
				update_post_meta( $post_ID->ID, 'property_type_investment', "true" );
			} else { update_post_meta( $post_ID->ID, 'property_type_investment', "false" ); }
			if(strpos($listing_category, "10") !== false) {
				update_post_meta( $post_ID->ID, 'property_type_office', "true" );
			} else { update_post_meta( $post_ID->ID, 'property_type_office', "false" ); }
			if(strpos($listing_category, "11") !== false) {
				update_post_meta( $post_ID->ID, 'property_type_rental', "true" );
			} else { update_post_meta( $post_ID->ID, 'property_type_rental', "false" ); }
			if(strpos($listing_category, "12") !== false) {
				update_post_meta( $post_ID->ID, 'property_type_industrial', "true" );
			} else { update_post_meta( $post_ID->ID, 'property_type_industrial', "false" ); }
			if(strpos($listing_category, "18") !== false) {
				update_post_meta( $post_ID->ID, 'property_type_land', "true" );
			} else { update_post_meta( $post_ID->ID, 'property_type_land', "false" ); }
			
			//$units = fopen("ja0yu_listings_units.csv", "r");
		}
	//}
}

fclose($doc);

}





else if($_GET['type'] == "news") {

/*
SELECT  `id` ,  `title` ,  `catid` ,  `published` ,  `introtext` ,  `fulltext` ,  `created` 
FROM  `ja0yu_k2_items` 
WHERE catid =4
OR  `catid` =14
OR catid =15
LIMIT 0 , 130
*/


$doc = fopen("ja0yu_k2_items.csv", "r");

	while (!feof($doc)) {
		$news = fgetcsv($doc, 1024, ";");
		$legacyId = $news[0];
		$title = $news[1];
		$category = $news[2];
		$published = $news[3];
		$introtext = $news[4];
		$fulltext = $news[5];
		$date = $news[6];
		echo $title . ' ' . $category;
		// 4news=5 
		// 14pressrelease=6 
		// 15recentdeals=7
		$newcat = null;
		if($category==4) { $newcat = 5; }
		else if($category == 14) { $newcat = 6; }
		else if($category == 15) { $newcat = 7; }
		// Create post object
		$my_post = array(
		  'post_title'    => $title,
		  'post_content'  => $fulltext,
		  'post_status'   => 'publish',
		  'post_author'   => 1,
		  'post_category' => array($newcat),
		  'post_date' => $date
		);
		
		if( null == get_page_by_title( $title, OBJECT) ) {
			$post_ID = wp_insert_post( $my_post );
		} else {
			$post_ID = 	get_page_by_title( $title, OBJECT);
		}
		
		// Insert the post into the database
		//wp_insert_post( $my_post );
		echo '<br>'.$legacyId .'<br>';
		echo md5('Image'.$legacyId).'<br>';
		
		//$img_path = 'http://hybridhosting.net/markeim/wp-content/uploads/2014/06/'.md5("Image".$legacy_k2_id)."_XL.jpg";
		$attachment = get_postid_by_title(md5("Image".$legacyId)."_XL", 'attachment');
		set_post_thumbnail( $post_ID, $attachment );
		
		echo "<br><br>----------------------------------------------<br><br>";
		
	}
	fclose($doc);

} 




else if($_GET['type'] == "units") {
	
	$args = array( 
	    'post_type' => 'properties',
	    'posts_per_page' => -1
	);
	
	$custom_query = new WP_Query($args);
	while($custom_query->have_posts()) : $custom_query->the_post();
		$unit_count = 0;
		the_title();
		
		$legacy_id = get_post_meta($post->ID, 'legacy_id', true);
		
		$doc = fopen("ja0yu_listings_units2.csv", "r");
		while (!feof($doc)) {
			$units = fgetcsv($doc, 1024, ";");
			if($legacy_id == $units[1]) {
				 $has1000 = false;
				 $has5000 = false;
				 $has10000 = false;
				 $has25000 = false;
				$name = $units[2];
				$unit_space = $units[3];
				$unit_sale_price = $units[4];
				$unit_lease_price = $units[6];
				echo $name . ' ' . $unit_space . '<br><br>';
				
				delete_post_meta($post->ID, 'property_sale_price');
				delete_post_meta($post->ID, 'property_lease_price');
				if($unit_sale_price != "0.00") {
					add_post_meta($post->ID, 'property_sale_price', $unit_sale_price);
				}
				if($unit_lease_price != "0.00") {
					add_post_meta($post->ID, 'property_lease_price', $unit_lease_price);
				}
				
				delete_post_meta($post->ID, 'unit_active'.$unit_count);
				delete_post_meta($post->ID, 'unit_title'.$unit_count);
				delete_post_meta($post->ID, 'unit_space'.$unit_count);
				add_post_meta($post->ID, 'unit_active'.$unit_count, 'true');
				add_post_meta($post->ID, 'unit_title'.$unit_count, $name);
				add_post_meta($post->ID, 'unit_space'.$unit_count, $unit_space);
				
				if((float)$unit_space >= 1000 && (float)$unit_space <= 5000) { $has1000 = true; }
				if((float)$unit_space >= 5000 && (float)$unit_space <= 10000) { $has5000 = true; }
				if((float)$unit_space >= 10000 && (float)$unit_space <= 25000) { $has10000 = true; }
				if((float)$unit_space >= 25000) { $has25000 = true; }
				
				if($has1000) {
					update_post_meta( $post->ID, 'has1000', 'true' );
				} else {
					//update_post_meta( $post->ID, 'has1000', 'false' );
				}
				
				if($has5000) {
					update_post_meta( $post->ID, 'has5000', 'true' );
				} else {
					//update_post_meta( $post->ID, 'has5000', 'false' );
				}
				
				if($has10000) {
					update_post_meta( $post->ID, 'has10000', 'true' );
				} else {
					//update_post_meta( $post->ID, 'has10000', 'false' );
				}
				
				if($has25000) {
					update_post_meta( $post->ID, 'has25000', 'true' );
				} else {
					//update_post_meta( $post->ID, 'has25000', 'false' );
				}
				
				$unit_count++;
			}
		}
		fclose($doc);
		
		
		echo "<br><br>----------------------------------------------<br><br>";
		
	endwhile; 
	wp_reset_postdata();
	
		
} else if($_GET['type'] == "pdf") {
	
	$args = array( 
	    'post_type' => 'properties',
	    'posts_per_page' => -1
	);
	
	$custom_query = new WP_Query($args);
	while($custom_query->have_posts()) : $custom_query->the_post();
		$unit_count = 0;
		the_title();
		
		$legacy_k2_id = get_post_meta($post->ID, 'legacy_k2_id', true);
		
		$doc = fopen("ja0yu_k2_attachments.csv", "r");
		while (!feof($doc)) {
			$units = fgetcsv($doc, 1024, ";");
			if($legacy_k2_id == $units[1]) {
				update_post_meta( $post->ID, 'file_upload1', 'http://hybridhosting.net/markeim/wp-content/uploads/pdf/'.$units[2] );
				echo $units[2];
			}
		}
		echo "<br><br>"."Image".$legacy_k2_id;
		echo "<br><br>";
		$img_path = 'http://hybridhosting.net/markeim/wp-content/uploads/2014/06/'.md5("Image".$legacy_k2_id)."_XL.jpg";
		//update_post_meta( $post->ID, 'gallery_image1', $img_path );
		//update_post_meta( $post->ID, 'gallery_image1', "" );
		
		echo "<br><br>";	
		
		
		$attachment = get_postid_by_title(md5("Image".$legacy_k2_id)."_XL", 'attachment');
		echo "attachment: ".$attachment;
		echo "<br><br>";
		set_post_thumbnail( $post->ID, $attachment );
		
		fclose($doc);
		
		
		echo "<br><br>----------------------------------------------<br><br>";
		
	endwhile; 
		
}
?>