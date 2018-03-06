@extends('NGAFID-master')

@section('cssScripts')
    <link href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css" rel="stylesheet" />
    <style>
        .ui-datepicker-calendar {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b>Self Defined Approach Analysis</b>
                        <span class="pull-right">{{date("D M d, Y G:i A T")}}</span>
                    </div>

                    <div class="panel-body">
                        {!! Form::open(['method' => 'GET', 'url' => '/approach/selfdefined', 'class' => 'form-horizontal', 'id' => 'sdTool']) !!}
                            {!! Form::token() !!}
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('Airport', 'Airport:') !!}
                                    {!! Form::text('airports', '', ['class' => 'form-control', 'id' => 'airports']) !!}
                                </div>
                            </div>
                            <div class="col-md-2 col-md-offset-1">
                                <div class="form-group">
                                    {!! Form::label('Runway', 'Runway:') !!}
                                    {!! Form::select('runway', $airports, $selectedRunway, ['placeholder' => 'Select Runway', 'class' => 'form-control', 'id' => 'runway']) !!}
                                </div>
                            </div>
                            <div class="col-md-2 col-md-offset-1">
                                <div class="form-group">
                                    {!! Form::label('Month/Year', 'Month/Year:') !!}
                                    <div class="input-group">
                                        <input class="form-control mthYr" id="mthYr" type="text" name="mthYr" value="{{ $date }}" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" id="display" class="btn btn-primary btn-sm pull-right" data-link="{{ url('/approach/selfdefined') }}">
                                    Display
                                </button>
                            </div>

                            <br /><br />

                            <div id="chart" class="col-md-12"></div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('jsScripts')
    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

    <script src="http://code.highcharts.com/4.2.2/highcharts.js"></script>
    <script src="http://code.highcharts.com/4.2.2/modules/drilldown.js"></script>
    <script src="http://code.highcharts.com/4.2.2/modules/no-data-to-display.js"></script>
    <script src="http://code.highcharts.com/4.2.2/modules/exporting.js"></script>

    <script type="text/javascript">
        $(function () {

            // Provide auto-complete feature to Airports text box
            $("#airports").autocomplete({
                source: "airports",
                minLength: 3,
                select: function (event, ui) {
                    var idx = ui.item.id;
                    var CSRF_TOKEN = $('input[name="_token"]').val();
                    $.ajax({
                        type: "GET",
                        url: "{{ url('/approach/runways') }}",
                        data: {code: idx, _token: CSRF_TOKEN},
                        success: function (data) {
                            var items = "";
                            $.each(data.data, function (key, val) {
                                items += '<option value="' + key + '">' + val + '</option>';
                            });

                            $('#runway').html(items);
                        }
                    });
                }
            });

            // jQuery UI datepicker
            $('#mthYr').datepicker({
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'yy-mm',
                onClose: function (dateText, inst) {
                    function isDonePressed() {
                        return $('#ui-datepicker-div').html().indexOf('ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all ui-state-hover') > -1;
                    }

                    if (isDonePressed()) {
                        var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                        var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                        $(this).datepicker('setDate', new Date(year, month, 1)).trigger('change');

                        $('#mthYr').focusout();
                    }
                },
                beforeShow: function (input, inst) {
                    inst.dpDiv.addClass('month_year_datepicker');

                    if ((datestr = $(this).val()).length > 0) {
                        var year = datestr.substring(0, 4);
                        var month = datestr.substring(datestr.length - 2, datestr.length);
                        $(this).datepicker('option', 'defaultDate', new Date(year, month - 1, 1));
                        $(this).datepicker('setDate', new Date(year, month - 1, 1));
                    }
                }
            });

            Highcharts.chart('chart', {
                chart: {
                    inverted: true,
                    type: 'column'
                },
                title: {
                    text: 'Self Defined Glide Path Analysis'
                },
                xAxis: {
                    title: {
                        text: 'Glide Path (in degrees)'
                    },
                    tickInterval: 0.5,
                    min: 0,
                    max: 10,
                    startOnTick: true,
                    reversed: false,
                    gridLineWidth: 1
                },
                yAxis: {
                    title: {
                        text: 'Number of occurrences'
                    },
                    tickInterval: 1
                },
                plotOptions: {
                    column: {
                        colorByPoint: false,
                        tooltip: {
                            pointFormatter: function () {
                                return '<b>' + this.x + '\u00B0-' + (this.x + .5) + '\u00B0</b>' +
                                    '<br /><b>Occurrences: ' + this.y + '</b>';
                            }
                        }
                    },
                    series: {
                        cursor: 'pointer',
                        events: {
                            click: function (e) {
                                window.open(
                                    "{{ url('approach/selfdefined/flights?') }}" +
                                    $.param({
                                        runway: $('#runway :selected').html(),
                                        date: $('#mthYr').val(),
                                        gpa_low: e.point.x,
                                        gpa_high: e.point.x + 0.5,
                                        flight_id: e.point.ids
                                    }), ''
                                ).focus();
                            }
                        }
                    }
//                    scatter: {
//                        tooltip: {
//                            pointFormatter: function () {
//                                return '<b>' + this.info + '</b>' +
//                                    '<br /><b>Glide Path: ' + this.x + '\u00B0</b>';
//                            }
//                        }
//                    }
                }
            });

            $('#sdTool').submit(function (e) {
                e.preventDefault();

                var chart = $('#chart').highcharts();
                var mthYr = $('#mthYr').val();
                var rnwy = $('#runway').val();
                var CSRF_TOKEN = $('input[name="_token"]').val();

                chart.showLoading('Loading ...');

                while (chart.series.length > 0)
                    chart.series[0].remove(true);

                $.ajax({
                    type: 'GET',
                    dataType: 'json',
                    url: '{{ url('/approach/selfdefined/chart') }}',
                    data: {date: mthYr, runway: rnwy, _token: CSRF_TOKEN},
                    success: function (data) {
//                        alert('success');

                        chart.addSeries({
                            name: 'Histogram',
                            type: 'column',
                            data: data['data'],
                            pointPadding: 0,
                            groupPadding: 0,
                            pointPlacement: 'between'
                        });

//                        chart.addSeries({
//                            name: 'Glide Path',
//                            type: 'scatter',
//                            data: data['data'],
//                            marker: {
//                                radius: 2,
//                                fillColor: '#000'
//                            }
//                        });

                        chart.hideLoading();
                    },
                    error: function (data) {
                    }
                });
            });

        });
    </script>
@endsection
