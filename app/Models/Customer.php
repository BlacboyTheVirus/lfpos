<?php

namespace App\Models;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'count_id',
        'customer_code', 
        'customer_name', 
        'customer_phone', 
        'customer_email', 
        'customer_amount_due', 
        'customer_invoice_due', 
        'created_by'
    ];

    protected $appends = ['total_due'];

   
    public function getTotalDueAttribute()
    {
        return $this->customer_amount_due + $this->customer_invoice_due;
    }

    public function invoices(){
        return $this->hasMany(Invoice::class);
    }

    public function payments(){
        return $this->hasManyThrough(Payment::class, Invoice::class);
    }

    public function invoiceitems(){
        return $this->hasManyThrough(Invoiceitem::class, Invoice::class);
    }


}
