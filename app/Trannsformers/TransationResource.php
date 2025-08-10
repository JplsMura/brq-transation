<?php

namespace App\Trannsformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'cpf_cnpj' => $this->cpf_cnpj,
            'valor' => $this->valor,
            'data_hora' => $this->data_hora,
            'localizacao' => $this->localizacao,
            'status' => $this->status,
            'motivo_risco' => $this->motivo_risco,
        ];
    }
}
