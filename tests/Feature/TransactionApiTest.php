<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TransactionApiTest extends TestCase
{
    public function testMockResponse()
    {
        $response = $this->post('/api/mock-response', [], ['X-Mock-Status' => 'accepted']);
        $response->assertStatus(200)->assertJson(['status' => 'accepted']);
    }

    public function testMakePaymentSuccess()
    {
        Http::fake([
            '*' => Http::response(['status' => 'accepted'], 200),
        ]);

        $response = $this->post('/api/make-payment', [], ['X-Mock-Status' => 'accepted']);
        $response->assertStatus(200)->assertJsonStructure(['transaction_id']);
    }

    public function testMakePaymentFailure()
    {
        Http::fake([
            '*' => Http::response(['status' => 'failed'], 400),
        ]);

        $response = $this->post('/api/make-payment', [], ['X-Mock-Status' => 'failed']);
        $response->assertStatus(400)->assertJson(['status' => 'failed']);
    }

    public function testUpdateTransaction()
    {
        $response = $this->put('/api/update-transaction/123', ['status' => 'completed']);
        $response->assertStatus(200)->assertJson(['message' => 'Transaction updated successfully']);
    }
}