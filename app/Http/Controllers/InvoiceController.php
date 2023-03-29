<?php

namespace App\Http\Controllers;


use Exception;
use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Invoiceitem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    

    public function index()
    {
        $invoices = Invoice::all();
        return view('invoices.index', compact('invoices'));

    }

    public function create()
    {
        return view('invoices.create');

    }


    public function newcode()
    {
         //get the current invoice code and return to form
         $new_invoice_id = Invoice::max('count_id') + 1;

         return response()->json([
             'count_id'      => $new_invoice_id,
             'invoice_code' =>  config('global.invoice_code_prefix') . str_pad($new_invoice_id, 4, "0", STR_PAD_LEFT),
         ]);
    }



    public function view($id){
        $invoice = Invoice::findOrFail($id);
        $invoice->invoice_date = Carbon::parse($invoice->invoice_date)->format('jS, F Y');
        $invoice->payment_status = ucfirst($invoice->payment_status);
        foreach ($invoice->payments as $payment) {
            $payment->payment_date = Carbon::parse($payment->payment_date)->format('d-m-Y');

        }
        
        return view('invoices.view', compact(['invoice']));
    }


    public function store(Request $request){
        //dd($request->toArray());

        $customer_id = $request->customer_id;
        $count_id = $request->count_id;
        $invoice_code = $request->invoice_code;
        $invoice_date = Carbon::parse($request->invoice_date)->format('Y-m-d');
        $invoice_note = $request->invoice_note;
        $subtotal = $request->subtotal;
        $discount = $request->discount;
        $roundoff = $request->roundoff;
        $grandtotal = $request->grandtotal;
        $amount_paid = $request->amount_paid;
        $amount_due = $grandtotal - $amount_paid; 
       

     

            if ($amount_due <= 0) {
                $payment_status = 'paid';
            } elseif ($amount_due == $grandtotal) {
                $payment_status = 'unpaid';
            } elseif ($amount_due < $grandtotal) {
                $payment_status = 'partial';
            }

        $payment_type = $request->payment_type;
        $payment_note = $request->payment_note;


        $item_id = $request->item_id;
        $item_name = $request->item_name;
        $width = $request->width;
        $height = $request->height;
        $unit_price = $request->unit_price;
        $amount = $request->amount;
        $quantity = $request->quantity;
        $total_amount = $request->total_amount;

// dd($request);

        DB::beginTransaction();
        try {
            //SAVE INVOICE DETAILS
            $invoice = new Invoice();
            $invoice->customer_id = $customer_id;
            $invoice->count_id = $count_id;
            $invoice->invoice_code = $invoice_code;
            $invoice->invoice_date = $invoice_date;
            $invoice->invoice_subtotal = $subtotal;
            $invoice->invoice_discount = $discount;
            $invoice->invoice_roundoff = $roundoff;
            $invoice->invoice_grand_total = $grandtotal;
            $invoice->invoice_amount_paid = $amount_paid;
            $invoice->invoice_amount_due = $amount_due;
            $invoice->invoice_note = $invoice_note;
            $invoice->payment_status = $payment_status;
            $invoice->created_by = Auth::user()->name;

            $invoice->save();            
            $last_invoice_id = $invoice->id;
          
            ////Update Amount Due in Customers
            $customers = Customer::where('id', $customer_id)->get();
            foreach ($customers as $customer) {
                $customer->customer_invoice_due = $customer->customer_invoice_due + $amount_due;
                $customer->save();
            }
            
            //INVOICE ITEMS DETAILS
            $invoice_items = [];
            foreach ($item_id as $key => $value) {
                $invoice_items[$key]['product_id'] = $item_id[$key];
                $invoice_items[$key]['product_name'] = $item_name[$key];
                $invoice_items[$key]['width'] = $width[$key];
                $invoice_items[$key]['height'] = $height[$key];
                $invoice_items[$key]['unit_price'] = $unit_price[$key];
                $invoice_items[$key]['quantity'] = $quantity[$key];
                $invoice_items[$key]['unit_amount'] = $amount[$key];
                $invoice_items[$key]['total_amount'] = $total_amount[$key];
            }

           // dd ($invoice_items);
            $invoice->invoiceitems()->createMany($invoice_items);

            //PAYMENT DETAILS
            if ($amount_paid > 0) {
                $invoice->payments()->create([
                    'invoice_id'      => $last_invoice_id,
                    'payment_date'    => $invoice_date,
                    'payment_type'    => $payment_type,
                    'amount'          => $amount_paid,
                    'payment_note'    => $payment_note,
                    'payment_created_by'    => Auth::user()->name,
                ]);
            }

            DB::commit();
            return (['status'=> $last_invoice_id, 'message' => 'Invoice has been successfully created.']);

        }  catch(Exception $ex){
            DB::rollBack();
            //throw $ex;
            return (['status'=> 0, 'message' => 'Oh! Oh!. Something unusal just happened. Please try again.']);

        } //END DB TRANSACTIONS


    } // end INVOICE STORE


    public function edit(Request $request){
        $invoice = Invoice::findOrFail($request->id);
        $invoice->invoice_date = Carbon::parse($invoice->invoice_date)->format('d-m-Y');


        foreach ($invoice->invoiceitems as $item) {
            $item->width = number_format($item->width, 2, '.', '');
            $item->height = number_format($item->height, 2, '.', '');
        }

        foreach ($invoice->payments as $payment) {
            $payment->payment_date = Carbon::parse($payment->payment_date)->format('d-m-Y');
        }
        return view('invoices.edit', compact(['invoice']));
    }// END INVOICE EDIT



