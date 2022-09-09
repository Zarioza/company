<?php

use App\Http\Controllers\Api\V1\EmployeeController;
use App\Http\Controllers\Api\V1\PositionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'v1',
    'as' => 'api.',
], function () {
    Route::post('/positions', [PositionController::class, 'store'])->name('position.store');
    Route::get('/positions', [PositionController::class, 'index'])->name('position.index');
    Route::patch('/positions/{position:id}', [PositionController::class, 'update'])->name('position.update');
    Route::get('/positions/{position:id}', [PositionController::class, 'show'])->name('position.show');
    Route::delete('/positions/{position:id}', [PositionController::class, 'destroy'])->name('position.destroy');

    Route::post('/employees', [EmployeeController::class, 'store'])->name('employee.store');
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employee.index');
    Route::get('/employees/{employee:id}', [EmployeeController::class, 'show'])->name('employee.show');
    Route::patch('/employees/{employee:id}', [EmployeeController::class, 'update'])->name('employee.update');
});
