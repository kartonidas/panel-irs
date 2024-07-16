<?php

namespace App\Http\Requests\Office;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Rules\Nip;
use App\Rules\Pesel;
use App\Rules\Regon;
use App\Models\Court;
use App\Models\Customer;
use App\Models\Dictionary;

class CaseStoreRequest extends FormRequest
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
            "customer_signature" => ["required", "max:80"],
            "rs_signature" => ["required", "max:30"],
            "opponent" => ["required", "max:250"],
            "opponent_pesel" => ["nullable", "max:15", new Pesel],
            "opponent_regon" => ["nullable", "max:15", new Regon],
            "opponent_nip" => ["nullable", "max:15", new Nip],
            "opponent_krs" => ["nullable", "max:100"],
            "opponent_street" => ["nullable", "max:200"],
            "opponent_zip" => ["nullable", "max:50"],
            "opponent_city" => ["nullable", "max:200"],
            "opponent_phone" => ["nullable", "max:50"],
            "opponent_email" => ["nullable", "email", "max:50"],
            "status_id" => ["required", "integer", Rule::in(array_keys(Dictionary::getByType("case_status")))],
            "death" => ["sometimes", "boolean"],
            "date_of_death" => ["sometimes", "required_if:death,1", "date_format:Y-m-d"],
            "insolvency" => ["sometimes", "boolean"],
            "completed" => ["sometimes", "boolean"],
            "baliff" => ["sometimes", "max:250"],
            "court_id" => ["nullable", "integer", Rule::in(Court::pluck("id")->all())],
        ];
        
        return $rules;
    }
    
    public function messages(): array
    {
        return [
            "customer_id.required" => __("Uzupełnij klienta"),
            "customer_id.integer" => __("Nieprawidłowy klient"),
            "customer_id.in" => __("Nieprawidłowy klient"),
            "customer_signature.required" => __("Uzupełnij oznaczenie klienta - numer sprawy klienta"),
            "customer_signature.max" => __("Maksymalna długość w polu oznaczenie klienta - numer sprawy klienta to :max znaków"),
            "rs_signature.required" => __("Uzupełnij oznaczenie RS"),
            "rs_signature.max" => __("Maksymalna długość w polu oznaczenie RS to :max znaków"),
            "opponent.required" => __("Uzupełnij przeciwnika"),
            "opponent.max" => __("Maksymalna długość w polu przeciwnik to :max znaków"),
            "opponent_pesel.max" => __("Maksymalna długość w polu pesel to :max znaków"),
            "opponent_regon.max" => __("Maksymalna długość w polu REGON to :max znaków"),
            "opponent_nip.max" => __("Maksymalna długość w polu NIP to :max znaków"),
            "opponent_krs.max" => __("Maksymalna długość w polu KRS to :max znaków"),
            "opponent_street.max" => __("Maksymalna długość w polu ulica to :max znaków"),
            "opponent_zip.max" => __("Maksymalna długość w polu kod pocztowy to :max znaków"),
            "opponent_city.max" => __("Maksymalna długość w polu miasto to :max znaków"),
            "opponent_phone.max" => __("Maksymalna długość w polu telefon to :max znaków"),
            "opponent_email.email" => __("Nieprawidłowy adres e-mail"),
            "opponent_email.max" => __("Maksymalna długość w polu adres e-mail to :max znaków"),
            "status_id.required" => __("Uzupełnij stan sprawy"),
            "status_id.integer" => __("Nieprawidłowa wartość w polu stan sprawy"),
            "status_id.in" => __("Nieprawidłowa wartość w polu stan sprawy"),
            "date_of_death.required" => __("Uzupełnij datę zgonu"),
            "date_of_death.date_format" => __("Nieprwaidłowy format w polu data zgonu (Y-m-d)"),
            "baliff.max" => __("Maksymalna długość w polu komornik to :max znaków"),
            "court_id.integer" => __("Nieprawidłowa wartość w polu sąd"),
            "court_id.in" => __("Nieprawidłowa wartość w polu sąd"),
        ];
    }
}