@extends('...app')

@section('content')
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
                <div class="panel-heading">API</div>
                <div class="panel-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Sensor ID</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><span class="label label-info">{{ $sensor->unique_id }}</span></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">API Key</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><span class="label label-info">{{ $sensor->api_key }}</span></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Method</label>
                            <div class="col-sm-10">
                                <p class="form-control-static">GET</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">URL</label>
                            <div class="col-sm-10">
                                <p class="form-control-static">{{ 'http://'.$_SERVER['HTTP_HOST'].'/api/data/push' }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Parameters</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><code>id</code> (sensor ID)</p>
                                <p class="form-control-static"><code>t</code> (temperature reading in °C)</p>
                                <p class="form-control-static"><code>h</code> (humidity in %)</p>
                                <p class="form-control-static"><code>api_key</code> (unique API key)</p>
                                <p class="form-control-static"><code>response_type</code> (optional) (empty or <i>basic</i>)</p>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Example request</label>
                            <div class="col-sm-10">
                                <p class="form-control-static">{{ 'http://'.$_SERVER['HTTP_HOST'].'/api/data/push?id='.$sensor->unique_id.'&t=30.15&h=41.25&api_key='.$sensor->api_key }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Example responses</label>
                            <div class="col-sm-10">
                                <p class="form-control-static">
                                    <code>{"status":"success","read_every":15}</code>
                                </p>
                                <p class="form-control-static">
                                    <code>{"status":"error","reason":"Invalid temperature or humidity readings. Must be numeric value."}</code>
                                </p>
                                <p class="form-control-static">
                                    <code>{"status":"error","reason":"Invalid API key."}</code>
                                </p>
                                <p class="form-control-static">
                                    <code>{"status":"error","reason":"Invalid sensor unique ID."}</code>
                                </p>
                                <br>
                                <p class="form-control-static">
                                    With <code>&response_type=basic</code> server returns plain text with either <code>success</code> or <code>fail</code>.
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- /.container -->
    </div>
@endsection
