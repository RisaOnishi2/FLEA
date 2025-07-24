<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function show($id)
    {
        $item = Item::findOrFail($id);

        $paymentMethods = [
            'コンビニ払い',
            'カード支払い',
        ];

        $user = Auth::user();
        $profile = $user->profile;
        $selectedPaymentMethod = request('payment_method', 'コンビニ払い');

        if (session()->has('shipping_address')) {
            $shipping = session('shipping_address');
            $profile->postal_code = $shipping['postal_code'] ?? $profile->postal_code;
            $profile->address     = $shipping['address'] ?? $profile->address;
            $profile->building    = $shipping['building'] ?? $profile->building;
        }

        return view('purchase', compact('item', 'paymentMethods', 'profile','selectedPaymentMethod'));
    }

    public function store(PurchaseRequest $request, $id)
    {

        $item = Item::findOrFail($id);

        if ($item->is_sold) {
            return back()->withErrors(['item' => 'この商品はすでに購入済みです。']);
        }

        $user = Auth::user();
        $profile = $user->profile;

        $order = new Order();
        $order->user_id = $user->id;
        $order->item_id = $item->id;
        $order->price = $item->price;
        $order->payment_method = $request->payment_method;
        $order->address = $request->address;
        $order->postal_code = $request->postal_code;
        $order->building = $request->building;
        $order->save();

        $item->is_sold = true;
        $item->save();

        return redirect()->route('purchase.show', $item->id)->with('success', '購入が完了しました。');
    }

    public function edit($id)
    {
        $shippingAddress = session('shipping_address'); 
        return view('address', compact('shippingAddress', 'id'));
    }

    public function update(Request $request, $id)
    {
        session([
            'shipping_address' => [
                'postal_code' => $request->input('postal_code'),
                'address'     => $request->input('address'),
                'building'    => $request->input('building'),
            ]
        ]);

    return redirect()->route('purchase.show', ['id' => $id])
        ->with('success', '配送先を保存しました');
    }
}
