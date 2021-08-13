<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralHelper;
use App\Models\Borrower;
use App\Models\Setting;
use Cartalyst\Sentinel\Laravel\Facades\Reminder;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;
use Sentinel;
use Illuminate\Http\Request;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use App\Http\Requests;
use App\Models\LoanTransaction;
use App\Models\JournalEntry;
use App\Models\RepaymentCreated;

class ApiController extends Controller
{
    public function __construct()
    {
        
    }

    public function loginAdmin(Request $request)
    {
        $credentials = array(
            "email" => $request->email,
            "password" => $request->password,
        );
        if (Sentinel::authenticate($credentials) != false) {
            return response()->json([
                'status' => 200,                
                'message' => trans_choice('general.logged_in', 1),
                'data' => Sentinel::authenticate($credentials)
            ], 200);
        } else {
            return response()->json([
                'status' => 400,
                'error' => trans_choice('general.invalid_login_details', 1)
            ], 200);
        }        
    }

    public function login(Request $request)
    {  
        if ($request->username == 'test' && $request->password == 'abcd1234') {
            return response()->json([
                'status' => 200,
                'message' => 'available'
            ], 200);
        } else {
            if (Borrower::where('username', $request->username)->where('password', md5($request->password))->count() == 1) {
                $borrower = Borrower::where('username', $request->username)->where('password',
                    md5($request->password))->first();
                if ($borrower->active == 1) {
                    return response()->json([
                        'status' => 200,
                        'active' => 1,
                        'message' => trans_choice('general.logged_in', 1),
                        'data' => $borrower
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 200,
                        'active' => 0,
                        'message' => trans_choice('general.account_not_active', 1)
                    ], 200);
                }
            } else if (Borrower::where('email', $request->username)->where('password', md5($request->password))->count() == 1) {
                $borrower = Borrower::where('email', $request->username)->where('password',
                    md5($request->password))->first();
                if ($borrower->active == 1) {
                    return response()->json([
                        'status' => 200,
                        'active' => 1,
                        'message' => trans_choice('general.logged_in', 1),
                        'data' => $borrower
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 200,
                        'active' => 0,
                        'message' => trans_choice('general.account_not_active', 1)
                    ], 200);
                }
            } else {
                return response()->json([
                    'status' => 400,
                    'error' => trans_choice('general.invalid_login_details', 1)
                ], 200);
            }
        }        
    }
    
