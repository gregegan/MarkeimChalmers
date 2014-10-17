<?php

get_header();

$custom = get_post_custom(get_the_ID());

$post_title = get_the_title();
$post_permalink = get_permalink(get_the_ID());
?>


<div class="middle">
  
  <div class="road">
    
    <div class="slide">
      
      <div class="feature">
        
        <h3>
          <?php the_title(); ?>
        </h3>
        
      </div>
      
      <div class="squre">
        
        <div class="building">
      
      <ul>
      	<li>
      		<div style="font-weight:bold;">
	            <div id="property_address"><?php echo $custom['property_address'][0]; ?></div> 
	        	<?php if($custom['property_city'][0] != "") {?><div style="display:inline-block;" id="property_city"><?php echo $custom['property_city'][0]; ?></div>,<?php } ?> 
	        	<div style="display:inline-block;" id="property_state"><?php echo $custom['property_state'][0]; ?></div>
	      		<div id="property_zip"><?php echo $custom['property_zip'][0]; ?></div>
			</div>
      	</li>
      	<?php 
      	if($custom['property_type_investment'][0] == 'true' || $custom['property_type_land'][0] == 'true' || $custom['property_type_office'][0] == 'true' || $custom['property_type_rental'][0] == 'true' || $custom['property_type_industrial'][0] == 'true' || $custom['property_type_mixed'][0] == 'true') {
      	?>
      	<li>
      		Property Type:
      		<span>
      			<?php if($custom['property_type_investment'][0] == 'true') { echo 'Investment<br>'; } ?>
      			<?php if($custom['property_type_land'][0] == 'true') { echo 'Land<br>'; } ?>
      			<?php if($custom['property_type_office'][0] == 'true') { echo 'Office<br>'; } ?>
      			<?php if($custom['property_type_rental'][0] == 'true') { echo 'Retail<br>'; } ?>
      			<?php if($custom['property_type_industrial'][0] == 'true') { echo 'Industrial<br>'; } ?>
      			<?php if($custom['property_type_mixed'][0] == 'true') { echo 'Mixed<br>'; } ?>
      		</span>
      	</li>
      	<?php } ?>
        <?php 
        if($custom['property_sale_price'][0] != '' || $custom['property_sale_text'][0] != '') { ?>
        <li>
          Sale Price:	
          <span>
            <?php
            if($custom['property_sale_price'][0] != '') { ?>
            	$<?php echo number_format($custom['property_sale_price'][0], 2, '.', ''); 
        	}
           	echo $custom['property_sale_text'][0]; 
           	?>
          </span>
        </li>
        <?php }
        if($custom['property_lease_price'][0] != '' || $custom['property_lease_text'][0] != '') { ?>
		<li>
          Lease Price:	
          <span>
          	<?php
          	if($custom['property_lease_price'][0] != '') { ?>
            	$<?php echo number_format($custom['property_lease_price'][0], 2, '.', ''); 
            }
        	echo $custom['property_lease_text'][0]; 
        	?>
          </span>
        </li>  
        <?php }
        if($custom['property_cap_rate'][0] != '') { ?>
        <li>
          Cap Rate: 
          <span>
       		<?php echo $custom['property_cap_rate'][0]; ?>
          </span>
        </li>
        <?php }
        if($custom['property_available_space'][0] != '') { ?>
        <li>
          Building Size: 
          <span>
            <?php echo $custom['property_available_space'][0]; ?> Sq. Ft.
          </span>
        </li>
        <?php }
        if($custom['property_acreage'][0] != '') { ?>
        <li>
          Lot Size: 
          <span>
           	<?php echo $custom['property_acreage'][0]; ?> Acres
          </span>
        </li>
        <?php }
        if($custom['property_year_built'][0] != '') { ?>
        <li>
          Year Built: 
          <span>
            <?php echo $custom['property_year_built'][0]; ?>
          </span>
        </li>
        <?php }
        if($custom['property_status'][0] != '') { ?>
        <li>
          Status: 
          <span>
            <?php echo $custom['property_status'][0]; ?>
          </span>
          
        </li>
        <?php } 
        if($custom['property_zoning'][0] != '') { ?>
        <li>
          Zoning: 
          <span>
            <?php echo $custom['property_zoning'][0]; ?>
          </span>
          
        </li>
        <?php } 
        if($custom['property_retaxes'][0] != '') { ?>
        <li>
          RE Taxes: 
          <span>
            <?php echo $custom['property_retaxes'][0]; ?>
          </span>
          
        </li>
        <?php } ?>
        <?php
		if($custom['property_sale_type_sale'][0] == true || $custom['property_sale_type_sale'][0] == "true") {
	        $hasUnits = false;
	        for($x = 0; $x < 20; $x++) {
				$unit_active = $custom['unit_active'.$x][0];
				if($unit_active == "true") {
					$hasUnits = true;
					break;
				}
	        }
	        if($hasUnits == true) {
	        ?>
		    	 <li class="units">
		    	 	Spaces Available:
		    	 	<span>
				        <?php 
						for($x = 0; $x < 20; $x++) {
							$unit_space = $custom['unit_space'.$x][0];
							$unit_active = $custom['unit_active'.$x][0];
							if($unit_active == "true") {
								if($x > 0) { echo '</br></br>'; }
							?>
								
									
										<?php 
										if($custom['unit_title'.$x][0] != "DEFAULT_UNIT" && $custom['unit_title'.$x][0] != "DEFAULT UNIT" && $custom['unit_title'.$x][0] != "default unit" && $custom['unit_title'.$x][0] != "") { 
											echo '<b>'.$custom['unit_title'.$x][0].'</b></br>'; 
										} else { 
											//echo $custom['property_address'][0]; 
										} 
										?>
									
								<?php if($unit_space != "") { echo $unit_space;  ?> Sq. Ft. <?php } ?>
								
							<?php
							}
						}
						?>
					</span>
				</li>
		        <?php 
	        }
		}
        ?>
      </ul>
      
    </div>
    
    
    <div class="touch">
      <?php 
      	$thumb_id = get_post_thumbnail_id();
		$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumbnail-size', true);
		$thumb_url = $thumb_url_array[0];
		?>
      <a href="<?php echo $thumb_url; ?>"><img src="<?php echo $thumb_url; ?>" class="six" /></a>
      	
      
      <div class="pagi">
        <?php if($custom['gallery_image2'][0] != "") { ?>
        <a href="<?php echo $custom['gallery_image2'][0]; ?>"><img src="<?php echo $custom['gallery_image2'][0]; ?>" class="look" /></a>
        <?php } 
        if($custom['gallery_image3'][0] != "") { ?>
        <a href="<?php echo $custom['gallery_image3'][0]; ?>"><img src="<?php echo $custom['gallery_image3'][0]; ?>" class="look" /></a>
        <?php } 
        if($custom['gallery_image4'][0] != "") { ?>
        <a href="<?php echo $custom['gallery_image4'][0]; ?>"><img src="<?php echo $custom['gallery_image4'][0]; ?>" class="look1" /></a>
        <?php } ?>
        
      </div>
      
    </div>
    
  </div>
  
  <div class="clear">
  </div>
  
  <div class="fix">
  	<?php if($custom['file_upload1'][0] != "") { ?>
    <a target="_blank" href="<?php echo $custom['file_upload1'][0]; ?>">
      Marketing package
    </a>
    <?php } ?>
  </div>
  
  <div class="high">
  	   <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <?php the_content(); ?>
		<?php endwhile; endif; ?>
  </div>
  
  <div class="load">
    
    <?php if($custom['file_upload2'][0] != "") { ?>
    <p>
      Download attachments:
    </p>
    <a href="<?php echo $custom['file_upload2'][0]; ?>">
      Fact Sheet
    </a>
    <?php } ?>
    
  </div>
  
  <div class="map">
    
    <h5>
      Location
    </h5>
    
    <div id="property_lat" style="display:none;"><?php echo $custom['property_lat'][0]; ?></div>
    <div id="property_long" style="display:none;"><?php echo $custom['property_long'][0]; ?></div>
    <div class="googlemap">
		<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBd2CrZ5PmJ-q7F4GwaBIsOUHX-H6XhkT8&sensor=false"></script>
		<div style="clear:both;margin:15px 0;">
	    	<div style="width:670px; height:400px;margin:0 auto;" id="map-canvas"></div>
	    </div>
    </div>
    
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>properties/" class="return">
      Return to Current Listings
    </a>
    
  </div>
  
