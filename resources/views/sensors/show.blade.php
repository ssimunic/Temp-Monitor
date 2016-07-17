@extends('...app')

@section('content')
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&key=AIzaSyCohrfFCZgG_gj6Usd48nIY6VUwSeDC9mw"></script>

    <script type="text/javascript">
        var map = null;
        var marker = null;

        var infowindow = new google.maps.InfoWindow({
            size: new google.maps.Size(150,50)
        });

        function createMarker(latlng, name, html) {
            var contentString = html;
            var marker = new google.maps.Marker({
                position: latlng,
                map: map,
                zIndex: Math.round(latlng.lat()*-100000)<<5
            });

            google.maps.event.addListener(marker, 'click', function() {
                infowindow.setContent(contentString);
                infowindow.open(map,marker);
            });
            google.maps.event.trigger(marker, 'click');
            return marker;
        }

        function initialize() {
            var myOptions = {
                @if(empty($sensor->location))
                zoom: 3,
                @else
                zoom: 14,
                @endif
                @if(empty($sensor->location))
                center: new google.maps.LatLng(45, 15),
                @else
                center: new google.maps.LatLng({{ $sensor->location }}),
                @endif
                mapTypeControl: true,
                mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
                navigationControl: true,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }

            map = new google.maps.Map(document.getElementById("map_canvas"),
                    myOptions);

            google.maps.event.addListener(map, 'click', function() {
                infowindow.close();
            });

            google.maps.event.addListener(map, 'click', function(event) {
                if (marker) {
                    marker.setMap(null);
                    marker = null;
                }
                marker = createMarker(event.latLng, "name", "<b><span style='color: black'>{{ $sensor->name }}</span></b><br>Temperature: <b>@if($sensor->last_temp) {{ round($sensor->last_temp,2) }} °C @else <i>Unknown</i> @endif</b><br>Humidity: <b>@if($sensor->last_hum) {{ round($sensor->last_hum,2) }} % @else <i>Unknown</i> @endif</b>");

                var location = document.getElementById("location");
                location.value = event.latLng;
            });

                    @if(!empty($sensor->location))
            var position = new google.maps.LatLng({{ $sensor->location }});
            marker = createMarker(position, "name", "<b><span style='color: black'>{{ $sensor->name }}</span></b><br>Temperature: <b>@if($sensor->last_temp) {{ round($sensor->last_temp,2) }} °C @else <i>Unknown</i> @endif</b><br>Humidity: <b>@if($sensor->last_hum) {{ round($sensor->last_hum,2) }} % @else <i>Unknown</i> @endif</b>");
            @endif
        }

        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
    <div class="content" >
        <div class="container" style=" padding-left: 20px; padding-right: 20px; padding-top: 0px;">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(Session::has('message'))
                <div class="alert alert-info" role="alert">{{ Session::get('message')}} {{ Session::forget('message') }}</div>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">Settings</div>
                <div class="panel-body">
                    <form id="sensorsettings" class="form-horizontal" method="get" action="/sensoredit/">
                        <input type="hidden" name="location" id="location" value="{{ $sensor->location }}">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Unique ID</label>
                            <div class="col-sm-1">
                                <input type="hidden" name="unique_id" value="{{ $sensor->unique_id }}">
                                <p class="form-control-static">{{ $sensor->unique_id }}
                                </p>
                            </div>
                            <label class="col-sm-2 control-label">MAC Address</label>
                            <div class="col-sm-2">
                                <p class="form-control-static">
                                    @if($sensor->mac_address)
                                        {{ $sensor->mac_address }}
                                    @else
                                        <i>Unknown</i>
                                    @endif
                                </p>
                            </div>
                            <label class="col-sm-2 control-label">Firmware</label>
                            <div class="col-sm-1">
                                <p class="form-control-static">
                                    @if($sensor->version)
                                        v{{ $sensor->version }}
                                    @else
                                        <i>Unknown</i>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{ $sensor->name }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="notes" class="col-sm-2 control-label">Notes</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" rows="3" id="notes" name="notes" placeholder="Enter notes here ...">{{ $sensor->notes }}</textarea>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Read every</label>
                            <div class="col-sm-1">
                                <select class="form-control" name="deepsleep_time">
                                    <option <?php if($sensor->sensorconfig->deepsleep_time==1) echo "selected"; ?>>1</option>
                                    <option <?php if($sensor->sensorconfig->deepsleep_time==5) echo "selected"; ?>>5</option>
                                    <option <?php if($sensor->sensorconfig->deepsleep_time==10) echo "selected"; ?>>10</option>
                                    <option <?php if($sensor->sensorconfig->deepsleep_time==15) echo "selected"; ?>>15</option>
                                    <option <?php if($sensor->sensorconfig->deepsleep_time==20) echo "selected"; ?>>20</option>
                                    <option <?php if($sensor->sensorconfig->deepsleep_time==30) echo "selected"; ?>>30</option>
                                    <option <?php if($sensor->sensorconfig->deepsleep_time==60) echo "selected"; ?>>60</option>
                                </select>
                            </div>
                            <div class="col-sm-1">
                                <p class="form-control-static">
                                    minutes
                                </p>
                            </div>

                        </div>
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <span class="help-block">This value is returned on every successful API call. It is not mandatory to use it.</span>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-2">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" <?php if($sensor->contact_type!=0) echo "checked"; ?> name="alertme"> Alert me when temperature/humidity is too low/high
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="alerts" style="display: <?php if($sensor->contact_type==0) echo "none"; else echo "block"; ?>;">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="textinput">Min/max temp. (°C)</label>
                                <div class="col-sm-1">
                                    <input type="number" min="-40" placeholder="-10.00" name="min_temp" class="form-control" value="{{ $sensor->sensorconfig->min_temp  }}">
                                </div>
                                <div class="col-sm-1">
                                    <input type="number" max="80" placeholder="45.00" name="max_temp" class="form-control" value="{{ $sensor->sensorconfig->max_temp  }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="textinput">Min/max hum. (%)</label>
                                <div class="col-sm-1">
                                    <input type="number" min="0" placeholder="0.00" name="min_hum" class="form-control" value="{{ $sensor->sensorconfig->min_hum  }}">
                                </div>

                                <div class="col-sm-1">
                                    <input type="number" max="100" placeholder="100.00" name="max_hum" class="form-control" value="{{ $sensor->sensorconfig->max_hum  }}">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Contact type</label>
                                <div class="col-sm-2">
                                    <select class="form-control" name="contact_type">
                                        <option <?php if($sensor->contact_type==1) echo "selected"; ?> value="1">Mail</option>
                                        <option <?php if($sensor->contact_type==2) echo "selected"; ?> disabled value="2">SMS</option>
                                    </select>

                                </div>
                                <div class="col-sm-3">
                                    <p class="form-control-static">
                                        SMS is currently disabled.
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Contact</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="name" name="contact" placeholder="Email or phone number" value="{{ $sensor->contact }}">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Location</label>
                            <div class="col-sm-10">
                                <div id="map_canvas" style="height: 350px; border-radius: 5px;"></div>
                                <span class="help-block">Click to mark area where your sensor is located.</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- /.container -->
    </div>
@endsection
