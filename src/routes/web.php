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
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SignUpController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

//公開ルート（ログイン不要）新規登録画面
Route::get('/sign-up/start', [SignUpController::class, 'start'])->name('sign-up.start');

Route::get('/sign-up/admin', [SignUpController::class, 'showAdminForm'])->name('sign-up.admin');
Route::post('/sign-up/admin', [SignUpController::class, 'storeAdmin'])->name('sign-up.admin.store');

Route::get('/sign-up/company', [SignUpController::class, 'showCompanyForm'])->name('sign-up.company'); 
Route::post('/sign-up/register', [SignUpController::class, 'finalRegister'])->name('sign-up.register');


// 公開ルート（ログイン不要）
Route::get('/', fn () => view('welcome'));
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// パスワードリセットルート
Route::middleware('guest')->group(function () {
    // パスワードリセットリンク依頼画面
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');

    // パスワードリセットリンク送信用
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    // リセットリンクをクリックしたときに表示する新パスワード入力画面
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    // 新パスワードを更新するためのPOSTルート
    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.update');

// ← この位置に移動（公開ルートの下、auth ミドルウェアの外）
Route::get('/survey/fill/{token}', [SurveyController::class, 'employeeSurveyShow'])->name('survey.fill');
Route::post('/survey/employee/{token}', [SurveyController::class, 'employeeSurveyPost'])->name('survey.employee.post');
Route::get('/survey/employee/{id}/success', [SurveyController::class, 'employeeSurveySuccess'])->name('survey.employee-survey-success');
Route::get('/survey/employee/{id}/fail', [SurveyController::class, 'employeeSurveyFail'])->name('survey.employee-survey-fail');


});


Route::middleware('auth')->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::get('/surveys/{survey}/unanswered', [SurveyController::class, 'unansweredUsers'])
    ->name('survey.unanswered-users')
    ->middleware(['auth']);
    Route::post('/survey/{survey}/remind-unanswered', [SurveyController::class, 'remindUnanswered'])
    ->name('survey.remind-unanswered');


    Route::get('/departments', [DepartmentsController::class, 'index'])->name('departments.index');
    Route::get('/measures', [MeasureController::class, 'index'])->name('measure.index');
    Route::get('/items', [SurveyController::class, 'index'])->name('items.index');
});

// 管理者のみアクセス可能なルート
Route::middleware(['auth', 'admin.only'])->group(function () {

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
    Route::post('/distribution/survey/{id}/end', [DistributionController::class, 'endSurvey'])->name('survey.end');


    // View表示系
    Route::view('/distribution/item-edit', 'distribution.item_edit')->name('survey.item-edit');
    Route::view('/distribution/advanced-setting', 'distribution.advanced_setting')->name('survey.advanced-setting');
    Route::view('/distribution/confirmation', 'distribution.confirmation')->name('survey.confirmation');
    Route::view('/sidebar', 'components.sidebar');
    Route::view('/distribution/completion', 'distribution.completion')->name('survey.completion');

    // 設問設定
    Route::get('/configuration-file/item_list', [SurveyQuestionController::class, 'index'])->name('survey_questions.index');
    Route::get('/configuration-file/item_create', [SurveyQuestionController::class, 'create'])->name('survey_questions.create');
    Route::post('/configuration-file/item_store', [SurveyQuestionController::class, 'store'])->name('survey_questions.store');
    Route::get('/configuration-file/item_edit/{id}', [SurveyQuestionController::class, 'edit'])->name('survey_questions.edit');
    Route::put('/configuration-file/item_update/{id}', [SurveyQuestionController::class, 'update'])->name('survey_questions.update');
    Route::delete('/configuration-file/item_delete/{id}', [SurveyQuestionController::class, 'destroy'])->name('survey_questions.destroy');
});

// mentorミドルウェア（AIチャット機能のみ）
Route::middleware(['mentor'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/ask', [ChatController::class, 'ask'])->name('chat.ask');
    Route::post('/chat-data/ask', [ChatDataController::class, 'ask'])->name('chatdata.ask');
});

// 従業員アンケート（個別表示）
Route::get('/survey/employee/{id}', [SurveyController::class, 'employeeSurveyShow'])->name('survey.employee');
Route::post('/survey/employee/{token}', [SurveyController::class, 'employeeSurveyPost'])->name('survey.employee.post');
Route::get('/survey/employee/{id}/success', [SurveyController::class, 'employeeSurveySuccess'])->name('survey.employee-survey-success');
Route::get('/survey/employee/{id}/fail', [SurveyController::class, 'employeeSurveyFail'])->name('survey.employee-survey-fail');
