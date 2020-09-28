<!DOCTYPE html>

<html lang="en-US">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="ThemeStarz">

    <link href="assets/fonts/font-awesome.css" rel="stylesheet" type="text/css">
    <link href="assets/fonts/elegant-fonts.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Lato:400,300,700,900,400italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="assets/css/zabuto_calendar.min.css" type="text/css">
    <link rel="stylesheet" href="assets/css/owl.carousel.css" type="text/css">

    <link rel="stylesheet" href="assets/css/trackpad-scroll-emulator.css" type="text/css">
    <link rel="stylesheet" href="assets/css/jquery.nouislider.min.css" type="text/css">
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">

    <title>Locations - Directory Listing HTML Template</title>

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
                        <div class="form-group">
                            <input type="text" class="form-control" name="keyword" placeholder="Enter keyword">
                        </div>
                        <!--end form-group-->
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <select class="form-control selectpicker" name="city">
                                        <option value="">Location</option>
                                            <option value="1">New York</option>
                                            <option value="2">Washington</option>
                                            <option value="3">London</option>
                                            <option value="4">Paris</option>
                                    </select>
                                </div>
                                <!--end form-group-->
                            </div>
                            <!--end col-md-6-->
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <select class="form-control selectpicker" name="category">
                                        <option value="">Category</option>
                                        <option value="restaurant">Restaurant</option>
                                        <option value="car rental">Car Rental</option>
                                        <option value="relax">Relax</option>
                                        <option value="sport">Sport</option>
                                        <option value="wellness">Wellness</option>
                                    </select>
                                </div>
                                <!--end form-group-->
                            </div>
                            <!--end col-md-6-->
                        </div>
                        <!--end row-->
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <input type="text" class="form-control date-picker" name="min-price" placeholder="Event Date">
                                </div>
                                <!--end form-group-->
                            </div>
                            <!--end col-md-6-->
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <div class="ui-slider" id="price-slider" data-value-min="10" data-value-max="400" data-value-type="price" data-currency="$" data-currency-placement="before">
                                        <div class="values clearfix">
                                            <input class="value-min" name="value-min[]" readonly>
                                            <input class="value-max" name="value-max[]" readonly>
                                        </div>
                                        <div class="element"></div>
                                    </div>
                                    <!--end price-slider-->
                                </div>
                            </div>
                            <!--end col-md-6-->
                        </div>
                        <!--end row-->
                        <div class="form-group">
                            <button type="submit" data-ajax-response="map" data-ajax-data-file="assets/external/data_2.php" data-ajax-auto-zoom="1" class="btn btn-primary pull-right"><i class="fa fa-search"></i></button>
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
<!--end page-wrapper-->
<script type="text/javascript" src="assets/js/jquery-2.2.1.min.js"></script>
<script type="text/javascript" src="assets/js/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="assets/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyBEDfNcQRmKQEyulDN8nGWjLYPm8s4YB58&libraries=places"></script>
<script type="text/javascript" src="assets/js/richmarker-compiled.js"></script>
<script type="text/javascript" src="assets/js/markerclusterer_packed.js"></script>
<script type="text/javascript" src="assets/js/infobox.js"></script>
<script type="text/javascript" src="assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="assets/js/jquery.fitvids.js"></script>
<script type="text/javascript" src="assets/js/icheck.min.js"></script>
<script type="text/javascript" src="assets/js/owl.carousel.min.js"></script>
<script type="text/javascript" src="assets/js/jquery.trackpad-scroll-emulator.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="assets/js/jquery.nouislider.all.min.js"></script>

<script type="text/javascript" src="assets/js/custom.js"></script>
<script type="text/javascript" src="assets/js/maps.js"></script>

<script>
    var optimizedDatabaseLoading = 0;
    var _latitude = 40.7344458;
    var _longitude = -73.86704922;
    var element = "map-homepage";
    var markerTarget = "infobox"; // use "sidebar", "infobox" or "modal" - defines the action after click on marker
    var sidebarResultTarget = "sidebar"; // use "sidebar", "modal" or "new_page" - defines the action after click on marker
    var showMarkerLabels = false; // next to every marker will be a bubble with title
    var mapDefaultZoom = 14; // default zoom
    heroMap(_latitude,_longitude, element, markerTarget, sidebarResultTarget, showMarkerLabels, mapDefaultZoom);
</script>

</body>

