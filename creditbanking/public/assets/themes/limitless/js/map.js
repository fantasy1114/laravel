$(document).ready(function () {

	setTimeout(function(){
		initialize();
	}, 500);
	
	$('.click-tabs').on('click', function(){
		setTimeout(function(){initialize();}, 500);
	});	
});

var marker = [], infowindow = [], map, image = '', waypoints = [], geolocate;

function addMarker(location, name, loan_name, loan_id, amount){
	marker[name] = new google.maps.Marker({
		position: location,
		map: map,
		// icon: image
	});
	marker[name].setMap(map);

	infowindow[name] = new google.maps.InfoWindow({
		content:				
			'<h4>Nombre cliente: ' + loan_name + '</h4>' +
			'<h5>ID Prestamo: ' + loan_id + '</h5>' + 
			'<h5>Amount: ' + amount + '</h5>'
	});
	
	google.maps.event.addListener(marker[name], 'click', function() {
		infowindow[name].open(map,marker[name]);
	});
}

function initialize() {

	var lat = $('#map-canvas').attr("data-lat");
	var lng = $('#map-canvas').attr("data-lng");
	var mapStyle = $('#map-canvas').attr("data-style");
	var setZoom = parseInt($('#map-canvas').attr("data-zoom"));

	var latlng = new google.maps.LatLng(parseFloat(lat), parseFloat(lng));

	var styles = "";

	if (mapStyle=="1"){
		styles = [{"featureType":"all","elementType":"all","stylers":[{"hue":"#0081ff"},{"visibility":"on"},{"lightness":"-65"},{"saturation":"75"}]},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":25},{"visibility":"simplified"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]}];
		image = '/assets/dist/imgs/marker.png';
	}
	if(mapStyle=="2"){
		styles = [{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"on"},{"lightness":33}]},{"featureType":"administrative","elementType":"labels","stylers":[{"saturation":"-100"}]},{"featureType":"administrative","elementType":"labels.text","stylers":[{"gamma":"0.75"}]},{"featureType":"administrative.neighborhood","elementType":"labels.text.fill","stylers":[{"lightness":"-37"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#f9f9f9"}]},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"saturation":"-100"},{"lightness":"40"},{"visibility":"off"}]},{"featureType":"landscape.natural","elementType":"labels.text.fill","stylers":[{"saturation":"-100"},{"lightness":"-37"}]},{"featureType":"landscape.natural","elementType":"labels.text.stroke","stylers":[{"saturation":"-100"},{"lightness":"100"},{"weight":"2"}]},{"featureType":"landscape.natural","elementType":"labels.icon","stylers":[{"saturation":"-100"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"saturation":"-100"},{"lightness":"80"}]},{"featureType":"poi","elementType":"labels","stylers":[{"saturation":"-100"},{"lightness":"0"}]},{"featureType":"poi.attraction","elementType":"geometry","stylers":[{"lightness":"-4"},{"saturation":"-100"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#c5dac6"},{"visibility":"on"},{"saturation":"-95"},{"lightness":"62"}]},{"featureType":"poi.park","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":20}]},{"featureType":"road","elementType":"all","stylers":[{"lightness":20}]},{"featureType":"road","elementType":"labels","stylers":[{"saturation":"-100"},{"gamma":"1.00"}]},{"featureType":"road","elementType":"labels.text","stylers":[{"gamma":"0.50"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"saturation":"-100"},{"gamma":"0.50"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#c5c6c6"},{"saturation":"-100"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"lightness":"-13"}]},{"featureType":"road.highway","elementType":"labels.icon","stylers":[{"lightness":"0"},{"gamma":"1.09"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#e4d7c6"},{"saturation":"-100"},{"lightness":"47"}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"lightness":"-12"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"saturation":"-100"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#fbfaf7"},{"lightness":"77"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"lightness":"-5"},{"saturation":"-100"}]},{"featureType":"road.local","elementType":"geometry.stroke","stylers":[{"saturation":"-100"},{"lightness":"-15"}]},{"featureType":"transit.station.airport","elementType":"geometry","stylers":[{"lightness":"47"},{"saturation":"-100"}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"on"},{"color":"#acbcc9"}]},{"featureType":"water","elementType":"geometry","stylers":[{"saturation":"53"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"lightness":"-42"},{"saturation":"17"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"lightness":"61"}]}];
		image = '/assets/dist/imgs/marker_2.png';
	}
	if(mapStyle=="3"){
		styles = [{"featureType":"all","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry","stylers":[{"weight":"0.5"},{"visibility":"on"}]},{"featureType":"administrative","elementType":"labels","stylers":[{"visibility":"simplified"}]},{"featureType":"administrative","elementType":"labels.text","stylers":[{"lightness":"-50"},{"saturation":"-50"}]},{"featureType":"administrative.neighborhood","elementType":"labels.text","stylers":[{"hue":"#009aff"},{"saturation":"25"},{"lightness":"0"},{"visibility":"simplified"},{"gamma":"1"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"saturation":"0"},{"lightness":"100"},{"gamma":"2.31"},{"visibility":"on"}]},{"featureType":"landscape","elementType":"labels","stylers":[{"visibility":"simplified"},{"lightness":"20"},{"gamma":"1"}]},{"featureType":"landscape","elementType":"labels.text.fill","stylers":[{"saturation":"-100"},{"lightness":"-100"}]},{"featureType":"landscape","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"landscape.man_made","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"lightness":"0"},{"saturation":"45"},{"gamma":"4.24"},{"visibility":"simplified"},{"hue":"#00ff90"}]},{"featureType":"poi.park","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"road","elementType":"geometry","stylers":[{"saturation":"-100"},{"color":"#f5f5f5"}]},{"featureType":"road","elementType":"labels.text","stylers":[{"visibility":"simplified"},{"color":"#666666"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"labels.icon","stylers":[{"saturation":"-25"}]},{"featureType":"transit.line","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"transit.station.airport","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"lightness":"50"},{"gamma":".75"},{"saturation":"100"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"simplified"}]},{"featureType":"water","elementType":"labels.icon","stylers":[{"visibility":"off"}]}];
		image = '/assets/dist/imgs/marker_3.png';
	}
	if(mapStyle=="4"){
		styles = [{"featureType":"all","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]}];
		image = '/assets/dist/imgs/marker_4.png';
	}
	if(mapStyle=="5"){
		styles = [{"featureType":"all","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]}];
		image = '/assets/dist/imgs/marker_5.png';
	}
	if(mapStyle=="6"){
		styles = [{"featureType":"all","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"on"}]}];
		image = '/assets/dist/imgs/marker_4.png';
	}

	var styledMap = new google.maps.StyledMapType(styles,{name: "Styled Map"});

	var mapOptions = {
		zoom: setZoom,
		
		panControl: false,
		panControlOptions: {
			position: google.maps.ControlPosition.LEFT_BOTTOM
		},
		zoomControl: true,
		zoomControlOptions: {
			style: google.maps.ZoomControlStyle.LARGE,
			position: google.maps.ControlPosition.LEFT_BOTTOM
		},
		streetViewControl: true,
		streetViewControlOptions: {
			position: google.maps.ControlPosition.LEFT_BOTTOM
		},
		scrollwheel: false,
		center: latlng,
		mapTypeControlOptions: {
			mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
		}
	};
	map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);	
	map.mapTypes.set('map_style', styledMap);
	map.setMapTypeId('map_style');

	var marker = new google.maps.Marker({position: latlng, map: map, icon: '/software/credidata/public/assets/dist/imgs/marker_2.png'});

	// navigator.geolocation.getCurrentPosition(function(position) {
	// 	geolocate = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);		
	// 	// var infowindow = new google.maps.InfoWindow({
	// 	// 		map: map,
	// 	// 		position: geolocate,
	// 	// 		content:
	// 	// 				'<h1>Location pinned from HTML5 Geolocation!</h1>' +
	// 	// 				'<h2>Latitude: ' + position.coords.latitude + '</h2>' +
	// 	// 				'<h2>Longitude: ' + position.coords.longitude + '</h2>'
	// 	// });
	// 	map.setCenter(geolocate);
	// 	var marker = new google.maps.Marker({position: geolocate, map: map});
	// 	waypoints.push({
	// 		location: geolocate,
	// 		stopover: true
	// 	});
	// });	

	var directionsService = new google.maps.DirectionsService();
    var directionsDisplay = new google.maps.DirectionsRenderer();
	directionsDisplay.setMap(map);		

	var index = 0;
	$('.addresses-block a').each(function(){
		var mark_lat = $(this).attr('data-lat');
		var mark_lng = $(this).attr('data-lng');
		var this_index = $('.addresses-block a').index(this);
		var mark_name = 'template_marker_' + index;
		var mark_locat = new google.maps.LatLng(mark_lat, mark_lng);
		var loan_name = $(this).attr('data-name');
		var loan_id = $(this).attr('data-id');
		var amount = $(this).attr('data-amount');
		amount = parseFloat(amount).toFixed(2);
		if (index === 0) {
			map.setCenter(mark_locat);
		}
		waypoints.push({
			location: mark_locat,
			stopover: true
		});
		addMarker(mark_locat, mark_name, loan_name, loan_id, '$' + amount);
		index++;
	});

	var locationCount = waypoints.length;
	if(locationCount > 0) {
		var start = waypoints[0].location;
		var end = waypoints[locationCount-1].location;
		drawPath(directionsService, directionsDisplay, start, end);
	}
}

function drawPath(directionsService, directionsDisplay, start, end) {
	directionsService.route({
		origin: start,
		destination: end,
		waypoints: waypoints,
		optimizeWaypoints: true,
		travelMode: 'DRIVING'
	}, function(response, status) {
			if (status === 'OK') {
			directionsDisplay.setDirections(response);
			} else {
			window.alert('Problem in showing direction due to ' + status);
			}
	});
}

    	