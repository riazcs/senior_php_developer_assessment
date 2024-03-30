<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MockResponseController;
use App\Http\Controllers\TransactionController;

Route::post('/mock-response', [MockResponseController::class, 'create']);
Route::post('/make-payment', [TransactionController::class, 'makePayment']);
Route::put('/update-transaction/{transaction_id}', [TransactionController::class, 'updateTransaction']);