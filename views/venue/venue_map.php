<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;


Yii::$app->params['page_title'] = 'Map of venues';
Yii::$app->params['page_layout'] = '-h -s';

if (!defined('GOOGLE_MAPS_API_KEY')) {
    define('GOOGLE_MAPS_API_KEY', 'AIzaSyA3olwX3jjdSSpnaDvyt2DAgpf-smBt7GE');
}

$theVenues = \common\models\Venue::find()
    ->select(['id', 'name', 'latlng'])
    ->where(['stype'=>['hotel', 'home']])
    ->andWhere('latlng!=""')
    ->asArray()
    ->all();

$mapCenter = $_GET['center'] ?? '21.0419135,105.8462718';
$mapCenter = explode(',', $mapCenter);

?>

<!-- <div class="col-md-12"> -->
    <div id="map" style="height: 100% !important;
    padding-bottom:45%;
    position: relative;
    width: 100%;"></div>
<!-- </div> -->
<script>

function initMap() {
    var contentString = '<div id="content">'+
    '<div id="siteNotice">'+
    '</div>'+
    '<h4 id="firstHeading" class="firstHeading">Uluru</h4>'+
    '<div id="bodyContent">'+
    '<p><b>Uluru</b>, also referred to as <b>Ayers Rock</b>, is a large ' +
    'sandstone rock formation in the southern part of the '+
    'Northern Territory, central Australia. It lies 335&#160;km (208&#160;mi) '+
    'south west of the nearest large town, Alice Springs; 450&#160;km '+
    '(280&#160;mi) by road. Kata Tjuta and Uluru are the two major '+
    'features of the Uluru - Kata Tjuta National Park. Uluru is '+
    'sacred to the Pitjantjatjara and Yankunytjatjara, the '+
    'Aboriginal people of the area. It has many springs, waterholes, '+
    'rock caves and ancient paintings. Uluru is listed as a World '+
    'Heritage Site.</p>'+
    '<p>Attribution: Uluru, <a href="https://en.wikipedia.org/w/index.php?title=Uluru&oldid=297882194">'+
    'https://en.wikipedia.org/w/index.php?title=Uluru</a> '+
    '(last visited June 22, 2009).</p>'+
    '</div>'+
    '</div>';

    var infowindow = new google.maps.InfoWindow({
        content: contentString
    });

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 16,
        center: {lat:<?= $mapCenter[0] ?>, lng: <?= $mapCenter[1] ?? 0 ?>}
    });

    // Create an array of alphabetical characters used to label the markers.
    var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    // Add some markers to the map.
    // Note: The code uses the JavaScript Array.prototype.map() method to
    // create an array of markers based on a given "locations" array.
    // The map() method here has nothing to do with the Google Maps API.
    var markers = locations.map(function(location, i) {
        var marker = new google.maps.Marker({
            position: location,
            label: labels[i % labels.length],
            title: names[i],
        });
        marker.addListener('click', function() {
            infowindow.setContent('<div><h4><a href="/venues/r/' + names[i]['id'] + '">' + names[i]['name'] + '</a></h4></div>')
            infowindow.open(map, marker);
        });
        return marker;
    });

    // Add a marker clusterer to manage the markers.
    var markerCluster = new MarkerClusterer(map, markers,
        {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
    }
    var locations = [
        <?php foreach ($theVenues as $i=>$venue) { $ll = explode(',', $venue['latlng']); ?>
        <?= $i > 0 ? ', ' : '' ?>{lat: <?= $ll[0] ?>, lng: <?= $ll[1] ?? 0 ?>}
        <?php } ?>
    ]
    var names = [
        <?php foreach ($theVenues as $i=>$venue) { ?>
        <?= $i > 0 ? ', ' : '' ?>{id: <?= $venue['id'] ?>, name:'<?= Html::encode($venue['name']) ?>'}
        <?php } ?>
    ]
</script>
<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_MAPS_API_KEY ?>&callback=initMap"></script>