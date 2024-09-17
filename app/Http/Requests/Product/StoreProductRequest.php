<?php

namespace App\Http\Requests\Product;

use App\Traits\Request\HandleAvailabilityValidation;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    use HandleAvailabilityValidation;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|string|max:255',
            'unit_availability' => 'required|boolean',
            'unit_price' => 'required_if:unit_availability,true|numeric|min:0',
            'unit_discount_quantity' => 'required_if:unit_availability,true|integer|min:1',
            'unit_discount_price' => 'required_if:unit_availability,true|numeric|min:0',
            'weight_availability' => 'required|boolean',
            'weight_price' => 'required_if:weight_availability,true|numeric|min:0',
            'weight_discount_quantity' => 'required_if:weight_availability,true|integer|min:1',
            'weight_discount_price' => 'required_if:weight_availability,true|numeric|min:0',
            'unit_name' => 'required_if:unit_availability,true|string|max:255',
            'is_available' => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $this->withAvailabilityValidation($validator);
    }
}