///////////////////////////////////////////////////////
// UPDATE INVOICE
//////////////////////////////////////////////////////

    public function update(Request $request){
        // dd($request->toArray());
        // exit;


        $invoice_id = $request->invoice_id;
        $invoice_code = $request->invoice_code;
        $count_id = $request->count_id;
        $customer_id = $request->customer_id;
        $old_customer_id = $request->old_customer_id;
        $invoice_date = Carbon::parse($request->invoice_date)->format('Y-m-d');
        $invoice_note = $request->invoice_note;
        $subtotal = $request->subtotal;
        $discount = $request->discount;
        $roundoff = $request->roundoff;
        $grandtotal = $request->grandtotal;
        $amount_paid = $request->amount_paid;
        $amount_due = $grandtotal - $amount_paid; 

            if ($amount_due <= 0) {
                $payment_status = 'paid';
            } elseif ($amount_due == $grandtotal) {
                $payment_status = 'unpaid';
            } elseif ($amount_due < $grandtotal) {
                $payment_status = 'partial';
            }

        $payment_type = $request->payment_type;
        $payment_note = $request->payment_note;


        $item_id = $request->item_id;
        $item_name = $request->item_name;
        $width = $request->width;
        $height = $request->height;
        $unit_price = $request->unit_price;
        $amount = $request->amount;
        $quantity = $request->quantity;
        $total_amount = $request->total_amount;

       
        // dd($initial_amount_due);
        
        DB::beginTransaction();
        try {
            //UPDATE INVOICE DETAILS
            $invoice = Invoice::where('id', '=', $invoice_id)->first();
               
            $initial_amount_paid = $invoice->invoice_amount_paid;
            $initial_amount_due = $invoice->invoice_amount_due;
            $total_pay = $initial_amount_paid + $amount_paid;
            

            if ($total_pay >= $grandtotal){
                $payment_status = 'paid';
            } else if ($total_pay == 0 ){
                $payment_status = 'unpaid';
            } else if ($total_pay < $grandtotal){
                $payment_status = 'partial';
            }
    

           $invoice->update([
                'customer_id'       => $customer_id,
                'count_id'          => $count_id,
                'invoice_code'      => $invoice_code,
                'invoice_date'      => $invoice_date,
                'invoice_subtotal'  => $subtotal,
                'invoice_discount'  => $discount,
                'invoice_roundoff'  => $roundoff,
                'invoice_grand_total'   => $grandtotal,
                'invoice_amount_paid'   => $total_pay,
                'invoice_amount_due'    => $grandtotal - $total_pay,
                'invoice_note'      => $invoice_note,
                'payment_status'    => $payment_status,
                'created_by'        => Auth::user()->name,
            ]);

                  
            $last_invoice_id = $invoice->id;

            ///////////////////////////////////////////////////
            // RE WRITE TO CALCULATE FROM PAYMENTS DB AND INVOICE
            //////////////////////////////////
                 
                ////Update Amount Due in Owner Customer
                  $customers = Customer::find($customer_id);
                  $initial_customer_due = $customers->customer_invoice_due;
                  $customers->update([
                      'customer_invoice_due'  => $initial_customer_due  + ($grandtotal - $initial_amount_due - $total_pay),
                  ]);


             
            //INVOICE ITEMS DETAILS
            $invoice_items = [];
            foreach ($item_id as $key => $value) {
                $invoice_items[$key]['product_id'] = $item_id[$key];
                $invoice_items[$key]['product_name'] = $item_name[$key];
                $invoice_items[$key]['width'] = $width[$key];
                $invoice_items[$key]['height'] = $height[$key];
                $invoice_items[$key]['unit_price'] = $unit_price[$key];
                $invoice_items[$key]['quantity'] = $quantity[$key];
                $invoice_items[$key]['unit_amount'] = $amount[$key];
                $invoice_items[$key]['total_amount'] = $total_amount[$key];
            }

           // dd ($invoice_items);
           $invoice->invoiceitems()->delete();
           $invoice->invoiceitems()->createMany($invoice_items);

            //PAYMENT DETAILS
            if ($amount_paid > 0){
                $invoice->payments()->create([
                    'invoice_id'      => $last_invoice_id,
                    'payment_date'    => $invoice_date,
                    'payment_type'    => $payment_type,
                    'amount'          => $amount_paid,
                    'payment_note'    => $payment_note,
                    'payment_created_by'      => Auth::user()->name,
                ]);
            }



/////////////////////////////////////////////////////////
//// CHANGED CUSTOMER ON INVOICE 

if ($old_customer_id != $customer_id){

    //get amount due on INVOICE
        $current_invoice_due = $invoice->invoice_amount_due;

    // subtract from invoice due on OLD CUSTOMER TABLE
        $old_cust = Customer::find($old_customer_id);
        $old_cust->update([
                        'customer_invoice_due' => $old_cust->customer_invoice_due  -  $current_invoice_due
        ]);

    // add to Invoice due on NEW CUSTOMER

        $new_cust = Customer::find($customer_id);
        $new_cust->update([
                        'customer_invoice_due' => $new_cust->customer_invoice_due  +  $current_invoice_due
        ]);

}


//////////////////////////////////////////////////////
             
            DB::commit();
            return (['status'=> $last_invoice_id, 'message' => 'Invoice has been successfully updated.']);

        }  catch(Exception $ex){
            DB::rollBack();
            // dd($ex);
            return (['status'=> 0, 'message' => 'Oh! Oh!. Something unusal just happened. Please try again.']);

        } //END DB TRANSACTIONS



    } // END INVOICE UPDATE



