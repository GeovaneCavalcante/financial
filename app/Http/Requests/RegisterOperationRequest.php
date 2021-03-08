<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterOperationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
            'value' => ['required', ['is_currency']],
            'payee' => 'required|exists:users,id',
            'payer' => 'required|exists:users,id',
        ];
    }

    /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

    /**
     * Implementação de uma validação personalizada
     *
     * @param $validator
     */
    public function withValidator($validator)
    {
        $validator->addExtension(
            'is_currency',
            function ($attribute, $value, $parameters, $validator) {
                return preg_match("/^-?[0-9]+(?:\.[0-9]{1,2})?$/", $value);
            }
        );
        $validator->addReplacer(
            'is_currency',
            function ($message, $attribute, $rule, $parameters, $validator) {
                return __("It is not a monetary value.", compact('attribute'));
            }
        );
    }
}
