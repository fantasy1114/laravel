@extends('layouts.master')
@section('title'){{trans_choice('general.borrower',1)}} {{trans_choice('general.report',2)}}
@endsection
@section('content')
    <div class="panel panel-white">
        <div class="panel-heading">
            <h4 class="panel-title">
                {{trans_choice('general.client_report_library',1)}}
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

                <!----<tr>
                    <td>
                        <a class="btn btn-info btn-lg btn-block" role="button" href="{{url('report/borrower_report/borrowers_overview')}}">RP001</a>
                    </td>
                    <td>
                        Reporte de clientes con informacion extendida.
                    </td>
                    <td><a class="btn btn-success" role="button" disabled="true" href="{{url('report/borrower_report/borrowers_overview')}}">ABRIR <i class="icon-search4"></i> </a>
                    </td>
                </tr>--->
                <tr>
                    <td>
                        <a class="btn btn-info btn-lg btn-block" role="button" href="{{url('report/borrower_report/borrower_numbers')}}">RC002</a>
                    </td>
                    <td>
                    {{trans_choice('general.customer_summary_report',1)}}
                    </td>
                    <td><a class="btn btn-success" role="button" href="{{url('report/borrower_report/borrower_numbers')}}">{{trans_choice('general.open',1)}} <i class="icon-search4"></i> </a>
                    </td>
                </tr>

                <!---<tr>
                    <td>
                        <a class="btn btn-info btn-lg btn-block" role="button" href="{{url('report/borrower_report/top_borrowers')}}">{{trans_choice('general.top',1)}} {{trans_choice('general.borrower',2)}} {{trans_choice('general.report',1)}}</a>
                    </td>
                    <td>
                        {{trans_choice('general.top_borrowers_report_description',1)}}
                    </td>
                    <td><a class="btn btn-success" role="button" href="{{url('report/borrower_report/top_borrowers')}}">ABRIR <i class="icon-search4"></i> </a>
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
