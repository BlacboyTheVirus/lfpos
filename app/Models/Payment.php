<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Payment extends Model
{
    use HasFactory;
    use \Znck\Eloquent\Traits\BelongsToThrough;



    protected $fillable = [
        'invoice_id',
        'payment_date',
        'payment_type', 
        'amount', 
        'payment_note', 
        'created_at',
        'updated_at',
        'payment_created_by'
    ];


    public function customer()
    {
        return $this->belongsToThrough(Customer::class, Invoice::class);
    }

}
