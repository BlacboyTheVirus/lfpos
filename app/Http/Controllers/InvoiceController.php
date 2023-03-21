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


    public function view(Request $request){
        $invoice = Invoice::findOrFail($request->id);
        $invoice->invoice_date = Carbon::parse($invoice->invoice_date)->format('jS, F Y');
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
                ]);
            }

            DB::commit();
            return (['status'=> $last_invoice_id, 'message' => 'Invoice has been successfully created.']);

        }  catch(Exception $ex){
            DB::rollBack();
            //throw $ex;
            return (['status'=> 0, 'message' => $ex.'Oh! Oh!. Something unusal just happened. Please try again.']);

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
            // RE WRITE TO CALCULATE FROM PAYMENTS DB AND INVOICE  //////////////////////////////////
                 
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


}
