<?
// $baseUrl = Yii::getAlias('@www');
// $this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css');
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/trackpad-scroll-emulator/1.0.8/trackpad-scroll-emulator.min.css');
$this->registerCssFile('/assets/venue_map/css/style.css');
$this->registerJsFile("https://maps.googleapis.com/maps/api/js?key=AIzaSyDXvUpUA9ImPLvHAMMZiaZIjuqgUD40kgI", ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile("/assets/venue_map/js/markerclusterer_packed.js", ['depends'=>'yii\web\JqueryAsset']);
// $this->registerJsFile("https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js", ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/trackpad-scroll-emulator/1.0.8/jquery.trackpad-scroll-emulator.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile("/assets/venue_map/js/richmarker-compiled.js", ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile("/assets/venue_map/js/maps.js?3", ['depends'=>'yii\web\JqueryAsset']);
?>
<style>
	#map-homepage {min-height: 660px}
</style>
<div class="hero-section has-map" style="height: 660px;">
	<div class="col-md-8">
		<div class="map-wrapper">
            <div class="geo-location">
                <i class="fa fa-map-marker"></i>
            </div>
            <div class="map" id="map-homepage"></div>
        </div>
	</div>
	<div class="col-md-4" style="height: 100%">
		<div class="results-wrapper">
			<div class="form search-form inputs-underline">
                <form style="padding: 10px 20px;">
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
            <div class="clearfix"></div>
			<div class="results" style="height: 322px;">
                <div class="tse-scrollable ">
                    <div class="tse-scroll-content wrapper" style="width: 493px; height: 322px;">
                    	<div class="tse-content">
                            <div class="section-title">
                                <h2>Search Results<span class="results-number"></span></h2>
                            </div>
                            <div class="results-content"></div>
                        </div>
                	</div>
                </div>
                <!--end tse-scrollable-->
            </div>
		</div>
	</div>
</div>
<?
$this->registerJs('
var _latitude = 21.042035;
var _longitude = 105.846631;
var element = "map-homepage";
var markerTarget = "modal"; // use "sidebar", "infobox" or "modal" - defines the action after click on marker
var sidebarResultTarget = "modal"; // use "sidebar", "modal" or "new_page" - defines the action after click on marker
var showMarkerLabels = false; // next to every marker will be a bubble with title
var mapDefaultZoom = 15; // default zoom
heroMap(_latitude,_longitude, element, markerTarget, sidebarResultTarget, showMarkerLabels, mapDefaultZoom);
 $(".wrapper").TrackpadScrollEmulator();
');
?>
