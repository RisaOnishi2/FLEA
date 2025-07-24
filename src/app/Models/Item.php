<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item_condition;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'price',
        'description',
        'image',
        'brand',
        'is_sold',
        'item_condition_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likedBy()
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'item_category');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function item_condition()
    {
        return $this->belongsTo(Item_condition::class, 'item_condition_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
