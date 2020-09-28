$(function() {
                points = [];
                $("a.map.vietnam").trigger("click");
            })
            $('canvas').mouseover(function() {
                return false;
            })
            function outputUpdate(vol) {
                document.querySelector('#volume').value = vol;
            }
            $('#save').click(function() {
                var canvas = document.getElementById("myCanvas"), ctx = canvas.getContext("2d");
// draw to canvas...
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

                $('#myCanvas').drawImage({
                    source: 'map/' + $(this).data("map"),
                    layer: true,
                    x: 0, y: 0,
                    width: width,
                    height: height,
                    fromCenter: false,
                    scale: 1
                });
                if ($(this).hasClass("vietnam")) {
                    points = <?= $jsonPoints ?>;
                    $('#locations .location').prop('disabled', true).trigger("chosen:updated");
                    $('#locations .vietnam-location').prop('disabled', false).trigger("chosen:updated");
                    $('#locations').val('').trigger('chosen:updated');
                    drawMapVietnam();
                } else if ($(this).hasClass("laos")) {
                    points = <?= $jsonPointsLaos ?>;
                    $('#locations .location').prop('disabled', true).trigger("chosen:updated");
                    $('#locations .laos-location').prop('disabled', false).trigger("chosen:updated");
                    $('#locations').val('').trigger('chosen:updated');
                } else if ($(this).hasClass('cambodge')) {
                    points = <?= $jsonPointsCambodge ?>;
                    $('#locations .location').prop('disabled', true).trigger("chosen:updated");
                    $('#locations .cambodge-location').prop('disabled', false).trigger("chosen:updated");
                    $('#locations').val('').trigger('chosen:updated');
                }

            })

            function drawMapVietnam() {
                $('canvas').drawText({
                    fillStyle: '#000',
                    layer: true,
                    name: 'texthanoi',
                    draggable: true,
                    strokeWidth: 2,
                    x: 525, y: 315,
                    fontSize: 15,
                    fontFamily: 'DINAlternate-Medium, sans-serif',
                    fontStyle: 'normal',
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
                }).drawImage({
                    layer: true,
                    source: 'map/capitan.png',
                    name: 'pointhanoi',
                    x: 514, y: 321,
                    width: 22,
                    height: 21,
                    fromCenter: false
                }).drawImage({
                    layer: true,
                    source: 'map/name-vietnam.png',
                    name: 'name-vietnam',
                    draggable: true,
                    x: 382, y: 524,
                    width: 84,
                    height: 23,
                    fromCenter: false
                });
            }
            ;

            $('#clear-all').click(function() {
                $('#locations').val('').trigger('chosen:updated');
            })

            $('#locations').chosen({
                no_results_text: 'Oops, nothing found!',
                inherit_select_classes: true
            });

            $("#submit").click(function() {
                var tour = $('#locations').getSelectionOrder();
                var transports = <?= $jsonTransports ?>;
                var x1, x2, y1, y2, d, yl, yn, cx1, cy1, label1, label2;
                var angle = -$("#change-line").val() * 0.017453292519943295;
                for (var i = 0; i < tour.length; i++) {
                    var canvas = document.getElementById('myCanvas');
                    var ctx = canvas.getContext('2d');
                    var cxtCustom = ctx;
                    var strokeDash = [8, 8];

                    if ($('#change-dashed').is(":checked")) {
                        strokeDash = [];
                    }

                    if (i % 2 == 0 && i != (tour.length - 1)) {
                        if ((tour[0] == tour[tour.length - 1]) && i == (tour.length - 3)) {
                            angle = 0;
                        }
                        x1 = parseInt(points[tour[i]]["data"].split(",")[0]);
                        label1 = points[tour[i]]["label"];
                        y1 = parseInt(points[tour[i]]["data"].split(",")[1]);
                        x2 = parseInt(points[tour[i + 2]]["data"].split(",")[0]);
                        label2 = points[tour[i + 2]]["label"];
                        y2 = parseInt(points[tour[i + 2]]["data"].split(",")[1]);
                        yl = y2 >= y1 ? y2 : y1;
                        yn = y2 >= y1 ? y1 : y2;
                        d = Math.sqrt((x1 - x2) * (x1 - x2) + (y1 - y2) * (y1 - y2)) / 2;
                        cx1 = (x1 + x2 - Math.tan(angle) * (y2 - y1)) / 2;
                        cy1 = (x2 * x2 + y2 * y2 - x1 * x1 - y1 * y1 + (x1 - x2) * (x2 + x1 - Math.tan(angle) * (y2 - y1))) / (2 * (y2 - y1));
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
                            x1: x1, y1: y1,
                            cx1: cx1, cy1: cy1,
                            x2: x2, y2: y2,
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
                                strokeWidth: 4,
                                strokeDash: strokeDash,
                                strokeDashOffset: 0,
                                strokeJoin: 'round',
                                rounded: true,
                                endArrow: false,
                                arrowRadius: 20,
                                arrowAngle: 60,
                                x1: x1, y1: y1,
                                cx1: cx1, cy1: cy1,
                                x2: x2, y2: y2,
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
//                        Draw text 1
                        if ($('canvas').getLayer('text' + label1.trim().toLowerCase()) === undefined) {
                            $('canvas').drawText({
                                fillStyle: '#000',
                                label: true,
                                name: 'text' + label1.trim().toLowerCase(),
                                draggable: true,
                                strokeWidth: 2,
                                x: x1, y: y1 - 10,
                                fontSize: 15,
                                fontFamily: 'DINAlternate-Medium, sans-serif',
                                fontStyle: 'normal',
                                text: label1,
                                dblclick: function(layer) {
                                    // code to run when square is clicked
                                    $('canvas').removeLayer(layer.name);
                                    return false;
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
                        if ($('canvas').getLayer('text' + label2.trim().toLowerCase()) === undefined) {
                            $('canvas').drawText({
                                fillStyle: '#000',
                                label: true,
                                name: 'text' + label2.trim().toLowerCase(),
                                draggable: true,
                                strokeWidth: 2,
                                x: x2, y: y2 - 10,
                                fontSize: 15,
                                fontFamily: 'DINAlternate-Medium, sans-serif',
                                fontStyle: 'normal',
                                text: label2,
                                dblclick: function(layer) {
                                    // code to run when square is clicked
                                    $('canvas').removeLayer(layer.name);
                                    return false;
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
                            })
                        }
                        if ($('canvas').getLayer('point' + label1.trim().toLowerCase()) === undefined) {
                            $('canvas').drawArc({
                                label: true,
                                name: 'point' + label1.trim().toLowerCase(),
                                draggable: true,
                                fillStyle: '#e5007e',
                                strokeStyle: '#e5007e',
                                strokeWidth: 1,
                                x: x1, y: y1,
                                radius: 5,
                                dblclick: function(layer) {
                                    // code to run when square is clicked
                                    $('canvas').removeLayer(layer.name);
                                    return false;
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
                        if ($('canvas').getLayer('point' + label2.trim().toLowerCase()) === undefined) {
                            $('canvas').drawArc({
                                label: true,
                                name: 'point' + label2.trim().toLowerCase(),
                                draggable: true,
                                fillStyle: '#e5007e',
                                strokeStyle: '#e5007e',
                                strokeWidth: 1,
                                x: x2, y: y2,
                                radius: 5,
                                dblclick: function(layer) {
                                    // code to run when square is clicked
                                    $('canvas').removeLayer(layer.name);
                                    return false;
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
                        strokeWidth: 1,
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