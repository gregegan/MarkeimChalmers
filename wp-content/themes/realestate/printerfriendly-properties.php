<?php

define( 'WP_USE_THEMES', false );  
require('/home/gregegan/public_html/markeim/wp-blog-header.php'); 

	$property_sale_type_lease = (isset($_POST['property_sale_type_lease'])) ? $_POST['property_sale_type_lease'] : $_GET['property_sale_type_lease'];
	$property_sale_type_sale = (isset($_POST['property_sale_type_sale'])) ? $_POST['property_sale_type_sale'] : $_GET['property_sale_type_sale'];
	$property_type_investment = (isset($_POST['property_type_investment'])) ? $_POST['property_type_investment'] : $_GET['property_type_investment'];
	$property_type_office = (isset($_POST['property_type_office'])) ? $_POST['property_type_office'] : $_GET['property_type_office'];
	$property_type_rental = (isset($_POST['property_type_rental'])) ? $_POST['property_type_rental'] : $_GET['property_type_rental'];
	$property_type_land = (isset($_POST['property_type_land'])) ? $_POST['property_type_land'] : $_GET['property_type_land'];
	$property_type_industrial = (isset($_POST['property_type_industrial'])) ? $_POST['property_type_industrial'] : $_GET['property_type_industrial'];
	$property_type_mixed = (isset($_POST['property_type_mixed'])) ? $_POST['property_type_mixed'] : $_GET['property_type_mixed'];
	$property_space_requirement = (isset($_POST['property_space_requirement'])) ? $_POST['property_space_requirement'] : $_GET['property_space_requirement'];
	
	if(!$property_space_requirement) { $property_space_requirement = array(); }
	
	$isSale = false;
	$isLease = false;
	
	$querystr = "
	SELECT p.*
	FROM $wpdb->posts AS p
	";
	
	if($property_sale_type_lease == "true") {
		$isLease = true;
		$querystr .= "
		LEFT JOIN (
       		SELECT post_id AS ID,
      			meta_value AS property_sale_type_lease
     		FROM wp_postmeta
            WHERE meta_key = 'property_sale_type_lease'
        ) AS a ON p.ID = a.ID
		";
		
		/*$andAppend .= "
   		AND a.property_sale_type_lease = 'true'
   		";*/
   		$andLeaseAppend .= "
   		a.property_sale_type_lease = 'true'
   		";
	}
	
	if($property_sale_type_sale == "true") {
		$isSale = true;
		$querystr .= "
		LEFT JOIN (
       		SELECT post_id AS ID,
      			meta_value AS property_sale_type_sale
     		FROM wp_postmeta
            WHERE meta_key = 'property_sale_type_sale'
        ) AS b ON p.ID = b.ID
		";
		
		/*$andAppend .= "
   		AND b.property_sale_type_sale = 'true'
   		";*/
   		$andSaleAppend .= "
   		b.property_sale_type_sale = 'true'
   		";
	}	
	
	// filter by unit sizes - has1000,has5000,has10000,has25000
	// only do this if filtering by LEASE or BUY&LEASE
	if($property_space_requirement && ($isLease || (!$isLease && !$isSale))) {
		$count = 0;
		foreach($property_space_requirement as $property) {
			if($property != "") {
			$key = 'space'.$property;
			$querystr .= "
			LEFT JOIN (
				SELECT post_id AS ID,
					meta_value AS has".$property."
				FROM wp_postmeta
				WHERE meta_key  = 'has".$property."'
			) AS ".$key." ON p.ID = ".$key.".ID
			";
			
			if($count != 0) { $spaceAppend .= " OR "; }
			$spaceAppend .= "
			".$key.".has".$property." = 'true'
			";
			}
			$count++;
		}
	} else if($property_space_requirement && $isSale) {
		$count = 0;
		foreach($property_space_requirement as $property) {
			$key = 'x'.$property;
			$querystr .= "
			LEFT JOIN (
	       		SELECT post_id AS ID,
	      			meta_value AS property_available_space
	     		FROM wp_postmeta
	            WHERE meta_key = 'property_available_space'
	        ) AS ".$key." ON p.ID = ".$key.".ID
			";
			
			if($count != 0) { $spaceAppend .= " OR "; }
			if($property == '1000') {
				$spaceAppend .= " (".$key.".property_available_space >= 1000 AND ".$key.".property_available_space < 5000) ";
			} else if($property == '5000') {
				$spaceAppend .= " (".$key.".property_available_space >= 5000 AND ".$key.".property_available_space < 10000) ";
			} else if($property == '10000') {
				$spaceAppend .= " (".$key.".property_available_space >= 10000 AND ".$key.".property_available_space < 25000) ";
			} else if($property == '25000') {
				$spaceAppend .= " (".$key.".property_available_space >= 25000) ";
			}

			$count++;
		}
	}
	
	$property_types = array(
		'property_type_office', 
		'property_type_rental', 
		'property_type_industrial', 
		'property_type_mixed', 
		'property_type_investment'
	);
	
	$count = 0;
	foreach($property_types as $property_type) {
		if(isset($property_type) && ${$property_type} == "true") {
			$querystr .= "
			LEFT JOIN (
				SELECT post_id AS ID,
					meta_value AS ".$property_type."
				FROM wp_postmeta
				WHERE meta_key = '".$property_type."'
			) AS ".$property_type." ON p.ID = ".$property_type.".ID
			";
			
			if($count != 0) { $typeAppend .= " OR "; }
			$typeAppend .= "
			".$property_type.".".$property_type." = 'true'
			";
			$count++;
		}
	}
	
	// special LAND query - show all land properties
	if(isset($property_type_land) && $property_type_land == "true") {
		$querystr .= "
		LEFT JOIN (
			SELECT post_id AS ID,
				meta_value AS property_type_land
			FROM wp_postmeta
			WHERE meta_key = 'property_type_land'
		) AS property_type_land ON p.ID = property_type_land.ID
		";
		
		$landAppend .= " property_type_land.property_type_land = 'true'";
	}
	
	$querystr .= "
		LEFT JOIN (
       		SELECT post_id AS ID,
      			meta_value AS property_sort
     		FROM wp_postmeta
            WHERE meta_key  = 'property_sort'
        ) AS c ON p.ID = c.ID
		";
	
	$querystr .= "
	WHERE p.post_status = 'publish'
   	AND p.post_date < NOW()
	AND post_type = 'properties'
   	";
   	
   	if($andSaleAppend != "" && $andLeaseAppend != "") {
   		$querystr .= "AND (" . $andSaleAppend . " OR " . $andLeaseAppend . ")";	
   	} else if($andSaleAppend != "") {
   		$querystr .= "AND " . $andSaleAppend;
   	} else if($andLeaseAppend != "") {
   		$querystr .= "AND " . $andLeaseAppend;
   	}
   	//$querystr .= $andAppend;

	if($querystr != "") {
		if(sizeof($property_space_requirement) > 0 && $property_space_requirement[0] != "") {
	   		$querystr .= " AND (";	
	   	}
	   	$querystr .= $spaceAppend;
		if(sizeof($property_space_requirement) > 0 && $property_space_requirement[0] != "") {
	   		$querystr .= ")";	
	   	}
	}
   	
   	if($typeAppend != "") {
	   	$querystr .= " AND (";	
	   	$querystr .= $typeAppend;
		$querystr .= ")";
   	}
   	
   	if(isset($landAppend)) {
   		if($typeAppend != "") {
   			$querystr .= " OR " . $landAppend;	
   		} else {
   			$querystr .= " AND " . $landAppend;	
   		}
   	}
	
	$querystr .= "
	ORDER BY c.property_sort ASC, p.post_date DESC
	";
 	
 	if(isset($_GET['debug'])) {
 		echo $querystr;
 	}

 	$pageposts = $wpdb->get_results($querystr, OBJECT);
