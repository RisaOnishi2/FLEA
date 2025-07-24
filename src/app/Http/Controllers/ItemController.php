<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Comment;
use App\Models\Item_condition;
use App\Http\Requests\CommentRequest;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $pageType = $request->query('page', 'home');
    
        if ($pageType === 'mylist' && Auth::check()) {
            $items = Auth::user()->likes()->get();
        } elseif ($pageType === 'mylist') {
            // ★ ゲストがマイリストを見に来た場合は空を返す
           $items = collect(); // 空のコレクション
        } else {
            $items = Auth::check()
                ? Item::where('user_id', '!=', Auth::id())->get()
                : Item::all();
        }
    
        return view('index', [
            'items' => $items,
            'pageType' => $pageType,
        ]);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $items = Item::where('name', 'like', '%' . $keyword . '%')->get();

        return view('index', compact('items', 'keyword'));
    }
    
    public function detail($id)
    {
        $item = Item::with(['user.profile','categories', 'item_condition', 'comments.user'])->findOrFail($id);
        $comments = $item->comments;
        $profile = $item->user->profile;

        return view('detail', compact('item', 'comments', 'profile'));
    }

    public function storeComment(CommentRequest $request, $id)
    {
        $item = Item::findOrFail($id);

        $comment = new Comment([
            'comment' => $request->input('comment'),
            'user_id' => Auth::id(), 
        ]);

        $item->comments()->save($comment);

        return redirect()->route('item.detail', $id);
    }
}
