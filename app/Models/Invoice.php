<?php

namespace App\Models;

use App\Models\Payment;
use App\Models\Customer;
use App\Models\Invoiceitem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;


    protected $fillable = [
        'customer_id',
        'count_id',
        'invoice_code', 
        'invoice_date', 
        'invoice_subtotal', 
        'invoice_discount',
        'invoice_roundoff', 
        'invoice_grand_total',
        'invoice_amount_paid',
        'invoice_amount_due',
        'invoice_note',
        'payment_status',
        'created_by',
        'created_at',
        'updated_at'
    ];



    public function invoiceitems(){
        return $this->hasMany(Invoiceitem::class);
    }

    public function payments(){
        return $this->hasMany(Payment::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }


}