</div>



</div>


<div class="contact">
  
  <div class="property">
    <h3>
      Contact Listing Broker
    </h3>
    
    <div class="number">
    	<?php
   			$brokers = $custom['broker_ids'][0];
   			$broker_ids = explode( ',', $brokers);
   			$query = new WP_Query( 
   				array( 
   					'post_type' => 'brokers', 
   					'post__in' => $broker_ids
   				)
   			);
   			$brokeremails = array();
   			while($query->have_posts()) {
				$query->the_post();
				$broker = get_post_custom(get_the_ID());
				$brokeremails[] = $broker['broker_email'][0];
				?>
				<p>
					<a href="<?php echo the_permalink(); ?>">
						<?php echo get_the_title(); ?>
					</a> | <?php echo $broker['broker_phone'][0]; ?>
				</p>
				<?php
			}
   		?>
      

      
    </div>
    
    
    <div class="farm">
<?php 
if ( isset($_POST) && wp_verify_nonce($_POST['broker_contact_form'],'my_nonce_action') ) {
	echo 'Form Successfully Submitted';
	$data = (object)  array(
   		'title' => $_POST['form_title'],
    	'posted_data' => array(
    		'property_id' => $_POST['property_id'],
    		'property_name' => $_POST['property_name'],
    		'name' => $_POST['yourname'],
    		'email' => $_POST['youremail'],
    		'message' => $_POST['yourmessage'],
    		'brokeremails' => $_POST['brokeremails']
    	)
    );
    
    require_once(ABSPATH . 'wp-content/plugins/contact-form-7-to-database-extension/CFDBFormIterator.php');
	$plugin = new CF7DBPlugin();
	$plugin->saveFormData($data);
    
} else {


?>
	<form action="" method="POST">
		<?php wp_nonce_field( 'my_nonce_action', 'broker_contact_form' ); ?>
		<input type="hidden" name="form_title" value="brokercontact" />
      	<input type="hidden" name="brokeremails" value="<?php echo implode($brokeremails, ','); ?>">
      	<input type="hidden" name="property_id" value="<?php echo get_the_ID(); ?>">
      	<input type="hidden" name="property_name" value="<?php echo get_the_title(); ?>">
      <p>
        Your Name 
        <span>
          *
        </span>
        
      </br>
    
    <input type="text" id="yourname" name="yourname" class="ink" />
  </p>
  
  <p>
    Your E-mail 
    <span>
      *
    </span>
    
  </br>

<input type="text" id="youremail" name="youremail" class="ink" />
</p>

<p>
  Your Message
  <span>
    *
  </span>
  
</br>

<textarea cols="" rows="" id="yourmessage" name="yourmessage" class="lab">
</textarea>
</p>

<p>
  <input type="submit" class="sending" value="Contact Broker" />
</p>
</form>
<?php } ?>

