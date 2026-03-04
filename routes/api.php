<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::apiResource('todos', TodoController::class);
Route::patch('/todos/{id}/complete', [TodoController::class, 'complete']);

