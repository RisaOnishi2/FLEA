<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile;
        $page = $request->query('page', 'sell');

        if ($page === 'buy') {
            // Orders 経由で購入した商品を取得
            $items = $user->purchasedItems()->latest()->get();
        } else {
            // 自分が出品した商品
            $items = $user->items()->latest()->get();
        }

        return view('mypage.index', compact('user', 'profile', 'items', 'page'));
    }
}
