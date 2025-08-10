<?php

namespace App\Enums;

enum TransactionStatusEnum: string
{
    case ALTO_RISCO = 'alto risco';
    case APROVADA = 'aprovada';
    case ANALISANDO = 'analisando';
}
