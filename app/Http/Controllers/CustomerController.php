<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $customers = Customer::all();
        return view ('customers.index', compact('customers'));
    }

    ///////////////////////////////////////////////
    /////// for Invoice form Customer List
    ///////////////////////////////////////////////

    public function getcustomers(Request $request) 
    { 
        $search = $request->search;
        if($search == ''){
            $customers = Customer::orderby('customer_code','asc')->select('id', 'customer_code', 'customer_name')->get();
        }else{
            $customers = Customer::orderby('customer_code','asc')->select('id', 'customer_code', 'customer_name')->where('customer_name', 'like', '%' .$search . '%')->get();
        }

        $response = array();
        foreach($customers as $customer){
            $response[] = array(
                "id"=>$customer->id,
                "text"=>$customer->customer_code . " | ". $customer->customer_name,
            );
        }
        return response()->json($response);
    }



     ///////////////////////////////////////////////
    /////// Amount due
    ///////////////////////////////////////////////

    public function amountdue(Request $request) 
    { 
        $id = $request->id;
        
        $dues = Customer::where('id', '=', $id)->get();
       // dd($due);
        foreach ($dues as $due) {
            $amountdue = $due->customer_amount_due + $due->customer_invoice_due;
        }
            
                return $amountdue;
    }



     ///////////////////////////////////////////////
    /////// Amount due
    ///////////////////////////////////////////////

    public function showpayprevious(Request $request) 
    { 
        $id = $request->id;
        $dues = Customer::where('id', '=', $id)->get();
        foreach ($dues as $due) {
            $amountdue = $due->customer_amount_due ;
        }
        return $amountdue;
    }




    public function ajax(Request $request)
    {

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        $show_account_receivable = $request->get('show_account_receivable');

        // Total records
        $totalRecords = Customer::select('count(*) as allcount')->count();

        $totalRecordswithFilter = Customer::select('count(*) as allcount')
            ->where('customer_code', 'like', '%' . $searchValue . '%')
            ->orWhere('customer_name', 'like', '%' . $searchValue . '%')
            ->orWhere('customer_phone', 'like', '%' . $searchValue . '%')
            ->orWhere('customer_email', 'like', '%' . $searchValue . '%')
            ->orWhere('customer_amount_due', 'like', '%' . $searchValue . '%')
            ->orWhere('customer_invoice_due', 'like', '%' . $searchValue . '%')
            ->count();

            // $users = User::where('active','1')->where(function($query) {
            //     $query->where('email','jdoe@example.com')
            //                 ->orWhere('email','johndoe@example.com');
            // })->get();

        // Fetch records
        $records = Customer::orderBy($columnName, $columnSortOrder)
            ->where('customer_code', 'like', '%' . $searchValue . '%')
            ->orWhere('customer_name', 'like', '%' . $searchValue . '%')
            ->orWhere('customer_phone', 'like', '%' . $searchValue . '%')
            ->orWhere('customer_email', 'like', '%' . $searchValue . '%')
            ->orWhere('customer_amount_due', 'like', '%' . $searchValue . '%')
            ->orWhere('customer_invoice_due', 'like', '%' . $searchValue . '%')
            ->select('*', DB::raw("(customer_amount_due + customer_invoice_due) as total_dues"))
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr = array();

       

        foreach ($records as $record) {

            $paypreviousdue = "";

            $id = $record->id;
            $customer_code = $record->customer_code;
            $customer_name = $record->customer_name;
            $customer_phone = $record->customer_phone;
            $customer_email = $record->customer_email;
            $customer_amount_due = $record->customer_amount_due;
            $customer_invoice_due = $record->customer_invoice_due;
            $total_dues = $record->total_dues;

            if($customer_amount_due > 0){
                $paypreviousdue = "   <a class='dropdown-item' onclick='show_pay_previous(".$id.")' href='#' ><i class='fa fa-money-bill mr-2'></i>Pay Previous Due</a>";
            }


            if ($show_account_receivable == 'checked') {
                if (($total_dues) > 0) {
                    $data_arr[] = array(
                        "customer_code"         =>  $customer_code,
                        "customer_name"         =>  $customer_name,
                        "customer_phone"        =>  $customer_phone,
                        "customer_email"        =>  $customer_email,
                        "customer_amount_due"   =>  $customer_amount_due,
                        "customer_invoice_due"  =>  $customer_invoice_due,
                        "customer_invoice_due"  =>  $customer_invoice_due,
                        "total_dues"             =>  $total_dues,
                        "action"                =>  ($id <> 1)? "<div class='btn-group'><button type='button' class='btn btn-sm btn-info dropdown-toggle dropdown-icon' data-toggle='dropdown'>Action <span class='sr-only'>Toggle Dropdown</span></button><div class='dropdown-menu' role='menu'>
                        <a class='dropdown-item edit-button' id= '" . $id . "' href='edit/" .  $id . "'  ><i class= 'fas fa-edit mr-2'></i>Edit</a>
                        <a class='dropdown-item' onclick='view_transactions(".$id.")' href='#' ><i class='fa fa-money-bill mr-2'></i>View Transactions</a>
                        ".$paypreviousdue."
                        <div class='dropdown-divider'></div>
                        <a class='dropdown-item' href='#'><i class='far fa-trash-alt mr-2 text-danger'></i>Delete</a>
                        </div></div>" :
                         "<div class='btn-group'><button type='button' class='btn btn-sm btn-info dropdown-toggle dropdown-icon' data-toggle='dropdown'>Action <span class='sr-only'>Toggle Dropdown</span></button><div class='dropdown-menu' role='menu'>
                        <a class='dropdown-item edit-button' id= '" . $id . "' href='edit/" .  $id . "'  ><i class= 'fas fa-edit mr-2'></i>Edit</a>
                        <a class='dropdown-item' onclick='view_transactions(".$id.")' href='#' ><i class='fa fa-money-bill mr-2'></i>View Transactions</a>
                        ".$paypreviousdue."
                        </div></div>" ,
                    );
                    $totalRecords--;
                    $totalRecordswithFilter--;
                }
            } else {
                $data_arr[] = array(
                    "customer_code"         =>  $customer_code,
                    "customer_name"         =>  $customer_name,
                    "customer_phone"        =>  $customer_phone,
                    "customer_email"        =>  $customer_email,
                    "customer_amount_due"   =>  $customer_amount_due,
                    "customer_invoice_due"  =>  $customer_invoice_due,
                    "total_dues"             =>  $total_dues,
                    "action"                =>  ($id <> 1) ? "<div class='btn-group'><button type='button' class='btn btn-sm btn-info dropdown-toggle dropdown-icon' data-toggle='dropdown'>Action <span class='sr-only'>Toggle Dropdown</span></button><div class='dropdown-menu' role='menu'>
                    <a class='dropdown-item edit-button' id= '" . $id . "' href='edit/" .  $id . "'  ><i class= 'fas fa-edit mr-2'></i>Edit</a>
                    <a class='dropdown-item' onclick='view_transactions(".$id.")' href='#' ><i class='fa fa-money-bill mr-2'></i>View Transactions</a>
                    ".$paypreviousdue."
                    <div class='dropdown-divider'></div>
                    <a class='dropdown-item' onclick = 'delete_customer(" .$id. ")' href='#'><i class='far fa-trash-alt mr-2 text-danger'></i>Delete</a>
                    </div></div>": 
                    "<div class='btn-group'><button type='button' class='btn btn-sm btn-info dropdown-toggle dropdown-icon' data-toggle='dropdown'>Action <span class='sr-only'>Toggle Dropdown</span></button><div class='dropdown-menu' role='menu'>
                    <a class='dropdown-item edit-button' id= '" . $id . "' href='edit/" .  $id . "'  ><i class= 'fas fa-edit mr-2'></i>Edit</a>
                    <a class='dropdown-item' onclick='view_transactions(".$id.")' href='#' ><i class='fa fa-money-bill mr-2'></i>View Transactions</a>
                    ".$paypreviousdue."
                   
                    </div></div>",
                );
            }// end if show account recievables


        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return response()->json($response);
    }

    ///////////////////////////////////////////////////
    // CREATE CUSTOMER FORM
    //////////////////////////////////////////////////

    public function create()
    {
        //get the current customer code and return to form
        $new_customer_id = Customer::max('count_id') + 1;

        return response()->json([
            'count_id'      => $new_customer_id,
            'customer_code' =>  config('global.customer_code_prefix') . str_pad($new_customer_id, 4, "0", STR_PAD_LEFT),
        ]);
    }


    //////////////////////////////////////////////
    // VIEW TRANSACTIONS
    ////////////////////////////////////////////

    public function transactions(Request $request){
        $customer_id = $request->id;
        $customers = Customer::where('id', $customer_id)->get();
        foreach ($customers as $customer){
            $customer_name = $customer->customer_name;
            $customer_code = $customer->customer_code;
            $customer_phone = $customer->customer_phone;
            $customer_email = $customer->customer_email;

        }
       
        $invoices = Invoice::where('customer_id', $customer_id)->get();

        $transaction_details = '';
        $transaction_footer = '';

        if ($invoices->count() == 0) {
            $transaction_details = '<tr><td colspan="7" class="text-danger text-center text-sm">No Records Found</td></tr>';
        } else {

            $total_invoice = 0;
            $total_payment = 0;
            $total_due= 0;
           

            foreach ($invoices as $key => $invoice) {
                    $total_invoice += $invoice->invoice_grand_total;
                    $total_payment += $invoice->invoice_amount_paid;
                    $total_due += $invoice->invoice_amount_due;
                    
                    if($invoice->payment_status == "paid"){
                        $pstatus_class = "badge-success";
                    } else if ($invoice->payment_status == "partial"){
                        $pstatus_class = "badge-warning";
                    } else {
                        $pstatus_class = "badge-danger";
                    }
                    

                $transaction_details = $transaction_details . '<tr id="invoice_'.$invoice->id.'" class="invoice_row">
                                  <td>'.++$key.'</td>
                                  <td>'.Carbon::parse($invoice->invoice_date)->format('d-m-Y').'</td>
                                  <td class="text-right pr-2"> '.$invoice->invoice_code.'</td> 
                                  <td class="text-right pr-2"> ₦ <span class="grandtotal_row">'.number_format($invoice->invoice_grand_total,2,).'</span></td>
                                   <td class="text-right pr-2"> ₦ <span class="payment_row">'.number_format($invoice->invoice_amount_paid,2,).'</span></td>
                                  <td class="text-right pr-2"> ₦ <span class="amountdue_row">'.number_format($invoice->invoice_amount_due,2,).'</span></td>
                                  <td><span class="badge '.$pstatus_class .'"> '.$invoice->payment_status.'</span></td>
                                  <td>
                                  <a onclick="delete_invoice('.$invoice->id.')" class="pointer btn btn-sm btn-danger "><i class="fa fa-trash-alt"></i></a>

                                  <a href="invoices/view/'.$invoice->id.'" class="pointer btn btn-sm btn-info "><i class="fa fa-eye"></i></a>
                                  </td>
                              </tr>';
            }

            $transaction_footer = "<tfoot><tr style='background-color:#f4f6f9 !important'>
            <td colspan=3 class='text-right'></td>
             <td class='text-right'><span class='add_total_invoice'>".money_format($total_invoice)."</span></td>
            <td class='text-right'><span class='add_total_payment'>".money_format($total_payment)."</span></td>
            <td class='text-right'><span class='add_total_due'>".money_format($total_due)."</span></td>
            <td></td>
            <td></td>
            </tr></tfoot>";

        }

      
        $reponse = '<div class="row">
                <div class="col-md-12">
                    <div class="row invoice-info">
                    <div class="col-sm-4 invoice-col">
                        Customer Information 
                        <address>
                            <strong>'.$customer_name.'</strong><br>
                            <strong>'.$customer_code.'</strong><br>
                            
                        </address>
                    </div>

                    <!-- /.col -->
                    <div class="col-sm-4 invoice-col">
                        Contact Information:
                        <address>
                        Phone : <b>'.$customer_phone.'</b><br>
                        Email :<b> '.$customer_email.'</b><br>
                        </address>
                    </div>
                    <!-- /.col -->
                    
                    </div>
                    <!-- /.row -->
                </div>
                <div class="col-md-12">
                
            
                        <div class="row">
                            <div class="col-md-12">
                            
                                <table class="table table-bordered table-condensed" >
                                    <thead>
                                        <tr style="background-color:#f4f6f9 !important">
                                            <td>#</td>
                                            <td>Invoice Date</td>
                                            <td>Invoice Code</td>
                                            <td>Invoice Total</td>
                                            <td>Total Payment</td>
                                            <td>Amount Due</td>
                                            <td>Payment Status</td>
                                            <td>Action</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    '.$transaction_details.'
                                        
                                    </tbody>

                                    '.$transaction_footer.'
                                </table>
                        
                            </div>
                            <div class="clearfix"></div>
                        </div>    
            
                </div><!-- col-md-9 -->
                <!-- RIGHT HAND -->
            </div>';

        return $reponse;
    }


    ///////////////////////////////////////////////////
    // PAY PREVIOUS
    ///////////////////////////////////////////////////

    public function payprevious(Request $request)
    {
        $customer_id = $request->customer_id;
        $prev_amt_paid =  $request->prev_amt_paid;
        $prev_payment_method = $request->prev_payment_method;
        
        DB::beginTransaction();
        try {

            // INSERT PAYMENT TO ACCOUNTS

            // UPDATE CUSTOMER AMOUNT DUE
            $customers = Customer::where('id', $customer_id)->get();
            foreach ($customers as $customer) {
                $customer->customer_amount_due = $customer->customer_amount_due - $prev_amt_paid;
                $customer->save();
            }
            
            DB::commit();

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e . ' - Sorry, couldn\'t update payment.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Whoops! Something unusual just happened.',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => $customer->customer_amount_due.' - Customer payment has been updated.',
        ]);
    }



    ///////////////////////////////////////////////////
    // CUSTOMER.STORE
    ///////////////////////////////////////////////////

    public function store(Request $request)
    {
        $count_id = $request->count_id;
        $customer_code = $request->customer_code;
        $customer_name = $request->customer_name;
        $customer_phone = $request->customer_phone;
        $customer_email = $request->customer_email;
        $customer_amount_due = $request->customer_amount_due;

        try {
           $data =  Customer::create([
                'count_id'           =>  $count_id,
                'customer_code'      =>  $customer_code,
                'customer_name'      =>  $customer_name,
                'customer_phone'     =>  $customer_phone,
                'customer_email'     =>  $customer_email,
                'customer_amount_due' =>  $customer_amount_due,
                'customer_invoice_due' =>  0.00,
                'created_by'         =>  auth()->user()->name
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => $e . ' - Sorry, couldn\'t create a new customer.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Whoops! Something unusual just happened.',
            ]);
        }

        return response()->json([
            'status' => true,
            'id'=> $data->id,
            'text' => $customer_code . ' | '. $customer_name,
            'message' => 'Customer has been created.',
        ]);
    }

    ///////////////////////////////////////////////
    // SHOW sCUSTOMER EDIT
    //////////////////////////////////////////////
    public function edit(Request $request)
    {
        $customer = Customer::find($request->id);

        $response = array(
            "customer_name" => $customer->customer_name,
            "customer_phone" => $customer->customer_phone,
            "customer_email" => $customer->customer_email,
            "customer_code" => $customer->customer_code,
            "customer_amount_due" => $customer->customer_amount_due,
        );

        return response()->json($response);
    }

    ///////////////////////////////////////////////
    // UPDATE CUSTOMER RECORD
    //////////////////////////////////////////////
    public function update(Request $request)
    {
        $id = $request->customer_id;
        $customer_name = $request->customer_name;
        $customer_phone = $request->customer_phone;
        $customer_email = $request->customer_email;
        $customer_amount_due = $request->customer_amount_due;

        try {
            Customer::where('id', $id)
                ->update([
                    'customer_name'      =>  $customer_name,
                    'customer_phone'     =>  $customer_phone,
                    'customer_email'     =>  $customer_email,
                    'customer_amount_due' =>  $customer_amount_due,
                ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Sorry, couldn\'t update the database.' . $e,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Whoops! Something unusual just happened.',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Customer record has been updated.',
        ]);
    }


    /////////////////////////////////////////////
    // CUSTOMER DELETE
    ////////////////////////////////////////////
    public function delete($id){
        
        //get the customer record
        $customer = Customer::with('invoices')->findOrFail($id);
        $total_previous_due = $customer->customer_amount_due;

        //get all invoices and account ENTRIES that belongs to the customer
        $invoices = $customer->invoices;
        $total_invoice_due = 0;
        
        $new_narrative = "Deleted Invoice from ".$customer->customer_code." | ". $customer->customer_name ;
        
        DB::beginTransaction();

        try {
            //change Invoice ID to 1 (Walk in Customer)and Invoice Notes to include Old Customer
            foreach ($invoices as $key => $invoice) {
                $total_due = $total_invoice_due + $invoice->invoice_amount_due;

                $invoice->update([
                    'customer_id' => 1,
                    'invoice_note' => $invoice->invoice_note . " - " . $new_narrative
                ]);
        
            }

            //update amount due on Walkin Customer
            $walkcustomer =  Customer::find(1);
            $walkcustomer->update([
                'customer_invoice_due' => $walkcustomer->customer_invoice_due + $total_invoice_due,
                'customer_amount_due' => $walkcustomer->customer_amount_due + $total_previous_due,

            ]);

            // change Account entries to belong to customer 1


            //delete Customer record
            $customer->delete();


            DB::commit();
            return (['status'=> 1, 'message' => ' - The Customer has been successfully deleted.']);

        } catch(Exception $ex) {

            DB::rollBack();
            return (['status'=> 0, 'message' => ' Oh Oh! Something went wrong.']);
        }

            
        
            
            
            
    }


}
