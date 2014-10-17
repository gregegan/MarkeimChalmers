(function($){
	
	var $property_sale_type_lease = $("#property_sale_type_lease"),
		$property_sale_type_sale = $("#property_sale_type_sale"),
		$propertyleaseprice = $("#property-lease-price"),
		$propertysaleprice = $("#property-sale-price");
	
	if($property_sale_type_lease.attr("checked") !== "checked") {
		$propertyleaseprice.toggle();	
	}
	
	if($property_sale_type_sale.attr("checked") !== "checked") {
		$propertysaleprice.toggle();	
	}
		
	$property_sale_type_lease.on("change", function() {
		$propertyleaseprice.toggle();
	});
	
	$property_sale_type_sale.on("change", function() {
		$propertysaleprice.toggle();
	});
	
	(function() {
		var $lat = $("#property_lat"),
			$long = $("#property_long");
				
		$("#getGeo").on('click', function() {
			var $address = $("#property_address").val(),
				$city = $("#property_city").val(),
				$state = $("#property_state").val(),
				$zip = $("#property_zip").val();
			
			var address = '' + $address + ' ' + $city + ', ' + $state + ' ' + $zip;
			$.ajax({
			  url: "http://maps.googleapis.com/maps/api/geocode/json?address="+address+"&sensor=false",
			  type: "POST",
			  success: function(res){
			     $lat.val(res.results[0].geometry.location.lat);
			     $long.val(res.results[0].geometry.location.lng);
			     gMapsInitialize();
			  }
			});
		});
	
		var map;
		function gMapsInitialize() {
			var myLatlng = new google.maps.LatLng($lat.val(),$long.val()),
				mapOptions = {
					zoom: 9,
	    			center: myLatlng
	  			},
	  			map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions),
	  			infowindow = new google.maps.InfoWindow({});
	
	  		var marker = new google.maps.Marker({
	      		position: myLatlng,
	      		map: map,
	    		title: $("#title").val()
			});
	  		google.maps.event.addListener(marker, 'click', function() {
	    		infowindow.open(map,marker);
			});
		}
		
		google.maps.event.addDomListener(window, 'load', gMapsInitialize);
	})();
	
	$('.upload_image_button').click(function(e) {
		var $this = $(this),
			custom_uploader;
		e.preventDefault();
		
		//If the uploader object has already been created, reopen the dialog
		if (custom_uploader) {
			custom_uploader.open();
			return;
		}

		//Extend the wp.media object
		custom_uploader = wp.media.frames.file_frame = wp.media({
			title: 'Choose Image',
			button: {
				text: 'Choose Image'
			},
			multiple: false
		});

		//When a file is selected, grab the URL and set it as the text field's value
		custom_uploader.on('select', function() {
			attachment = custom_uploader.state().get('selection').first().toJSON();
			$this.parent().find('.upload_image_field').val(attachment.url);
		});

		//Open the uploader dialog
		custom_uploader.open();
	});

})(jQuery);