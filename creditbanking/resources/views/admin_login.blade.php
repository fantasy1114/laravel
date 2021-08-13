@extends('layouts.auth')
@section('title')
    {{trans_choice('general.app_name',1)}} | {{trans_choice('general.login',1)}}
@endsection
<div class="container" style="background-image: url({{asset('assets/dist/img/background2.jpg')}}); width: 100%; height: 100%; background-size: cover; -moz-background-size: cover; -webkit-background-size: cover; -o-background-size: cover;">
@section('content')

 <!---{{ trans('general.login') }}--->
    <div class="panel panel-body login-form">
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
         <!---<div class="text-center">
           <div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div> 
        </div>--->
        {!! Form::open(array('url' => url('login'), 'method' => 'post', 'name' => 'form','class'=>'f-login-form')) !!}
        <div class="text-center">
            <h1 class="content-group">{{ \App\Models\Setting::where('setting_key','company_name')->first()->setting_value }}
            </h1>
            <h5 class="content-group"><p class="login-box-msg">{{ trans('general.sign_in') }}</p>
            </h5>
        </div>
        <div class="form-group has-feedback has-feedback-left">
            {!! Form::email('email', null, array('class' => 'form-control', 'placeholder'=>trans_choice("Username",1),'required'=>'required')) !!}
            <div class="form-control-feedback">
                <i class="icon-envelop text-muted"></i>
            </div>
        </div>
        <div class="form-group has-feedback has-feedback-left">
            {!! Form::password('password', array('class' => 'form-control', 'placeholder'=>trans('general.password'),'required'=>'required')) !!}
            <div class="form-control-feedback">
                <i class="icon-lock2 text-muted"></i>
            </div>
        </div>
        <div class="form-group login-options">
            <div class="row">
                <div class="col-sm-6">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="remember" class="styled">
                        {{ trans('general.remember') }}
                    </label>
                </div>

                <div class="col-sm-6 text-right">
                    <a href="javascript:;" id="forget-password">{{ trans('general.forgot_password') }}</a>
                </div>
            </div>
        </div>
        <div class="form-group">
            <center>
            <button style="background-color: #4CAF50;" type="submit" class="btn btn-primary btn-block">{{ trans('general.login') }}</button>
        </center>
        </div>
        {!! Form::close() !!}
        {!! Form::open(array('url' => url('reset'), 'method' => 'post', 'name' => 'form','class'=>'f-forget-form ')) !!}
        <p class="login-box-msg">{{ trans('general.forgot_password_msg') }}</p>

        <div class="form-group has-feedback has-feedback-left">
            {!! Form::email('email', null, array('class' => 'form-control', 'placeholder'=>trans_choice('general.email',1),'required'=>'required')) !!}
            <div class="form-control-feedback">
                <i class="icon-envelop text-muted"></i>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-8">
                <div class="">
                    <a href="javascript:;" class="btn bg-pink-400" id="back-btn"><i
                                class="fa fa-backward"></i> {{ trans('general.back') }}</a>
                </div>
            </div>
            <div class="col-xs-4">
                <button type="submit"
                        class="btn bg-pink-400">{{ trans('general.reset') }}</button>
            </div>
            <!-- /.col -->
        </div>
        
        {!! Form::close() !!}
    </div>
  </div> 
  

    <script>
        $(document).ready(function () {
            jQuery('.f-register-form').hide();
            jQuery('.f-forget-form').hide();
            jQuery('#forget-password').click(function () {
                jQuery('.f-login-form').hide();
                jQuery('.f-forget-form').show();
            });
            jQuery('#register-btn').click(function () {
                jQuery('.f-login-form').hide();
                jQuery('.f-register-form').show();
            });
            jQuery('#back-btn').click(function () {
                jQuery('.f-login-form').show();
                jQuery('.f-forget-form').hide();
            });
            jQuery('#register-back-btn').click(function () {
                jQuery('.f-login-form').show();
                jQuery('.f-register-form').hide();
            });

        });
    </script>
@endsection
