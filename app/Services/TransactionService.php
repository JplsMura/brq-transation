<?php

namespace App\Services;

use App\Enums\TransactionStatusEnum;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\TransactionRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TransactionService
{
    protected $transactionRepository;

    /**
     * @param TransactionRepositoryInterface $transactionRepository
     */
    public function __construct(TransactionRepositoryInterface $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * @param int $id
     * @return Transaction|null
     */
    public function findTransactionById(int $id): ?Transaction
    {
        return Transaction::findOrFail($id);
    }

    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function listTransactions(Request $request): LengthAwarePaginator
    {
        $cacheKey = 'transactions_list.' . md5(json_encode($request->all()));

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($request) {
            $query = Transaction::query();

            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('data_hora', [$request->input('start_date'), $request->input('end_date')]);
            }

            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            return $query->paginate(10);
        });
    }

    /**
     * @param array $data
     * @return Transaction
     */
    public function createTransaction(array $data): Transaction
    {
        $data['data_hora'] = Carbon::now();
        $data = $this->applyRiskLogic($data);
        return $this->transactionRepository->create($data);
    }

    /**
     * Aplica a lógica de risco com base no valor e horário da transação.
     *
     * @param array $data
     * @return array
     */
    private function applyRiskLogic(array $data): array
    {
        $valor = $data['valor'];
        $dataHora = Carbon::parse($data['data_hora']);
        $hora = (int)$dataHora->format('H');

        if ($valor > 1000 && ($hora >= 22 || $hora < 6)) {
            $data['status'] = TransactionStatusEnum::ALTO_RISCO->value;
            $data['motivo_risco'] = 'Transação acima de R$1000 entre 22h e 6h';
        } else {
            $data['status'] = TransactionStatusEnum::APROVADA->value;
            $data['motivo_risco'] = null;
        }

        return $data;
    }
}
