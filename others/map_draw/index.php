<?php include 'data_inc.php';
 //var_dump($pointsLaos);exit;
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>Draw Map for Devis - Amica Travel</title>
        <style>
            body {
                margin: 0px;
                padding: 0px;
                background-color: #c2c2c2;
            }
            #myCanvas{
                position: relative;
                background-color: #fff;
            }
            #locations_chosen{
                min-width: 700px;
                max-width: 800px;
                width: auto;
            }
        </style>
    </head>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">Change location's name</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">New name:</label>
                            <input type="text" class="form-control" id="change-name">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                    <button id="remove-text" type="button" class="btn btn-danger" data-dismiss="modal">Delete</button>
                    <button id="submit-change-text" data-layer="" type="button" class="btn btn-primary">OK</button>
                </div>
            </div>
        </div>
    </div>
    <body style="padding: 20px;background: #c2c2c2;">
        <div style="margin-bottom: 20px;">
            <em style="width: 200px;display: inline-block;"><label for="multiple-label-example">Click to change map</label></em>
            <a data-width="1508" data-height="2052" class="map btn btn-success active vietnam" data-map="vietnam1.jpg" href="javascript:void(0)" role="button">Vietnam</a>
            <a data-width="1580" data-height="1872" class="map btn btn-success laos" data-map="laos1.jpg" href="javascript:void(0)" role="button">Laos</a>
            <a data-width="1900" data-height="1872" class="map btn btn-success cambodge" data-map="cambodge.jpg" href="javascript:void(0)" role="button">Cambodge</a>
            <a data-width="1500" data-height="3049" class="map btn btn-success myanmar" data-map="myanmar- no title-01.png" href="javascript:void(0)" role="button">Myanmar</a>
            <a data-width="1500" data-height="1551" class="map btn btn-success multipays-new" data-map="Multipays- No title.png" href="javascript:void(0)" role="button">Multipays New</a>
            <a data-width="1900" data-height="2200" class="map btn btn-success multipays" data-map="multipays.jpg" href="javascript:void(0)" role="button">Multipays</a>
        </div>
        <div>
            <em style="width: 200px; display: inline-block;"><label for="multiple-label-example">Click to choose locations</label></em>
            <select data-placeholder="Choose locations" multiple class="chosen-select" id="locations" tabindex="7" style="width: auto; max-width:800px;min-width: ">
                <optgroup label="TRANSPORTS">
                    <?php foreach ($transports as $ktp => $tp): ?>
                        <option class='<?= $ktp ?>' data-type="transport" value="<?= $ktp ?>"><?= $tp['label']; ?></option>
                    <?php endforeach; ?>
                </optgroup>
                <optgroup label="LOCATIONS" class="vietnam-location">
                    <?php foreach ($points as $kpo => $po) : ?>
                        <option class="location vietnam-location" data-type='location' value="<?= $kpo; ?>"><?= $po['label'] ?></option>
                    <?php endforeach; ?>
                    <?php foreach ($pointsLaos as $kpo => $po) : ?>
                        <option disabled="true" class="location laos-location" data-type='location' value="<?= $kpo; ?>"><?= $po['label'] ?></option>
                    <?php endforeach; ?>
                    <?php foreach ($poitsCambodge as $kpo => $po) : ?>
                        <option disabled="true" class="location cambodge-location" data-type='location' value="<?= $kpo; ?>"><?= $po['label'] ?></option>
                    <?php endforeach; ?>
                    <?php foreach ($pointsMyanmar as $kpo => $po) : ?>
                        <option disabled="true" class="location myanmar-location" data-type='location' value="<?= $kpo; ?>"><?= $po['label'] ?></option>
                    <?php endforeach; ?>
                    <?php foreach ($pointsMultiNew as $kpo => $po) : ?>
                        <option disabled="true" class="location multipays-new-location" data-type='location' value="<?= $kpo; ?>"><?= $po['label'] ?></option>
                    <?php endforeach; ?>
                    <?php foreach ($pointsMulti as $kpo => $po) : ?>
                        <option disabled="true" class="location multipays-location" data-type='location' value="<?= $kpo; ?>"><?= $po['label'] ?></option>
                    <?php endforeach; ?>
                </optgroup>
            </select>
            <input class="btn btn-danger" type="button" id="clear-all" value="Clear All"/>
        </div>
        <p style="display: inline-block">
            <input class="btn btn-primary btn-lg" type="button" id="submit" value="Draw"/>
            <input class="btn btn-info btn-lg" type="button" id="save" value="Save"/>
        </p>
        <div class="bg-info" style="padding: 10px 20px; width: 500px; border-radius: 10px; margin: 10px 0 10px 45px; display: inline-block;">
            <h4 id="dealing-with-specificity" style="color: #1b809e; margin: 10px 0;">Control</h4>
            <div class="checkbox">
                <label>
                    <input id="change-dashed" type="checkbox" value="">
                    No Dashed Line
                </label>
                <label style="margin-left: 155px;">
                    <input id="en-map" type="checkbox" value="">
                    ENGLISH
                </label>
            </div>
            <label for=fader>Flexure</label>
            <input style="width: 300px;display: inline-block;" type=range min=-60 max=60 value=30 id=change-line step=10 list=volsettings oninput="outputUpdate(value)">
            <datalist id=volsettings>
                <option>-60</option>
                <option>-50</option>
                <option>-40</option>
                <option>-30</option>
                <option>-10</option>
                <option>0</option>
                <option>10</option>
                <option>20</option>
                <option>30</option>
                <option>40</option>
                <option>50</option>
                <option>60</option>
            </datalist>
            <output for=fader id=volume style="width: 30px; height: 30px; text-align: center; border-radius: 5px; background: none repeat scroll 0% 0% rgb(194, 194, 194); display: inline-block;">30</output>

        </div>



        <canvas id="myCanvas" width="1900"></canvas>
        <div class="add-info" style="z-index: 9999; position: fixed; background: rgba(0,0,0,0.5); top:0;bottom: 0;left:0;right:0; display: none;">


        </div>
        <form id="form-info" style="width: 500px; position: fixed; top:2%;right: 0; height: auto; z-index: 99999999; display: none; background: black;">
                <div class="form-group">
                    <label style="color: white;" for="recipient-name" class="control-label">Stt:</label>
                    <input type="text" class="form-control" id="stt" value="0">

                    <label style="color: white;" for="recipient-name" class="control-label">Name:</label>
                    <input type="text" class="form-control" id="name">
                    <label style="color: white;" for="recipient-name" class="control-label">X:</label>
                    <input type="text" class="form-control" id="width">
                    <label style="color: white;" for="recipient-name" class="control-label">Y:</label>
                    <input type="text" class="form-control" id="height">
                    <input class="btn btn-info btn-lg" type="button" id="add" value="add"/>
                </div>
            <div class="addtext" style="background: white; margin: 10px; padding: 10px; border: 1px solid blue; height: 300px; overflow: scroll;">

                </div>
            </form>
        <link rel='stylesheet' href="chosen.css" type="text/css"/>
        <link rel='stylesheet' href="css/bootstrap.min.css" type="text/css"/>
        <link rel='stylesheet' href="css/fontface.css" type="text/css"/>
        <script src="jquery.min.js" type="text/javascript"></script>
        <script src="bootstrap.min.js" type="text/javascript"></script>
        <script src="chosen.jquery.js" type="text/javascript"></script>
        <script type="text/javascript" src="chosen.order.jquery.min.js"></script>
        <script src="jcanvas.js" type="text/javascript"></script>
        <script src="js/FileSaver.js" type="text/javascript"></script>
        <script>
            $(function() {
                points = [];
                $("a.map.vietnam").trigger("click");
            })
            $('canvas').mouseover(function() {
                return false;
            })
            $('canvas').click(function(e){
                 var offset = $(this).offset();
                  var v = $('#stt').val() * 1 + 1;
               // alert(v);
                //alert(e.pageX - offset.left);
               // alert(e.pageY - offset.top);
               var width = e.pageX - offset.left;
               var height = e.pageY - offset.top;
               // $('.add-info').show();
                $('#form-info').show();
                $('#form-info #width').val(parseInt(width));
                $('#form-info #height').val(parseInt(height));

                $('#stt').val(v);

            });
            $('.add-info').click(function(){
                $(this).hide();
                $('#form-info').hide();
            });
            $('#add').click(function(){
                var stt = $('#stt').val();
                var name = $('#name').val();
                var x = $('#width').val();
                var y = $('#height').val();
                var demo ='<p style="margin:0;">"' + stt + '" => ' + '["label" =>' + '"' + name + '", "data" => "' + x + ',' + y + '"],</p>';
                $('.addtext').append(demo);
            });
            var scale = 1;
            var fontSize = 20;
           // var fontSize_Multipays = 10;
            var pointR = 10;
            var ex, ey;
            function outputUpdate(vol) {
                document.querySelector('#volume').value = vol;
            }
            $('#save').click(function() {
                var canvas = document.getElementById("myCanvas"), ctx = canvas.getContext("2d");
                canvas.toBlob(function(blob) {
                    saveAs(blob, "map-to-devis.jpg");
                });
            });
            $('a.map').click(function() {
                var width = parseInt($(this).data("width"));
                var height = parseInt($(this).data("height"));
                var canvas = document.getElementById('myCanvas');

                canvas.width = width;
                canvas.height = height;
                $('a.map').removeClass("active");
                $(this).addClass("active");
                $('canvas').removeLayers();
                $('#myCanvas').drawImage({
                    source: 'map/' + $(this).data("map"),
                    layer: true,
//                    name: 'country',
                    x: 0, y: 0,
                    width: width,
                    height: height,
                    fromCenter: false,
                    scale: 1,
                });
                if ($(this).hasClass("vietnam")) {
                    scale = 1;
                    ex = 1105;
                    ey = 1037;
                    points = <?= $jsonPoints ?>;
                    $('#locations .location').prop('disabled', true).trigger("chosen:updated");
                    $('#locations .vietnam-location').prop('disabled', false).trigger("chosen:updated");
                    $('#locations').val('').trigger('chosen:updated');
                    drawMapVietnam();
                } else if ($(this).hasClass("laos")) {
                    scale = 1.5;
                    ex = 100;
                    ey = 1256;
                    points = <?= $jsonPointsLaos ?>;
                    $('#locations .location').prop('disabled', true).trigger("chosen:updated");
                    $('#locations .laos-location').prop('disabled', false).trigger("chosen:updated");
                    $('#locations').val('').trigger('chosen:updated');
                    drawMapLaos();
                } else if ($(this).hasClass('cambodge')) {
                    scale = 1.8;
                    ex = 1270;
                    ey = 1278;
                    points = <?= $jsonPointsCambodge ?>;
                    $('#locations .location').prop('disabled', true).trigger("chosen:updated");
                    $('#locations .cambodge-location').prop('disabled', false).trigger("chosen:updated");
                    $('#locations').val('').trigger('chosen:updated');
                    drawMapCambodge();
                } else if ($(this).hasClass('myanmar')) {
                    scale = 1.8;
                    ex = 1270;
                    ey = 1278;
                    points = <?= $jsonPointsMyanmar ?>;
                    $('#locations .location').prop('disabled', true).trigger("chosen:updated");
                    $('#locations .myanmar-location').prop('disabled', false).trigger("chosen:updated");
                    $('#locations').val('').trigger('chosen:updated');
                    drawMapMyanmar();
                }else if ($(this).hasClass('multipays-new')) {
                    scale = 0.8;
                    ex = 1205;
                    ey = 401;
                   // fontSize = 15;
                    pointR = 6;
                    points = <?= $jsonPointsMultiNew ?>;
                    $('#locations .location').prop('disabled', true).trigger("chosen:updated");
                    $('#locations .multipays-new-location').prop('disabled', false).trigger("chosen:updated");
                    $('#locations').val('').trigger('chosen:updated');
                    drawMapMultiNew();
                }else if ($(this).hasClass('multipays')) {
                    scale = 1.2;
                    ex = 1534;
                    ey = 1326;
                    points = <?= $jsonPointsMulti ?>;
                    $('#locations .location').prop('disabled', true).trigger("chosen:updated");
                    $('#locations .multipays-location').prop('disabled', false).trigger("chosen:updated");
                    $('#locations').val('').trigger('chosen:updated');
                    drawMapMulti();
                }

            })

            function drawMapVietnam() {
                $('canvas').drawText({
                    fillStyle: '#000',
                    layer: true,
                    name: 'text6',
                    draggable: true,
                    strokeWidth: 2,
                    x: 525, y: 315,
                    fontSize: fontSize,
                    fontFamily: 'DINAlternate-Medium, sans-serif',
                    fontStyle: 'normal',
                    text: 'HA NOI',
                    scale: scale,
                    mouseover: function(layer) {
                        $('canvas').setLayer(layer.name, {
                            opacity: 0.5
                        });
                    },
                    mouseout: function(layer) {
                        $('canvas').setLayer(layer.name, {
                            opacity: 1
                        });
                    }
                }).drawImage({
                    layer: true,
                    source: 'map/capitan.png',
                    name: 'point6',
                    draggable: true,
                    x: 514, y: 321,
                    width: 26,
                    height: 25,
                    scale: 1,
                    bringToFront: true,
                    fromCenter: false
                }).drawImage({
                    layer: true,
                    source: 'map/name-vietnam.png',
                    name: 'name-vietnam',
                    draggable: true,
                    x: 382, y: 524,
                    width: 100,
                    height: 27,
                    fromCenter: false
                });
            }
            ;
            function drawMapLaos() {
                $('canvas').drawText({
                    fillStyle: '#000',
                    layer: true,
                    name: 'text73',
                    draggable: true,
                    strokeWidth: 2,
                    x: 549, y: 942,
                    fontSize: fontSize,
                    scale: scale,
                    bringToFront: true,
                    fontFamily: 'DINAlternate-Medium, sans-serif',
                    fontStyle: 'normal',
                    text: 'VIENTIANE',
                    mouseover: function(layer) {
                        $('canvas').setLayer(layer.name, {
                            opacity: 0.5
                        });
                    },
                    mouseout: function(layer) {
                        $('canvas').setLayer(layer.name, {
                            opacity: 1
                        });
                    }
                }).drawImage({
                    layer: true,
                    source: 'map/capitan.png',
                    draggable: true,
                    name: 'point73',
                    x: 539, y: 892,
                    width: 32,
                    height: 32,
                    fromCenter: false
                }).drawImage({
                    layer: true,
                    source: 'map/name-laos.png',
                    name: 'name-laos',
                    draggable: true,
                    x: 586, y: 700,
                    width: 100,
                    height: 41,
                    fromCenter: false
                });
            }

            function drawMapCambodge() {
                $('canvas').drawText({
                    fillStyle: '#000',
                    layer: true,
                    name: 'text108',
                    draggable: true,
                    strokeWidth: 2,
                    x: 1009, y: 958,
                    fontSize: fontSize,
                    fontFamily: 'DINAlternate-Medium, sans-serif',
                    fontStyle: 'normal',
                    scale: scale,
                    text: 'PHNOM PENH',
                    mouseover: function(layer) {
                        $('canvas').setLayer(layer.name, {
                            opacity: 0.5
                        });
                    },
                    mouseout: function(layer) {
                        $('canvas').setLayer(layer.name, {
                            opacity: 1
                        });
                    }
                }).drawImage({
                    layer: true,
                    source: 'map/capitan.png',
                    draggable: true,
                    name: 'point108',
                    x: 1009, y: 978,
                    width: 22,
                    bringToFront: true,
                    height: 21,
                    scale: 2,
                    fromCenter: false
                }).drawImage({
                    layer: true,
                    source: 'map/name-cambodge.png',
                    name: 'name-laos',
                    draggable: true,
                    x: 441, y: 816,
                    width: 332,
                    height: 69,
                    fromCenter: false,
                    bringToFront: true
                });
            }
            function drawMapMyanmar() {
                $('canvas').drawText({
                    fillStyle: '#000',
                    layer: true,
                    name: 'text12',
                    draggable: true,
                    strokeWidth: 2,
                    x: 530, y: 1445,
                    fontSize: fontSize,
                    fontFamily: 'DINAlternate-Medium, sans-serif',
                    fontStyle: 'normal',
                    scale: scale,
                    text: 'NAYPYIDAW',
                    mouseover: function(layer) {
                        $('canvas').setLayer(layer.name, {
                            opacity: 0.5
                        });
                    },
                    mouseout: function(layer) {
                        $('canvas').setLayer(layer.name, {
                            opacity: 1
                        });
                    }
                }).drawImage({
                    layer: true,
                    source: 'map/capitan.png',
                    draggable: true,
                    name: 'point12',
                    x: 644, y: 1432,
                    width: 22,
                    bringToFront: true,
                    height: 21,
                    scale: 2,
                    fromCenter: false
                }).drawImage({
                    layer: true,
                    source: 'map/name-birmanie.png',
                    name: 'name-birmanie',
                    draggable: true,
                    x: 639, y: 987,
                    width: 200,
                    height: 45,
                    fromCenter: false,
                    bringToFront: true
                });
            }
            function drawMapMulti() {
                $('canvas').drawText({
                    fillStyle: '#000',
                    layer: true,
                    name: 'text221',
                    draggable: true,
                    strokeWidth: 2,
                    x: 718, y: 1706,
                    fontSize: fontSize,
                    fontFamily: 'DINAlternate-Medium, sans-serif',
                    fontStyle: 'normal',
                    scale: scale,
                    text: 'PHNOM PENH',
                    mouseover: function(layer) {
                        $('canvas').setLayer(layer.name, {
                            opacity: 0.5
                        });
                    },
                    mouseout: function(layer) {
                        $('canvas').setLayer(layer.name, {
                            opacity: 1
                        });
                    }
                }).drawText({
                    fillStyle: '#000',
                    layer: true,
                    name: 'text119',
                    draggable: true,
                    strokeWidth: 2,
                    x: 838, y: 357,
                    fontSize: fontSize,
                    fontFamily: 'DINAlternate-Medium, sans-serif',
                    fontStyle: 'normal',
                    scale: scale,
                    text: 'HA NOI',
                    mouseover: function(layer) {
                        $('canvas').setLayer(layer.name, {
                            opacity: 0.5
                        });
                    },
                    mouseout: function(layer) {
                        $('canvas').setLayer(layer.name, {
                            opacity: 1
                        });
                    }
                }).drawText({
                    fillStyle: '#000',
                    layer: true,
                    name: 'text186',
                    draggable: true,
                    strokeWidth: 2,
                    x: 395, y: 841,
                    fontSize: fontSize,
                    fontFamily: 'DINAlternate-Medium, sans-serif',
                    fontStyle: 'normal',
                    scale: scale,
                    text: 'VIENTIANE',
                    mouseover: function(layer) {
                        $('canvas').setLayer(layer.name, {
                            opacity: 0.5
                        });
                    },
                    mouseout: function(layer) {
                        $('canvas').setLayer(layer.name, {
                            opacity: 1
                        });
                    }
                }).drawImage({
                    layer: true,
                    source: 'map/capitan.png',
                    draggable: true,
                    name: 'point186',
                    x: 382, y: 800,
                    width: 22,
                    height: 21,
                    scale: scale,
                    bringToFront: true,
                    fromCenter: false
                }).drawImage({
                    layer: true,
                    draggable: true,
                    source: 'map/capitan.png',
                    name: 'point119',
                    x: 828, y: 373,
                    width: 22,
                    height: 21,
                    scale: scale,
                    bringToFront: true,
                    fromCenter: false
                }).drawImage({
                    layer: true,
                    draggable: true,
                    source: 'map/capitan.png',
                    name: 'point221',
                    x: 711, y: 1666,
                    width: 22,
                    height: 21,
                    scale: scale,
                    bringToFront: true,
                    fromCenter: false
                }).drawImage({
                    layer: true,
                    source: 'map/name-cambodge.png',
                    name: 'name-cambodge',
                    draggable: true,
                    x: 441, y: 1602,
                    width: 135,
                    height: 30,
                    fromCenter: false,
                    bringToFront: true
                }).drawImage({
                    layer: true,
                    source: 'map/name-laos.png',
                    name: 'name-laos',
                    draggable: true,
                    x: 423, y: 672,
                    width: 68,
                    height: 30,
                    fromCenter: false,
                    bringToFront: true
                }).drawImage({
                    layer: true,
                    source: 'map/name-vietnam.png',
                    name: 'name-vietnam',
                    draggable: true,
                    x: 680, y: 607,
                    width: 107,
                    height: 31,
                    fromCenter: false,
                    bringToFront: true
                });
            }
            function drawMapMultiNew() {
                $('canvas').drawText({
                    fillStyle: '#000',
                    layer: true,
                    name: 'text76',
                    draggable: true,
                    strokeWidth: 2,
                    x: 944, y: 1292,
                    fontSize: fontSize,
                    fontFamily: 'DINAlternate-Medium, sans-serif',
                    fontStyle: 'normal',
                    scale: scale,
                    text: 'PHNOM PENH',
                    mouseover: function(layer) {
                        $('canvas').setLayer(layer.name, {
                            opacity: 0.5
                        });
                    },
                    mouseout: function(layer) {
                        $('canvas').setLayer(layer.name, {
                            opacity: 1
                        });
                    }
                }).drawText({
                    fillStyle: '#000',
                    layer: true,
                    name: 'text105',
                    draggable: true,
                    strokeWidth: 2,
                    x: 1028, y: 562,
                    fontSize: fontSize,
                    fontFamily: 'DINAlternate-Medium, sans-serif',
                    fontStyle: 'normal',
                    scale: scale,
                    text: 'HA NOI',
                    mouseover: function(layer) {
                        $('canvas').setLayer(layer.name, {
                            opacity: 0.5
                        });
                    },
                    mouseout: function(layer) {
                        $('canvas').setLayer(layer.name, {
                            opacity: 1
                        });
                    }
                }).drawText({
                    fillStyle: '#000',
                    layer: true,
                    name: 'text35',
                    draggable: true,
                    strokeWidth: 2,
                    x: 787, y: 794,
                    fontSize: fontSize,
                    fontFamily: 'DINAlternate-Medium, sans-serif',
                    fontStyle: 'normal',
                    scale: scale,
                    text: 'VIENTIANE',
                    mouseover: function(layer) {
                        $('canvas').setLayer(layer.name, {
                            opacity: 0.5
                        });
                    },
                    mouseout: function(layer) {
                        $('canvas').setLayer(layer.name, {
                            opacity: 1
                        });
                    }
                }).drawText({
                    fillStyle: '#000',
                    layer: true,
                    name: 'text12',
                    draggable: true,
                    strokeWidth: 2,
                    x: 254, y: 683,
                    fontSize: fontSize,
                    fontFamily: 'DINAlternate-Medium, sans-serif',
                    fontStyle: 'normal',
                    scale: scale,
                    text: 'NAYPYIDAW',
                    mouseover: function(layer) {
                        $('canvas').setLayer(layer.name, {
                            opacity: 0.5
                        });
                    },
                    mouseout: function(layer) {
                        $('canvas').setLayer(layer.name, {
                            opacity: 1
                        });
                    }
                }).drawImage({
                    layer: true,
                    source: 'map/capitan.png',
                    draggable: true,
                    name: 'point76',
                    x: 951, y: 1267,
                    width: 20,
                    height: 21,
                    scale: scale,
                    bringToFront: true,
                    fromCenter: false
                }).drawImage({
                    layer: true,
                    draggable: true,
                    source: 'map/capitan.png',
                    name: 'point105',
                    x: 1019, y: 568,
                    width: 20,
                    height: 21,
                    scale: scale,
                    bringToFront: true,
                    fromCenter: false
                }).drawImage({
                    layer: true,
                    draggable: true,
                    source: 'map/capitan.png',
                    name: 'point35',
                    x: 778, y: 800,
                    width: 20,
                    height: 21,
                    scale: scale,
                    bringToFront: true,
                    fromCenter: false
                }).drawImage({
                    layer: true,
                    draggable: true,
                    source: 'map/capitan.png',
                    name: 'point12',
                    x: 298, y: 670,
                    width: 20,
                    height: 21,
                    scale: scale,
                    bringToFront: true,
                    fromCenter: false
                }).drawImage({
                    layer: true,
                    source: 'map/name-cambodge.png',
                    name: 'name-cambodge',
                    draggable: true,
                    x: 845, y: 1300,
                    width: 75,
                    height: 20,
                    fromCenter: false,
                    bringToFront: true
                }).drawImage({
                    layer: true,
                    source: 'map/name-laos.png',
                    name: 'name-laos',
                    draggable: true,
                    x: 803, y: 728,
                    width: 48,
                    height: 18,
                    fromCenter: false,
                    bringToFront: true
                }).drawImage({
                    layer: true,
                    source: 'map/name-birmanie.png',
                    name: 'name-birmanie',
                    draggable: true,
                    x: 300, y: 463,
                    width: 73,
                    height: 22,
                    fromCenter: false,
                    bringToFront: true
                }).drawImage({
                    layer: true,
                    source: 'map/name-vietnam.png',
                    name: 'name-vietnam',
                    draggable: true,
                    x: 939, y: 693,
                    width: 67,
                    height: 20,
                    fromCenter: false,
                    bringToFront: true
                });
            }


            $('#clear-all').click(function() {
                $('#locations').val('').trigger('chosen:updated');
            })

            $('#locations').chosen({
                no_results_text: 'Oops, nothing found!',
                inherit_select_classes: true
            });
            function isNumber(obj) {
                return !isNaN(parseFloat(obj))
            }

            $("#submit").click(function() {
                var tour = $('#locations').getSelectionOrder();
                var transports = <?= $jsonTransports ?>;
                console.log(transports);
                var x1, x2, y1, y2, d, yl, yn, cx1, cy1, label1, label2;
                var angle = -$("#change-line").val() * 0.017453292519943295;
                var e = 0;
                var tp = [];
                for (var i = 0; i < tour.length; i++) {
                    var canvas = document.getElementById('myCanvas');
                    var ctx = canvas.getContext('2d');
                    var cxtCustom = ctx;
                    var strokeDash = [8 * scale, 8 * scale];
                    var explain = null;
                    if ($('#change-dashed').is(":checked")) {
                        strokeDash = [];
                    }
                    if (!isNumber(tour[i]) && (tp.indexOf(tour[i]) < 0)) {
                        tp.push(tour[i]);
                        e++;
                        switch (tour[i]) {
                            case 'ap':
                                explain = 'airplane';
                                break;
                            case 'tr':
                                explain = 'train';
                                break;
                            case 'ca':
                                explain = 'car';
                                break;
                            case 'mt':
                                explain = 'motobike';
                                break;
                            case 'bc':
                                explain = 'bycicle';
                                break;
                            case 'bo':
                                explain = 'boat';
                                break;
                        }
                        if ($('#en-map').is(":checked")) {
                            explain += '_en';
                        }
                        explain += '.png';
                        $('canvas').drawImage({
                            layer: true,
                            source: 'explain/' + explain,
                            name: 'explain-' + tour[i],
                            groups: ['explain'],
                            draggable: true,
                            dragGroups: ['explain'],
                            x: ex, y: ey + e * 68,
                            width: 250,
                            height: 68,
                            fromCenter: false
                        });

                    }
                    if (isNumber(tour[i])) {
                        x1 = parseInt(points[tour[i]]["data"].split(",")[0]);
                        label1 = points[tour[i]]["label"];
                        y1 = parseInt(points[tour[i]]["data"].split(",")[1]);
                        if ($('canvas').getLayer('text' + tour[i]) === undefined) {
                            $('canvas').drawText({
                                fillStyle: '#000',
                                label: true,
                                name: 'text' + tour[i],
                                draggable: true,
                                strokeWidth: 2,
                                x: x1, y: y1 - 20 * scale,
                                fontSize: fontSize,
                                fontFamily: 'DINAlternate-Medium, sans-serif',
                                fontStyle: 'normal',
                                text: label1,
                                scale: scale,
                                dblclick: function(layer) {
                                    $('#change-name').val(layer.text);
                                    $('#submit-change-text').data('layer', layer.name);
                                    $('#myModal').modal('show');
                                    // code to run when square is clicked
                                },
                                mouseover: function(layer) {
                                    $('canvas').setLayer(layer.name, {
                                        opacity: 0.5
                                    });
                                },
                                mouseout: function(layer) {
                                    $('canvas').setLayer(layer.name, {
                                        opacity: 1
                                    });
                                }
                            });
                        }
                        if ($('canvas').getLayer('point' + tour[i]) === undefined) {
                            $('canvas').drawArc({
                                label: true,
                                arc: true,
                                name: 'point' + tour[i],
                                draggable: true,
                                fillStyle: '#e5007e',
                                strokeStyle: '#e5007e',
                                strokeWidth: 1,
                                x: x1, y: y1,
                                radius: pointR,
                                scale: scale,
                                bringToFront: true,
                                dblclick: function(layer) {
                                    // code to run when square is clicked
                                    $('canvas').removeLayer(layer.name);
                                    return false;
                                },
                                dragstop: function(layer) {
                                    // code to run as layer is being dragged
                                    points[parseInt(layer.name.match(/\d+/)[0])].data = layer.x + ',' + layer.y;
                                },
                                mouseover: function(layer) {
                                    $('canvas').setLayer(layer.name, {
                                        opacity: 0.5
                                    });
                                },
                                mouseout: function(layer) {
                                    $('canvas').setLayer(layer.name, {
                                        opacity: 1
                                    });
                                }
                            });
                        }
                        ;
                    }

//                    if (i % 2 == 0 && i != (tour.length - 1)) {
                    if (isNumber(tour[i]) && !isNumber(tour[i + 1]) && isNumber(tour[i + 2]) && tour.length > 2 && i <= (tour.length - 2)) {
                        if ((tour[0] == tour[tour.length - 1]) && i == (tour.length - 3)) {
                            angle = 0;
                        }
                        x2 = parseInt(points[tour[i + 2]]["data"].split(",")[0]);
                        y2 = parseInt(points[tour[i + 2]]["data"].split(",")[1]);
//                        if(newPoints['point'+tour[i+2]]){
//                            x2 = parseInt(newPoints['point'+tour[i+2]].split(",")[0]);
//                            y2 = parseInt(newPoints['point'+tour[i+2]].split(",")[1]);
//                        }
                        label2 = points[tour[i + 2]]["label"];

                        yl = y2 >= y1 ? y2 : y1;
                        yn = y2 >= y1 ? y1 : y2;
                        d = Math.sqrt((x1 - x2) * (x1 - x2) + (y1 - y2) * (y1 - y2)) / 2;
                        cx1 = (x1 + x2 - Math.tan(angle) * (y2 - y1)) / 2;
                        cy1 = (x2 * x2 + y2 * y2 - x1 * x1 - y1 * y1 + (x1 - x2) * (x2 + x1 - Math.tan(angle) * (y2 - y1))) / (2 * (y2 - y1));
						 var posC = mathC(x2, y2, cx1, cy1, pointR);
                        var posA = mathC(x1, y1, cx1, cy1, pointR);
                        var argsCustom = {
                            strokeStyle: transports[tour[i + 1]]['color'],
                            strokeWidth: 1,
                            layer: true,
                            strokeDash: strokeDash,
                            strokeJoin: 'round',
                            rounded: true,
                            endArrow: false,
                            arrowRadius: 20,
                            arrowAngle: 50,
                            x1: posA.x, y1: posA.y,
                            cx1: cx1, cy1: cy1,
                            x2: posC.x, y2: posC.y,
                            x: 0,
                            y: 0,
                            _toRad: 0.017453292519943295
                        };
                        var l = 2;
                        var paramsCustom = new jCanvasObject(argsCustom);
//                        Draw curve and arrow head
                        if ($('canvas').getLayerGroup('line' + tour[i] + tour[i + 2]) === undefined) {

                            _addArrowCustom(cxtCustom, paramsCustom, paramsCustom, paramsCustom[ 'cx' + (l - 1) ] + paramsCustom.x, paramsCustom[ 'cy' + (l - 1) ] + paramsCustom.y, paramsCustom[ 'x' + l ] + paramsCustom.x, paramsCustom[ 'y' + l ] + paramsCustom.y, 'line' + tour[i] + tour[i + 2]);
                            $('canvas').drawQuadratic({
                                layer: true,
                                groups: ['line' + tour[i] + tour[i + 2]],
                                strokeStyle: transports[tour[i + 1]]['color'],
                                strokeWidth: 4 * scale,
                                strokeDash: strokeDash,
                                strokeDashOffset: 0,
                                strokeJoin: 'round',
                                rounded: true,
                                endArrow: false,
                                arrowRadius: 20,
                                arrowAngle: 60,
                                x1: posA.x, y1: posA.y,
								cx1: cx1, cy1: cy1,
								x2: posC.x, y2: posC.y,
                                dblclick: function(layer) {
                                    // code to run when square is clicked
                                    $('canvas').removeLayerGroup(layer.groups[0]);
                                    return false;
                                },
                                mouseover: function(layer) {
                                    $('canvas').setLayerGroup(layer.groups[0], {
                                        opacity: 0.7
                                    });
                                },
                                mouseout: function(layer) {
                                    $('canvas').setLayerGroup(layer.groups[0], {
                                        opacity: 1
                                    });
                                }
                            });
                        }
                        continue;
                    }
                }
            });

            function jCanvasObject(args) {
                var params = this,
                        propName;
                // Copy the given parameters into new object
                for (propName in args) {
                    // Do not merge defaults into parameters
                    if (args.hasOwnProperty(propName)) {
                        params[ propName ] = args[ propName ];
                    }
                }
                return params;
            }
            // Adds arrow to path using the given properties
            function _addArrowCustom(ctx, params, path, x1, y1, x2, y2, group) {
                ctx.webkitLineDash = ctx.mozDash = [];
                angle *= params._toRad;
                path.arrowAngle *= params._toRad;
                path.arrowRadius *= scale;
                var leftX, leftY,
                        rightX, rightY,
                        offsetX, offsetY,
                        angle;

                // If arrow radius is given and path is not closed
                if (path.arrowRadius && !params.closed) {
                    var PI = Math.PI,
                            round = Math.round,
                            abs = Math.abs,
                            sin = Math.sin,
                            cos = Math.cos,
                            atan2 = Math.atan2,
                            // Calculate angle
                            angle = atan2((y2 - y1), (x2 - x1));
                    // Adjust angle correctly
                    angle -= PI;
                    // Calculate offset to place arrow at edge of path
                    offsetX = (params.strokeWidth * cos(angle));
                    offsetY = (params.strokeWidth * sin(angle));

                    // Calculate coordinates for left half of arrow
                    leftX = x2 + (path.arrowRadius * cos(angle + (path.arrowAngle / 2)));

                    leftY = y2 + (path.arrowRadius * sin(angle + (path.arrowAngle / 2)));
                    // Calculate coordinates for right half of arrow
                    rightX = x2 + (path.arrowRadius * cos(angle - (path.arrowAngle / 2)));
                    rightY = y2 + (path.arrowRadius * sin(angle - (path.arrowAngle / 2)));

                    $('canvas').drawPath({
                        layer: true,
                        groups: [group],
                        draggable: true,
                        strokeStyle: params.strokeStyle,
                        strokeWidth: 1 * scale,
                        bringToFront: true,
                        fillStyle: params.strokeStyle,
                        p1: {
                            type: 'line',
                            x1: leftX - offsetX, y1: leftY - offsetY,
                            x2: x2 - offsetX, y2: y2 - offsetY,
                            x3: rightX - offsetX, y3: rightY - offsetY,
                            x4: leftX - offsetX, y4: leftY - offsetY
                        },
                    });

                }
            }
            ;
            $('#submit-change-text').click(function() {
                $('canvas').getLayer($(this).data('layer')).text = $('#change-name').val().toUpperCase();
                $('#myModal').modal('hide');
            });
            $('#remove-text').click(function() {
                $('canvas').removeLayer($('#submit-change-text').data('layer'));
                $('#myModal').modal('hide');
            });
			function mathC(xb, yb, xm, ym, R) {
                var result = [];
                var x, y;
                var a = 1 + ((yb - ym) * (yb - ym)) / ((xb - xm) * (xb - xm));
                var b = 2 * ((yb - ym)*(xb * ym - xm * yb) / ((xb - xm) * (xb - xm)) - xb - yb * (yb - ym) / (xb - xm));
                var c = (xb * ym - xm * yb) * (xb * ym - xm * yb) / ((xb - xm) * (xb - xm)) - 2*yb * (xb*ym - xm*yb) / (xb - xm) + xb * xb + yb * yb - R * R;
                var delta = b*b -4*a*c;
                var x1 = (-b + Math.sqrt(delta)) / (2 * a);
                var x2 = (-b - Math.sqrt(delta)) / (2 * a);
                var y1 = (x1 * (yb - ym) + xb * ym - yb * xm) / (xb - xm);
                var y2 = (x2 * (yb - ym) + xb * ym - yb * xm) / (xb - xm);
                var d1 = (x1 - xm) * (x1 - xm) + (y1 - ym) * (y1 - ym);
                var d2 = (x2 - xm) * (x2 - xm) + (y2 - ym) * (y2 - ym);
                if (d1 < d2) {
                    x = x1;
                    y = y1;
                } else {
                    x = x2;
                    y = y2;
                }
                result['x'] = x;
                result['y'] = y;
                return result;
            };
        </script>
    </body>
</html>