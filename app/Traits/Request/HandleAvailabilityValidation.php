<?php

namespace App\Traits\Request;

use Illuminate\Validation\Validator;

trait HandleAvailabilityValidation
{
    /**
     * Add validation to ensure that either unit_availability or weight_availability is true.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withAvailabilityValidation(Validator $validator)
    {
        $validator->after(function ($validator) {
            $unitAvailability = $this->input('unit_availability');
            $weightAvailability = $this->input('weight_availability');

            if (!$unitAvailability && !$weightAvailability) {
                $validator->errors()->add('availability', 'Either unit_availability or weight_availability must be true.');
            }
        });
    }
}
