<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'sales_transaction_id',
        'product_id',
        'name',
        'unit',
        'qty',
        'price',
        'discount',
        'taxes',
        'total'
    ];
}
