<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ResultController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthenticationController::class,'register']);
Route::post('login', [AuthenticationController::class,'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthenticationController::class,'logout']);
    Route::get('user', [AuthenticationController::class,'user']);

    Route::get('subjects',function (){
        return response()->json(\App\Models\Subject::get());
    });

    Route::get('quizzes', [QuizController::class, 'index']);
    Route::post('quizzes', [QuizController::class, 'store']);
    Route::get('quizzes/{id}', [QuizController::class, 'show']);
    Route::put('quizzes/{id}', [QuizController::class, 'update']);

    Route::post('quizzes/{quizId}/questions', [QuestionController::class, 'store']);
    Route::get('quizzes/{quizId}/questions', [QuestionController::class, 'index']);
    Route::delete('questions/{id}', [QuestionController::class, 'destroy']);

    Route::post('quizzes/{quizId}/results', [ResultController::class, 'store']);
    Route::get('results', [ResultController::class, 'index']);
});