    public function register(Request $request)
    {
        if (Setting::where('setting_key', 'allow_self_registration')->first()->setting_value == 1) {
            $rules = array(
                'repeatpassword' => 'required|same:password|min:6',
                'password' => 'required|min:6',
                'username' => 'required|unique:borrowers',
                'email' => 'required|unique:borrowers'
            );
            $validator = Validator::make(Input::all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'msg' => $validator->messages()                    
                ], 200);
            } else {
                $borrower = new Borrower();
                if (Setting::where('setting_key', 'client_auto_activate_account')->first()->setting_value == 1) {
                    $borrower->active = 1;
                } else {
                    $borrower->active = 0;
                }
                $borrower->source = 'admin';
                $borrower->user_id = 1;
                $borrower->email = $request->email;
                $borrower->username = $request->username;
                $borrower->first_name = $request->first_name;
                $borrower->last_name = $request->last_name;
                $borrower->password = md5($request->password);
                $borrower->title = 'Mr';
                $borrower->branch_id = 1;
                $date = explode('-', date("Y-m-d"));
                $borrower->year = $date[0];
                $borrower->month = $date[1];
                $borrower->save();
                if ($borrower->active == 1) {
                    $user = Borrower::where('email', $request->email)->first();                    
                    return response()->json([
                        'status' => 200,
                        'active' => 1,
                        'message' => trans('general.successfully_registered_logged_in'),
                        'data' => $user
                    ], 200);                    
                } else {
                    return response()->json([
                        'status' => 200,
                        'active' => 0,
                        'message', trans('general.successfully_registered')
                    ], 200);
                }                
            }
        } else {
            return response()->json([
                'status' => 400,
                'msg' => 'Registration disabled'
            ], 200);
        }
    }

    public function getRoutes() {
        $data = \App\Models\LoanProduct::all();
        return response()->json([
            'status' => 200,
            'all_routes' => $data
        ], 200);
    }

    public function dashboard(Request $request) {
        // $loans_released_monthly = array();
        // $loan_collections_monthly = array();
        $date = date("Y-m-d");
        // $start_date1 = date_format(date_sub(date_create($date),
        //     date_interval_create_from_date_string('1 years')),
        //     'Y-m-d');
        // $start_date2 = date_format(date_sub(date_create($date),
        //     date_interval_create_from_date_string('1 years')),
        //     'Y-m-d');
        // $monthly_actual_expected_data = [];
        // $monthly_disbursed_loans_data = [];
        // $loop_date = date_format(date_sub(date_create($date),
        //     date_interval_create_from_date_string('1 years')),
        //     'Y-m-d');
        // for ($i = 1; $i < 14; $i++) {
        //     $d = explode('-', $loop_date);
        //     $actual = 0;
        //     $expected = 0;
        //     $principal = 0;
        //     $actual = $actual + \App\Models\LoanTransaction::where('transaction_type',
        //             'repayment')->where('reversed', 0)->where('year',
        //             $d[0])->where('month',
        //             $d[1])->where('branch_id',
        //             $request->user_id)->sum('credit');
        //     foreach (\App\Models\Loan::select("loan_schedules.principal", "loan_schedules.interest", "loan_schedules.penalty",
        //         "loan_schedules.fees")->where('loans.branch_id',
        //         $request->user_id)->whereIn('loans.status',
        //         ['disbursed', 'closed', 'written_off'])->join('loan_schedules', 'loans.id', '=',
        //         'loan_schedules.loan_id')->where('loan_schedules.deleted_at', NULL)->where('loan_schedules.year',
        //         $d[0])->where('loan_schedules.month',
        //         $d[1])->get() as $key) {
        //         $expected = $expected + $key->interest + $key->penalty + $key->fees + $key->principal;
        //         $principal = $principal + $key->principal;

        //     }
        //     array_push($monthly_actual_expected_data, array(
        //         'month' => date_format(date_create($loop_date),
        //             'M' . ' ' . $d[0]),
        //         'actual' => $actual,
        //         'expected' => $expected
        //     ));
        //     array_push($monthly_disbursed_loans_data, array(
        //         'month' => date_format(date_create($loop_date),
        //             'M' . ' ' . $d[0]),
        //         'value' => $principal,
        //     ));
        //     //add 1 month to start date
        //     $loop_date = date_format(date_add(date_create($loop_date),
        //         date_interval_create_from_date_string('1 months')),
        //         'Y-m-d');
        // }
        // //daily users
        // $loan_statuses = [];
        // array_push($loan_statuses, array(
        //     'label' => trans_choice('general.pending', 1),
        //     'value' => \App\Models\Loan::where('status', 'pending')->count(),
        //     'color' => "#FF8A65",
        //     'highlight' => "#FF8A65",
        //     'link' => url('loan/data?status=pending'),
        //     'class' => "warning-300",

        // ));
        // array_push($loan_statuses, array(
        //     'label' => trans_choice('general.approved', 1),
        //     'value' => \App\Models\Loan::where('status', 'approved')->count(),
        //     'color' => "#64B5F6",
        //     'highlight' => "#64B5F6",
        //     'link' => url('loan/data?status=approved'),
        //     'class' => "primary-300",

        // ));

        // array_push($loan_statuses, array(
        //     'label' => trans_choice('general.disbursed', 1),
        //     'value' => \App\Models\Loan::where('status', 'disbursed')->count(),
        //     'color' => "#1565C0",
        //     'highlight' => "#1565C0",
        //     'link' => url('loan/data?status=disbursed'),
        //     'class' => "primary-800",

        // ));
        // array_push($loan_statuses, array(
        //     'label' => trans_choice('general.rescheduled', 1),
        //     'value' => \App\Models\Loan::where('status', 'rescheduled')->count(),
        //     'color' => "#00ACC1",
        //     'highlight' => "#00ACC1",
        //     'link' => url('loan/data?status=rescheduled'),
        //     'class' => "info-600",

        // ));
        // array_push($loan_statuses, array(
        //     'label' => trans_choice('general.written_off', 1),
        //     'value' => \App\Models\Loan::where('status', 'written_off')->count(),
        //     'color' => "#D32F2F",
        //     'highlight' => "#D32F2F",
        //     'link' => url('loan/data?status=written_off'),
        //     'class' => "danger-700",

        // ));
        // array_push($loan_statuses, array(
        //     'label' => trans_choice('general.declined', 1),
        //     'value' => \App\Models\Loan::where('status', 'declined')->count(),
        //     'color' => "#EF5350",
        //     'highlight' => "#EF5350",
        //     'link' => url('loan/data?status=declined'),
        //     'class' => "danger-400",

        // ));
        // array_push($loan_statuses, array(
        //     'label' => trans_choice('general.closed', 1),
        //     'value' => \App\Models\Loan::where('status', 'closed')->count(),
        //     'color' => "#66BB6A",
        //     'highlight' => "#66BB6A",
        //     'link' => url('loan/data?status=closed'),
        //     'class' => "success-400",

        // ));
        
        $user_count = \App\Models\Loan::where('loan_product_id', $request->route_id)->where('status', 'disbursed')->count();

        $total_outstanding = 0;
        foreach(\App\Models\Loan::where('first_payment_date','<=', $date)->where('branch_id', 1)->where('status', 'disbursed')->where('loan_product_id', $request->route_id)->orderBy('release_date','asc')->get() as $key) {
            $loan_due_items = GeneralHelper::loan_due_items($key->id, $key->release_date, $date);
            $loan_paid_items = GeneralHelper::loan_paid_items($key->id, $key->release_date, $date);
            $balance = GeneralHelper::loan_total_balance($key->id);
            $due = ($loan_due_items["principal"] + $loan_due_items["interest"] + $loan_due_items["fees"] + $loan_due_items["penalty"]) - ($loan_paid_items["principal"] + $loan_paid_items["interest"] + $loan_paid_items["fees"] + $loan_paid_items["penalty"]);
            if ($due > 0) {
                $total_outstanding = $total_outstanding + $balance;
            }
        }

        $currency_position = Setting::where('setting_key', 'currency_position')->first()->setting_value;
        $total_symbol = Setting::where('setting_key', 'currency_symbol')->first()->setting_value;
        $total_received = [];
        $total_v = 0;
        foreach(\App\Models\Loan::where('loan_product_id', $request->route_id)->where('status', 'disbursed')->groupBy('id')->get() as $loan) {
            $total_v = $total_v + \App\Models\LoanTransaction::where('transaction_type', 'repayment')->where('reversed', 0)->where('date', date("Y-m-d"))->where('loan_id', $loan->id)->where('user_id', $request->user_id)->sum('credit');
        }
        $total_val = $total_v;
        
        array_push($total_received, array(
            'symbol_position' => $currency_position,
            'symbol' => $total_symbol,
            'value' => $total_val
        ));
        


        // $currency_position = Setting::where('setting_key', 'currency_position')->first()->setting_value;
        // $total_symbol = Setting::where('setting_key', 'currency_symbol')->first()->setting_value;
        // $principal = 0;
        // foreach (\App\Models\Loan::where('branch_id', $request->user_id)->whereIn('status', ['disbursed', 'closed', 'written_off'])->get() as $key) {
        //     $principal = $principal + \App\Models\LoanSchedule::where('loan_id', $key->id)->sum('principal');
        // }
        // $total_value = number_format($principal,2);
        // $total = [];
        // array_push($total, array(
        //     'symbol_position' => $currency_position,
        //     'symbol' => $total_symbol,
        //     'value' => $total_value
        // ));

        // $payment_received = GeneralHelper::loans_total_paid();

        // $due = 0;
        // foreach (\App\Models\Loan::where('branch_id', $request->user_id)->whereIn('status', ['disbursed', 'closed', 'written_off'])->get() as $key) {
        //     $due = $due + GeneralHelper::loan_total_due_amount($key->id);
        // }
        // $total_debt = $due;

        // $balance_received_today = [];
        // $balance1 = number_format(\App\Models\LoanTransaction::where('transaction_type', 'repayment')->where('reversed', 0)->where('date',date("Y-m-d"))->sum('credit'),2);
        // array_push($balance_received_today, array(
        //     'symbol_position' => $currency_position,
        //     'symbol' => $total_symbol,
        //     'value' => $balance1
        // ));

        // $balance_received_previous_week = [];
        // $balance2 = number_format(\App\Models\LoanTransaction::where('transaction_type', 'repayment')->where('reversed', 0)->whereBetween('date',array('date_sub(now(),INTERVAL 1 WEEK)','now()'))->sum('credit'), 2);
        // array_push($balance_received_previous_week, array(
        //     'symbol_position' => $currency_position,
        //     'symbol' => $total_symbol,
        //     'value' => $balance2
        // ));

        // $balance_charged_thismonth = [];
        // $balance3 = number_format(\App\Models\LoanTransaction::where('transaction_type', 'repayment')->where('reversed', 0)->where('year', date("Y"))->where('month', date("m"))->sum('credit'), 2);
        // array_push($balance_charged_thismonth, array(
        //     'symbol_position' => $currency_position,
        //     'symbol' => $total_symbol,
        //     'value' => $balance3
        // ));

        return response()->json([
            'status' => 200,
            // 'monthly_actual_expected_data' => $monthly_actual_expected_data,
            // 'monthly_disbursed_loans_data' => $monthly_disbursed_loans_data,
            // 'loans_released_monthly' => $loans_released_monthly,
            // 'loan_collections_monthly' => $loan_collections_monthly,
            // 'loan_statuses' => $loan_statuses,
            'user_count' => $user_count,
            'total_outstanding' => $total_outstanding,
            'total_received' => $total_received
            // 'total' => $total,
            // 'payment_received' => $payment_received,
            // 'total_debt' => $total_debt,
            // 'balance_received_today' => $balance_received_today,
            // 'balance_received_previous_week' => $balance_received_previous_week,
            // 'balance_charged_thismonth' => $balance_charged_thismonth
        ], 200);
    }

    public function getAllLoans() {
        //whereIn('status', ['disbursed', 'closed'])
        $loans = array();
        foreach (\App\Models\Loan::where('status', 'disbursed')->get() as $key) {
            if (!empty($key->borrower)) {
                $borrower = ' (' . $key->borrower->first_name . ' ' . $key->borrower->last_name . ")";
            } else {
                $borrower = '';
            }
            // $loans[$key->id] = "#" . $key->id . $borrower;
            array_push($loans, array(
                'key' => $key->id,
                'name' => "#" . $key->id . $borrower,
                'route_id' => $key->loan_product_id
            ));
        }
        
        $repayment_methods = array();
        foreach (\App\Models\LoanRepaymentMethod::all() as $key) {
            $repayment_methods[$key->id] = $key->name;
        }

        $custom_fields = \App\Models\CustomField::where('category', 'repayments')->get();

        $company_name = \App\Models\Setting::where('setting_key','company_name')->first()->setting_value;
        $company_address = \App\Models\Setting::where('setting_key','company_address')->first()->setting_value;

        return response()->json([
            'status' => 200,
            'loans' => $loans,
            'repayment_methods' => $repayment_methods,
            'custom_fields' => $custom_fields,
            'company_name' => $company_name,
            'company_address' => $company_address
        ], 200);
    }

    public function getLoans(Request $request) {
        $loans = array();
        foreach (\App\Models\Loan::where('status', 'disbursed')->where('loan_product_id', $request->route_id)->get() as $key) {

            if (!empty($key->borrower)) {
                $borrower = ' (' . $key->borrower->first_name . ' ' . $key->borrower->last_name . ")";
            } else {
                $borrower = '';
            }
            $loans[$key->id] = "#" . $key->id . $borrower;
        }
        
        $repayment_methods = array();
        foreach (\App\Models\LoanRepaymentMethod::all() as $key) {
            $repayment_methods[$key->id] = $key->name;
        }

        $custom_fields = \App\Models\CustomField::where('category', 'repayments')->get();

        return response()->json([
            'status' => 200,
            'loans' => $loans,
            'repayment_methods' => $repayment_methods,
            'custom_fields' => $custom_fields
        ], 200);
    }

    public function getLoanById(Request $request) {      

        foreach (\App\Models\Loan::where('branch_id', 1)->where('id', $request->loan_id)->get() as $key) {
            if (!empty($key->borrower)) {
                $borrower = $key->borrower->first_name . ' ' . $key->borrower->last_name;
            } else {
                $borrower = '';
            }

            $loan_paid_items = \App\Helpers\GeneralHelper::loan_paid_items($request->loan_id, $key->release_date, date("Y-m-d"));
            $original_date = $key->release_date;            
            $fecha_de_desembolso = date("d-m-Y", strtotime($original_date));

            $loan = $key;
        }

        $balance = \App\Helpers\GeneralHelper::loan_total_balance($key->id);

        $company_name = \App\Models\Setting::where('setting_key','company_name')->first()->setting_value;
        $company_address = \App\Models\Setting::where('setting_key','company_address')->first()->setting_value;
        
        $late_fee_balance = \App\Helpers\GeneralHelper::loan_total_penalty($request->loan_id)-$loan_paid_items['penalty'];

        $loan_sched = \App\Models\LoanSchedule::where('loan_id', $request->loan_id)->orderBy('due_date', 'asc')->first();
        $date1 = new \DateTime($loan_sched->due_date);
        $date2 = new \DateTime(date('Y-m-d'));
        $due_days = $date2->diff($date1)->format("%a");


        $paid_count = 0;
        $paid_amount = 0;
        $unpaid_count = 0;
        $unpaid_amount = 0;

        $paid_rate = 0;
        $unpaid_rate = 0;

        $totalPrincipal = \App\Models\LoanSchedule::where('loan_id', $request->loan_id)->sum('principal');
        $payPrincipal = \App\Models\LoanTransaction::where('loan_id', $request->loan_id)->where('transaction_type', 'repayment')->where('reversed', 0)->where('payment_type', 'principal')->sum('credit');
        $balancePrincipal = $totalPrincipal - $payPrincipal;

        $loan_schedules = \App\Models\LoanSchedule::where('loan_id', $request->loan_id)->get();
        $payments = \App\Models\LoanTransaction::where('loan_id', $request->loan_id)->where('transaction_type', 'repayment')->where('reversed', 0)->where('payment_type', 'regular')->sum('credit');

        foreach ($loan_schedules as $schedule) {
            $schedule_count = count($loan_schedules);
            $principal = $balancePrincipal / $schedule_count;            
            $loanRate = $loan->interest_rate;

            if ($loan->repayment_cycle=='daily') {
                $interest = (($balancePrincipal * $loanRate) / 100.00) / 30;
            } elseif ($loan->repayment_cycle=='weekly') {
                $interest = (($balancePrincipal * $loanRate) / 100.00) / 4;
            } elseif ($loan->repayment_cycle=='bi_monthly') {
                $interest = (($balancePrincipal * $loanRate) / 100.00) / 2;
            } elseif ($loan->repayment_cycle=='monthly') {
                $interest = ($balancePrincipal * $loanRate) / 100.00;        
            } else {
                $interest = 0;
            }            
                                
            $due = $principal + $interest + $schedule->fees + $schedule->penalty - $schedule->interest_waived;
            $paid = 0;
                                            
            if ($payments > 0) {
                if ($payments > $due) {
                    $paid = $due;
                    $payments = $payments - $due;                    
                } else {
                    $paid = $payments;
                    $payments = 0;
                }
            } else {
            }
            $outstanding = $due - $paid;
                        
            if ($outstanding == 0) {
                $paid_amount = $paid_amount + $paid;
                $paid_count = $paid_count + 1;                
            }
            if ($outstanding != 0) {
                $unpaid_amount = $unpaid_amount + $outstanding;
                $unpaid_count = $unpaid_count + 1;
            }
            $paid_rate = $paid_rate + $paid / $due;
            $unpaid_rate = $unpaid_rate + $outstanding / $due;
        }

        return response()->json([
            'status' => 200,
            'customer' => $borrower,
            'balance' => $balance,
            'company_name' => $company_name,
            'company_address' => $company_address,
            'late_fee_balance' => $late_fee_balance,
            'disbursement_date' => $fecha_de_desembolso,
            'due_days' => $due_days,
            'paid_total_count' => $paid_count,
            'paid_amount' => $paid_amount,
            'unpaid_total_count' => $unpaid_count,
            'unpaid_amount' => $unpaid_amount,
            'total_count' => $paid_count + $unpaid_count,
            'paid_count' => number_format($paid_rate, 2, '.', ""),
            'unpaid_count' => number_format($unpaid_rate, 2, '.', ""),
        ], 200);
    }

    public function getRecipeId(Request $request) {
        if (\App\Models\LoanTransaction::where('transaction_type', 'repayment')->where('date', date("Y-m-d"))->where('loan_id', $request->loan_id)->count() > 0) {
            $maxId = \App\Models\LoanTransaction::where('transaction_type', 'repayment')->where('date', date("Y-m-d"))->where('loan_id', $request->loan_id)->orderBy('id', 'DESC')->first()->receipt;
            return response()->json([
                'status' => 200,
                'max_id' => $maxId
            ], 200);
        } else {
            return response()->json([
                'status' => 400,
                'max_id' => 0
            ], 200);
        }
        
    }

    public function saveRepayment(Request $request) {
        
        if ($request->collection_date > date("Y-m-d")) {
            return response()->json([
                'status' => 400,
                'error' => trans_choice('general.future_date_error', 1)
            ], 200);
            exit();
        }

        $loan = \App\Models\Loan::find($request->loan_id);
        $loan_transaction = new LoanTransaction();
        $loan_transaction->user_id = $request->user_id;
        $loan_transaction->branch_id = 1;
        $loan_transaction->loan_id = $loan->id;
        $loan_transaction->borrower_id = $loan->borrower_id;
        $loan_transaction->transaction_type = "repayment";
        $loan_transaction->receipt = $request->receipt;
        $loan_transaction->date = $request->collection_date;
        $loan_transaction->reversible = 1;
        $loan_transaction->repayment_method_id = $request->repayment_method_id;
        $date = explode('-', $request->collection_date);
        $loan_transaction->year = $date[0];
        $loan_transaction->month = $date[1];
        $loan_transaction->credit = $request->amount;
        $loan_transaction->notes = $request->notes;
        $loan_transaction->lat = $request->lat;
        $loan_transaction->lng = $request->long;

        if ($request->repayment_type == "2") {
            $loan_transaction->payment_type = "principal";            
        }
        $loan_transaction->save();

        // //fire payment added event
        // //debit and credit the necessary accounts
        $allocation = GeneralHelper::loan_allocate_payment($loan_transaction);        

        //principal
        if ($allocation['principal'] > 0) {
            if (!empty($loan->loan_product->chart_loan_portfolio)) {
                $journal = new JournalEntry();
                $journal->user_id = $request->user_id;
                $journal->account_id = $loan->loan_product->chart_loan_portfolio->id;
                $journal->branch_id = $loan->branch_id;
                $journal->date = $request->collection_date;
                $journal->year = $date[0];
                $journal->month = $date[1];
                $journal->borrower_id = $loan->borrower_id;
                $journal->transaction_type = 'repayment';
                $journal->transaction_sub_type = 'repayment_principal';
                $journal->name = "Principal Repayment";
                $journal->loan_id = $loan->id;
                $journal->loan_transaction_id = $loan_transaction->id;
                $journal->credit = $allocation['principal'];
                $journal->reference = $loan_transaction->id;
                $journal->save();
            }
            if (!empty($loan->loan_product->chart_fund_source)) {
                $journal = new JournalEntry();
                $journal->user_id = $request->user_id;
                $journal->account_id = $loan->loan_product->chart_fund_source->id;
                $journal->branch_id = $loan->branch_id;
                $journal->date = $request->collection_date;
                $journal->year = $date[0];
                $journal->month = $date[1];
                $journal->borrower_id = $loan->borrower_id;
                $journal->transaction_type = 'repayment';
                $journal->name = "Principal Repayment";
                $journal->loan_id = $loan->id;
                $journal->loan_transaction_id = $loan_transaction->id;
                $journal->debit = $allocation['principal'];
                $journal->reference = $loan_transaction->id;
                $journal->save();
            }
        }
        //interest
        if ($allocation['interest'] > 0) {
            if (!empty($loan->loan_product->chart_income_interest)) {
                $journal = new JournalEntry();
                $journal->user_id = $request->user_id;
                $journal->account_id = $loan->loan_product->chart_income_interest->id;
                $journal->branch_id = $loan->branch_id;
                $journal->date = $request->collection_date;
                $journal->year = $date[0];
                $journal->month = $date[1];
                $journal->borrower_id = $loan->borrower_id;
                $journal->transaction_type = 'repayment';
                $journal->transaction_sub_type = 'repayment_interest';
                $journal->name = "Interest Repayment";
                $journal->loan_id = $loan->id;
                $journal->loan_transaction_id = $loan_transaction->id;
                $journal->credit = $allocation['interest'];
                $journal->reference = $loan_transaction->id;
                $journal->save();
            }
            if (!empty($loan->loan_product->chart_receivable_interest)) {
                $journal = new JournalEntry();
                $journal->user_id = $request->user_id;
                $journal->account_id = $loan->loan_product->chart_receivable_interest->id;
                $journal->branch_id = $loan->branch_id;
                $journal->date = $request->collection_date;
                $journal->year = $date[0];
                $journal->month = $date[1];
                $journal->borrower_id = $loan->borrower_id;
                $journal->transaction_type = 'repayment';
                $journal->name = "Interest Repayment";
                $journal->loan_id = $loan->id;
                $journal->loan_transaction_id = $loan_transaction->id;
                $journal->debit = $allocation['interest'];
                $journal->reference = $loan_transaction->id;
                $journal->save();
            }
        }
        //fees
        if ($allocation['fees'] > 0) {
            if (!empty($loan->loan_product->chart_income_fee)) {
                $journal = new JournalEntry();
                $journal->user_id = $request->user_id;
                $journal->account_id = $loan->loan_product->chart_income_fee->id;
                $journal->branch_id = $loan->branch_id;
                $journal->date = $request->collection_date;
                $journal->year = $date[0];
                $journal->month = $date[1];
                $journal->borrower_id = $loan->borrower_id;
                $journal->transaction_type = 'repayment';
                $journal->transaction_sub_type = 'repayment_fees';
                $journal->name = "Fees Repayment";
                $journal->loan_id = $loan->id;
                $journal->loan_transaction_id = $loan_transaction->id;
                $journal->credit = $allocation['fees'];
                $journal->reference = $loan_transaction->id;
                $journal->save();
            }
            if (!empty($loan->loan_product->chart_receivable_fee)) {
                $journal = new JournalEntry();
                $journal->user_id = $request->user_id;
                $journal->account_id = $loan->loan_product->chart_receivable_fee->id;
                $journal->branch_id = $loan->branch_id;
                $journal->date = $request->collection_date;
                $journal->year = $date[0];
                $journal->month = $date[1];
                $journal->borrower_id = $loan->borrower_id;
                $journal->transaction_type = 'repayment';
                $journal->name = "Fees Repayment";
                $journal->loan_id = $loan->id;
                $journal->loan_transaction_id = $loan_transaction->id;
                $journal->debit = $allocation['fees'];
                $journal->reference = $loan_transaction->id;
                $journal->save();
            }
        }
        if ($allocation['penalty'] > 0) {
            if (!empty($loan->loan_product->chart_income_penalty)) {
                $journal = new JournalEntry();
                $journal->user_id = $request->user_id;
                $journal->account_id = $loan->loan_product->chart_income_penalty->id;
                $journal->branch_id = $loan->branch_id;
                $journal->date = $request->collection_date;
                $journal->year = $date[0];
                $journal->month = $date[1];
                $journal->borrower_id = $loan->borrower_id;
                $journal->transaction_type = 'repayment';
                $journal->transaction_sub_type = 'repayment_penalty';
                $journal->name = "Penalty Repayment";
                $journal->loan_id = $loan->id;
                $journal->loan_transaction_id = $loan_transaction->id;
                $journal->credit = $allocation['penalty'];
                $journal->reference = $loan_transaction->id;
                $journal->save();
            }
            if (!empty($loan->loan_product->chart_receivable_penalty)) {
                $journal = new JournalEntry();
                $journal->user_id = $request->user_id;
                $journal->account_id = $loan->loan_product->chart_receivable_penalty->id;
                $journal->branch_id = $loan->branch_id;
                $journal->date = $request->collection_date;
                $journal->year = $date[0];
                $journal->month = $date[1];
                $journal->borrower_id = $loan->borrower_id;
                $journal->transaction_type = 'repayment';
                $journal->name = "Penalty Repayment";
                $journal->loan_id = $loan->id;
                $journal->loan_transaction_id = $loan_transaction->id;
                $journal->debit = $allocation['penalty'];
                $journal->reference = $loan_transaction->id;
                $journal->save();
            }
        }
        // echo "Nothing";exit;
        //save custom meta
        $custom_fields = \App\Models\CustomField::where('category', 'repayments')->get();
        foreach ($custom_fields as $key) {
            $custom_field = new CustomFieldMeta();
            $id = $key->id;
            $custom_field->name = $request->$id;
            $custom_field->parent_id = $loan_transaction->id;
            $custom_field->custom_field_id = $key->id;
            $custom_field->category = "repayments";
            $custom_field->save();
        }
        //update loan status if need be
        if (round(GeneralHelper::loan_total_balance($loan->id)) <= 0) {
            $l = \App\Models\Loan::find($loan->id);
            $l->status = "closed";
            $l->save();
        }
        
        // event(new RepaymentCreated($loan_transaction));        
        // GeneralHelper::audit_trail("Pago aplicado a prestamos ID:" . $loan->id);

        return response()->json([
            'status' => 200,
            'loan_transaction_id' => $loan_transaction->loan_id,
            'message' => 'Pago procesado con éxito'
        ], 200);
    }

    public function profileUpdate(Request $request)
    {
        $user = Sentinel::findById($request->user_id);
        $credentials = [
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'notes' => $request->notes,
            'gender' => $request->gender,
            'phone' => $request->phone
        ];
        if (!empty($request->password)) {
            $credentials['password'] = $request->password;
        }
        $user = Sentinel::update($user, $credentials);
        return response()->json([
            'status' => 200,
            'message' => 'Procesado con exito',
            'user_data' => $user
        ], 200);
    }

    public function getRepaymentReportOfDaily(Request $request) {
        $loans = array();
        $repaymens = array();

        foreach (\App\Models\Loan::where('status', 'disbursed')->where('loan_product_id', $request->route_id)->get() as $key) {
            $loans[] = $key->id;
        }

        foreach (\App\Models\LoanTransaction::where('transaction_type', 'repayment')->where('date', date("Y-m-d"))->whereIn('loan_id', $loans)->where('user_id', $request->user_id)->where('reversed', 0)->get() as $key) {
            if (!empty($key->borrower)) {
                $borrower = $key->borrower->first_name . ' ' . $key->borrower->last_name;
            } else {
                $borrower = '';
            }
            
            array_push($repaymens, array(
                'loan_id' => $key->loan_id,
                'borrower_id' => $key->borrower_id,
                'credit' => $key->credit,
                'customer' => $borrower,
                'receipt' => $key->receipt,
                'date' => date("d-m-Y", strtotime($key->date)),
                'payment_method' => $key->repayment_method_id,
            ));
        }
        
        return response()->json([
            'status' => 200,
            'data' => $repaymens
        ], 200);
    }

    public function getDistributionData(Request $request) {
        $total_principal = 0;
        $total_fees = 0;
        $total_interest = 0;
        $total_penalty = 0;
        $transaction_type = '';
        $transaction_date = '';
        foreach (\App\Models\LoanTransaction::where('transaction_type', 'repayment')->where('reversed', 0)->where('receipt', $request->receipt)->get() as $key) {
            $principal = \App\Models\JournalEntry::where('loan_transaction_id', $key->id)->where('reversed',0)->where('name', "Principal Repayment")->sum('credit');

            $interest = \App\Models\JournalEntry::where('loan_transaction_id', $key->id)->where('reversed',0)->where('name', "Interest Repayment")->sum('credit');

            $fees = \App\Models\JournalEntry::where('loan_transaction_id', $key->id)->where('reversed',0)->where('name', "Fees Repayment")->sum('credit');

            $penalty = \App\Models\JournalEntry::where('loan_transaction_id', $key->id)->where('reversed',0)->where('name', "Penalty Repayment")->sum('credit');

            $total_principal = $total_principal + $principal;
            $total_interest = $total_interest + $interest;
            $total_fees = $total_fees + $fees;
            $total_penalty = $total_penalty + $penalty;

            $transaction_type = $key->transaction_type;            
        }
        return response()->json([
            'status' => 200,
            'principal' => $principal,
            'interest' => $interest,
            'fees' => $fees,
            'penalty' => $penalty,
            'transaction_type' => $transaction_type            
        ], 200);
    }

    public function getLoansReportData(Request $request) {
        $loans = array();

        if (empty($request->last_loanid)) {
            // $from_data = date('Y-m-d');
            $loans_data = \App\Models\Loan::where('status', 'disbursed')->where('loan_product_id', $request->route_id)->orderBy('id','DESC')->limit(10)->get();
        } else {
            // $from_data = $request->last_date;
            $loans_data = \App\Models\Loan::where('status', 'disbursed')->where('loan_product_id', $request->route_id)->where('id','<', $request->last_loanid)->orderBy('id','DESC')->limit(10)->get();
        }

        foreach ($loans_data as $key) {
            if (!empty($key->borrower)) {
                $borrower = $key->borrower->first_name . ' ' . $key->borrower->last_name;
            } else {
                $borrower = '';
            }

            $status = '';
            if ($key->maturity_date < date("Y-m-d") && GeneralHelper::loan_total_balance($key->id) > 0) {
                $status = trans_choice('general.past_maturity',1);
            }
            else {
                if($key->status=='pending') {
                    $status = trans_choice('general.pending',1).' '.trans_choice('general.approval',1);
                }
                if($key->status=='approved') {
                    $status = trans_choice('general.awaiting',1).' '.trans_choice('general.disbursement',1);
                }                    
                if($key->status=='disbursed') {
                    $status = trans_choice('general.active',1);
                }                    
                if($key->status=='declined') {
                    $status = trans_choice('general.declined',1);
                }
                if($key->status=='withdrawn') {
                    $status = trans_choice('general.withdrawn',1);
                }
                if($key->status=='written_off') {
                    $status = trans_choice('general.written_off',1);
                }
                if($key->status=='closed') {
                    $status = trans_choice('general.closed',1);
                }                    
                if($key->status=='pending_reschedule') {
                    $status = trans_choice('general.pending',1).' '.trans_choice('general.reschedule',1);
                }
                if($key->status=='rescheduled') {
                    $status = trans_choice('general.rescheduled',1);
                }
            }

            $loan_due_items = GeneralHelper::loan_due_items($key->id, $key->release_date, date('Y-m-d'));
            $loan_paid_items = GeneralHelper::loan_paid_items($key->id, $key->release_date, date('Y-m-d'));
            $outstanding = ($loan_due_items["principal"] + $loan_due_items["interest"] + $loan_due_items["fees"] + $loan_due_items["penalty"]) - ($loan_paid_items["principal"] + $loan_paid_items["interest"] + $loan_paid_items["fees"] + $loan_paid_items["penalty"]);
            $balance = \App\Helpers\GeneralHelper::loan_total_balance($key->id);



            // $schedules = \App\Models\LoanSchedule::where('loan_id', $key->id)->where('due_date', '>', date("Y-m-d"))->orderBy('due_date', 'asc')->first();
            // if (!empty($schedules)) {
            //     $overdue_date = $schedules->due_date;
            //     $date1 = new \DateTime($overdue_date);
            //     $date2 = new \DateTime(date('Y-m-d'));
            //     $days_arrears = $date2->diff($date1)->format("%a");
            // } else {
            //     $days_arrears = 0;
            // }

            $timely = 0;
            $total_overdue = 0;
            $overdue_date = "";
            $total_till_now = 0;
            $count = 1;
            $total_due = 0;
            $totalPrincipal = \App\Models\LoanSchedule::where('loan_id',$key->id)->sum('principal');
            $payPrincipal = \App\Models\LoanTransaction::where('loan_id', $key->id)->where('transaction_type', 'repayment')->where('reversed', 0)->where('payment_type', 'principal')->sum('credit');
            $balancePrincipal = $totalPrincipal - $payPrincipal;

            $principal_balance = $balancePrincipal;
            $payments = \App\Models\LoanTransaction::where('loan_id', $key->id)->where('transaction_type', 'repayment')->where('reversed', 0)->where('payment_type', 'regular')->sum('credit');
            $next_payment = [];            
            $schedules = \App\Models\LoanSchedule::where('loan_id', $key->id)->orderBy('due_date', 'asc')->get();

            foreach ($schedules as $schedule) {
                $schedule_count = count($schedules);
                $principal = $balancePrincipal / $schedule_count;                
                $loanRate = $key->interest_rate;

                if ($key->repayment_cycle=='daily') {
                    $interest = (($balancePrincipal * $loanRate) / 100.00) / 30;
                } elseif ($key->repayment_cycle=='weekly') {
                    $interest = (($balancePrincipal * $loanRate) / 100.00) / 4;
                } elseif ($key->repayment_cycle=='bi_monthly') {
                    $interest = (($balancePrincipal * $loanRate) / 100.00) / 2;
                } elseif ($key->repayment_cycle=='monthly') {
                    $interest = ($balancePrincipal * $loanRate) / 100.00;        
                } else {
                    $interest = 0;
                }

                $principal_balance = $principal_balance - $principal;                
                                    
                $due = $principal + $interest + $schedule->fees + $schedule->penalty- $schedule->interest_waived;
                $total_due = $total_due + ($principal + $interest + $schedule->fees + $schedule->penalty-$schedule->interest_waived);
                
                $paid = 0;
                $paid_by = '';
                
                if ($payments > 0) {
                    if ($payments > $due) {
                        $paid = $due;
                        $payments = $payments - $due;
                        //find the corresponding paid by date
                        $p_paid = 0;
                        foreach (\App\Models\LoanTransaction::where('loan_id',
                            $key->id)->where('transaction_type',
                            'repayment')->where('reversed', 0)->orderBy('date',
                            'asc')->get() as $keyy) {
                            $p_paid = $p_paid + $keyy->credit;
                            if ($p_paid >= $total_due) {
                                $paid_by = $keyy->date;
                                if ($keyy->date > $schedule->due_date && date("Y-m-d") > $schedule->due_date) {                                    
                                    $total_overdue = $total_overdue + 1;
                                    $overdue_date = '';
                                }
                                break;
                            }
                        }
                    } else {
                        $paid = $payments;
                        $payments = 0;
                        if (date("Y-m-d") > $schedule->due_date) {                            
                            $total_overdue = $total_overdue + 1;
                            $overdue_date = $schedule->due_date;
                        }
                        $next_payment[$schedule->due_date] = (($schedule->principal + $schedule->interest + $schedule->fees + $schedule->penalty- $schedule->interest_waived) - $paid);
                    }
                } else {
                    if (date("Y-m-d") > $schedule->due_date) {
                        $total_overdue = $total_overdue + 1;
                        $overdue_date = $schedule->due_date;
                    }
                    $next_payment[$schedule->due_date] = (($schedule->principal + $schedule->interest + $schedule->fees + $schedule->penalty- $schedule->interest_waived));
                }
                // $outstanding = $due - $paid;
            }
            if (!empty($overdue_date) && $overdue_date != '') {                
                $date1 = new \DateTime($overdue_date);
                $date2 = new \DateTime(date('Y-m-d'));
                $days_arrears = $date2->diff($date1)->format("%a");
            } else {
                $days_arrears = 0;
            }


            if(!empty($key->loan_product)) {
                $product_name = $key->loan_product->name;
            } else {
                $product_name = '';
            }

            if($key->repayment_cycle=='daily')
                $repayment_cycle = trans_choice('general.daily',1);
            else if($key->repayment_cycle=='weekly')
                $repayment_cycle = trans_choice('general.weekly',1);
            else if($key->repayment_cycle=='monthly')
                $repayment_cycle = trans_choice('general.monthly',1);
            else if($key->repayment_cycle=='bi_monthly')
                $repayment_cycle = trans_choice('general.bi_monthly',1);
            else if($key->repayment_cycle=='quarterly')
                $repayment_cycle = trans_choice('general.quarterly',1);
            else if($key->repayment_cycle=='semi_annual')
                $repayment_cycle = trans_choice('general.semi_annually',1);
            else if($key->repayment_cycle=='annually')
                $repayment_cycle = trans_choice('general.annual',1);
            else $repayment_cycle = 'None';

            $payment_today = \App\Models\LoanTransaction::where('loan_id', $key->id)->where('transaction_type', 'repayment')->where('reversed', 0)->where('payment_type', 'regular')->where('date', date('Y-m-d'))->get();
            
            array_push($loans, array(
                'loan_id' => $key->id,
                'customer' => $borrower,
                'status' => $status,
                'outstanding' => $outstanding,
                'due_days' => $days_arrears,
                'product_name' => $product_name,
                'amount_approved' => $key->principal,
                'disturb_date' => date("d-m-Y", strtotime($key->release_date)),
                'balance' => $balance,
                'payment_frequency' => $repayment_cycle,
                'repayment_cycle' => $key->repayment_cycle,
                'release_data' => $key->first_payment_date,
                'payment_today' => count($payment_today)
            ));
        }    

        return response()->json([
            'status' => 200,
            'loans' => $loans
        ], 200);
    }


    public function getLoanDetailFromLoanReport(Request $request) {
        $loan = \App\Models\Loan::where('branch_id', 1)->where('id', $request->loan_id)->first();
        if (!empty($loan->borrower)) {
            $phone_number = $loan->borrower->mobile;
            $customer_location = $loan->borrower->address;
        } else {
            $phone_number = '';
            $customer_location = '';
        }
        
        $last_pay = \App\Models\LoanTransaction::where('loan_id', $request->loan_id)->where('transaction_type', 'repayment')->where('reversed', 0)->orderBy('date', 'desc')->first();
        if(!empty($last_pay)) {
            $last_payment = $last_pay->credit;
            $last_payment_date = $last_pay->date;
            $date1 = new \DateTime($last_payment_date);
            $date2 = new \DateTime(date('Y-m-d'));
            $remain_days = $date2->diff($date1)->format("%a");

            $lat = $last_pay->lat;
            $long = $last_pay->lng;
        } else {
            $last_payment = 0;
            $last_payment_date = 0;
            $remain_days = 0;
            $lat = 0;
            $long = 0;
        }

        $next_payment = [];
        $next_pay = 0;
        $next_pay_date = '';
        $totalPrincipal = \App\Models\LoanSchedule::where('loan_id', $request->loan_id)->sum('principal');
        $payPrincipal = \App\Models\LoanTransaction::where('loan_id', $request->loan_id)->where('transaction_type', 'repayment')->where('reversed', 0)->where('payment_type', 'principal')->sum('credit');
        $balancePrincipal = $totalPrincipal - $payPrincipal;

        $payments = \App\Models\LoanTransaction::where('loan_id', $request->loan_id)->where('transaction_type', 'repayment')->where('reversed', 0)->where('payment_type', 'regular')->sum('credit');
        $loan_schedules = \App\Models\LoanSchedule::where('loan_id', $request->loan_id)->orderBy('due_date', 'asc')->get();
        if (count($loan_schedules) > 0) {
            foreach ($loan_schedules as $schedule) {
                $schedule_count = count($loan_schedules);
                $principal = $balancePrincipal / $schedule_count;
                $loanRate = $loan->interest_rate;
                if ($loan->repayment_cycle=='daily') {
                    $interest = (($balancePrincipal * $loanRate) / 100.00) / 30;            
                } elseif ($loan->repayment_cycle=='weekly') {
                    $interest = (($balancePrincipal * $loanRate) / 100.00) / 4;            
                } elseif ($loan->repayment_cycle=='bi_monthly') {
                    $interest = (($balancePrincipal * $loanRate) / 100.00) / 2;            
                } elseif ($loan->repayment_cycle=='monthly') {
                    $interest = ($balancePrincipal * $loanRate) / 100.00;        
                } else {
                    $interest = 0;
                }
                $due = $principal + $interest + $schedule->fees + $schedule->penalty- $schedule->interest_waived;

                if ($schedule->due_date > date("Y-m-d")) {
                    $next_payment[$schedule->due_date] = $due;
                }

                // if ($payments > 0) {
                //     if ($payments > $due) {
                        
                //     } else {
                //         $paid = $payments;
                //         $payments = 0;
                //         $next_payment[$schedule->due_date] = (($schedule->principal + $schedule->interest + $schedule->fees + $schedule->penalty- $schedule->interest_waived) - $paid);
                //     }
                // } else {
                //     $next_payment[$schedule->due_date] = (($schedule->principal + $schedule->interest + $schedule->fees + $schedule->penalty- $schedule->interest_waived));
                // }
            }
            if (count($next_payment) > 0) {
                foreach($next_payment as $key=>$value) {
                    if ($key > date("Y-m-d")) {
                        $next_pay = $value;
                        $next_pay_date = date("d-m-Y", strtotime($key));
                        break;
                    }
                }
            }
        }
        
        $amount_payment = \App\Models\LoanSchedule::where('loan_id', $request->loan_id)->count();
        $original_date = $loan->first_payment_date;

        $balance = 0;
        $histories = [];
        foreach (\App\Models\LoanTransaction::where('loan_id',$request->loan_id)->where('reversed', 0)->whereIn('reversal_type',['user','none'])->get() as $key) {
            $balance = $balance + ($key->debit - $key->credit);            
            array_push($histories, array(
                'refer_id' => $key->id,
                'date' => date("d-m-Y", strtotime($key->date)),
                'debit' => $key->debit,
                'credit' => $key->credit,
                'balance' => $balance
            ));
        }

        $loan_paid_items = \App\Helpers\GeneralHelper::loan_paid_items($request->loan_id, $loan->release_date, date("Y-m-d"));
        $late_fee_balance = \App\Helpers\GeneralHelper::loan_total_penalty($request->loan_id)-$loan_paid_items['penalty'];

        $loan_comments = [];
        foreach (\App\Models\LoanComment::where('loan_id', $request->loan_id)->orderBy('user_id', 'asc')->orderBy('id', 'asc')->get() as $key) {
            $comment_user = \App\Models\User::where('id', $key->user_id)->first();
            $comment['user'] = $comment_user->first_name. ' ' .$comment_user->last_name;
            $comment['comment'] = $key->notes;
            $comment['date'] = date("d-m-Y", strtotime($key->created_at));
            $loan_comments[] = $comment;
        }

        return response()->json([
            'status' => 200,
            'loan' => $loan,
            'last_payment' => $last_payment,
            'last_payment_date' => $remain_days,
            'late_fee_balance' => $late_fee_balance,
            'next_pay' => $next_pay,
            'next_pay_date' => $next_pay_date,
            'amount_payment' => $amount_payment,
            'start_paying' => date("d-m-Y", strtotime($original_date)),
            'history' => $histories,
            'loan_comments' => $loan_comments,
            'phone_number' => $phone_number,
            'lat' => $lat,
            'long' => $long,
            'customer_location' => $customer_location
        ], 200);
    }
    
    public function updateTrackLocation(Request $request) {
        $user_id = $request->user_id;
        $lat = $request->lat;
        $long = $request->long;

        $credentials = [
            'lat' => $lat,
            'lng' => $long
        ];

        $user = \App\Models\User::where('id', $user_id)->update($credentials);
        
        return response()->json([
            'status' => 200,
            'message' => 'Procesado con exito',
            'user_data' => $user
        ], 200);
    }
}
