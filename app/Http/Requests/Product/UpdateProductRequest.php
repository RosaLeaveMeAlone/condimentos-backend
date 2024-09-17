<?php

namespace App\Http\Requests\Product;

use App\Traits\Request\HandleAvailabilityValidation;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'image' => 'sometimes|string|max:255',
            'unit_availability' => 'required|boolean',
            'unit_price' => 'required_if:unit_availability,true|numeric|min:0',
            'unit_discount_quantity' => 'required_if:unit_availability,true|integer|min:1',
            'unit_discount_price' => 'required_if:unit_availability,true|numeric|min:0',
            'weight_availability' => 'required|boolean',
            'weight_price' => 'required_if:weight_availability,true|numeric|min:0',
            'weight_discount_quantity' => 'required_if:weight_availability,true|integer|min:1',
            'weight_discount_price' => 'required_if:weight_availability,true|numeric|min:0',
            'unit_name' => 'nullable|string|max:255',
            'category_id' => 'sometimes|exists:categories,id',
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
