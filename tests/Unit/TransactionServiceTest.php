<?php

use App\Enums\TransactionStatusEnum;
use App\Models\Transaction;
use App\Repositories\TransactionRepositoryInterface;
use App\Services\TransactionService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use function Pest\Faker\faker;

uses(WithFaker::class);

beforeEach(function () {
    Carbon::setTestNow(Carbon::now());
});

afterEach(function () {
    Carbon::setTestNow();
    Mockery::close();
});

it('should mark a transaction above 1000 between 22h and 6h as high risk', function () {
    Carbon::setTestNow(Carbon::parse('2025-08-09 23:00:00'));

    $data = [
        'valor' => 1500.00,
        'localizacao' => $this->faker->city,
        'cpf_cnpj' => $this->faker->numerify('###########')
    ];

    $mockRepository = Mockery::mock(TransactionRepositoryInterface::class);
    $mockRepository->shouldReceive('create')
        ->once()
        ->andReturnUsing(function ($args) {
            $transaction = new Transaction();
            $transaction->forceFill($args);
            return $transaction;
        });

    $service = new TransactionService($mockRepository);
    $result = $service->createTransaction($data);

    expect($result->status)->toBe(TransactionStatusEnum::ALTO_RISCO);
    expect($result->motivo_risco)->toBe('Transação acima de R$1000 entre 22h e 6h');
});

it('transaction_below_1000_or_outside_the_risk_period', function() {
    $data1 = [
        'valor' => 500.00,
        'localizacao' => $this->faker->city,
        'cpf_cnpj' => $this->faker->numerify('###########')
    ];

    $data2 = [
        'valor' => 1500.00,
        'localizacao' => $this->faker->city,
        'cpf_cnpj' => $this->faker->numerify('###########')
    ];

    $mockRepository = Mockery::mock(TransactionRepositoryInterface::class);
    $mockRepository->shouldReceive('create')
        ->twice()
        ->andReturnUsing(function ($args) {
            $transaction = new Transaction();
            $transaction->forceFill($args);
            return $transaction;
        });

    $service = new TransactionService($mockRepository);

    // Cenário 1: valor abaixo de R$1000
    Carbon::setTestNow(Carbon::parse('2025-08-09 23:00:00'));
    $result1 = $service->createTransaction($data1);
    expect($result1->status)->toBe(TransactionStatusEnum::APROVADA);
    expect($result1->motivo_risco)->toBeNull();

    // Cenário 2: valor acima de R$1000, mas fora do horário de risco
    Carbon::setTestNow(Carbon::parse('2025-08-09 10:00:00'));
    $result2 = $service->createTransaction($data2);
    expect($result2->status)->toBe(TransactionStatusEnum::APROVADA);
    expect($result2->motivo_risco)->toBeNull();
});
