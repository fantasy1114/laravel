@extends('layouts.master')
@section('title')
    {{trans_choice('general.financial',1)}} {{trans_choice('general.report',2)}}
@endsection
@section('content')
    <div class="panel panel-white">
        <div class="panel-heading">
            <h6 class="panel-title">{{trans_choice('general.financial',1)}} {{trans_choice('general.report',2)}}</h6>

            <div class="heading-elements">

            </div>
        </div>
        <div class="panel-body">
            <table id="" class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>{{trans_choice('general.report_code',1)}}</th>
                    <th>{{trans_choice('general.report_description',1)}}</th>
                    <th>{{trans_choice('general.actions',1)}}</th>
                </tr>
                </thead>
                <tbody>

                <tr>
                    <td>
                        <a class="btn btn-info btn-lg btn-block" role="button" href="{{url('report/financial_report/balance_sheet')}}">RF001</a>
                    </td>
                    <td>
                    {{trans_choice('general.cash_flow_summary_report',1)}}
                    </td>
                    <td><a class="btn btn-success" role="button" href="{{url('report/financial_report/balance_sheet')}}">{{trans_choice('general.open',1)}} <i class="icon-search4"></i> </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a class="btn btn-info btn-lg btn-block" role="button" href="{{url('report/financial_report/trial_balance')}}">RF002</a>
                    </td>
                    <td>
                        {{trans_choice('general.trial_balance',1)}}
                    </td>
                    <td><a class="btn btn-success" role="button" href="{{url('report/financial_report/trial_balance')}}">{{trans_choice('general.open',1)}} <i class="icon-search4"></i> </a>
                    </td>
                </tr>

                <tr class="hidden">
                    <td>
                        <a class="btn btn-info btn-lg btn-block" role="button" href="{{url('report/financial_report/cash_flow')}}">RF003</a>
                    </td>
                    <td>
                        {{trans_choice('general.cash_flow',1)}}
                    </td>
                    <td><a class="btn btn-success" role="button" href="{{url('report/financial_report/cash_flow')}}">{{trans_choice('general.open',1)}} <i class="icon-search4"></i> </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a class="btn btn-info btn-lg btn-block" role="button" href="{{url('report/financial_report/income_statement')}}">RF004</a>
                    </td>
                    <td>
                        {{trans_choice('general.income_statement_description',1)}}
                    </td>
                    <td><a class="btn btn-success" role="button" href="{{url('report/financial_report/income_statement')}}">{{trans_choice('general.open',1)}} <i class="icon-search4"></i> </a>
                    </td>
                </tr>
                <!--<tr>
                    <td>
                        <a class="btn btn-info btn-lg btn-block" role="button" href="{{url('report/financial_report/provisioning')}}">RF005</a>
                    </td>
                    <td>
                        {{trans_choice('general.provisioning_description',1)}}
                    </td>
                    <td><a class="btn btn-success" role="button" href="{{url('report/financial_report/provisioning')}}">Abrir <i class="icon-search4"></i> </a>
                    </td>
                </tr>-->
                </tbody>
            </table>
        </div>
        <!-- /.panel-body -->
    </div>
    <!-- /.box -->
@endsection
@section('footer-scripts')

@endsection