?>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/vio.css" type="text/css">
<div>
	<a href="#" style="font-color:red;" onClick="window.print();return false">[Click Here to Print]</a>
</div>

<div style="margin:10px;">
	<img src="http://hybridhosting.net/markeim/wp-content/themes/realestate/images/logo_03.png" class="logo">
</div>
<br>
	<div class="slide" style="clear: both;float: left;margin-top: 20px;">
			<div class="prop">
				<div class="spend">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="spare">
				 		<tr class="heading"> 
				   			<td class="ks">&nbsp;</td>
				   			<td class="pare6">Address </td>
				   			<td class="pare">SF Available </br> (+/-)</td>
				   			<td class="pare5">Asking </br> Price</td>
				   			<td class="pare">Comments</td>
				   			<td class="pare">Brokers</td>
				 		</tr>
				 	</table>
			
					<div class="flare">
						<table id="properties" width="100%" border="0" cellspacing="0" cellpadding="0" class="cms">
	
							<?php 
								if ($pageposts): 
									global $post; 
						 			foreach ($pageposts as $post): 
						 				setup_postdata($post); 
						 				$custom = get_post_custom(get_the_ID());
						 				//print_r($custom);
						 	?>
 <tr class="action" >
   	<td width="260" class="gear">
   		<h3 class="red_head" id="red_head"><?php echo $custom['property_head'][0]; ?></h3>
   		<a href="<?php the_permalink() ?>"><?php echo get_the_post_thumbnail($page->ID, 'medium'); ?></a>
   	</td>
   	<td width="140" class="bnt">
		<a class="title" href="<?php the_permalink() ?>">
			<?php the_title(); ?>
		</a>
		<p>
			<span class="propertyaddress"><?php echo $custom['property_address'][0]; ?></span></br>
			<span class="city"><?php if($custom['property_city'][0] != "") { echo $custom['property_city'][0];  ?>, <?php } ?>
			<?php echo $custom['property_state'][0]; ?></span></br>
			<?php if($custom['file_upload1'][0] != "") { ?>
			<a href="<?php echo $custom['file_upload1'][0]; ?>" class="lebel"> 
				Download </br>Brochure
			</a>
			<?php } ?>
		</p>
	</td>
   	<td class="bnt1">
   		<?php if($custom['property_acreage'][0] != "") {?> <p><?php echo $custom['property_acreage'][0] ?> Acres <br></p><?php } ?>
   		<?php if($custom['property_available_space'][0] != "") {?> <p><?php echo $custom['property_available_space'][0] ?> Sq. Ft. <br></p><?php } ?>
		<?php
		$count = 0;
		for($x = 0; $x < 20; $x++) {
			$unit_space = $custom['unit_space'.$x][0];
			$unit_active = $custom['unit_active'.$x][0];
			if(/*(in_array($unit_space, $property_space_requirement) || count($property_space_requirement) == 0) && */$unit_active == "true") {
				?>
					<p>
						<b>
							<?php 								
							if($custom['unit_title'.$x][0] != "DEFAULT_UNIT" && $custom['unit_title'.$x][0] != "DEFAULT UNIT" && $custom['unit_title'.$x][0] != "default unit" && $custom['unit_title'.$x][0] != "") { 
								echo $custom['unit_title'.$x][0]; 
							} else { 
								//echo $custom['property_address'][0]; 
							} 
							?>
						</b>
							
						</br>
					<?php if($unit_space != "") { echo $unit_space;  ?> Sq. Ft. <?php } ?>
					</p>
				<?php
				/*if($count == 3) { break; }
				$count++;*/
			}
		}
		?>&nbsp;
	</td>
   	<td class="bnt2">
	   	<?php
	   	if($custom['property_sale_type_sale'][0] == "true" && $custom['property_sale_price'][0] != "0.00" && $custom['property_sale_price'][0] != "") {
	   	?>
		   	<p>
		   		<b>Sale:</b></br> 
		   		<?php
	            if($custom['property_sale_price'][0] != '') { ?>
	            	$<?php echo number_format($custom['property_sale_price'][0], 2, '.', ','); 
	        	}
	           	echo $custom['property_sale_text'][0]; 
	           	?>
		   	</p>
	   	<?
	   	}
	   	if($custom['property_sale_type_lease'][0] == "true" && $custom['property_lease_price'][0] != "0.00" && $custom['property_lease_price'][0] != "") {
	   	?>
		   	<p>
		   		<b>Lease:</b></br> 
		   		<?php
	          	if($custom['property_lease_price'][0] != '') { ?>
	            	$<?php echo number_format($custom['property_lease_price'][0], 2, '.', ','); 
	            }
	        	echo $custom['property_lease_text'][0]; 
	        	?>
		   	</p>
	   	<?php
	   	}
	   	?>&nbsp;
   	</td>
   	<td width="180" class="bnt3">
		<?php
		echo $custom['property_comments'][0];
		?>
   	</td>
   	<td class="bnt5">
   		<?php
   			$brokers = $custom['broker_ids'][0];
   			$broker_ids = explode( ',', $brokers);
   			$query = new WP_Query( 
   				array( 
   					'post_type' => 'brokers', 
   					'post__in' => $broker_ids
   				)
   			);
   			while($query->have_posts()) {
				$query->the_post();
				$broker = get_post_custom(get_the_ID());
				?>
				<p>
					<a href="<?php echo the_permalink(); ?>">
						<?php echo get_the_title(); ?>
					</a></br><?php echo $broker['broker_phone'][0]; ?>
				</p>
				<?php
			}
   		?>
	</td>
</tr>
						 
						  		<?php endforeach; ?>
							<?php else : ?>
						    	<h2 class="center">Not Found</h2>
						    	<p class="center">Sorry, no properties available.</p>
							<?php endif; ?>
 
						</table>
					</div>
			 	</div>
			</div>
			<!--<img src="<?php echo get_template_directory_uri(); ?>/images/numbering_15.png" class="num" />-->
		</div>
	</div>