<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'price',
        'payment_method',
        'address',
        'postal_code',
        'building',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
       return $this->belongsTo(Item::class);
    }
}
