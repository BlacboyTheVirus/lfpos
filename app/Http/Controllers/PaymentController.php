<?php

namespace App\Http\Controllers;

use Exception;
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
}
