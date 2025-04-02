<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MindMapController;
use App\Http\Controllers\MeasureController;
use App\Http\Controllers\DepartmentsController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatDataController;

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

// 公開ルート
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/departments', [DepartmentsController::class, 'index'])->name('dashboard');
    Route::get('/measures', [MeasureController::class, 'index'])->name('measure.index');
    Route::get('/items', [ItemController::class, 'index'])->name('item.index');
});

// 認証が必要なルート
Route::middleware('auth')->group(function () {
    // ダッシュボード
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // プロフィール管理
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 施策関連
    Route::get('/create-policy', [MeasureController::class, 'create'])->name('create-policy');
    Route::post('/store-policy', [MeasureController::class, 'store'])
    ->name('measures.store');

Route::get('/measures', [MeasureController::class, 'index'])
    ->name('measures.index');

    Route::get('/measures/evaluation/{id}', [MeasureController::class, 'evaluationDetail'])->name('measures.evaluation-detail');

    Route::get('/measures/no-evaluation', [MeasureController::class, 'noEvaluation'])->name('measure.no-evaluation');

    Route::post('/tasks/{id}/toggle', [MeasureController::class, 'toggleStatus'])->name('tasks.toggle');

    Route::get('/get-assignees/{department_id}', [MeasureController::class, 'getAssignees']);

    // 従業員関連
    Route::get('/setting/employee-list', [SettingController::class, 'employeeList'])->name('setting.employee-list');

    // マインドマップ
    Route::get('/mindmap', [MindMapController::class, 'index'])->name('mindmap.index');

    // 配信設定
    Route::get('/distribution/survey/create', [DistributionController::class, 'create'])->name('survey.create');
    Route::post('/distribution/survey/store', [DistributionController::class, 'store'])->name('survey.store');
    Route::post('/survey-question/toggle-display/{id}', [DistributionController::class, 'toggleDisplayStatus'])->name('survey.toggle-display');
    Route::post('/survey/save-session', [DistributionController::class, 'saveToSession'])->name('survey.save-session');

    // グループ選択画面
    Route::get('/distribution/group-selection', function () {
        return view('distribution.group_selection');
    })->name('survey.group-selection');

    // 項目編集画面
    Route::get('/distribution/item-edit', function () {
        return view('distribution.item_edit');
    })->name('survey.item-edit');

    // サイドバー
    Route::get('/sidebar', function () {
        return view('components.sidebar');
    });

//従業員一覧のルート設定
    Route::middleware('auth')->group(function () {
        Route::get('/setting/employee-list', [SettingController::class, 'employeeList'])->name('setting.employee-list');
        Route::delete('/setting/employee-delete/{id}', [SettingController::class, 'deleteEmployee'])->name('employee.delete');

    // 従業員アンケート
    Route::get('/survey/employee', function () {
        return view('survey.employee_survey');
    });

    //部署選択画面のルート設定
    Route::middleware('auth')->group(function () {
        Route::get('/distribution/group-selection', [DistributionController::class, 'groupSelection'])->name('survey.group-selection');
        Route::post('/distribution/finalize-distribution', [DistributionController::class, 'finalizeDistribution'])->name('survey.finalize-distribution');
    });

    // アンケート詳細設定画面（部署選択画面の次ステップ）
    Route::get('/distribution/advanced-setting', function () {
        return view('distribution.advanced_setting');
    })->name('survey.advanced-setting');
    //アンケート詳細画面のルート設定
    Route::post('/distribution/advanced-setting/save', [DistributionController::class, 'saveSettings'])->name('survey.save-settings');

    // 配信内容確認画面へ遷移
    Route::get('/distribution/confirmation', function () {
        return view('distribution.confirmation');
    })->name('survey.confirmation');


    // 配信内容確認画面
    Route::get('/distribution/confirmation', function () {
        return view('distribution.confirmation');
    })->name('survey.confirmation');

    // 実際に配信を実行
    Route::post('/distribution/send', [DistributionController::class, 'sendSurvey'])->name('survey.send');
});

Route::group(['middleware' => ['mentor']], function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/ask', [ChatController::class, 'ask'])->name('chat.ask');
    Route::post('/chat-data/ask', [ChatDataController::class, 'ask'])->name('chatdata.ask');
});

// 認証関連のルート
require __DIR__.'/auth.php';
});
