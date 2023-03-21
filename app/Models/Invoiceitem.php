<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoiceitem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'product_id',
        'product_name', 
        'width', 
        'height', 
        'unit_price',
        'quantity', 
        'unit_amount',
        'total_amount',
        'created_at',
        'updated_at'
    ];
}
