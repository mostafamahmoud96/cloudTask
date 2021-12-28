<?php

use App\Http\Controllers\Api\FileController;
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
Route::post('upload', [FileController::class, 'upload_file'])->name('upload_file');
Route::get('getFileName', [FileController::class, 'get_file_by_name'])->name('file_name');
Route::post('deletefile', [FileController::class, 'delete_file_by_name'])->name('delete_file');