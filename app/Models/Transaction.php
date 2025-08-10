<?php

namespace App\Models;

use App\Enums\TransactionStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';
    protected $fillable = [
        'cpf_cnpj',
        'valor',
        'data_hora',
        'localizacao',
        'status',
        'motivo_risco'
    ];

    protected $casts = [
        'data_hora' => 'datetime',
        'status' => TransactionStatusEnum::class,
    ];

    /**
     * Formata o CPF/CNPJ ao ser lido.
     */
    public function getCpfCnpjAttribute($value)
    {
        $value = preg_replace('/[^0-9]/', '', $value);

        if (strlen($value) === 11) {
            return substr($value, 0, 3) . '.' . substr($value, 3, 3) . '.' . substr($value, 6, 3) . '-' . substr($value, 9, 2);
        } elseif (strlen($value) === 14) {
            return substr($value, 0, 2) . '.' . substr($value, 2, 3) . '.' . substr($value, 5, 3) . '/' . substr($value, 8, 4) . '-' . substr($value, 12, 2);
        }

        return $value;
    }

    /**
     * Limpa o CPF/CNPJ antes de ser salvo no banco de dados.
     */
    public function setCpfCnpjAttribute($value)
    {
        $this->attributes['cpf_cnpj'] = preg_replace('/[^0-9]/', '', $value);
    }

    /**
     * Formata a data e hora para o padrão brasileiro (d/m/Y H:i:s) ao ser lido.
     */
    protected function getDataHoraAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->format('d/m/Y H:i:s');
        }

        return null;
    }

    /**
     * Formata o valor para o padrão de moeda brasileiro (R$ 1.234,56).
     */
    protected function getValorAttribute($value)
    {
        return 'R$ ' . number_format($value, 2, ',', '.');
    }
}
