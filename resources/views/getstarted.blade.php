@extends('app')

@section('content')
    <div class="content">
        <div class="container" style=" padding-left: 20px; padding-right: 20px; padding-top: 0px;">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="page-header" style="margin-top: 0px;">
                        <h1 style="margin-top: 5px;">Get started</h1>
                    </div>
                    In this short guide you will learn how to make your IoT board talk to {{ env('WEBNAME') }} servers.
                </div>
            </div>
        </div>
    </div>
@endsection
