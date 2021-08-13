@extends('layouts.master')
@section('title')
    {{trans_choice('general.app_name',1)}} | Dashboard
@endsection
@section('content')
    <div class="row">
        @if(Sentinel::hasAccess('dashboard.registered_borrowers'))
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="panel panel-body bg-blue-400 has-bg-image">
                    <div class="media no-margin">
                        <div class="media-body">
                            <h3 class="no-margin">{{ \App\Models\Borrower::count() }}</h3>
                            <span class="text-uppercase text-size-mini">{{trans_choice('general.active_client',1)}}<!---{{ trans_choice('general.total',1) }} {{ trans_choice('general.borrower',2) }}---></span>
                        </div>

                        <div class="media-right media-middle">
                            <i class="icon-users4 icon-3x opacity-100"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(Sentinel::hasAccess('dashboard.total_loans_released'))
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="panel panel-body bg-indigo-400 has-bg-image">
                    <div class="media no-margin">
                        <div class="media-body">
                            @if(\App\Models\Setting::where('setting_key', 'currency_position')->first()->setting_value=='left')
                                <h3 class="no-margin"> {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value }}{{ number_format(\App\Helpers\GeneralHelper::loans_total_principal(),2) }} </h3>
                            @else
                                <h3 class="no-margin"> {{ number_format(\App\Helpers\GeneralHelper::loans_total_principal(),2) }}  {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value}}</h3>
                            @endif
                            <span class="text-uppercase text-size-mini">{{trans_choice('general.total_disbursed',1)}}</span>
                        </div>
                        <div class="media-right media-middle">
                            <i class="icon-drawer-out icon-3x opacity-100"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(Sentinel::hasAccess('dashboard.total_collections'))
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="panel panel-body bg-success-400 has-bg-image">
                    <div class="media no-margin">
                        <div class="media-body">
                            @if(\App\Models\Setting::where('setting_key', 'currency_position')->first()->setting_value=='left')
                                <h3 class="no-margin">{{ number_format(\App\Helpers\GeneralHelper::loans_total_paid(),2) }} </h3>
                            @else
                                <h3 class="no-margin">{{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value}}</h3>
                            @endif
                            <span class="text-uppercase text-size-mini">{{trans_choice('general.payment_received',1)}}<!---{{ trans_choice('general.payment',2) }}---></span>
                        </div>
                        <div class="media-right media-middle">
                            <i class="icon-enter6 icon-3x opacity-100"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(Sentinel::hasAccess('dashboard.loans_disbursed'))
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="panel panel-body bg-danger-400 has-bg-image">
                    <div class="media no-margin">
                        <div class="media-body">
                            @if(\App\Models\Setting::where('setting_key', 'currency_position')->first()->setting_value=='left')
                                <h3 class="no-margin">{{ number_format(\App\Helpers\GeneralHelper::loans_total_due(),2) }}</h3>
                            @else
                                <h3 class="no-margin">{{ number_format(\App\Helpers\GeneralHelper::loans_total_due(),2) }}</h3>
                            @endif
                            <span class="text-uppercase text-size-mini">{{trans_choice('general.total_debit',1)}}<!---{{ trans_choice('general.due',1) }} {{ trans_choice('general.amount',1) }}---></span>
                        </div>
                        <div class="media-right media-middle">
                            <i class="icon-pen-minus icon-3x opacity-100"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    <!-- GRAFICA PIE -->
    <div class="row">
        @if(Sentinel::hasAccess('dashboard.loans_disbursed'))
            <div class="col-md-4">
                <div class="panel panel-flat">
                    <div class="panel-body">
                        <div class="list-group no-border no-padding-top bg-info">
                            @foreach(json_decode($loan_statuses) as $key)
                                <a href="{{$key->link}}" class="list-group-item">
                                    <span class="pull-right"><strong>{{$key->value}}</strong></span>
                                    <strong>{{$key->label}}</strong>
                                </a>
                            @endforeach
                        </div>
                        <center>
                        <canvas id="loan_status_pie" height="334"></canvas>
                        </center>
                    </div>
                </div>
            </div>
            @endif
            
            
        <div class="col-md-8">

            @if(Sentinel::hasAccess('dashboard.loans_collected_monthly_graph'))
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <center><h2 class="panel-title">{{trans_choice('general.monthly_payment_trend',1)}}</h2></center>
                        <div class="heading-elements">
                            <!---<ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                                <li><a data-action="close"></a></li>
                            </ul>--->
                        </div>
                    </div>
                    <div class="panel-body">
                        <div id="monthly_actual_expected_data" class="chart" style="height: 320px;">
                        </div>
                    </div>
                </div>
            @endif
            
        @if(Sentinel::hasAccess('dashboard.loans_disbursed'))
            <!-- Sales stats -->
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <center>
                        <h2 class="panel-title">{{trans_choice('general.collection_distribution',1)}}<!---{{ trans_choice('general.collection',1) }} {{ trans_choice('general.statistic',2) }}---></h2></center>
                        <div class="heading-elements">
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php
                        $target = 0;
                        foreach (\App\Models\LoanSchedule::where('year', date("Y"))->where('month',
                            date("m"))->get() as $key) {
                            $target = $target + $key->principal + $key->interest + $key->fees + $key->penalty;
                        }
                        $paid_this_month = \App\Models\LoanTransaction::where('transaction_type',
                            'repayment')->where('reversed', 0)->where('year', date("Y"))->where('month',
                            date("m"))->sum('credit');
                        if ($target > 0) {
                            $percent = round(($paid_this_month / $target) * 100);
                        } else {
                            $percent = 0;
                        }

                        ?>
                        <div class="container-fluid">
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <div class="content-group">
                                        @if(\App\Models\Setting::where('setting_key', 'currency_position')->first()->setting_value=='left')
                                            <h3 class="text-semibold no-margin">{{ number_format(\App\Models\LoanTransaction::where('transaction_type',
                    'repayment')->where('reversed', 0)->where('date',date("Y-m-d"))->sum('credit'),2) }}</h3>
                                        @else
                                            <h3 class="text-semibold no-margin">{{ number_format(\App\Models\LoanTransaction::where('transaction_type',
                    'repayment')->where('reversed', 0)->where('date',date("Y-m-d"))->sum('credit'),2) }}</h3>
                                        @endif

                                        <h5 class="text-semibold no-margin">{{trans_choice('general.paid_today',1)}}</h5>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="content-group">
                                        @if(\App\Models\Setting::where('setting_key', 'currency_position')->first()->setting_value=='left')
                                            <h3 class="text-semibold no-margin">{{ number_format(\App\Models\LoanTransaction::where('transaction_type',
                    'repayment')->where('reversed', 0)->whereBetween('date',array('date_sub(now(),INTERVAL 1 WEEK)','now()'))->sum('credit'),2) }} </h3>
                                        @else
                                            <h3 class="text-semibold no-margin">{{ number_format(\App\Models\LoanTransaction::where('transaction_type',
                    'repayment')->where('reversed', 0)->whereBetween('date',array('date_sub(now(),INTERVAL 1 WEEK)','now()'))->sum('credit'),2) }}</h3>
                                        @endif
                                        <h5 class="text-semibold no-margin">{{trans_choice('general.paid_last_week',1)}}</h5>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="content-group">
                                        @if(\App\Models\Setting::where('setting_key', 'currency_position')->first()->setting_value=='left')
                                            <h3 class="text-semibold no-margin">{{ number_format($paid_this_month,2) }} </h3>
                                        @else
                                            <h3 class="text-semibold no-margin">{{ number_format($paid_this_month,2) }}</h3>
                                        @endif
                                        <h5 class="text-semibold no-margin">{{trans_choice('general.paid_this_month',1)}}</h5>
                                    </div>
                                </div>

                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <h6 class="no-margin text-semibold bg-primary">{{trans_choice('general.total_of',1)}} ${{number_format($target,2)}} {{trans_choice('general.equivalent_to',1)}} {{$percent}}% {{trans_choice('general.collection',1)}}<!---{{ trans_choice('general.monthly',1) }} {{ trans_choice('general.target',1) }}---></h6>
                                    </div>
                                    <div class="progress" data-toggle="tooltip">

                                        <div class="progress-bar bg-teal progress-bar-striped active"
                                             style="width: {{$percent}}%">
                                            <span>{{$percent}}% {{trans_choice('general.completed',1)}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif            
 </div>
 
 </div>
 @if(Sentinel::hasAccess('dashboard.loans_collected_monthly_graph'))
  <div class="panel panel-white">
                <div class="panel-heading">
                    <h2 class="panel-title">{{trans_choice('general.capital_disbursed_per_month',1)}}</h2>

                    <div class="heading-elements">

                    </div>
                </div>
                <div class="panel-body  no-padding">
                    <div id="monthly_disbursed_loans_data" class="chart" style="height: 420px;">
                    </div>
                </div>
            </div>
@endif            


<script>
        $(document).ready(function () {
            $("body").addClass('sidebar-xs');
        });
    </script>

    <script src="{{ asset('assets/plugins/amcharts/amcharts.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/amcharts/serial.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/amcharts/pie.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/amcharts/themes/light.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/amcharts/plugins/export/export.min.js') }}"
            type="text/javascript"></script>
            
    <script>   
    
        AmCharts.makeChart("monthly_actual_expected_data", {
            "type": "serial",
            "theme": "light",
            "autoMargins": true,
            "marginLeft": 30,
            "marginRight": 8,
            "marginTop": 10,
            "marginBottom": 26,
            "fontFamily": 'Open Sans',
            "color": '#888',

            "dataProvider": {!! $monthly_actual_expected_data !!},
            "valueAxes": [{
                "axisAlpha": 0,

            }],
            "startDuration": 1,
            "graphs": [{
                "balloonText": "<span style='font-size:20px;'>[[title]] en el mes [[category]]:<b> $[[value]]</b> [[additional]]</span>",
                "bullet": "round",
                "bulletSize": 10,
                "lineColor": "#0DD102",
                "lineThickness": 3,
                "negativeLineColor": "#0dd102",
                "title": "{{ trans('general.payment_received') }}",
                "type": "smoothedLine",
                "valueField": "actual"
            }, {
                "balloonText": "<span style='font-size:20px;'>[[title]] en el mes [[category]]:<b> $[[value]]</b> [[additional]]</span>",
                "bullet": "round",
                "bulletSize": 10,
                "lineColor": "#ADADAD",
                "lineThickness": 3,
                "negativeLineColor": "#ADADAD",
                "title": "{{ trans('general.expected_payment') }}",
                "type": "smoothedLine",
                "valueField": "expected"
            }],
            "categoryField": "month",
            "categoryAxis": {
                "gridPosition": "start",
                "axisAlpha": 0,
                "tickLength": 0,
                "labelRotation": 30,

            }, "export": {
                "enabled": true,
                "libs": {
                    "path": "{{asset('assets/plugins/amcharts/plugins/export/libs')}}/"
                }
            }, "legend": {
                "position": "bottom",
                "marginRight": 100,
                "autoMargins": true
            },


        });
  
    </script>
     
         <script src="{{ asset('assets/plugins/chartjs/Chart.min.js') }}"
            type="text/javascript"></script>
    <script>
        var ctx3 = document.getElementById("loan_status_pie").getContext("2d");
        var data3 ={!! $loan_statuses !!};
        var myBarChart = new Chart(ctx3).Pie(data3, {
            type: 'pie',
            segmentShowStroke: true,
            segmentStrokeColor: "#fff",
            segmentStrokeWidth: 0,
            animationSteps: 100,
            tooltipCornerRadius: 0,
            animationEasing: "linear",
            animateRotate: true,
            animateScale: true,
            responsive: true,

            legend: {
                display: true,
                labels: {
                    fontColor: 'rgb(255, 99, 132)'
                }
            }
        });
        
        AmCharts.makeChart("monthly_disbursed_loans_data", {
            "type": "serial",
//            "theme": "light",
//            "autoMargins": true,
            "marginLeft": 30,
            "marginRight": 8,
            "marginTop": 10,
            "marginBottom": 26,
            "fontFamily": 'Open Sans',
            "color": '#888',

            "dataProvider": {!! $monthly_disbursed_loans_data !!},
            "valueAxes": [{
                "axisAlpha": 0,

            }],
            "startDuration": 1,
            "graphs": [{
                "balloonText": "<span style='font-size:20px;'>[[title]] en el mes de [[category]]: $<b>[[value]]</b> [[additional]]</span>",
                "lineColor": "#1d81cf",
                "fillAlphas": 1,
                "negativeLineColor": "#1d81cf",
                "title": "{{trans_choice('general.principal',1)}}",
                "type": "column",
                "valueField": "value",
            }],
            "categoryField": "month",
            "categoryAxis": {
                "gridPosition": "start",
                "axisAlpha": 0,
                "tickLength": 0,
                "labelRotation": 30,

            }, "export": {
                "enabled": true,
                "libs": {
                    "path": "{{asset('assets/plugins/amcharts/plugins/export/libs')}}/"
                }
            }


        });
        
    </script>
   
    
@endsection
