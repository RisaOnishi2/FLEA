<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile;
        return view('profile-update', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;

        // 🔸 画像バリデーション
        $imageRequest = app(ProfileRequest::class);
        $imageRequest->merge(request()->all());
        $imageData = $imageRequest->validate($imageRequest->rules());

        // 🔸 住所バリデーション
        $addressRequest = app(AddressRequest::class);
        $addressRequest->merge(request()->all());
        $addressData = $addressRequest->validate($addressRequest->rules());

        // 🔸 usersテーブルの更新
        $user->name = $addressData['name'];
        $user->save();

        // 🔸 画像アップロード
        $imageFilename = $profile ? $profile->image : null;
        if ($request->hasFile('image')) {
            $imageFilename = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('public/profile_images', $imageFilename);
        }

        // 🔸 profilesテーブルの更新
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'image' => $imageFilename,
                'postal_code' => $addressData['postal_code'],
                'address' => $addressData['address'],
                'building' => $addressData['building'],
            ]
        );

        return redirect()->route('home');
    }
}
