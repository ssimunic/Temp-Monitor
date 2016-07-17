@extends('app')

@section('content')
    <div class="container" style=" padding-left: 20px; padding-right: 20px; padding-top: 0px;">
            <!--
              <div class="b" style="position: absolute; width: 100%; left:0; margin-top: -25px;  background: url('/img/bg.png') no-repeat ;  background-size: cover; border-bottom: solid 1px whitesmoke;">
                <h1 style="color: white;  text-shadow: 1px 1px 1px black;">{{ env('WEBNAME') }}</h1>
                <p class="lead" style="color: whitesmoke; text-shadow: 1px 1px 1px grey;">Smart Wi-Fi Temperature & Humidity Sensor</p>

              </div>
		    -->
        <div class="b">
            <h1>{{ env('WEBNAME') }}</h1>
            <p class="lead">Internet of Things data platform for temperature and humidity sensors with maps</p>
            <br>
            <iframe src="https://ghbtns.com/github-btn.html?user=ssimunic&repo=Temp-Monitor&type=star&count=true&size=large" frameborder="0" scrolling="0" width="160px" height="30px"></iframe>
            <br><br>
            <hr>
        </div>
        <div class="row" style="margin-bottom: 50px; <!--margin-top: 225px;-->">
            <div class="col-xs-12 col-sm-3 col-md-4 col-lg-3">
                <div class="box">
                    <div class="box-icon">
                        <span class="glyphicon glyphicon-flash" style="font-size: 20px;"></span>
                    </div>
                    <div class="info">
                        <h4 class="text-center">Setup</h4>
                        <p>
                            Connect your Raspberry Pi, Arduino or other IoT boards and start monitoring temperature and humidity of your environment from anywhere in the world.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                <div class="box">
                    <div class="box-icon">
                        <span class="glyphicon glyphicon-cloud" aria-hidden="true" style="font-size: 20px;"></span>
                    </div>
                    <div class="info">
                        <h4 class="text-center">Data</h4>
                        <p>
                            Sensor readings are stored in the cloud. You can visualize data through various graphs that feature Live, daily and monthly readings.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                <div class="box">
                    <div class="box-icon">
                        <span class="glyphicon glyphicon-phone" style="font-size: 20px;"></span>
                    </div>
                    <div class="info">
                        <h4 class="text-center">Alerts</h4>
                        <p>
                            Customize your sensor's rules. If temperature or humidity is above or below limits, or batteries are low, you will receive a message via SMS or email.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                <div class="box">
                    <div class="box-icon">
                        <span class="glyphicon glyphicon-pushpin" style="font-size: 20px;"></span>
                    </div>
                    <div class="info">
                        <h4 class="text-center">Maps</h4>
                        <p>
                            If you have more sensors at your house or at some location, you can use Google maps or create custom ones to make monitoring easier.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container -->
@endsection
