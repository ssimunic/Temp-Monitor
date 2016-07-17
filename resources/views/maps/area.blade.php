@extends('...app')

@section('content')
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

    <div class="content" style="">
        <div class="container" style=" padding-left: 20px; padding-right: 20px; padding-top: 0px; width: 1170px; ">
            @if(Agent::isMobile() || Agent::isTablet())
                <div class="alert alert-info" role="alert">This map is not optimized for mobile and tablet devices.</div>
            @endif
            @if(Session::has('message'))
                <div class="alert alert-info" role="alert">{{ Session::get('message')}} {{ Session::forget('message') }}</div>
            @endif
            <div   class="panel panel-default" style="margin: 5px; border: #bbbbbb dashed 1px; border-radius: 2px;box-shadow: none;">
                <div class="panel-body" >
                    <?php
                    list($width, $height, $type, $attr) = getimagesize("uploads/".$map->image_path);
                    ?>
                    <div id="areaphoto" style="height: 650px; background: url('/uploads/{{ $map->image_path }}') no-repeat center; background-size: contain; position: relative;">
                        <div style="position: absolute; right: 0px; z-index: 55">
                            <select class="form-control" id="select_view">
                                <option value="default">Default</option>
                                <option value="plain" @if(isset($_GET['type']) && $_GET['type']=="plain") selected @endif >Plain</option>
                                <option value="heatmap" @if(isset($_GET['type']) && $_GET['type']=="heatmap") selected @endif>Heatmap</option>
                            </select>
                        </div>
                        @foreach($mapentries as $mapentry)
                            <?php
                            $map_location = $mapentry->map_location;
                            $map_location = str_replace("pct", "%", $mapentry->map_location);
                            $map_location = explode(':', $map_location);
                            $l = $map_location[0];
                            $t = $map_location[1];
                            ?>

                            @if(isset($_GET['type']) && $_GET['type']=="plain")
                                <span class="draggable label label-primary" id="{{ $mapentry->id }}" style="cursor: pointer;
                                        display:inline-block;padding-top:0;padding-bottom:0; line-height:1.5em; text-align: left; font-size: 11px;
                                        position: absolute;
                                        left: {{ $l }};
                                        top: {{ $t }};">
                                    {{ round($mapentry->sensor->last_temp, 2) }} °C<br>
                                    {{ round($mapentry->sensor->last_hum, 2) }} %
                                </span>
                            @elseif(isset($_GET['type']) && $_GET['type']=="heatmap")

                                <?php

                                $temp = $mapentry->sensor->last_temp;
                                $color = "black";
                                /*
                                                        if($temp >= 40)
                                                            $color = "FF3333";
                                                        elseif($temp >= 35)
                                                            $color = "FF6633";
                                                        elseif($temp >= 30)
                                                            $color = "CCFF33";
                                                        elseif($temp >= 25)
                                                            $color = "99FF00";
                                                        elseif($temp >= 20)
                                                            $color = "33FF33";
                                                        elseif($temp >= 15)
                                                            $color = "33CC33";
                                                        elseif($temp >= 10)
                                                            $color = "00CC66";
                                                        elseif($temp >= 5)
                                                            $color = "0099CC";
                                                        elseif($temp >= 0)
                                                            $color = "0066FF";
                                                        elseif($temp >= -5)
                                                            $color = "0033FF";
                                                        elseif($temp >= -10)
                                                            $color = "0033FF";
                                                        elseif($temp >= -20)
                                                            $color = "0000FF";
                                */
                                ?><!--
                      <div class="draggable"
                            id="{{ $mapentry->id }}"
                            data-container="body"
                            data-toggle="popover"
                            data-placement="top"
                            data-html="true"
                            data-content="<b><a href='/monitor/{{ $mapentry->sensor->unique_id }}'>{{ $mapentry->sensor->name }}</a></b><br>Temperature: <b>{{ round($temp, 2) }} °C</b><br>Humidity: <b>{{ round($mapentry->sensor->last_hum, 2) }} %</b>"
                            data-original-title=""
                            title=""
                            src="/img/heat.png"
                            style="height: 24px; cursor: pointer; position: absolute;
                            left: {{ $l }};
                            top: {{ $t }};
                            border-radius: 50%;
                            width: 24px;
                            height: 24px; background: #{{ $color }}; color: #f5f5f5"><b>&nbsp;{{ round($temp, 0) }}°</b></div>-->
                            @else
                                <img class="draggable tester"
                                     id="{{ $mapentry->id }}"
                                     data-container="body"
                                     data-toggle="popover"
                                     data-placement="top"
                                     data-html="true"
                                     data-content="<b><a href='/monitor/{{ $mapentry->sensor->unique_id }}'>{{ $mapentry->sensor->name }}</a></b><br>Temperature: <b>{{ round($mapentry->sensor->last_temp, 2) }} °C</b><br>Humidity: <b>{{ round($mapentry->sensor->last_hum, 2) }} %</b>"
                                     data-original-title=""
                                     title=""
                                     src="/img/marker.png"
                                     style="height: 32px; cursor: pointer; position: absolute;
                                             left: {{ $l }};
                                             top: {{ $t }};">

                            @endif

                        @endforeach
                        @if(isset($_GET['type']) && $_GET['type']=="heatmap")
                            <div class="heatmap" style="height: 650px; width: 100%; position: absolute; top: 0; left: 0;">
                                @endif
                            </div>
                            @if(isset($_GET['type']) && $_GET['type']=="heatmap")

                    </div>
                    <script src="/js/heatmap.min.js"></script>
                    <script>
                        window.onload = function() {

                            var heatmapInstance = h337.create({
                                container: document.querySelector('.heatmap')
                            });

                            var points = [];
                            var max = 65;

                                    @foreach($mapentries as $mapentry)
                                    <?php
                                    $map_location = $mapentry->map_location;
                                    $map_location = str_replace("pct", "%", $mapentry->map_location);
                                    $map_location = explode(':', $map_location);
                                    $l = $map_location[0];
                                    $t = $map_location[1];
                                    ?>

                            var val = {{ $mapentry->sensor->last_temp }}+20;
                            // now also with custom radius
                            var radius = 25;

                            var point = {
                                x: {{ str_replace("px","",$l) }}+15,
                                y: {{ str_replace("px","",$t) }}+30,
                                value: val,
                                // radius configuration on point basis
                                radius: radius
                            };
                            points.push(point);
                                    @endforeach

                            var data = {
                                        max: max,
                                        data: points
                                    };

                            heatmapInstance.setData(data);

                            document.querySelector('.trigger-refresh').onclick = function() {
                                heatmapInstance.setData(generateRandomData(200));
                            };

                        };
                    </script>

                    <div style="height: 25px; color: #f5f5f5;margin: 5px; padding-bottom: 10px; border-radius: 2px; background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAAAKCAYAAABCHPt+AAABk0lEQVRIS92Xy3KDMAxFT4YMBPj/r2y76YOUTDPpiErUaIzLI6Fps1FshBe6HF9pB5cLt/5lZyhaqBrIW6ibr/+yVzZwOEH5BsUJKouaY/mDeIT8HarjMN+dV+zP5EAFXaw1yrrQ/RI4ABJtT2K4jr3vz7Mz5b19CzwCL8AD8AQ8655FeSY5ttb83eaCWNFNECmqFLeWqGJJlOddTiiSiiXixfI70b/Py7OPqxfbixcTKxNBQhFEFBPARPJrzd9ekCmESFE9UZ6QGFFXJiQkyhNh6xhZf4sQua6MAPdF94TINWaC3IiQpcX+f4SEHjHmIWsJUdFTHhLe+WMeslS0WYS462z7K2sLQlT0lIcsLfYsQqzYKQ8xY/81D1lLSKorM+I2ICQkzHdnPSGu2IOuykz9rgkRo+5a4ISHpLoy86SVhKSKbYSErbM3/L7LumtCejKCOcN7SNfK6nwx1mWtJCTWFU25hlJzxyJCwjklaIG38xDzjpSHrCXEzS0xD5k75PliTyJEhr1XHQp/mDv6a0w95BPxAOBMFsbSCwAAAABJRU5ErkJggg==) no-repeat center center; background-size: cover;
                                                margin: 0px;border-radius: 2px;">
                        <div class="col-md-2 text-center"><b>-10 °C</b></div>
                        <div class="col-md-2 text-center"><b>0 °C</b></div>
                        <div class="col-md-2 text-center"><b>10 °C</b></div>
                        <div class="col-md-2 text-center"><b>20 °C</b></div>
                        <div class="col-md-2 text-center"><b>30 °C</b></div>
                        <div class="col-md-2 text-center"><b>40 °C</b></div>
                    </div>

                    <script>
                        function timeConverter(UNIX_timestamp){
                            var a = new Date(UNIX_timestamp);
                            var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                            var year = a.getFullYear();
                            var month = months[a.getMonth()];
                            var date = a.getDate();
                            var hour = a.getHours();
                            var min = a.getMinutes();
                            var sec = a.getSeconds();
                            var time = date + ',' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;
                            return a;
                        }

                        var ldata;

                        $.get( "/data/GC1YCJBLYS/24hours", function (data) {
                            ldata = data;
                            //alert(data);
                        });


                        $(function() {
                            $( "#slider" ).slider({
                                value: 24,
                                min: 0,
                                max: 24,
                                step: 1,
                                slide: function( event, ui ) {
                                    $( "#time" ).text(timeConverter(ldata[1][ui.value][0]));
                                    $("#36").html("<b>&nbsp;"+Math.round(ldata[1][ui.value][1])+"°</b>");
                                }
                            });
                        });
                    </script>
                    <!--
                  <hr>
                  <div style="margin: 5px; padding-bottom: 10px;">
                      <div id="slider" ></div>
                      <div class="col-md-12 text-center">
                        <span id="time" >Now</span>
                      </div>
                  </div>
                  -->
                    @endif
                </div>

            </div>
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true" style="margin: 5px; padding-bottom: 10px;">
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="headingOne">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Available sensors
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                        <div class="panel-body">
                            @foreach($sensors as $sensor)
                                <a href="/map/add/{{ $map->id }}/{{ $sensor->unique_id }}" class="label label-info" style="cursor: pointer;">{{ $sensor->name }}</a>
                            @endforeach
                            <span class="help-block">Click on sensor name to add it on map. Pull it to top left to remove it.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container -->
    </div>
    <script>
        $(function() {
            $( ".draggable" ).draggable({
                containment: "#areaphoto",
                start: function() {
                    $(this).popover('hide');
                },
                drag: function() {
                    $(this).css("cursor", "move");
                },
                stop: function() {
                    $(this).css("cursor", "pointer");
                    @if(!isset($_GET['type']) || $_GET['type']!="heatmap")

                        $(this).popover('show');
                            @endif
                    var relativeY = ($(this).offset().top - $("#areaphoto").offset().top)/*/$("#areaphoto").height() * 100*/;
                    var relativeX = ($(this).offset().left - $("#areaphoto").offset().left)/*/$("#areaphoto").width() * 100*/;
                    var map_entry_id = $(this).attr('id');

                    $.get( "/mapentry/edit/" + map_entry_id + "/" + relativeX + "px:" + relativeY + "px", function (data) {

                        if(data=="deleted") {
                            location.reload();
                        }
                    });
                }
            });

        });

        $().ready(function(){
            @if(!isset($_GET['type']) || $_GET['type']!="heatmap")
            $('.draggable').popover('show');
            @endif

            $('#select_view').on('change', function() {
                self.location="/map/view/{{ $map->id }}?type="+$("#select_view").val();
            });
        });
    </script>
@endsection
