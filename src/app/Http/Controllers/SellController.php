<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Item_condition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ExhibitionRequest;

class SellController extends Controller
{
    
    public function create()
    {
        $categories = Category::all();
        $conditions = Item_condition::all();

        return view('sell', compact('categories', 'conditions'));
    }

    public function store(ExhibitionRequest $request)
    {
        $path = $request->file('image')->store('sample_images', 'public');
        $filename = basename($path);

        $item = new Item();
        $item->user_id = auth()->id();
        $item->name = $request->name;
        $item->brand = $request->brand;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->image = $filename;
        $item->item_condition_id = $request->item_condition_id;
        $item->is_sold = false;
        $item->save();

        $item->categories()->sync($request->categories);

        return redirect()->route('mypage')->with('success', '商品を出品しました。');
    }
}
