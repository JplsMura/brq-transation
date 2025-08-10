<?php

namespace App\Repositories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;

class TransactionRepository implements TransactionRepositoryInterface
{
    /**
     * @param array $data
     * @return Transaction
     */
    public function create(array $data): Transaction
    {
        return Transaction::create($data);
    }

    /**
     * @param int $id
     * @return Transaction|null
     */
    public function findById(int $id): ?Transaction
    {
        return Transaction::find($id);
    }

    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return Transaction::all();
    }
}
