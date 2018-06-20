<!DOCTYPE html>

<html lang="en-US">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Lato:400,300,700,900,400italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/map/assets/bootstrap/css/bootstrap.css">

    <link rel="stylesheet" href="/map/assets/css/trackpad-scroll-emulator.css" type="text/css">
    <link rel="stylesheet" href="/map/assets/css/style.css" type="text/css">

</head>

<body class="homepage">
	<div class="page-wrapper">
	    <div id="page-content">
	        <div class="hero-section full-screen has-map has-sidebar">
	            <div class="map-wrapper">
	                <div class="geo-location">
	                    <i class="fa fa-map-marker"></i>
	                </div>
	                <div class="map" id="map-homepage"></div>
	            </div>
	            <!--end map-wrapper-->
	            <div class="results-wrapper">
	                <div class="form search-form inputs-underline">
	                    <form>
		                    <div class="section-title">
		                        <h2>Search</h2>
		                    </div>
		                    <div class="row">
		                    	<div class="col-md-3 form-group">
			                        <select name="stype_venue" class="form-control">
			                        	<option value="">Select type</option>
			                        	<option value="hotel">Hotel</option>
			                        	<option value="home">Homestay</option>
			                        </select>
			                    </div>
		                        <div class="col-md-9 col-sm-12">
			                        <div class="form-group">
			                            <input type="text" class="form-control" name="keyword" placeholder="Enter keyword">
			                        </div>
		                            <!--end form-group-->
		                        </div>
		                    </div>
		                    <div class="row">
		                    </div>
		                    <!--end row-->
		                    <div class="form-group">
		                        <button type="submit" data-ajax-response="map" data-ajax-data-file="map_data" data-ajax-auto-zoom="1" class="btn btn-primary pull-right"><i class="fa fa-search"></i></button>
		                    </div>
		                    <!--end form-group-->
		                </form>
	                    <!--end form-hero-->
	                </div>
	                <div class="results">
	                    <div class="tse-scrollable">
	                        <div class="tse-content">
	                            <div class="section-title">
	                                <h2>Search Results<span class="results-number"></span></h2>
	                            </div>
	                            <!--end section-title-->
	                            <div class="results-content"></div>
	                            <!--end results-content-->
	                        </div>
	                        <!--end tse-content-->
	                    </div>
	                    <!--end tse-scrollable-->
	                </div>
	                <!--end results-->
	            </div>
	            <!--end results-wrapper-->
	        </div>
	        <!--end hero-section-->

	    </div>
	    <!--end page-content-->
	</div>
	<script src="/map/assets/js/jquery-2.2.1.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="/map/assets/js/jquery-migrate-1.2.1.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="/map/assets/bootstrap/js/bootstrap.min.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXvUpUA9ImPLvHAMMZiaZIjuqgUD40kgI" type="text/javascript" charset="utf-8"></script>
	<script src="/map/assets/js/jquery.trackpad-scroll-emulator.min.js" type="text/javascript" charset="utf-8"></script>

	<script type="text/javascript" src="/map/assets/js/richmarker-compiled.js"></script>
	<script type="text/javascript" src="/map/assets/js/markerclusterer_packed.js"></script>
	<script src="/map/assets/js/maps.js" type="text/javascript" charset="utf-8"></script>
	<script>
		var _latitude = 21.042035;
		var _longitude = 105.846631;
		var element = "map-homepage";
		var markerTarget = "modal"; // use "sidebar", "infobox" or "modal" - defines the action after click on marker
		var sidebarResultTarget = "modal"; // use "sidebar", "modal" or "new_page" - defines the action after click on marker
		var showMarkerLabels = false; // next to every marker will be a bubble with title
		var mapDefaultZoom = 15; // default zoom
		heroMap(_latitude,_longitude, element, markerTarget, sidebarResultTarget, showMarkerLabels, mapDefaultZoom);
		$(document).ready(function($) {
		    heroSectionHeight();
		    $(".tse-scrollable").TrackpadScrollEmulator();
		    if( !viewport.is('xs') ){
		        $(".search-form.vertical").css( "top", ($(".hero-section").height()/2) - ($(".search-form .wrapper").height()/2) );
		        trackpadScroll("initialize");
		    }
		});
	</script>
</body>