<div class="social">
	
	<a href="mailto:?subject=<?php echo urlencode($post_title); ?>&body=<?php echo urlencode($post_permalink); ?>">Send to Friend</a>
	<br><br>
  
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style">
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
<a class="addthis_button_tweet"></a>
<a class="addthis_button_linkedin_counter"></a>
</div>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-52f050ea3562623f"></script>
<!-- AddThis Button END -->
  
</div>



</div>
</div>

<?php

	$querystr = "
	SELECT p.*
	FROM $wpdb->posts AS p
	";
	
	$querystr .= "
	LEFT JOIN (
   		SELECT post_id AS ID,
  			meta_value AS property_city
 		FROM wp_postmeta
        WHERE meta_key  = 'property_city'
    ) AS b ON p.ID = b.ID
	";
	
	$andAppend .= "
   	AND b.property_city = '" . $custom['property_city'][0] . "'
   	";
   	
   	$querystr .= "
	WHERE p.post_status = 'publish'
   	AND p.post_date < NOW()
	AND post_type = 'properties'
   	";
   	
   	$querystr .= $andAppend;
	
	$querystr .= "
	ORDER BY p.post_date DESC
	LIMIT 3
	";
	
	//echo $querystr;
	
	$pageposts = $wpdb->get_results($querystr, OBJECT);
	if ($pageposts): 
	global $post; 

?>

<div class="property">
  
  <h6>
    Other available properties 
    <?php if($custom['property_city'][0] != "") { ?>in <?php echo $custom['property_city'][0]; ?><?php } ?>
  </h6>
  
  <?php 
   	foreach ($pageposts as $post): 
 		setup_postdata($post); 
 		$custom2 = get_post_custom(get_the_ID());
  ?>
  
  <div class="widget">
    
    <a href="<?php the_permalink() ?>"><?php echo get_the_post_thumbnail($page->ID, 'medium'); ?></a>
    
    <p>
      <?php the_title(); ?>
    </p>
    
    <a href="<?php the_permalink() ?>">
      Read More
    </a>
    
    
  </div>
  <?php endforeach; ?>
  <?php endif; ?>
  

  
</div>
</div>


</div>



<script>
	(function($) {
		var $lat = $("#property_lat").text(),
			$long = $("#property_long").text(),
			$address = $("#property_address").text(),
			$city = $("#property_city").text(),
			$state = $("#property_state").text(),
			$zip = $("#property_zip").text();
		
		var address = '' + $address + ' ' + $city + ', ' + $state + ' ' + $zip;
		
		$.ajax({
		  url: "http://maps.googleapis.com/maps/api/geocode/json?address="+address+"&sensor=false",
		  type: "POST",
		  success: function(res){
		     gMapsInitialize();
		  }
		});
	
		var map;
		function gMapsInitialize() {
			var myLatlng = new google.maps.LatLng($lat,$long),
				mapOptions = {
					zoom: 9,
	    			center: myLatlng
	  			},
	  			map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions),
	  			infowindow = new google.maps.InfoWindow({});
	
	  		var marker = new google.maps.Marker({
	      		position: myLatlng,
	      		map: map
			});
	  		google.maps.event.addListener(marker, 'click', function() {
	    		infowindow.open(map,marker);
			});
		}
		
		google.maps.event.addDomListener(window, 'load', gMapsInitialize);
	})(jQuery);
</script>
<?php get_footer(); ?>