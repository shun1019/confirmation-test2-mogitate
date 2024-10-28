<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'price' => 'required|numeric|min:0|max:10000',
            'season' => 'required|array|min:1',
            'season.*' => 'exists:seasons,id',
            'description' => 'required|string|max:120',
            'image' => 'required|image|mimes:jpeg,png',
        ];
    }

    /**
     * Get custom error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => '商品名を入力してください',
            'price.required' => '値段を入力してください',
            'price.numeric' => '数値で入力してください',
            'price.min' => '0~10000以内で入力してください',
            'price.max' => '0~10000以内で入力してください',
            'season.required' => '季節を選択してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '120文字以内で入力してください',
            'image.required' => '商品画像を登録してください',
            'image.image' => '画像ファイルを選択してください',
            'image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
        ];
    }
}
