<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Task;
use App\Models\Measure;
use App\Models\Notification; // Notification モデルをインポート
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        App::setLocale('ja');

        View::composer('*', function ($view) {
            // 認証中のユーザーの通知を取得（最新5件、存在しない場合は空コレクション）
            $notifications = Auth::check()
                ? Notification::where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get()
                : collect();

            $executingTasksCount = Task::where('status', 0)->count(); // 実行中タスク
            $pendingEvaluationMeasuresCount = Measure::where('evaluation_status', 0)->count(); // 評価未対応施策

            $view->with([
                'notifications' => $notifications,
                'executingTasksCount' => $executingTasksCount,
                'pendingEvaluationMeasuresCount' => $pendingEvaluationMeasuresCount,
            ]);
        });
    }
}
