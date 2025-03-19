<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MindMapController;
use App\Http\Controllers\MeasureController;
use App\Http\Controllers\DepartmentsController;
use App\Http\Controllers\SurveyController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/departments', [DepartmentsController::class, 'index'])->name('dashboard');
    Route::get('/measures', [MeasureController::class, 'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//従業員アンケートのルート設定
Route::get('/survey/employee', function () {
    return view('survey.employee_survey');
});

Route::get('/api/survey/{surveyId}/questions', [SurveyController::class, 'getSurveyQuestions']);


Route::get('/mindmap', [MindMapController::class, 'index'])
    ->middleware('auth')
    ->name('mindmap.index');

    Route::get('/create-policy', function () {
        return view('create-policy');
    })->middleware(['auth']);

    Route::get('/create-policy', [MeasureController::class, 'create'])
        ->middleware('auth') // authミドルウェアで認証済みユーザーのみ
        ->name('create-policy');

    // 施策データ保存のルート（認証済みユーザーのみアクセス可能）
    Route::post('/store-policy', [MeasureController::class, 'store'])
    ->middleware('auth') // authミドルウェアで認証済みユーザーのみ
    ->name('store-policy');

require __DIR__.'/auth.php';
