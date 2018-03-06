@extends('NGAFID-master')

@section('cssScripts')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b>Trend Detection</b>
                        <span class="pull-right">{{date("D M d, Y G:i A T")}}</span>
                    </div>

                    <div class="panel-body">
                        {!! Form::open(['method' => 'GET', 'url' => 'flights/trend', 'class' => 'form-horizontal']) !!}
                        <p class="col-md-offset-1">
                            Select aircraft type, event:
                        </p>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-md-4 control-label">Aircraft</label>
                                <div class="col-md-6">
                                    {!! Form::select('aircraft', $trendData['aircraft'], $trendData['selectedAircraft'], ["class" => "form-control"]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Event</label>
                                <div class="col-md-6">
                                    {!! Form::select('event', $trendData['events'], $trendData['selectedEvent'], ["class" => "form-control"]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-md-4 control-label">
                                    Start Date
                                </label>
                                <div class="col-md-6 input-group">
                                    <input class="form-control" id="startDatepicker" type="text" name="startDate" value="{{ $trendData['startDate'] }}" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">
                                    End Date
                                </label>
                                <div class="col-md-6 input-group">
                                    <input class="form-control" id="endDatepicker" type="text" name="endDate" value="{{$trendData['endDate']}}" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    Display
                                </button>
                            </div>
                        </div>
                        <div id="container" class="col-md-10 col-md-offset-1"></div>
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
        var datePickerOptions = {
            changeYear: true,
            dateFormat: 'yy-mm-dd'
        };

        $(function () {
            $("#startDatepicker").datepicker(datePickerOptions);
            $("#endDatepicker").datepicker(datePickerOptions);
        });
    </script>
    <script type="text/javascript">
        $(function () {
            $(document).ready(function () {
                Highcharts.setOptions({
                    global: {
                        useUTC: false
                    }
                });

                var chart = new Highcharts.Chart({
                    chart: {
                        renderTo: 'container',
                        type: 'column',
                        animation: Highcharts.svg, // don't animate in old IE
                        marginRight: 10
                    },
                    title: {
                        text: 'Trend: {{$trendData['chart']['name']}}'
                    },
                    xAxis: {
                        title: {
                            text: 'Month-Year'
                        },
                        labels: {
                            format: "{value:%m-%Y}"
                        },
                        categories: [{{ implode(",", $trendData['chart']['categories']) }}]
                    },
                    yAxis: {
                        title: {
                            text: 'Percentage of Occurrences'
                        },
                        plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080'
                        }]
                    },
                    tooltip: {
                        formatter: function () {
                            return '<b>' + this.series.name + '</b><br/>' +
                                '<b>' + Highcharts.dateFormat('%m-%Y', this.x) + '</b><br/>' +
                                'percentage of occurrence: ' + '</b>' + Highcharts.numberFormat(this.y, 2) + '</b>';
                        }
                    },
                    legend: {
                        enabled: false
                    },
                    exporting: {
                        enabled: true
                    },
                    series: [{
                        name: '{{$trendData['chart']['name']}}',
                        data: [{{ implode(",", $trendData['chart']['data']) }}]

                    }],
                    credits: {
                        enabled: false
                    }
                });
            });
        });
    </script>
@endsection
