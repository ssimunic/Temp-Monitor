@extends('...app')

@section('content')
	<div class="content">
		<div class="container" style=" padding-left: 20px; padding-right: 20px; padding-top: 0px;">
			@if(Session::has('message'))
				<div class="alert alert-info" role="alert">{{ Session::get('message')}} {{ Session::forget('message') }}</div>
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
						<h1 style="margin-top: 5px;">Sensors</h1>
					</div>
					@if(empty($sensors[0]))
						<h6>No sensors to display.</h6><hr>
					@else
						<div class="table-responsive">

							<table class="table table-striped table-hover  ">
								<thead>
								<tr>
									<th>#</th>
									<th>Name</th>
									<th>Temperature</th>
									<th>Humidity</th>
									<th>Last read</th>
									<th>Options</th>
								</tr>
								</thead>
								<tbody>
								<?php $i = 1; ?>
								@foreach($sensors as $sensor)
									<tr>
										<td>{{ $i }}</td>
										<td><a href="{{ url('monitor/'.$sensor->unique_id) }}"  data-toggle="tooltip" data-placement="right" title="Monitor">{{ $sensor->name }}</a></td>
										<td>
											@if($sensor->last_temp)
												{{ round($sensor->last_temp,2) }} °C
											@else
												<i>Unknown</i>
											@endif
										</td>
										<td>
											@if($sensor->last_hum)
												{{ round($sensor->last_hum, 2) }} %
											@else
												<i>Unknown</i>
											@endif
										</td>
										<td>
											@if($sensor->last_check)
												{{ $sensor->last_check }}
											@else
												<i>Unknown</i>
											@endif
										</td>
										<td>
											<a href="{{ url('sensor/api/'.$sensor->unique_id) }}"><span class="glyphicon glyphicon-cloud" style="color: grey;" aria-hidden="true"  data-toggle="tooltip" data-placement="bottom" title="API"></span></a> &nbsp;
											<a href="{{ url('sensor/'.$sensor->unique_id) }}"><span class="glyphicon glyphicon-cog" style="color: grey;" aria-hidden="true"  data-toggle="tooltip" data-placement="bottom" title="Settings"></span></a> &nbsp;
											<a onclick="return confirm('Are you sure you want to delete {{ $sensor->name }}?')" href="{{ url('sensor/delete/'.$sensor->unique_id) }}"><span class="glyphicon glyphicon-remove" style="color: darkred;"  aria-hidden="true"  data-toggle="tooltip" data-placement="bottom" title="Delete"></span></a>
										</td>
									</tr>
									<?php $i++; ?>
								@endforeach
								</tbody>
							</table>
						</div>
				@endif
				<!-- Button trigger modal -->
					<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
						Add New
					</button>
					<!-- Modal -->
					<form class="form-horizontal" method="get" action="sensor/create">
						<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Add new sensor</h4>
									</div>
									<div class="modal-body">
										<fieldset>
											<div class="form-group">
												<label for="unique_id" class="col-lg-2 control-label">Unique ID</label>
												<div class="col-lg-10">
													<input name="unique_id" type="text" class="form-control" id="unique_id" placeholder="4RPFBHFT7V">
													<p class="form-control-static">
														Leave empty for random.
													</p>
												</div>
											</div>
											<div class="form-group">
												<label for="name" class="col-lg-2 control-label">Name</label>
												<div class="col-lg-10">
													<input name="name" type="text" class="form-control" id="name" placeholder="Kitchen room">
												</div>
											</div>
										</fieldset>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										<button type="submit" class="btn btn-primary">Add</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div><!-- /.container -->
	</div>
@endsection
