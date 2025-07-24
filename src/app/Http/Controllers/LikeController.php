<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function store($id)
    {
        $user = Auth::user();

        Like::firstOrCreate([
            'user_id' => $user->id,
            'item_id' => $id,
        ]);

        return back(); // 元のページにリダイレクト
    }

    public function destroy($id)
    {
        $user = Auth::user();

        Like::where('user_id', $user->id)
            ->where('item_id', $id)
            ->delete();

        return back(); // 元のページにリダイレクト
    }
}
