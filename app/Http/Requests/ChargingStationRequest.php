<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ChargingStationRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name' => 'required|unique:charging_stations|string|between:3,255',
            'city' => 'required|string|between:3,100',
            'open_from' => 'required|date_format:H:i',
            'open_to' => 'required|date_format:H:i',
            'latitude' => ['required', 'regex:/^(\+|-)?(?:90(?:(?:\.0{1,8})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,8})?))$/'],
            'longitude' => ['required', 'regex:/^(\+|-)?(?:180(?:(?:\.0{1,8})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,8})?))$/'],
        ];
    }

    /**
     * Get the error messages that apply to the request parameters.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'The Name field is required',
            'name.unique' => 'The name field must be unique. Please specify another Name.',
            'city.required' => 'The City field is required',
            'open_from.required' => 'The Open from field is required',
            'open_from.date_format'  => 'A Open from must be in time-format: H:i',
            'open_to.required' => 'The To field is required',
            'open_to.date_format'  => 'A To from must be in time-format: H:i',
            'latitude.required' => 'The Latitude field is required',
            'longitude.required' => 'The Longitude field is required',
        ];
    }

    public function all($keys = null)
    {
        return parent::all($keys);
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     *
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['errors' => $errors
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
