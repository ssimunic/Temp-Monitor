@extends('...app')

@section('content')
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

		//
		// LIVE CHART
		//

		$(document).ready(function() {
			var chart = new Highcharts.Chart({
				chart: {
					renderTo: 'live_container',
					defaultSeriesType: 'spline',
					events: {
						load: function () {
							setInterval(function () {
								$.ajax({
									url: '/data/{{ $sensor->unique_id }}/live',
									success: function (point) {
										var series = chart.series[0],
												shift = series.data.length > 20;

										chart.series[0].addPoint(eval(point[0]), true, shift);
										chart.series[1].addPoint(eval(point[1]), true, shift);
									},
									cache: false
								});
							}, 1000);
						}
					},
					zoomType: '',
				},
				title: {
					text: 'Live monitor'
				},
				tooltip: {
					shared: true
				},
				xAxis: {
					type: 'datetime',
					tickPixelInterval: 150,
					maxZoom: 20 * 1000
				},
				yAxis: [{
					title: {
						text: 'Temperature',
						style: {
							color: Highcharts.getOptions().colors[0]
						}
					},
					labels: {
						format: '{value}°C',
						style: {
							color: Highcharts.getOptions().colors[0]
						}
					},
					tooltip: {
						valueSuffix: ' °C'
					}

				}, {

					title: {
						text: 'Humidity',
						style: {
							color: 'darkred'
						}
					},
					labels: {
						format: '{value}%',
						style: {
							color: 'darkred'
						}
					},
					opposite: true

				}],
				/*
				 yAxis: {
				 minPadding: 0.2,
				 maxPadding: 0.2,
				 title: {
				 text: 'Value',
				 margin: 80
				 }
				 },*/
				series: [{
					name: 'Temperature',
					type: 'spline',
					tooltip: {
						valueSuffix: ' °C'
					},
					data: [],
					yAxis: 0,
				},
					{
						name: 'Humidity',
						type: 'spline',
						tooltip: {
							valueSuffix: ' %'
						},
						color: 'darkred',
						data: [],
						yAxis: 1,
					},]
			});
		});

		//
		// MONTHLY CHART
		//
		$(document).ready(function() {
			$.getJSON('/data/{{ $sensor->unique_id }}/30days', function(data) {

				var ranges = data[0];
				var averages = data[1];

				$('#monthly_container').highcharts({

					title: {
						text: 'Last 30 days'
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

		//
		// HOURLY CHART
		//
		$(document).ready(function() {
			$.getJSON('/data/{{ $sensor->unique_id }}/24hours', function(data) {

				var ranges = data[0];
				var averages = data[1];

				$('#hourly_container').highcharts({

					title: {
						text: 'Last 24 hours'
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
	</script>
	<div class="content">
		<div class="container" style=" padding-left: 20px; padding-right: 20px; padding-top: 0px;">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Graph 1</h3>
				</div>
				<div class="panel-body">
					<div id="live_container" style="min-width: 310px; height: 400px; margin: 0 auto; text-align:center;"><h4><span style="padding-top: 50px;">Loading...</span><h4></div>
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Graph 2</h3>
				</div>
				<div class="panel-body">
					<div id="hourly_container" style="min-width: 310px; height: 400px; margin: 0 auto; text-align:center;"><h4><span style="padding-top: 50px;">Loading...</span><h4></div>
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Graph 3</h3>
				</div>
				<div class="panel-body">
					<div id="monthly_container" style="min-width: 310px; height: 400px; margin: 0 auto; text-align:center;"><h4><span style="padding-top: 50px;">Loading...</span><h4></div>
				</div>
			</div>
		</div><!-- /.container -->
	</div>
@endsection
