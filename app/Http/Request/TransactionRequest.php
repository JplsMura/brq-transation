<?php

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionRequest extends FormRequest
{
    public function rules()
    {
        return [
            'cpf_cnpj' => ['required', 'string', 'max:14'],
            'valor' => 'required|numeric',
            'localizacao' => 'required|string',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
