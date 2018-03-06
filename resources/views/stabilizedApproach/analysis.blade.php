@extends('NGAFID-master')

@section('cssScripts')
    <link href="https://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b>Stabilized Approach Analysis</b>
                        <span class="pull-right">{{ date("D M d, Y G:i A T") }}</span>
                    </div>

                    {!! Form::open(['method' => 'GET', 'url' => '/approach/analysis', 'class' => 'form-horizontal', 'id' => 'saTool']) !!}
                        {!! Form::token() !!}
                        <div class="panel-body">
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::Label('Airport', 'Airport:') !!}
                                    {!! Form::text('airports', '', ['class' => 'form-control', 'id' => 'airports']) !!}
                                </div>
                            </div>
                            <div class="col-md-2 col-md-offset-1">
                                <div class="form-group">
                                    {!! Form::Label('Runway', 'Runway:') !!}
                                    {!! Form::select('runway', $airports, $selectedRunway, ['class' => 'form-control', 'id' => 'runway']) !!}
                                </div>
                            </div>
                            <div class="col-md-2 col-md-offset-1">
                                <div class="form-group">
                                    {!! Form::Label('Analysis', 'Analysis:') !!}
                                    {!! Form::select('type', $data['type'], $data['selType'], ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-2 col-md-offset-1">
                                <div class="form-group">
                                    {!! Form::Label('Month/Year', 'Month/Year:') !!}
                                    <div class="input-group">
                                        <input class="form-control mthYr" id="mthYr" type="text" name="mthYr" value="{{ $data['date'] }}" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" id="display" class="btn btn-primary btn-sm pull-right" data-link="{{ url('/approach/analysis') }}">
                                    Display
                                </button>

                            </div>

                            <br><br>

                            <div id="chart" class="col-md-12"></div>

                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('jsScripts')
    <script src="https://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

    <script src="https://code.highcharts.com/4.2.2/highcharts.js"></script>
    <script src="https://code.highcharts.com/4.2.2/modules/drilldown.js"></script>
    <script src="https://code.highcharts.com/4.2.2/modules/no-data-to-display.js"></script>
    <script src="https://code.highcharts.com/4.2.2/modules/exporting.js"></script>

    <script type="text/javascript">
        var isVisible = [];
        var numSeries = 0;
        var parentSubTitle = '';

        Highcharts.setOptions({
            lang: {
                drillUpText: '◁ Back'
            },
            drilldown: {
                animation: false
            },
            plotOptions: {
                series: {
                    animation: false
                }
            }
        });

        Highcharts.Tick.prototype.drillable = function () {};

        var parentY = '';
        var parentX = '';
        var upCtr = 0;

        $(function () {
            $("#airports").autocomplete({
                source: "airports",
                minLength: 3,
                select: function (event, ui) {
                    var idx = ui.item.id;
                    var CSRF_TOKEN = $('input[name="_token"]').val();
                    $.ajax({
                        type: "GET",
                        url: "{{url('/approach/runways')}}",
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

            $('.mthYr').datepicker({
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'yy-mm'
            }).focus(function () {
                var thisCalendar = $(this);
                $('.ui-datepicker-calendar').detach();
                $('.ui-datepicker-close').click(function () {
                    var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                    var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                    thisCalendar.datepicker('setDate', new Date(year, month, 1));
                });
            });

            $("#saTool").submit(function (e) {
                e.preventDefault();
                var chart = $('#chart').highcharts();
                var mthYr = $("input#mthYr").val();
                var qry = $('select[name=type]').val();
                var rnwy = $('select[name=runway]').val();
                var CSRF_TOKEN = $('input[name="_token"]').val();
                var url = $("#display").attr('data-link');


                if (chart.drilldownLevels !== undefined && chart.drilldownLevels.length > 0) {
                    chart.drillUp();
                }

                chart.showLoading('Loading ...');

                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        mthYr: mthYr,
                        type: qry,
                        runway: rnwy,
                        _token: CSRF_TOKEN
                    },
                    success: function (data) {
                        if (data.hasOwnProperty('data')) {
                            var total = (data.data.series).length;

                            if (total > 0) {
                                chart.legend.title.attr({
                                    text: '<span style="font-size: 9px; color: #666; font-weight: normal;">(Click to show/hide)</span>'
                                });

                                isVisible = [];
                                numSeries = total;

                            }

                            while (chart.series.length > 0) {
                                chart.series[0].remove(true);
                            }

                            for (i = 0; i < total; i++) {
                                chart.addSeries({
                                    id: data.data.series[i].id,
                                    name: data.data.series[i].name,
                                    marker: {symbol: data.data.series[i].symbol},
                                    data: data.data.series[i].data,
                                    visible: data.data.series[i].visible
                                }, false);
                            }

                            chart.hideLoading();
                            chart.yAxis[0].update({
                                labels: {
                                    enabled: data.data.yAxis
                                },
                                title: {
                                    text: data.data.yLabel
                                }
                            });
                            chart.xAxis[0].update({
                                title: {
                                    text: data.data.xLabel
                                }
                            });
                            chart.setTitle(null, {text: data.data.subTitle});
                            parentSubTitle = data.data.subTitle;

                            if (data.data.quad === true) {
                                if (total > 0) {
                                    chart.xAxis[0].addPlotLine({
                                        id: 'hlfs-origin-x',
                                        value: 0,
                                        color: 'rgb(192, 192, 192)',
                                        width: 2
                                    });
                                    chart.yAxis[0].addPlotLine({
                                        id: 'hlfs-origin-y',
                                        value: 0,
                                        color: 'rgb(192, 192, 192, 0.6)',
                                        width: 2
                                    });
                                }
                            }
                            else {
                                chart.xAxis[0].removePlotLine('hlfs-origin-x');
                                chart.yAxis[0].removePlotLine('hlfs-origin-y');

                                if (total > 0) {
                                    $(chart.series[1].legendItem.element).trigger('click'); //hide minimum on load
                                    $(chart.series[2].legendItem.element).trigger('click'); //hide maximum on load
                                }
                            }
                        }
                        else {
                            chart.hideLoading();
                        }
                    }
                }, "json");
            });

            $("#chart").highcharts({
                chart: {
                    renderTo: 'chart',
                    type: 'scatter',
                    zoomType: 'xy',
                    animation: false,
                    events: {
                        drilldown: function (e) {
                            // Append something to the subtitle and remove below on drillup
                            var chart = this;
                            parentY = chart.options.yAxis[0].title.text;
                            parentX = chart.options.xAxis[0].title.text;
                            upCtr = 0;

                            chart.showLoading('Loading ...');

                            for (j = 0; j < numSeries; j++) {
                                tmpSeries = chart.get(j + 1);
                                if (tmpSeries.visible === true) {
                                    isVisible.push(true);
                                }
                                else {
                                    isVisible.push(false);
                                }
                            }

                            var urlChart = '{{url('/approach/chart')}}';
                            var CSRF_TOKEN = $('input[name="_token"]').val();

                            $.ajax({
                                type: "GET",
                                url: urlChart,
                                data: {
                                    drill: e.point.drilldown,
                                    _token: CSRF_TOKEN
                                },
                                success: function (data) {
                                    if (data.hasOwnProperty('data')) {
                                        var total = (data.data.result).length;
                                        for (i = 0; i < total; i++) {
                                            chart.addSingleSeriesAsDrilldown(e.point, data.data.result[i]);
                                        }

                                        chart.hideLoading();

                                        chart.xAxis[0].setCategories(data.data.time, false);

                                        chart.yAxis[0].update({
                                            title: {
                                                text: 'Value'
                                            }
                                        });
                                        chart.xAxis[0].update({
                                            title: {
                                                text: 'Time'
                                            }
                                        });

                                        chart.xAxis[0].removePlotLine('hlfs-origin-x');
                                        chart.yAxis[0].removePlotLine('hlfs-origin-y');

                                        chart.setTitle(null, {text: data.data.summary});
                                        chart.applyDrilldown();
                                    }
                                }
                            }, "json");
                        },
                        drillup: function (e) {
                            if (upCtr == numSeries) {  //vFlag... drillup event being fired multiple times by highcharts
                                var chart = this;

                                this.yAxis[0].update({
                                    title: {
                                        text: parentY
                                    }
                                });

                                this.xAxis[0].update({
                                    title: {
                                        text: parentX
                                    }
                                });

                                // Add the line for the quadrants if parent is the HLFS graph (this isn't the ideal way...)
                                if (parentX.indexOf('Ideal Speed') >= 0) {
                                    this.xAxis[0].addPlotLine({
                                        id: 'hlfs-origin-x',
                                        value: 0,
                                        color: 'rgb(192, 192, 192)',
                                        width: 2
                                    });

                                    this.yAxis[0].addPlotLine({
                                        id: 'hlfs-origin-y',
                                        value: 0,
                                        color: 'rgb(192, 192, 192, 0.6)',
                                        width: 2
                                    });
                                }

                                for (j = 0; j < numSeries; j++) {
                                    this.get(j + 1).setVisible(isVisible[j]);
                                }

                                this.xAxis[0].setCategories(null);
                                this.setTitle(null, {text: parentSubTitle});
                            }

                            upCtr += 1;
                        }
                    },
                    animation: Highcharts.svg  // Don't animate in old IE
                },
                title: {
                    text: 'Stabilized Approach Analysis'
                },
                series: [],
                drilldown: {
                    series: []
                },
                plotOptions: {
                    scatter: {
                        animation: false,
                        marker: {
                            states: {
                                hover: {
                                    enabled: true,
                                    lineColor: 'rgb(100,100,100)',
                                    fillColor: false
                                }
                            }
                        },
                        allowPointSelect: true,
                        dataLabels: {
                            enabled: false
                        },
                        tooltip: {
                            headerFormat: '<b>{series.info}</b><br>'
                        },
                        showInLegend: true,
                        events: {
                            legendItemClick: function () {}
                        },
                        colorByPoint: true,
                        turboThreshold: 9999
                    },
                    line: {
                        animation: false
                    }
                },
                credits: {
                    enabled: false
                },
                tooltip: {
                    formatter: function () {
                        var point = this.point;
                        var val = '<b>' + this.point.info + '</b>';

                        if (point.drilldown) {
                            val += '<br><b>Click to view graph</b>';
                        } else {
                            var name = this.point.series.name;
                            val = '<b>' + name.toUpperCase() + '</b>' +
                                '<br/><b>Value: ' + '</b>' + Highcharts.numberFormat(this.y, 3) +
                                '<br/><b>Time: ' + '</b>' + this.x;
                        }

                        return val;
                    }
                },
                legend: {
                    title: {
                        text: " ",
                        style: {
                            fontStyle: 'italic'
                        }
                    },
                    layout: 'vertical',
                    backgroundColor: '#FFFFFF',
                    floating: true,
                    align: 'right',
                    x: -30,
                    y: 5,
                    verticalAlign: 'top',
                    borderWidth: 1,
                    borderColor: '#708090',
                    color: '#708090',
                    backgroundColor: 'rgba(255, 255, 255, 0.7)',
                    shadow: true
                },
                exporting: {
                    enabled: true
                }
            });
        });
    </script>

@endsection
