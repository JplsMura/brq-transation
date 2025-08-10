<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Jobs\ProcessTransaction;
use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use App\Http\Request\TransactionRequest;
use App\Trannsformers\TransationResource;

class TransactionController extends Controller
{
    use ApiResponse;

    protected $transactionService;

    /**
     * @param TransactionService $transactionService
     */
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * @param TransactionRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|Response|object
     */
    public function store(TransactionRequest $request)
    {
        ProcessTransaction::dispatch($request->validated());

        return $this->success(
            message: "Transação em processamento, acompanhe todas as transações em /transactions",
            data: [],
            status: Response::HTTP_ACCEPTED
        );
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|Response|object
     */
    public function show($id)
    {
        $transaction = $this->transactionService->findTransactionById($id);

        return $this->success(
            data: new TransationResource($transaction),
            status: Response::HTTP_OK
        );
    }

    public function index(Request $request)
    {
        $transactions = $this->transactionService->listTransactions($request);

        return $this->success(
            data: TransationResource::collection($transactions),
            status: Response::HTTP_OK
        );
    }
}
