<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image' => 'required|image|mimes:jpeg,png',
            'name' => 'required',
            'brand' => 'nullable',
            'description' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'item_condition_id' => 'required',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
        ];
    }

    public function messages()
    {
        return [
            'image.required' => '商品画像を選択してください。',
            'image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
            'name.required' => '商品名は必須です。',
            'description.required' => '商品の説明は必須です。',
            'description.max' => '商品の説明は255文字以内で入力してください。',
            'price.required' => '販売価格は必須です。',
            'price.integer' => '販売価格は数値で入力してください。',
            'price.min' => '販売価格は0円以上で入力してください。',
            'item_condition_id.required' => '商品の状態を選択してください。',
            'categories.required' => 'カテゴリを1つ以上選択してください。',
        ];
    }
}
