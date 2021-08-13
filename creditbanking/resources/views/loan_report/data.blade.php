@extends('layouts.master')
@section('title'){{trans_choice('general.report',2)}} {{trans_choice('general.of',1)}} {{trans_choice('general.loan',1)}} 
@endsection
@section('content')
    <div class="panel panel-white">
        <div class="panel-heading">
            <h4 class="panel-title">
                
            {{trans_choice('general.loan_report_library',1)}}
                
            </h4>

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
                <!---<tr>
                    <td>
                        <a href="{{url('report/loan_report/collection_sheet')}}">Reporte de pagos pendientes</a>
                    </td>
                    <td>
                        {{trans_choice('general.collection_sheet_report_description',1)}}
                    </td>
                    <td><a href="{{url('report/loan_report/collection_sheet')}}"><i class="icon-search4"></i> </a>
                    </td>
                </tr>--->
                <tr>
                    <td>
                        <a class="btn btn-info btn-lg btn-block" role="button" href="{{url('report/loan_report/repayments_report')}}">RP001</a>
                    </td>
                    <td>
                    {{trans_choice('general.detail_report_payment_received',1)}}
                    </td>
                    <td><a class="btn btn-success" role="button" href="{{url('report/loan_report/repayments_report')}}">{{trans_choice('general.open',1)}} <i class="icon-search4"></i> </a>
                    </td>
                </tr>

                <tr>
                    <td>
                        <a class="btn btn-info btn-lg btn-block" role="button"  href="{{url('report/loan_report/expected_repayments')}}">RP002</a>
                    </td>
                    <td>
                    {{trans_choice('general.summary_report_payment_user_route',1)}}
                    </td>
                    <td><a class="btn btn-success" role="button" href="{{url('report/loan_report/expected_repayments')}}">{{trans_choice('general.open',1)}} <i class="icon-search4"></i> </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a class="btn btn-info btn-lg btn-block" role="button" href="{{url('report/loan_report/arrears_report')}}">RP003</a>
                    </td>
                    <td>
                    {{trans_choice('general.Reporte detalle de prestamos en atraso',1)}}
                    </td>
                    <td><a class="btn btn-success" role="button" href="{{url('report/loan_report/arrears_report')}}">{{trans_choice('general.open',1)}} <i class="icon-search4"></i> </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a class="btn btn-info btn-lg btn-block" role="button" href="{{url('report/loan_report/disbursed_loans')}}">RP004</a>
                    </td>
                    <td>
                    {{trans_choice('general.Reporte detalle de prestamos desembolsados',1)}}
                    </td>
                    <td><a class="btn btn-success" role="button" href="{{url('report/loan_report/disbursed_loans')}}">{{trans_choice('general.open',1)}} <i class="icon-search4"></i> </a>
                    </td>
                </tr>
                <!---<tr>
                    <td>
                        <a href="{{url('report/loan_report/written_off_loans')}}">Reporte de prestamos castigados</a>
                    </td>
                    <td>
                       Detalle de todos los prestamos castigados {{trans_choice('general.written_off_loans_report_description',1)}}
                    </td>
                    <td><a href="{{url('report/loan_report/written_off_loans')}}"><i class="icon-search4"></i> </a>
                    </td>
                </tr>--->
            </tbody>
            </table>
        </div>
        <!-- /.panel-body -->
    </div>
    <!-- /.box -->
@endsection
@section('footer-scripts')

@endsection
