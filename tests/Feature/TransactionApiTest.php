<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;
use function Pest\Laravel\postJson;
use function Pest\Laravel\getJson;

uses(RefreshDatabase::class);

beforeEach(function () {
    $user = User::factory()->create();
    $this->token = JWTAuth::fromUser($user);
});

it('a guest cannot access transactions endpoints', function () {
    postJson('/api/transactions')
        ->assertStatus(401);
});

it('a user can create a transaction with valid data', function () {
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
    ])->postJson(route('transactions.store'), [
        'cpf_cnpj' => '12345678901',
        'valor' => 500.00,
        'localizacao' => 'Loja de Conveniencia',
    ]);

    $response->assertStatus(202);
    $response->assertJsonStructure([
        'success',
        'message',
        'data' => []
    ]);
});

it('a user can list transactions', function() {
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
    ])->getJson('/api/transactions');


    $response->assertStatus(200);

    $response->assertJsonStructure([
        'success',
        'data' => []
    ]);
});
