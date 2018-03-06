@extends('NGAFID-master')

@section('cssScripts')
    <style>
        .btn-group {
            float: none;
            display: inline-block;
        }

        div#spinner {
            display: none;
            width: 100px;
            height: 100px;
            position: fixed;
            top: 50%;
            left: 50%;
            text-align: center;
            margin-left: -50px;
            margin-top: -100px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b>Chart</b>
                        <span class="pull-right">{{date("D M d, Y G:i A T")}}</span>
                    </div>

                    <div class="panel-body">
                        {!! Form::open(['method' => 'GET', 'route' => ['flights/chart', $flight], 'class' => 'form-horizontal']) !!}
                        <div class="col-md-12">
                            Flight Summary:
                            <a href="#" data-toggle="popover" data-html="true" title="Flight Summary" data-content="{{ $summary }}">
                                <span class="glyphicon glyphicon-list-alt"></span>
                            </a> &nbsp;
                            Download Flight Data
                            <div class="btn-group">
                                <a href="#" class="glyphicon glyphicon-download-alt" data-toggle="dropdown" title="Download flight"></a>
                                &nbsp;&nbsp;
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="#" id="dwldFlight{{$flight}}" data-link="{{ URL::route('flights/download', $flight . '/csv') }}">
                                            CSV
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" id="dwldFlight{{$flight}}" data-link="{{ URL::route('flights/download', $flight . '/kml') }}">
                                            KML
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" id="dwldFlight{{$flight}}" data-link="{{ URL::route('flights/download', $flight . '/fdr') }}">
                                            X-Plane
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <p>
                                Select the flight parameter checkbox below and
                                click display to view the chart:
                            </p>
                            <div class="col-md-10">
                                {!! Form::checkbox('param[]', '1', Input::get('param.0'), ["class" => "checkbox-inline"]) !!}
                                Airspeed
                                {!! Form::checkbox('param[]', '2', Input::get('param.1'), ["class" => "checkbox-inline"]) !!}
                                MSL Altitude
                                {!! Form::checkbox('param[]', '3', Input::get('param.2'), ["class" => "checkbox-inline"]) !!}
                                Engine RPM
                                {!! Form::checkbox('param[]', '4', Input::get('param.3'), ["class" => "checkbox-inline"]) !!}
                                Pitch
                                {!! Form::checkbox('param[]', '5', Input::get('param.4'), ["class" => "checkbox-inline"]) !!}
                                Roll
                                {!! Form::checkbox('param[]', '6', Input::get('param.5'), ["class" => "checkbox-inline"]) !!}
                                Vertical Speed
                            </div>
                            <div class="col-md-2">
                                <button type="button" id="display" class="btn btn-primary btn-sm" data-link="{{ URL::route('flights/chart', $flight ) }}">
                                    Display
                                </button>
                            </div>
                            <div id="chart" class="col-md-12"></div>
                            <div id="spinner">
                                <img src="{{ asset('images/loading.gif') }}" />
                            </div>
                            <div class="modal fade" id="downloadStatus" tableindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body"></div>
                                        <div class="modal-footer">
                                            <a href="#" class="btn btn-default" data-dismiss="modal">
                                                Close
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('jsScripts')
    <script src="https://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

    <script src="https://code.highcharts.com/4.2.2/highcharts.js"></script>
    <script src="https://code.highcharts.com/4.2.2/modules/no-data-to-display.js"></script>
    <script src="https://code.highcharts.com/4.2.2/modules/exporting.js"></script>

    <script type="text/javascript">
        var spinnerVisible = false;

        function showProgress() {
            if (!spinnerVisible) {
                $("div#spinner").fadeIn("fast");
                spinnerVisible = true;
            }
        }

        function hideProgress() {
            if (spinnerVisible) {
                $("div#spinner").fadeOut("fast");
                spinnerVisible = false;
            }
        }

        $(function () {
            $('[data-toggle="popover"]').popover({
                placement: 'bottom'
            });

            $("#chart").highcharts({
                chart: {
                    renderTo: 'chart',
                    type: 'line',
                    zoomType: 'x',
                    animation: Highcharts.svg,  // Don't animate in old IE
                    marginRight: 10
                },
                title: {
                    text: 'Flight Parameter Chart'
                },
                xAxis: {
                    title: {
                        text: 'Time'
                    },
                    type: 'datetime',
                    dateTimeLabelFormats: {
                        second: '%H:%M:%S'
                    },
                    categories: []
                },
                yAxis: {
                    title: {
                        text: 'Value'
                    },
                    plotLines: [{
                        value: 0,
                        width: 1
                    }]
                },
                series: [],
                plotOptions: {
                    series: {
                        marker: {
                            enabled: false
                        }
                    }
                },
                credits: {
                    enabled: false
                },
                tooltip: {
                    crosshairs: [{
                        width: 1,
                        dashStyle: 'solid',
                        color: '#CCC'
                    }, false],
                    formatter: function () {
                        return '<b>' + this.series.name + '</b><br/>' +
                            '<b>time: ' + this.x + '</b><br/>' +
                            'value: ' + '</b>' + Highcharts.numberFormat(this.y, 2) + '</b>';
                    }
                },
                legend: {
                    enabled: true,
                    borderWidth: 0
                },
                exporting: {
                    enabled: true
                }
            });

            $("#display").click(function () {
                var chart = $('#chart').highcharts();

                // Remove old series data
                while (chart.series.length > 0) {
                    chart.series[0].remove(true);
                }

                var param = [];
                var i = 0;
                $.each($("input[name='param[]']:checked"), function () {
                    param.push($(this).val());
                });

                showProgress();

                var CSRF_TOKEN = $('input[name="_token"]').val();
                for (i = 0; i < param.length; i++) {
                    var url = $("#display").attr('data-link');

                    var idx = param[i];
                    url = url + "?param=" + idx;

                    $.ajax({
                        url: url,
                        type: "GET",
                        data: {_token: CSRF_TOKEN},

                        success: function (data) {
                            if (data.hasOwnProperty('data')) {
                                if (data.data.found === false) {
                                    alert('There was a problem displaying the graph');
                                }
                                else {
                                    // Plot new series data
                                    chart.addSeries({
                                        name: data.data.name,
                                        data: JSON.parse("[" + data.data.series + "]")
                                    });
                                    chart.xAxis[0].setCategories(data.data.time);
                                    chart.yAxis[0].setExtremes(null, null);
                                    chart.redraw();
                                }
                            }
                        }
                    });
                }

                hideProgress();
            });

            $("a[id^=dwldFlight]").click(function () {
                var duration = 0;
                var url = $(this).attr("data-link");
                var CSRF_TOKEN = $('input[name="_token"]').val();

                showProgress();

                $('#downloadStatus').modal('hide');

                $.ajax({
                    url: url + '/' + duration,
                    type: "GET",
                    data: {_token: CSRF_TOKEN},

                    success: function (data) {
                        hideProgress();

                        // Show message in modal
                        if (data.hasOwnProperty('data')) {
                            if (data.data.found == false) {
                                $('#downloadStatus .modal-body').html('<p>There was a problem retrieving information for this flight due to invalid data.</p>');
                            }
                            if (data.data.found == true) {
                                $('#downloadStatus .modal-body').html('<a id="dwldLink" href="' + data.data.file + '">Download File</a>');
                            }
                        }

                        $('#downloadStatus').modal('show');
                    }
                });
            });
        });
    </script>
@endsection
