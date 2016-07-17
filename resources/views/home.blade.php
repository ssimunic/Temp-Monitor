@extends('app')

@section('content')
	<div class="content">
		<div class="container" style=" padding-left: 20px; padding-right: 20px; padding-top: 0px;">
			<div class="panel panel-default">
				<div class="panel-body">
					Signed in as {{ Auth::user()->name }} (<b>{{ Auth::user()->email }}</b>).
					<br>
					Last login from @if(Auth::user()->last_ip) {{ Auth::user()->last_ip }} @else <i>Unknown</i> @endif on {{  date("F j, Y, g:i a", strtotime(Auth::user()->last_login))}}.
					<hr>
					You have <b>{{ $sensors_count }}</b> <a href="{{ url('/sensor') }}">sensors</a>  active.
				</div>
			</div>
		</div>
	</div>
@endsection
