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
use App\Http\Controllers\SurveyQuestionController;

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

// 公開ルート（ログイン不要）
Route::get('/', fn() => view('welcome'));
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/departments', [DepartmentsController::class, 'index'])->name('departments.index');
    Route::get('/measures', [MeasureController::class, 'index'])->name('measure.index');
    Route::get('/items', [SurveyController::class, 'index'])->name('items.index');
});

// 認証が必要なルート
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // プロフィール
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 施策
    Route::get('/create-policy', [MeasureController::class, 'create'])->name('create-policy');
    Route::post('/store-policy', [MeasureController::class, 'store'])->name('measures.store');
    Route::get('/measures', [MeasureController::class, 'index'])->name('measures.index');
    Route::get('/measures/no-evaluation/{id}', [MeasureController::class, 'evaluationDetail'])->name('measures.evaluation-detail');
    Route::post('/measures/no-evaluation/{id}', [MeasureController::class, 'storeEvaluation'])->name('measures.evaluation-store');
    Route::get('/measures/no-evaluation', [MeasureController::class, 'noEvaluation'])->name('measure.no-evaluation');
    Route::get('/measures/evaluation-list', [MeasureController::class, 'evaluationList'])->name('measures.evaluation-list');
    Route::get('/measures/evaluation/{id}', [MeasureController::class, 'evaluationListDetail'])->name('measures.evaluation-list-detail');
    Route::post('/tasks/{id}/toggle', [MeasureController::class, 'toggleStatus'])->name('tasks.toggle');
    Route::get('/get-assignees/{department_id}', [MeasureController::class, 'getAssignees']);

    // 従業員
    Route::get('/setting/employee-list', [SettingController::class, 'employeeList'])->name('setting.employee-list');
    Route::delete('/setting/employee-delete/{id}', [SettingController::class, 'deleteEmployee'])->name('employee.delete');
    Route::get('/setting/employee-create', [SettingController::class, 'createEmployee'])->name('employee.create');
    Route::post('/setting/employee-store', [SettingController::class, 'storeEmployee'])->name('employee.store');


    // マインドマップ
    Route::get('/mindmap', [MindMapController::class, 'index'])->name('mindmap.index');

    // 配信関連
    Route::get('/distribution/survey/create', [DistributionController::class, 'create'])->name('survey.create');
    Route::get('/distribution/group-selection', [DistributionController::class, 'groupSelection'])->name('survey.group-selection');
    Route::get('/distribution/survey/list', [DistributionController::class, 'list'])->name('survey.list');
    Route::post('/distribution/survey/store', [DistributionController::class, 'store'])->name('survey.store');
    Route::post('/survey-question/toggle-display/{id}', [DistributionController::class, 'toggleDisplayStatus'])->name('survey.toggle-display');
    Route::post('/survey/save-session', [DistributionController::class, 'saveToSession'])->name('survey.save-session');
    Route::post('/distribution/finalize-distribution', [DistributionController::class, 'finalizeDistribution'])->name('survey.finalize-distribution');
    Route::post('/distribution/send', [DistributionController::class, 'sendSurvey'])->name('survey.send');
    Route::post('/distribution/save-settings', [DistributionController::class, 'saveSettings'])->name('survey.save-settings');


    

    // View表示系
    Route::view('/distribution/item-edit', 'distribution.item_edit')->name('survey.item-edit');
    Route::view('/distribution/advanced-setting', 'distribution.advanced_setting')->name('survey.advanced-setting');
    Route::view('/distribution/confirmation', 'distribution.confirmation')->name('survey.confirmation');
    Route::view('/sidebar', 'components.sidebar');
    Route::view('/distribution/completion', 'distribution.completion')->name('survey.completion');


    // 従業員アンケート
    // Route::view('/survey/employee', 'survey.employee_survey');
    Route::get('/survey/employee/{id}', [SurveyController::class, 'employeeSurveyShow'])->name('survey.employee');
});


// 認証関連のルート（FortifyとかJetstream使ってたら）
Route::group(['middleware' => ['mentor']], function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/ask', [ChatController::class, 'ask'])->name('chat.ask');
    Route::post('/chat-data/ask', [ChatDataController::class, 'ask'])->name('chatdata.ask');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/configuration-file/item_list', [SurveyQuestionController::class, 'index'])->name('survey_questions.index');
    Route::get('/configuration-file/item_create', [SurveyQuestionController::class, 'create'])->name('survey_questions.create');
    Route::post('/configuration-file/item_store', [SurveyQuestionController::class, 'store'])->name('survey_questions.store');

    // 編集・更新・削除ルート（追加）
    Route::get('/configuration-file/item_edit/{id}', [SurveyQuestionController::class, 'edit'])->name('survey_questions.edit');
    Route::put('/configuration-file/item_update/{id}', [SurveyQuestionController::class, 'update'])->name('survey_questions.update');
    Route::delete('/configuration-file/item_delete/{id}', [SurveyQuestionController::class, 'destroy'])->name('survey_questions.destroy');
});