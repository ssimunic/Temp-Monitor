@extends('...app')

@section('content')
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&key=AIzaSyCohrfFCZgG_gj6Usd48nIY6VUwSeDC9mw"></script>
    <!-- HighCharts -->
    <script src="http://code.highcharts.com/highcharts.js"></script>
    <script src="http://code.highcharts.com/highcharts-more.js"></script>
    <script src="http://code.highcharts.com/modules/exporting.js"></script>

    <script type="text/javascript">

        //
        // Config
        //
        Highcharts.setOptions({
            global: {
                useUTC: false
            }
        });

        function redraw() {
            <?php $c=0; ?>
            //
            @foreach($sensors as $sensor)
            // HOURLY CHART
            //
            $(document).ready(function() {
                $.getJSON('/data/{{ $sensor->unique_id }}/24hours', function(data) {

                    var ranges = data[0];
                    var averages = data[1];

                    $('#hourly_container{{ $c }}').highcharts({

                        title: {
                            text: ''
                        },

                        xAxis: {
                            type: 'datetime'
                        },

                        yAxis: {
                            title: {
                                text: null
                            }
                        },

                        tooltip: {
                            crosshairs: true,
                            shared: true,
                            valueSuffix: '°C'
                        },

                        legend: {
                            enabled: false
                        },
                        exporting: {
                            enabled: false
                        },
                        series: [{
                            name: 'Average Temperature',
                            data: averages,
                            zIndex: 1,
                            marker: {
                                fillColor: 'white',
                                lineWidth: 2,
                                lineColor: Highcharts.getOptions().colors[0]
                            }
                        }, {
                            name: 'Range',
                            data: ranges,
                            type: 'arearange',
                            lineWidth: 0,
                            linkedTo: ':previous',
                            color: Highcharts.getOptions().colors[0],
                            fillOpacity: 0.3,
                            zIndex: 0
                        }]
                    });
                });
            });
            <?php $c++; ?>
            @endforeach

        }

        $(document).ready(function() {

            redraw();
        });
    </script>
    <?php
    if(!empty($sensors[0]))
    {

        $x = 0;
        $y = 0;

        $i = 0;
        foreach($sensors as $sensor)
        {
            if(!empty($sensor->location))
            {
                $t = explode(', ', $sensor->location);
                $x = $x + $t[0];
                $y = $y + $t[1];
                $i++;
            }
        }

        if($i!=0)
        {
            $x = $x / $i;
            $y = $y / $i;
        }
    }

    ?>
    <div class="content" style="width: 100%; height: 100%; top: 63px; left: 0px; position: fixed; ">
        @if(empty($sensors[0]))
            <div class="alert alert-info" role="alert" style="border-radius: 0px; margin-bottom: 0px; text-align: center;">
                You don't have any sensors.
            </div>
        @elseif($x==0 || $y==0)
            <div class="alert alert-info" role="alert" style="border-radius: 0px; margin-bottom: 0px; text-align: center;">
                You didn't specify location of your sensor(s).
            </div>
        @endif
        <div id="map_canvas" style="height: 100%; width: 100%; position: relative;"></div>
    </div>
    <?php $c = 0; ?>
    <script type="text/javascript">
        var locations = [
                @foreach($sensors as $sensor)
                <?php if(empty($sensor->location)) { $c++;  continue; } ?>
            ["<b><a href='/monitor/{{ $sensor->unique_id }}'>{{ $sensor->name }}</a></b> <a href='/sensor/{{ $sensor->unique_id }}'><span class='glyphicon glyphicon-cog' style='color: grey;' aria-hidden='true' data-toggle='tooltip' data-placement='bottom' title='Settings'></span></a><br>"+
            "Temperature: <b>@if($sensor->last_temp) {{ round($sensor->last_temp,2) }} °C @else <i>Unknown</i> @endif</b><br>"+
            "Humidity: <b>@if($sensor->last_hum) {{ round($sensor->last_hum,2) }} % @else <i>Unknown</i> @endif</b><hr>"+
            "<div id='hourly_container{{ $c }}' style='min-width: 310px; height: 200px; margin: 0 auto; text-align:center;'><h4><span style='padding-top: 50px;'>Loading...</span><h4></div>",
                {{ $sensor->location }},
                4],
            <?php $c++; ?>
            @endforeach
        ];
        var map = new google.maps.Map(document.getElementById('map_canvas'), {

            zoom: @if(empty($sensors[0]) || $x == 0 || $y == 0) 3 @else 8 @endif,
            center: new google.maps.LatLng(@if(empty($sensors[0]) || $x == 0 || $y == 0) 0, 0 @else {{ $x }}, {{ $y }} @endif),
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                disableDefaultUI: true
        });

        map.setOptions({zoomControlOptions: false});

        var infowindow = new google.maps.InfoWindow();

        var marker, i;

        for (i = 0; i < locations.length; i++) {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                map: map,
                animation: google.maps.Animation.DROP,
            });

            var infowindow = new google.maps.InfoWindow()

            infowindow.setContent(locations[i][0]);
            //infowindow.open(map, marker);
            google.maps.event.addListener(marker, 'click', (function(marker, i, infowindow) {
                return function() {
                    redraw();
                    infowindow.setContent(locations[i][0]);
                    infowindow.open(map, marker);
                }
            })(marker, i, infowindow));
        }

        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
@endsection
