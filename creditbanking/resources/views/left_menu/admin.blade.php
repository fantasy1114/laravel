<div class="sidebar sidebar-main sidebar-default">
    <div class="sidebar-content">

        <!-- User menu -->
        <div class="sidebar-user-material">
            <div class="category-content">
                <div class="sidebar-user-material-content">
                    <!---<a href="#"><img src="{{ asset('assets/themes/limitless/images/user.png') }}"
                                     class="img-rounded img-responsive" alt=""></a>--->
                    <!---<h6>Bienvenido {{ Sentinel::getUser()->first_name }} {{ Sentinel::getUser()->last_name }}</h6>--->
              
              <p class="text-white"><i class="fas icon-user"></i> {{trans_choice('general.welcome',1)}} {{ Sentinel::getUser()->first_name }} {{ Sentinel::getUser()->last_name }}</p>
                    <span class="text-size-small"></span>
                </div>
            </div>
        </div>
        <!-- -->
        <!-- /user menu -->
        <!-- Main navigation -->
        <div class="sidebar-category sidebar-category-visible">
            <div class="category-content no-padding bg-dark">
                <ul class="navigation navigation-main navigation-accordion">
                        @if(Sentinel::hasAccess('loans.view'))
                        <li class="@if(Request::is('setting/*')) active @endif bg-info">
                            <a href="{{ url('report/loan_report/expected_repayments') }}">
        <i class="fa fa-money"></i> <span>{{trans_choice('general.daliy_square',1)}}</span>
                            </a>
                        </li>
                    @endif              
                    <li class="@if(Request::is('dashboard')) active @endif">
                        <a href="{{ url('dashboard') }}">
                            <i class="fa fa-dashboard"></i> <span>{{trans_choice('general.dashboard',1)}}</span>
                        </a>
                    </li>
                   
                    @if(Sentinel::hasAccess('branches'))
                        <li class="treeview @if(Request::is('branch/*')) active @endif">
                            <a href="#">
                                <i class="fa fa-briefcase"></i> <span>{{trans_choice('general.branch',2)}}</span>
                            </a>
                            <ul class="treeview-menu">
                                @if(Sentinel::hasAccess('branches.view'))
                                    <li><a href="{{ url('branch/data') }}"><i
                                                    class="fa fa-circle-o"></i> {{trans_choice('general.view',1)}} {{trans_choice('general.branch',2)}}
                                        </a></li>
                                @endif
                                @if(Sentinel::hasAccess('branches.create'))
                                    <li><a href="{{ url('branch/create') }}"><i
                                                    class="fa fa-circle-o"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.branch',1)}}
                                        </a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    
                    
                    
                    
                    
                    @if(Sentinel::hasAccess('borrowers'))
                        <li class="treeview @if(Request::is('borrower/*')) active @endif">
                            <a href="#">
                                <i class="fa fa-users"></i> <span>{{trans_choice('general.borrower',2)}}</span>
                            </a>
                            <ul class="treeview-menu">
                                @if(Sentinel::hasAccess('borrowers.view'))
                                    <li><a href="{{ url('borrower/data') }}"><i
                                                    class="fa fa-circle-o"></i>{{trans_choice('general.active_client', 2)}} <!-- {{trans_choice('general.view',1)}} {{trans_choice('general.borrower',2)}}
                                        --></a></li>
                                    <li>
                                        <a href="{{ url('borrower/pending') }}"><i
                                                    class="fa fa-circle-o"></i>{{trans_choice('general.unsubscribe_customer', 2)}} <!--{{trans_choice('general.borrower',2)}} {{trans_choice('general.pending',1)}}
                                            --><span class="pull-right-container">
                                        <span class="label label-danger pull-right">{{\App\Models\Borrower::where('branch_id', session('branch_id'))->where('active',0)->count() }}</span>
                                    </span>
                                        </a>
                                    </li>
                                @endif
                                @if(Sentinel::hasAccess('borrowers.create'))
                                    <li><a href="{{ url('borrower/create') }}"><i
                                                    class="fa fa-circle-o"></i> {{trans_choice('general.create_new_customer', 2)}}<!--{{trans_choice('general.add',1)}} {{trans_choice('general.borrower',2)}}
                                        --></a></li>
                                @endif
                                @if(Sentinel::hasAccess('borrowers.groups'))
                                    <li><a href="{{ url('borrower/group/data') }}"><i
                                                    class="fa fa-circle-o"></i> {{trans_choice('general.view',1)}} {{trans_choice('general.borrower',1)}} {{trans_choice('general.group',2)}}
                                        </a></li>
                                @endif
                                @if(Sentinel::hasAccess('borrowers.groups'))
                                    <li><a href="{{ url('borrower/group/create') }}"><i
                                                    class="fa fa-circle-o"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.borrower',1)}} {{trans_choice('general.group',1)}}
                                        </a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                                
                    @if(Sentinel::hasAccess('loans'))
                        <li class="treeview
                @if(Request::is('loan/data')) active @endif @if(Request::is('loan/create')) active @endif @if(Request::is('loan/loan_calculator/*')) active @endif
                                ">
                            <a href="#">
                                <i class="fa fa-money"></i> <span>{{trans_choice('general.loan',2)}}</span>
                            </a>
                            <ul class="treeview-menu">
                                @if(Sentinel::hasAccess('loans.view'))
                                    
                                    
                                    <li><a href="{{ url('loan/data') }}"><i
                                                    class="fa fa-circle-o"></i>{{trans_choice('general.loan_report',2)}} <!---{{trans_choice('general.view',2)}} {{trans_choice('general.all',2)}} {{trans_choice('general.loan',2)}}
                                            ---><span class="pull-right-container">
                                        <span class="label label-info pull-right">{{\App\Models\Loan::where('branch_id', session('branch_id'))->count() }}</span>
                                    </span>
                                        </a></li>
                                @endif
                                 @if(Sentinel::hasAccess('loans.create'))
                                    <li><a href="{{ url('loan/create') }}">
                                        <i class="fa fa-circle-o"></i>{{trans_choice('general.create_new_loan',2)}}</a></li>
                                
                                
                                @endif
                                @if(Sentinel::hasAccess('loans.loan_calculator'))
                                    <li><a href="{{ url('loan/loan_calculator/create') }}"><i
                                                class="fa fa-circle-o"></i>{{trans_choice('general.calculator',1)}}
                                       </a></li>
                               @endif                                
                            </ul>
                        </li>
                         @endif
                        
                            
                        
                    @if(Sentinel::hasAccess('users'))
                        <li class="treeview
                        @if(Request::is('loan/loan_repayment_method/*')) active @endif
                        @if(Request::is('loan/loan_product/*')) active @endif
                        @if(Request::is('charge/*')) active @endif
                        @if(Request::is('loan/loan_disbursed_by/*')) active @endif
                        @if(Request::is('guarantor/*')) active @endif
                        @if(Request::is('loan/loan_fee/*')) active @endif
                        @if(Request::is('loan/loan_overdue_penalty/*')) active @endif
                        @if(Request::is('loan/loan_status/*')) active @endif
                        @if(Request::is('branch/*')) active @endif
                                ">
                            <a href="#">
                                
                                <i class="fa fa-cog"></i> <span>{{trans_choice('general.loan_parameter',1)}}</span>
                            </a>
                            <ul class="treeview-menu"> 
                                @if(Sentinel::hasAccess('loans.update'))
                                    <li><a href="{{ url('loan/loan_repayment_method/data') }}"><i
                                                    class="fa fa-circle-o"></i>{{trans_choice('general.payment_method',1)}}</a></li>
                                @endif
                                
                                <!---
                                @if(Sentinel::hasAccess('loans.update'))
                                    <li><a href="{{ url('loan/loan_application/data') }}"><i
                                                    class="fa fa-circle-o"></i>Solicitudes de Prestamos{{trans_choice('general.view',1)}} {{trans_choice('general.application',2)}}
                                        </a></li>--->
                                @endif
                                @if(Sentinel::hasAccess('loans.products'))
                                    <li><a href="{{ url('loan/loan_product/data') }}"><i
                                                    class="fa fa-circle-o"></i>{{trans_choice('general.route_report',1)}}<!--- {{trans_choice('general.manage',1)}} {{trans_choice('general.loan',1)}} {{trans_choice('general.product',2)}}
                                       ---> </a></li>@endif
                                @if(Sentinel::hasAccess('loans.fees'))
                                    <li><a href="{{ url('charge/data') }}"><i
                                                    class="fa fa-circle-o"></i>{{trans_choice('general.charge_report',1)}} <!---{{trans_choice('general.manage',1)}}  {{trans_choice('general.charge',2)}}
                                        ---></a></li>
                                @endif
                                @if(Sentinel::hasAccess('loans.update'))
                                    <li><a href="{{ url('loan/loan_disbursed_by/data') }}"><i
                                                    class="fa fa-circle-o"></i>{{trans_choice('general.officer_report',1)}} <!---{{trans_choice('general.manage',1)}} {{trans_choice('general.disbursed_by',1)}}
                                        ---></a></li>
                                
                                @endif
                
                                @if(Sentinel::hasAccess('loans.view'))
                                    <li><a href="{{ url('guarantor/data') }}"><i
                                                    class="fa fa-circle-o"></i>{{trans_choice('general.guarantee_report',1)}}<!--- {{trans_choice('general.view',1)}} {{trans_choice('general.guarantor',2)}}
                                        ---></a></li>
                                @endif
                                
                                @if(Sentinel::hasAccess('loans.view'))
                                    <li><a href="{{ url('loan/loan_fee/data') }}"><i
                                                    class="fa fa-circle-o"></i>{{trans_choice('general.more_adjustment',1)}} <!---{{trans_choice('general.manage',1)}} {{trans_choice('general.disbursed_by',1)}}
                                        ---></a></li>
                                @endif
                                
                                 @if(Sentinel::hasAccess('loans.view'))
                                    <li><a href="{{ url('loan/loan_overdue_penalty/data') }}"><i
                                                    class="fa fa-circle-o"></i>{{trans_choice('general.penalty_report',1)}} <!---{{trans_choice('general.manage',1)}} {{trans_choice('general.disbursed_by',1)}}
                                        ---></a></li>
                                @endif
                                
                                 @if(Sentinel::hasAccess('loans.view'))
                                    <li><a href="{{ url('loan/loan_status/data') }}"><i
                                                    class="fa fa-circle-o"></i>{{trans_choice('general.loan_status',1)}} <!---{{trans_choice('general.manage',1)}} {{trans_choice('general.disbursed_by',1)}}
                                        ---></a></li>
                                @endif
                                
                                 @if(Sentinel::hasAccess('loans.view'))
                                    <li><a href="{{ url('branch/data') }}"><i
                                                    class="fa fa-circle-o"></i>{{trans_choice('general.office_report',1)}}<!---{{trans_choice('general.manage',1)}} {{trans_choice('general.disbursed_by',1)}}
                                        ---></a></li>
                                @endif                                
                            </ul>
                        </li>                        
                    @endif


                    
                    @if(Sentinel::hasAccess('collateral'))
                        <li class="treeview @if(Request::is('collateral/*')) active @endif">
                            <a href="#">
                                <i class="fa fa-list"></i> <span>{{trans_choice('general.collateral',2)}}</span>
                            </a>
                            <ul class="treeview-menu">
                                @if(Sentinel::hasAccess('collateral.view'))
                                    <li><a href="{{ url('collateral/data') }}"><i
                                                    class="fa fa-circle-o"></i> {{trans_choice('general.warranty_report',1)}}<!---{{trans_choice('general.view',2)}} {{trans_choice('general.collateral',2)}} {{trans_choice('general.register',2)}}-->
                                        </a></li>
                                @endif
                                @if(Sentinel::hasAccess('collateral.view'))
                                    <li><a href="{{ url('collateral/data/create') }}"><i
                                                    class="fa fa-circle-o"></i> {{trans_choice('general.create_new_warranty',1)}}<!---{{trans_choice('general.view',2)}} {{trans_choice('general.collateral',2)}} {{trans_choice('general.register',2)}}-->
                                        </a></li>
                                @endif                                
                                @if(Sentinel::hasAccess('collateral.create'))
                                    <li><a href="{{ url('collateral/type/data') }}"><i
                                                    class="fa fa-circle-o"></i> {{trans_choice('general.types_guarantees',1)}}<!---{{trans_choice('general.manage',2)}} {{trans_choice('general.collateral',2)}} {{trans_choice('general.type',2)}}--->
                                        </a></li>
                                @endif
                            </ul>
                        </li>
                    @endif


                    
                    @if(Sentinel::hasAccess('repayments'))
                        <li class="treeview 
                        @if(Request::is('repayment/*')) active @endif
                        @if(Request::is('loan/repayment/*')) active @endif
                        @if(Request::is('loan/transaction/*')) active @endif
                        ">
                            <a href="#">
                                <i class="fa fa-dollar"></i> <span>{{trans_choice('general.repayment',2)}}</span>
                            </a>
                            <ul class="treeview-menu">
                                @if(Sentinel::hasAccess('repayments.view'))
                                    <li><a href="{{ url('repayment/data') }}"><i
                                                    class="fa fa-circle-o"></i>{{trans_choice('general.payment_report',1)}} <!---{{trans_choice('general.repayment',2)}}
                                        --></a></li>
                                @endif
                                @if(Sentinel::hasAccess('repayments.create'))
                                    <li><a href="{{ url('repayment/create') }}"><i
                                                    class="fa fa-circle-o"></i> {{trans_choice('general.create_new_payment',1)}}<!---{{trans_choice('general.add',2)}} {{trans_choice('general.repayment',1)}}
                                        --></a></li>
                                @endif

                            </ul>
                        </li>
                    @endif
               
             




                    @if(Sentinel::hasAccess('reports'))
                        <li class="treeview
                    @if(Request::is('accounting/*')) active @endif
                        @if(Request::is('chart_of_account/*')) active @endif">
                            <a href="#">
                                <i class="fa fa-credit-card"></i> <span>{{trans_choice('general.accounting',1)}}</span>
                            </a>
                            <ul class="treeview-menu">
                                @if(Sentinel::hasAccess('repayments.view'))
                                    <li><a href="{{ url('chart_of_account/data') }}"><i
                                                    class="fa fa-circle-o"></i> {{trans_choice('general.chart_of_account',2)}}
                                        </a></li>
                                @endif
                                @if(Sentinel::hasAccess('repayments.view'))
                                    <li><a href="{{ url('accounting/journal') }}"><i
                                                    class="fa fa-circle-o"></i> <!---{{trans_choice('general.journal',1)}}--->{{trans_choice('general.general_diary',1)}}
                                        </a></li>
                                @endif
                                @if(Sentinel::hasAccess('repayments.view'))
                                    <li><a href="{{ url('accounting/ledger') }}"><i
                                                    class="fa fa-circle-o"></i> {{trans_choice('general.ledger',1)}}
                                        </a></li>
                                @endif
                                @if(Sentinel::hasAccess('repayments.view'))
                                    <li><a href="{{ url('accounting/manual_entry/create') }}"><i
                                                    class="fa fa-circle-o"></i> <!---{{trans_choice('general.add',1)}} {{trans_choice('general.journal',1)}} {{trans_choice('general.manual_entry',1)}}--->{{trans_choice('general.manual_accounting_entry',1)}}
                                        </a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                   
                    @if(Sentinel::hasAccess('reports'))
                        <li class="treeview @if(Request::is('report/*')) active @endif">
                            <a href="#">
                                <i class="fa fa-bar-chart"></i> <span><!--{{trans_choice('general.report',2)}}-->{{trans_choice('general.reports',1)}}</span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{ url('report/borrower_report') }}"><i
                                                class="fa fa-circle-o"></i>{{trans_choice('general.client_report',1)}}<!-- {{trans_choice('general.report',2)}} {{trans_choice('general.borrower',1)}} 
                                    --></a></li>
                                <li><a href="{{ url('report/loan_report') }}"><i
                                                class="fa fa-circle-o"></i>{{trans_choice('general.loan_report',1)}}<!--{{trans_choice('general.report',2)}} {{trans_choice('general.loan',1)}} 
                                    --></a></li>
                                <li><a href="{{ url('report/financial_report') }}"><i
                                                class="fa fa-circle-o"></i>{{trans_choice('general.finaancial_report',1)}}<!--{{trans_choice('general.report',2)}} {{trans_choice('general.financial',1)}} 
                                    --></a></li>
                                <li><a href="{{ url('report/company_report') }}"><i
                                                class="fa fa-circle-o"></i>{{trans_choice('general.administrative_report',1)}}<!-- {{trans_choice('general.report',2)}} {{trans_choice('general.organisation',1)}} 
                                    --></a></li>
                            </ul>
                        </li>
                    @endif
                    
                  
              

                    @if(Sentinel::hasAccess('users'))
                        <li class="treeview 
                        @if(Request::is('user/data')) active @endif
                        @if(Request::is('user/role/data')) active @endif
                        @if(Request::is('user/role/create')) active @endif
                        @if(Request::is('user/create')) active @endif
                        @if(Request::is('user/*/show')) active @endif
                        @if(Request::is('user/*/edit')) active @endif
                        ">
                            <a href="{{ url('user/data') }}">
                                <i class="fa fa-users"></i> <span>{{trans_choice('general.access_configuration',1)}}<!---{{trans_choice('general.user',2)}}---></span>
                            </a>
                            <ul class="treeview-menu">
                                @if(Sentinel::hasAccess('users.view'))
                                    <li><a href="{{ url('user/data') }}">
                                            <i class="fa fa-circle-o"></i>
                                            <span>{{trans_choice('general.user_report',1)}} <!---{{trans_choice('general.view',2)}} {{trans_choice('general.user',2)}}</span>
                                        ---></a></li>
                                @endif
                                @if(Sentinel::hasAccess('users.roles'))
                                    <li><a href="{{ url('user/role/data') }}"><i
                                                    class="fa fa-circle-o"></i>{{trans_choice('general.user_roles',1)}}<!---{{trans_choice('general.manage',2)}} {{trans_choice('general.role',2)}}
                                        ---></a></li>
                                @endif
                                @if(Sentinel::hasAccess('users.create'))
                                    <li><a href="{{ url('user/create') }}"><i
                                                    class="fa fa-circle-o"></i>{{trans_choice('general.create_new_user',1)}}<!--- {{trans_choice('general.add',2)}} {{trans_choice('general.user',2)}}
                                        ---></a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if(Sentinel::hasAccess('audit_trail'))
                        <li class="@if(Request::is('audit_trail/*')) active @endif">
                            <a href="{{ url('audit_trail/data') }}">
                                <i class="fa fa-area-chart"></i> <span>{{trans_choice('general.audit_tracking',1)}} <!--- {{trans_choice('general.audit_trail',2)}}---></span>
                            </a>
                        </li>
                    @endif

                
                    @if(Sentinel::hasAccess('settings'))
                        <li class="@if(Request::is('setting/*')) active @endif">
                            <a href="{{ url('setting/data') }}">
                                <i class="fa fa-cog"></i> <span>{{trans_choice('general.general_configuration',1)}}<!---{{trans_choice('general.setting',2)}}---></span>
                            </a>
                        </li>
                    @endif

            <li class="@if(Request::is('user/profile')) active @endif">
                <a href="{{ url('user/profile') }}">
                   <i class="icon-user-plus"></i>
                   <span class="pull-right-container">{{trans_choice('general.my_profile',1)}}</span>
                </a>
            </li>
            <li class="@if(Request::is('user/gpsMap')) active @endif">
                <a href="{{ url('user/gpsMap') }}">
                   <i class="icon-map-marker"></i>
                   <span class="pull-right-container">{{trans_choice('general.map',1)}}
                    </span>
                </a>
            </li>
            <li class="@if(Request::is('dashboard')) @endif">
                <a href="{{ url('logout') }}">
                    <i class="icon-switch2"></i> <span>{{trans_choice('general.logout',1)}}</span>
                </a>
            </li>
        </ul>
    </div>
</div>
    </div>
</div>
