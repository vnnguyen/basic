<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>MapMaker | Amica Travel IMS</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <style>
html, body {
	font:13px/18px Arial;
  height: 100%;
  margin: 0;
  padding: 0;
}
h4 {padding:0; margin:0 0 8px;}
#map_canvas {
  height: 100%;
}
#legend {
	width:140px;
	background:#fff;
	padding:10px;
	margin:5px;
	border:1px solid #ccc;
	opacity:0.9;
 }
 #legend ul {padding:0; margin:0; list-style:none;}
.r1 {color:#ff0000;}
.r2 {color:#00f;}
.r3 {color:#c0c;}
.r4 {color:#ff0;}
.route-ka {color:#f6451a;}
.route-mh {color:#f5861a;}
.active {font-weight:bold;}
@media print {
  html, body {
    height: auto;
  }

  #map_canvas {
    height: 650px;
  }
}
</style>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=geometry&sensor=false&language=en"></script>
<script>
function mapInit() {
	var mapCenter = new google.maps.LatLng(21.279137,105.150146);
	var styles = [
		{
			"featureType": "administrative.province",
			"stylers": [
				{ "visibility": "off" }
			]
		},{
			"featureType": "administrative.locality",
			"stylers": [
				{ "visibility": "off" }
			]
		},{
			"featureType": "administrative.land_parcel",
			"stylers": [
				{ "visibility": "off" }
			]
		},{
			"featureType": "administrative.neighborhood",
			"stylers": [
				{ "visibility": "off" }
			]
		},{
			"featureType": "poi",
			"stylers": [
				{ "visibility": "off" }
			]
		},{
			"featureType": "road",
			"stylers": [
				{ "visibility": "simplified" }
			]
		},{
			"featureType": "water",
			"stylers": [
				{ "visibility": "simplified" }
			]
		}
	];
	var mapOptions = {
		zoom:9,
		minZoom:6,
		maxZoom:12,
		center: mapCenter,
		zoomControl:true,
		scaleControl:true,
		panControl:true,
		mapTypeControl:false,
		streetViewControl:false,
		overviewMapControl:true,
		mapTypeId:google.maps.MapTypeId.TERRAIN,
		styles: styles
	}

	var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
	map.controls[google.maps.ControlPosition.RIGHT_TOP].push(document.getElementById('legend'));
	
	var marker = new google.maps.Marker({
		position: mapCenter,
		map: map,
		title:'Center of map',
		draggable:true,
    animation: google.maps.Animation.DROP,
		icon: {
			path: google.maps.SymbolPath.CIRCLE,
			strokeColor:'#f60',
			strokeWeight:4,
			scale: 10
		}
	});
	
	var infowindow = new google.maps.InfoWindow({
		maxWidth:230
	});

  google.maps.event.addListener(marker, 'click', function(event) {
		$('#iw-r1').find('h4').html('LATLNG= ' + event.latLng);
		infowindow.setContent($('#iw-r1').html());
    infowindow.open(map, marker);
		// infowindow.setPosition(event.latLng);
  });
}

$(function(){
	mapInit();

});
</script>
</head>
<body>
	<div id="map_canvas"></div>
	<div id="legend">
		<ul>
			<li class="route route-bc">&mdash; Border Crawl</li>
			<li class="route route-hg">&mdash; Hanoi Getaway</li>
			<li class="route route-hs">&mdash; Hanoi to Saigon</li>
			<li class="route route-hh">&mdash; Hochiminh Trail</li>
			<li class="route route-ka">&mdash; Karst Away</li>
			<li class="route route-mh">&mdash; Mountain High</li>
		</ul>
	</div>
	<div style="display:none;">
		<div id="iw-r1">
			<div class="iw-content">
				<h4>Hanoi Get Away</h4>
				<p>3-5 days, 490-830 km. <a href="#">View images</a></p>
				<p><img src="http://img2.news.zing.vn/2013/01/07/d1.jpg" width="100%"/></p>
				<p>Relatively easy road riding that takes in rice terraced mountains, hill tribe cultures, jungle clad national parks and traditional homestays.</p>
			</div>
		</div>
	</div>	
</body>
</html><!--
  google.maps.event.addListener(r1, 'click', function(event) {
		infowindow.setContent($('#iw-r1').html());
    infowindow.open(map);
		infowindow.setPosition(event.latLng);
  });
	
  google.maps.event.addListener(r2, 'click', function(event) {
		infowindow.setContent($('#iw-r2').html());
    infowindow.open(map);
		infowindow.setPosition(event.latLng);
		$('.iw-content .carousel').carousel({
			cycle: true,
			interval: 5000,
			pause:'hover'
		});
  });

  google.maps.event.addListener(mhRoute, 'mouseover', function(event) {
    mhRoute.setOptions(routeMouseOverOptions);
    mh2Route.setOptions(routeMouseOverOptions);
    mh3Route.setOptions(routeMouseOverOptions);
		$('#legend .r1').addClass('active');
  });
	
  google.maps.event.addListener(mhRoute, 'mouseout', function(event) {
    mhRoute.setOptions(routeMouseOutOptions);
		mh2Route.setOptions(routeMouseOutOptions);
		mh3Route.setOptions(routeMouseOutOptions);
		$('#legend .r1').removeClass('active');
  });
	
  google.maps.event.addListener(r1, 'mouseover', function(event) {
    r1.setOptions(routeMouseOverOptions);
		$('#legend .r1').addClass('active');
  });
	
  google.maps.event.addListener(r1, 'mouseout', function(event) {
    r1.setOptions(routeMouseOutOptions);
		$('#legend .r1').removeClass('active');
  });
	
  google.maps.event.addListener(r2, 'mouseover', function(event) {
    r2.setOptions(routeMouseOverOptions);
		$('#legend .r2').addClass('active');
  });
	
  google.maps.event.addListener(r2, 'mouseout', function(event) {
    r2.setOptions(routeMouseOutOptions);
		$('#legend .r2').removeClass('active');
  });-->