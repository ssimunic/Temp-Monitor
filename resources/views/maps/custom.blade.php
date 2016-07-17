@extends('...app')

@section('content')
    <div class="content">
        <div class="container" style=" padding-left: 20px; padding-right: 20px; padding-top: 0px;">
            @if(Session::has('message'))
                <div class="alert alert-info" role="alert">{{ Session::get('message')}} {{ Session::forget('message') }}</div>
            @endif
            @if(Session::has('error'))
                <div class="alert alert-danger" role="alert">{{ Session::get('error')}} {{ Session::forget('error') }}</div>
            @endif
            <div class="panel panel-default" style="margin: 5px;">
                <div class="panel-body">
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
                    <div class="page-header" style="margin-top: 0px;">
                        <h1 style="margin-top: 5px;">Custom maps</h1>
                    </div>
                    @if(empty($sensors[0]))
                        <h6>You don't have any sensors to browse maps for.</h6>
                    @else
                        @if(empty($maps[0]))
                            <h6>You don't have any maps created.</h6>
                        @else
                            <div class="row">
                                @foreach($maps as $map)
                                    <div class="col-sm-6 col-md-4">
                                        <div class="thumbnail">
                                            <div class="caption" style="position: relative">
                                                <div style="position: absolute; right: 5px; top: 5px;"><a onclick="return confirm('Are you sure you want to delete {{ $map->name }}?')" href="/map/delete/{{ $map->id }}" class="label label-danger">Delete</a></div>
                                                <a href="/map/view/{{ $map->id }}"><div data-toggle="tooltip" data-placement="bottom" title="Launch" style="width: 100%; border-radius: 3px; height: 300px; background: url('@if($map->type==1) /img/google_maps.jpg @elseif($map->type==2) @if($map->image_path!="" && file_exists('uploads/'.$map->image_path)) /uploads/{{ $map->image_path }} @else /img/areaphoto.gif @endif @endif ') no-repeat center;  background-size: cover;"></div></a>
                                                <h3>{{ $map->name }}</h3>
                                            <!--<p><a href="/map/manage/{{ $map->id }}" class="btn btn-primary" style="width: 100%" role="button">Manage</a></p>-->
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                    <hr>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
                        Create new
                    </button>
                    <!-- Modal -->
                    {!! Form::open(array('url'=>'map','method'=>'POST', 'files'=>true, 'class'=>'form-horizontal')) !!}
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Create new map</h4>
                                </div>
                                <div class="modal-body">
                                    <fieldset>
                                        <div class="form-group">
                                            <label for="unique_id" class="col-lg-2 control-label">Name</label>
                                            <div class="col-lg-10">
                                                <input name="name" type="text" class="form-control" id="unique_id" placeholder="Name">
                                            </div>
                                        </div>
                                        <div class="form-group" id="maptype">
                                            <label class="col-lg-2 control-label">Type</label>
                                            <div class="col-lg-10">

                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="maptype" id="optionsRadios1" value="1" disabled>
                                                        Google Map
                                                    </label>
                                                </div>
                                                <div  class="radio">
                                                    <label>
                                                        <input type="radio" name="maptype" id="optionsRadios2" value="2">
                                                        Area photo
                                                    </label>
                                                    <div class="file"  style="display: none;"> <br>
                                                        <input accept="image/*" class="file" type="file" name="image" id="file">
                                                        <span class="help-block">Recommended image ratio is 16:9.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div><!-- /.container -->
    </div>
    <script>
        $(function(){
            $('#maptype').click(function() {
                var areaphoto = document.getElementById('optionsRadios2');
                if (areaphoto.checked) {
                    $('.file').show(200);
                } else {
                    $('.file').hide(200);
                }
            });
        });
    </script>
@endsection
