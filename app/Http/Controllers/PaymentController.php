<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(){
        $payments = Payment::all();
        return view ('payments.index', compact('payments'));
    }

    public function delete(Request $request){
        $payment = Payment::findOrFail($request->id);

        $payment_amount = $payment->amount;
        $invoice_id = $payment->invoice_id;
        $customer_id = $payment->customer->id;

        //  dd($customer_id);

        DB::beginTransaction();
        try {
            
            
            //update Invoice Amount Paid & Amount Due
            $invoice = Invoice::find($invoice_id);
            
            $updated_amount_paid = $invoice->invoice_amount_paid - $payment_amount;
            $updated_amount_due = $invoice->invoice_amount_due + $payment_amount;

            if ($updated_amount_paid >= $invoice->invoice_grand_total){
                $invoice->payment_status = "paid";
            } else if($updated_amount_paid == 0){
                $invoice->payment_status = "unpaid";
            } else if($updated_amount_paid < $invoice->invoice_grand_total){
                $invoice->payment_status = "partial";
            }

            $invoice->invoice_amount_paid = $updated_amount_paid;
            $invoice->invoice_amount_due = $updated_amount_due;
            $invoice->save();

            //update Customer Invoice Due
            $customer = Customer::find($customer_id);
            $customer->customer_invoice_due = $customer->customer_invoice_due + $payment_amount;
            $customer->save();

            //delete payment
            $payment->delete();
           
         
            DB::commit();
            return (['status'=> 1, 'message' => 'Payment has been successfully deleted.']);

        }  catch(Exception $ex){
            
            DB::rollBack();
            return (['status'=> 0, 'message' => 'Oh! Oh!. Something unusal just happened. Please try again.']);
          

        } //END DB TRANSACTIONS
         
        
        
    }





     //////////////////////////////// AJAX ////////////////////////

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
 
         // Total records
         $totalRecords = DB::table('payments')->select('count(*) as allcount')->count();
 
         $totalRecordswithFilter = DB::table('payments')->select('count(*) as allcount')
             ->join('invoices', 'invoices.id', '=', 'payments.invoice_id' )
             ->join('customers', 'customers.id', '=', 'invoices.customer_id' )
             ->where('payment_date', 'like', '%' . $searchValue . '%')
             ->orWhere('invoice_code', 'like', '%' . $searchValue . '%')
             ->orWhere('customer_name', 'like', '%' . $searchValue . '%')
             ->orWhere('amount', 'like', '%' . $searchValue . '%')
             ->orWhere('payment_note', 'like', '%' . $searchValue . '%')
             ->orWhere('payment_created_by', 'like', '%' . $searchValue . '%')
             ->count();
 
 
         // Fetch records
       
         $records = DB::table('payments')->orderBy($columnName, $columnSortOrder)
            ->join('invoices', 'invoices.id', '=', 'payments.invoice_id' )
            ->join('customers', 'customers.id', '=', 'invoices.customer_id' )
            ->where('payment_date', 'like', '%' . $searchValue . '%')
            ->orWhere('invoice_code', 'like', '%' . $searchValue . '%')
            ->orWhere('customer_name', 'like', '%' . $searchValue . '%')
            ->orWhere('amount', 'like', '%' . $searchValue . '%')
            ->orWhere('payment_note', 'like', '%' . $searchValue . '%')
            ->orWhere('payment_created_by', 'like', '%' . $searchValue . '%')
                ->select('payments.*', 'invoices.invoice_code', 'customers.customer_name')
                ->skip($start)
                ->take($rowperpage)
                ->get();
 
 
         $data_arr = array();
 
         foreach ($records as $record) {
 
             $id = $record->id;
             $payment_date = Carbon::parse($record->payment_date)->format('d-m-Y');
             $payment_type = $record->payment_type;
             $payment_customer_name = $record->customer_name;
             $payment_invoice_code = $record->invoice_code;
             $payment_amount = $record->amount;
             $payment_note = $record->payment_note;
             $payment_created_by = $record->payment_created_by;
 
         
           
                 $data_arr[] = array(
                         "payment_date"             =>  $payment_date,
                         "payment_type"             =>  $payment_type,
                         "customer_name"            =>  $payment_customer_name,  
                         "invoice_code"              =>  $payment_invoice_code,   
                         "amount"                    =>  $payment_amount,
                         "payment_note"             =>  $payment_note,
                         "payment_created_by"       =>  $payment_created_by,
                         "action"                   =>  "<div class='btn-group'>
                         <button type='button' class='btn btn-sm btn-info dropdown-toggle dropdown-icon' data-toggle='dropdown'>Action <span class='sr-only'>Toggle Dropdown</span></button>
                         <div class='dropdown-menu' role='menu'>
                         <a class='dropdown-item' onclick= 'delete_payment(".$id.")' href='#'><i class='far fa-trash-alt mr-2 text-danger'></i>Delete</a>
                         </div>
                         </div>" ,
                 );
           
 
 
         }
 
         $response = array(
             "draw" => intval($draw),
             "iTotalRecords" => $totalRecords,
             "iTotalDisplayRecords" => $totalRecordswithFilter,
             "aaData" => $data_arr
         );
 
         return response()->json($response);
     } //end AJAX
}
