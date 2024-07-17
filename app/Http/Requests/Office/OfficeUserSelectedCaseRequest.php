<?php

namespace App\Http\Requests\Office;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Models\Customer;
use App\Models\OfficeUsersCaseAccess;

class OfficeUserSelectedCaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customerIds = Customer::pluck("id")->all();
        
        $rules = [
            "customer_id" => ["required", "integer", Rule::in($customerIds)],
            "type" => ["required", Rule::in(array_keys(OfficeUsersCaseAccess::getCaseAccessTypes()))],
            "id" => ["nullable", "integer"],
        ];
        
        if(!empty($this->type) && $this->type == OfficeUsersCaseAccess::CASE_ACCESS_SELECTED)
        {
            $allowedNumbers = [];
            
            if(!empty($this->customer_id))
            {
                $customer = Customer::find($this->customer_id);
                if($customer)
                    $allowedNumbers = $customer->caseNumbers()->pluck("number")->all();
            }
            
            $rules["selected_case_numbers"] = ["required", "array", Rule::in($allowedNumbers)];
        }
        
        return $rules;
    }
    
    public function messages(): array
    {
        return [
            "customer_id.required" => __("Wybierz klienta"),
            "customer_id.integer" => __("Nieprawidłowy klient"),
            "customer_id.in" => __("Nieprawidłowy klient"),
            "type.required" => __("Wybierz rodzaj dostepu"),
            "type.in" => __("Nieprawidłowy rodzaj dostepu"),
            "selected_case_numbers.required" => __("Wybierz sprawy"),
            "selected_case_numbers.array" => __("Nieprawidłowe sprawy"),
            "selected_case_numbers.in" => __("Nieprawidłowe sprawy"),
        ];
    }
}