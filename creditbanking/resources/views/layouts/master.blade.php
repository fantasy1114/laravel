<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> @yield('title')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet"
          type="text/css">
          
   <!--  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/icon.png') }}"> -->
    
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <link rel="stylesheet" href="{{ asset('assets/themes/limitless/css/icons/icomoon/styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/themes/limitless/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/themes/limitless/css/core.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/themes/limitless/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/themes/limitless/css/colors.css') }}">
    <link href="{{ asset('assets/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/bootstrap-toastr/toastr.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/bootstrap-touchspin/bootstrap.touchspin.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/plugins/fullcalendar/fullcalendar.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/fancybox/jquery.fancybox.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/amcharts/plugins/export/export.css') }}"
          rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}"
          rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/plugins/datepicker/bootstrap-datepicker3.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery 2.2.3 -->
    <script src="{{ asset('assets/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/jQueryUi/jquery-ui.min.js') }}" type="text/javascript"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.6 -->
    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datepicker/bootstrap-datepicker.min.js') }}"
            type="text/javascript"></script>
    {{--Start Page header level scripts--}}
    @yield('page-header-scripts')
    {{--End Page level scripts--}}
</head>
<body class="">
<!-- Main navbar -->
<div class="navbar navbar-inverse bg-indigo">
    <div class="navbar-header">
        
        <a class="navbar-brand"
           href="{{url('/')}}"></a>
            <h4>Credit Banking Software</h>
        <ul class="nav navbar-nav visible-xs-block">
            <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
            <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
        </ul>
    </div>

    <div class="navbar-collapse collapse" id="navbar-mobile">
        <ul class="nav navbar-nav">
            <li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"> {{trans_choice('general.Menu',1)}} </i></a>
            </li>
        </ul>
        <!--- izquierda barra superior--->
        <div class="navbar-right">
         <p class="navbar-text">
            
        </p>
        </div>
    </div>
</div>
<!-- Page container -->
<div class="page-container">
    <div class="page-content">
        @include('left_menu.admin')
        @php updateCron(); @endphp
        <div class="content-wrapper">
            <div class="page-header page-header-default">
                <div class="page-header-content">
                    <div class="page-title">
                        <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">{{trans_choice('general.start',1)}}</span> -
                            @yield('title')</h4>
                    </div>
                    <div class="heading-elements">
                        <div class="heading-btn-group">

                        </div>
                    </div>
                </div>
                <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                        <li><a href="{{ url('dashboard') }}"><i class="icon-home2 position-left"></i> {{trans_choice('general.Home',1)}}</a></li>
                        <li class="active">@yield('title')</li>
                    </ul>
                </div>
            </div>
            <!-- /page header -->
            <div class="content">
                <section class="">
                    @if(Session::has('flash_notification.message'))
                        <script>toastr.{{ Session::get('flash_notification.level') }}('{{ Session::get("flash_notification.message") }}', 'Response Status')</script>
                    @endif
                    @if (isset($msg))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ $msg }}
                        </div>
                    @endif
                    @if (isset($error))
                        <div class="alert alert-error">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ $error }}
                        </div>
                    @endif
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @yield('content')
                </section>
                <!-- /.content -->
                <!-- Footer -->
                <div class="footer text-muted">
                {{trans_choice('general.software_description',1)}} <a
                            href="{{ \App\Models\Setting::where('setting_key','company_website')->first()->setting_value }}"
                            target="_blank">{{ \App\Models\Setting::where('setting_key','company_name')->first()->setting_value }}</a> {{trans_choice('general.all_right_reserved',1)}} &copy; {{ date("Y") }} 
                </div>
                <!-- /footer -->
            </div>
        </div>
        <!-- /content area -->
    </div>
    <!-- /page content -->
</div>
<!-- /page container -->
<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jquery-validation/additional-methods.min.js') }}"
        type="text/javascript"></script>
<script>
    jQuery.validator.setDefaults({
        // Different components require proper error label placement
        ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
        errorClass: 'validation-error-label',
        successClass: 'validation-valid-label',
        highlight: function(element, errorClass) {
            $(element).removeClass(errorClass);
        },
        unhighlight: function(element, errorClass) {
            $(element).removeClass(errorClass);
        },
        errorPlacement: function (error, element) {

            // Styled checkboxes, radios, bootstrap switch
            if (element.parents('div').hasClass("checker") || element.parents('div').hasClass("choice") || element.parent().hasClass('bootstrap-switch-container')) {
                if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                    error.appendTo(element.parent().parent().parent().parent());
                }
                else {
                    error.appendTo(element.parent().parent().parent().parent().parent());
                }
            }

            // Unstyled checkboxes, radios
            else if (element.parents('div').hasClass('checkbox') || element.parents('div').hasClass('radio')) {
                error.appendTo(element.parent().parent().parent());
            }

            // Input with icons and Select2
            else if (element.parents('div').hasClass('has-feedback') || element.hasClass('select2-hidden-accessible')) {
                error.appendTo(element.parent());
            }

            // Inline checkboxes, radios
            else if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                error.appendTo(element.parent().parent());
            }

            // Input group, styled file input
            else if (element.parent().hasClass('uploader') || element.parents().hasClass('input-group')) {
                error.appendTo(element.parent().parent());
            }

            else {
                error.insertAfter(element);
            }
        }
    });
</script>
<script src="{{ asset('assets/plugins/moment/js/moment.min.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('assets/plugins/bootstrap-touchspin/bootstrap.touchspin.min.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('assets/plugins/fancybox/jquery.fancybox.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jquery.numeric.js') }}"></script>

<script src="{{ asset('assets/themes/limitless/js/plugins/loaders/pace.min.js') }}"></script>
<script src="{{ asset('assets/themes/limitless/js/plugins/loaders/blockui.min.js') }}"></script>
<script src="{{ asset('assets/themes/limitless/js/core/app.js') }}"></script>
<script src="{{ asset('assets/themes/limitless/js/plugins/ui/ripple.min.js') }}"></script>
<script src="{{ asset('assets/themes/limitless/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
<!-- SlimScroll 1.3.0 -->
<script src="{{ asset('assets/themes/limitless/js/plugins/tables/datatables/datatables.min.js') }}"></script>

@yield('footer-scripts')
<!-- ChartJS 1.0.1 -->
<script src="{{ asset('assets/themes/limitless/js/custom.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDJYGsppHU_r_BjvfYFw-lwaQsbPqVV2zw&sensor=false&amp;language=en"></script>	
<script src="{{ asset('assets/themes/limitless/js/map.js') }}"></script>
</body>
</html>