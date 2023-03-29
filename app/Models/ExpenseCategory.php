<?php

namespace App\Models;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
        'category_description',
        'category_status', 
    ];


    public function expense(){
        return $this->hasMany(Expense::class);
    }

}