//////////////////////////////////////////////////////////
// DELETE INVOICE 
/////////////////////////////////////////////////////////

    public function delete($id){
    $invoice = Invoice::findOrFail($id);

    $grandtotal = $invoice->invoice_grand_total;
    $amountpaid = $invoice->invoice_amount_paid;
    $amountdue = $invoice->invoice_amount_due;
    $customer_id = $invoice->customer->id;

    DB::beginTransaction();
    try {

        

        //Delete Invoice Items
        $invoice->invoiceitems()->delete();

        //Delete Payments
        $invoice->payments()->delete();

        //Delete Invoice
        $invoice->delete();

        //subtract InvoiceDue from Customer Table
        $customer = Customer::find($customer_id);
        $customer->update([
            'customer_invoice_due' => $customer->customer_invoice_due  -  $amountdue
        ]);

        
        DB::commit();
        return (['status'=> 1, 'message' => 'Invoice has been successfully deleted.']);
    } catch(Exception $ex) {
        DB::rollBack();
        return (['status'=> 0, 'message' => $ex.' - Oh! Oh!. Something unusal just happened. Please try again.']);
    } //END DB TRANSACTIONS
     


}//END DELETE INVOICE












///////////////////////////////// INVOICE PAYMENT DETAILS /////////////

    public function paymentdetails(Request $request){

        $id = $request->id;
        $invoice = Invoice::with('payments')->findOrFail($id);

        

        $payments_details = '';
        if ($invoice->payments->count() == 0) {
            $payments_details = '<tr><td colspan="7" class="text-danger text-center text-sm">No Records Found</td></tr>';
        }
        foreach ($invoice->payments as $key => $payment) {
          $payments_details = $payments_details . '<tr id="payment_'.$payment->id.'">
                            <td>'.++$key.'</td>
                            <td>'.Carbon::parse($payment->payment_date)->format('d-m-Y').'</td>
                            <td class="text-right pr-2"> ₦ <span class="payment_row">'.number_format($payment->amount,2,).'</span></td>
                            <td class="text-center">'.$payment->payment_type.'</td>
                            <td>'.$payment->payment_note.'</td>
                            <td>'.$payment->payment_created_by.'</td>
                            <td><a onclick="delete_payment('.$payment->id.')" class="pointer btn btn-sm btn-danger "><i class="fa fa-trash-alt"></i></a>
                            </td>
                        </tr>';
        }
       
        $reponse = '<div class="row">
        <div class="col-md-12">
            <div class="row invoice-info">
              <div class="col-sm-4 invoice-col">
                Customer Information 
                <address>
                    <strong>'.$invoice->customer->customer_name.'</strong><br>
                    <strong>'.$invoice->customer->customer_code.'</strong><br>
                    <strong>'.$invoice->customer->customer_phone.'</strong><br>
                    
                </address>
              </div>

              <!-- /.col -->
              <div class="col-sm-4 invoice-col">
                Sales Information:
                <address>
                  <b>Invoice #: '.$invoice->invoice_code.'</b><br>
                  <b>Date : '.Carbon::parse($invoice->invoice_date)->format('d-m-Y').'</b><br>
                  <b>Grand Total : ₦ <span id="grand_total">'.number_format($invoice->invoice_grand_total,2,).'</span></b><br>
                </address>
              </div>
              <!-- /.col -->
              <div class="col-sm-4 invoice-col">
              Payment Summary:<br>
                <b>Paid Amount : ₦ <span id="total_payment">'.number_format($invoice->invoice_amount_paid,2,).'</span></b><br>
                <b>Due Amount : ₦ <span id="amount_due">'.number_format($invoice->invoice_amount_due,2,).'</span></b><br>
               
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
                                    <td>Payment Date</td>
                                    <td>Amount</td>
                                    <td>Payment Type</td>
                                    <td>Payment Note</td>
                                    <td>Created By</td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody>
                            '.$payments_details.'
                                
                            </tbody>
                        </table>
                 
                    </div>
                    <div class="clearfix"></div>
                </div>    
       
        </div><!-- col-md-9 -->
        <!-- RIGHT HAND -->
      </div>';

        return $reponse;

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

        $show_account_receivable = $request->get('show_account_receivable');

        // Total records
        $totalRecords = DB::table('invoices')->select('count(*) as allcount')->count();

        $totalRecordswithFilter = DB::table('invoices')->select('count(*) as allcount')
            ->join('customers', 'invoices.customer_id', '=', 'customers.id')
            ->where('invoice_code', 'like', '%' . $searchValue . '%')
            ->orWhere('invoice_date', 'like', '%' . $searchValue . '%')
            ->orWhere('customer_name', 'like', '%' . $searchValue . '%')
            ->orWhere('invoice_subtotal', 'like', '%' . $searchValue . '%')
            ->orWhere('invoice_discount', 'like', '%' . $searchValue . '%')
            ->orWhere('invoice_roundoff', 'like', '%' . $searchValue . '%')
            ->orWhere('invoice_grand_total', 'like', '%' . $searchValue . '%')
            ->orWhere('invoice_amount_paid', 'like', '%' . $searchValue . '%')
            ->orWhere('invoice_amount_due', 'like', '%' . $searchValue . '%')
            ->orWhere('invoice_note', 'like', '%' . $searchValue . '%')
            ->orWhere('payment_status', 'like', '%' . $searchValue . '%')
            ->orWhere('invoices.created_by', 'like', '%' . $searchValue . '%')
            ->count();

            // $users = User::where('active','1')->where(function($query) {
            //     $query->where('email','jdoe@example.com')
            //                 ->orWhere('email','johndoe@example.com');
            // })->get();

        // Fetch records
        $records = DB::table('invoices')->orderBy($columnName, $columnSortOrder)
            ->join('customers', 'invoices.customer_id', '=', 'customers.id')
            ->where('invoice_code', 'like', '%' . $searchValue . '%')
            ->orWhere('invoice_date', 'like', '%' . $searchValue . '%')
            ->orWhere('customer_name', 'like', '%' . $searchValue . '%')
            ->orWhere('invoice_subtotal', 'like', '%' . $searchValue . '%')
            ->orWhere('invoice_discount', 'like', '%' . $searchValue . '%')
            ->orWhere('invoice_roundoff', 'like', '%' . $searchValue . '%')
            ->orWhere('invoice_grand_total', 'like', '%' . $searchValue . '%')
            ->orWhere('invoice_amount_paid', 'like', '%' . $searchValue . '%')
            ->orWhere('invoice_amount_due', 'like', '%' . $searchValue . '%')
            ->orWhere('invoice_note', 'like', '%' . $searchValue . '%')
            ->orWhere('payment_status', 'like', '%' . $searchValue . '%')
            ->orWhere('invoices.created_by', 'like', '%' . $searchValue . '%')
            ->select('invoices.*', 'customers.customer_name')
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr = array();

        foreach ($records as $record) {

            $id = $record->id;
            $invoice_code = $record->invoice_code;
            $invoice_date = Carbon::parse($record->invoice_date)->format('d-m-Y');
            $invoice_customer_name = $record->customer_name;
            $invoice_grand_total = $record->invoice_grand_total;
            $invoice_amount_paid = $record->invoice_amount_paid;
            $invoice_amount_due = $record->invoice_amount_due;
            $invoice_payment_status = ucfirst($record->payment_status);
            $created_by = ucfirst($record->created_by);

        
            if ($show_account_receivable == 'checked') {
                if (($invoice_amount_due) > 0) {
                    $data_arr[] = array(
                        "invoice_date"              =>  $invoice_date,
                        "invoice_code"              =>  $invoice_code,
                        "customer_name"             =>  $invoice_customer_name,
                        "invoice_grand_total"       =>  $invoice_grand_total,
                        "invoice_amount_paid"       =>  $invoice_amount_paid,
                        "invoice_amount_due"        =>  $invoice_amount_due,
                        "payment_status"            =>  $invoice_payment_status,
                        "created_by"                =>  $created_by,
                        "action"                    =>  "<div class='btn-group'>
                        <button type='button' class='btn btn-sm btn-info dropdown-toggle dropdown-icon' data-toggle='dropdown'>Action <span class='sr-only'>Toggle Dropdown</span></button>
                        <div class='dropdown-menu' role='menu'>
                        <a class='dropdown-item' id= '" . $id . "' href='view/" .  $id . "'  ><i class= 'fas fa-eye mr-2'></i>View Invoice</a>
                        <a class='dropdown-item' id= '" . $id . "' href='edit/" .  $id . "'  ><i class= 'fas fa-edit mr-2'></i>Edit</a>
                        <a class='dropdown-item' onclick='view_payments(".$id.")' href='#' ><i class='fa fa-money-bill mr-2'></i>View Payments</a>
                        <div class='dropdown-divider'>
                        </div>
                        <a class='dropdown-item' href='#'><i class='far fa-trash-alt mr-2 text-danger'></i>Delete</a>
                        </div>
                        </div>" ,
                    );
                    $totalRecords--;
                    $totalRecordswithFilter--;
                }
            } else {
                $data_arr[] = array(
                    "invoice_date"                  =>  $invoice_date,
                        "invoice_code"              =>  $invoice_code,
                        "customer_name"             =>  $invoice_customer_name,
                        "invoice_grand_total"       =>  $invoice_grand_total,
                        "invoice_amount_paid"       =>  $invoice_amount_paid,
                        "invoice_amount_due"        =>  $invoice_amount_due,
                        "payment_status"            =>  $invoice_payment_status,
                        "created_by"                =>  $created_by,
                        "action"                    =>  "<div class='btn-group'>
                        <button type='button' class='btn btn-sm btn-info dropdown-toggle dropdown-icon' data-toggle='dropdown'>Action <span class='sr-only'>Toggle Dropdown</span></button>
                        <div class='dropdown-menu' role='menu'>
                        <a class='dropdown-item' id= '" . $id . "' href='view/" .  $id . "'  ><i class= 'fas fa-eye mr-2'></i>View Invoice</a>
                        <a class='dropdown-item' id= '" . $id . "' href='edit/" .  $id . "'  ><i class= 'fas fa-edit mr-2'></i>Edit</a>
                        <a class='dropdown-item' onclick='view_payments(".$id.")' href='#' ><i class='fa fa-money-bill mr-2'></i>View Payments</a><div class='dropdown-divider'>
                        </div>
                        <a class='dropdown-item' href='#'><i class='far fa-trash-alt mr-2 text-danger'></i>Delete</a>
                        </div>
                        </div>" ,
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
    } //end AJAX




}
