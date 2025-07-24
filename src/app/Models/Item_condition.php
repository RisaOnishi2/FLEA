<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item_condition extends Model
{
    use HasFactory;

    protected $table = 'item_conditions';

    protected $fillable = [
        'condition'
    ];
}