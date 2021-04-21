jQuery(function($){
if ($("#visits-google-map").length) {
	var geocoder = new google.maps.Geocoder();
	var address = document.getElementById('estate__street').value;
	geocoder.geocode( { 'address': address}, function(results, status) {
		if (status == 'OK') {
			map.setCenter(results[0].geometry.location);
			var marker = new google.maps.Marker({
				map: map,
				position: results[0].geometry.location,
				draggable: true
			});
		} else {
			alert('Geocode was not successful for the following reason: ' + status);
		}
	});

	initialize();
	var latlng, map, marker, address,
		div = $('#coordinates');

	function initialize(){
		latlng = new google.maps.LatLng(0.0000, 0.0000);
		map = new google.maps.Map(document.getElementById('map'), {
			center: latlng,
			zoom: 8,
			mapTypeControl:false,
			streetViewControl:false,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});
		google.maps.event.addListener(map, 'click', function(event) {
			placeMarker(event.latLng);
		});

	}

	function placeMarker(location) {
		if ( marker ) {
			marker.setPosition(location);
		} else {
			marker = new google.maps.Marker({
				position: location,
				map: map,
				title: 'Set lat/lon values for this property',
				draggable: true
			});
			google.maps.event.addListener(marker, 'dragend', function(a) {
				div.html(a.latLng.lat().toFixed(4) + ', ' + a.latLng.lng().toFixed(4)).clone().appendTo('body');
			});
		}
		
		geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				address = results[0]['formatted_address'];
			}
		});
		$("#estate___street").val(address);
		div.html(location.lat().toFixed(4) + ', ' + location.lng().toFixed(4)).clone().appendTo('body');
	}

	$("[data-save-coordinates]").on('click', function(){
		$("#coordinates_x").empty();
		$("#coordinate_y").empty();
		var coor = $("#coordinates").text();
		var coor = coor.split(', ');
		$("#coordinate_x").val(coor[0]);
		$("#coordinate_y").val(coor[1]);
		$("#save__coordinate_x").val(coor[0]);
		$("#save__coordinate_y").val(coor[1]);
	})
}
});