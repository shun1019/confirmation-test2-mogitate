<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0|max:10000',
            'image' => 'required|nullable|image|mimes:jpeg,png,jpg|max:2048',
            'season' => 'required|sometimes|array',
            'season.*' => 'exists:seasons,id',
            'description' => 'required|string|max:120',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '商品名を入力してください',
            'price.required' => '値段を入力してください',
            'price.numeric' => '数値で入力してください',
            'price.min' => '0円以上の値を入力してください',
            'price.max' => '10,000円以内で入力してください',
            'season.required' => '季節を選択してください',
            'description.required' => '商品の説明を入力してください',
            'description.max' => '120文字以内で入力してください',
            'image.image' => '商品画像を登録してください',
            'image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
        ];
    }
}
