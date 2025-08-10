<?php

namespace App\Repositories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;

interface TransactionRepositoryInterface
{
    public function create(array $data): Transaction;
    public function findById(int $id): ?Transaction;
    public function findAll(): Collection;
}
