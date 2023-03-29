<?php

namespace App\Models;

use App\Models\ExpenseCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expenses_date',
        'category_id',
        'expenses_for',
        'expenses_amount',
        'expenses_reference',
        'expenses_note',
        'expenses_created_by', 
    ];

    public function expensecategory(){
        return $this->belongsTo(ExpenseCategory::class);
    }

}
