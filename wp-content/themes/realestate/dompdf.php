<?php
require_once("lib/dompdf/dompdf_config.inc.php");
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

$html .= '<table id="properties" width="100%" border="0" cellspacing="0" cellpadding="0" class="cms">';
	
	if ($pageposts): 
		global $post; 
		foreach ($pageposts as $post): 
			setup_postdata($post); 
			$custom = get_post_custom(get_the_ID());
			
$html .= '<tr class="action" ><td width="260" class="gear"><h3 class="red_head" id="red_head">' . $custom['property_head'][0] . '</h3>';
$html .= '<a href="<?php the_permalink() ?>">' . get_the_post_thumbnail($page->ID, 'medium'). '</a></td>';			
$html .= '<td width="140" class="bnt"><a class="title" href="<?php the_permalink() ?>">' .the_title() .'</a><p><span class="propertyaddress">' . $custom['property_address'][0]. '</span></br></tr>';
			/*<span class="city"><?php if($custom['property_city'][0] != "") { echo $custom['property_city'][0];  ?>, <?php } ?>
			<?php echo $custom['property_state'][0]; ?></span></br>
			<?php if($custom['file_upload1'][0] != "") { ?>
			<a href="<?php echo $custom['file_upload1'][0]; ?>" class="lebel"> 
				Download </br>Brochure
			</a>
			<?php } ?></p></td>*/
			
		endforeach;
	endif;

$html .= '</table>';

$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("sample.pdf");

?>