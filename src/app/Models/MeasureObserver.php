<?php

namespace App\Observers;

use App\Models\Measure;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class MeasureObserver
{
    /**
     * Measure が作成された直後に呼ばれる
     *
     * @param  \App\Models\Measure  $measure
     * @return void
     */
    public function created(Measure $measure)
    {
        $this->checkAndNotify($measure);
    }

    /**
     * Measure が更新された直後に呼ばれる
     *
     * @param  \App\Models\Measure  $measure
     * @return void
     */
    public function updated(Measure $measure)
    {
        $this->checkAndNotify($measure);
    }

    /**
     * 現在が評価可能な期間内で、かつ評価が未実施（evaluation_status が 0）の場合に
     * 該当部署の全ユーザーに通知を送信する
     *
     * @param  \App\Models\Measure  $measure
     * @return void
     */
    protected function checkAndNotify(Measure $measure)
    {
        // start_date と end_date が設定されているか確認
        if (!$measure->start_date || !$measure->end_date) {
            return;
        }

        $now   = Carbon::now();
        $start = Carbon::parse($measure->start_date);
        $end   = Carbon::parse($measure->end_date);

        // 現在が評価期間内かチェック
        if ($now->between($start, $end)) {
            // 評価が未実施の状態（evaluation_status が 0）であれば通知を送る
            if ($measure->evaluation_status == 0) {
                $this->notifyDepartmentUsers(
                    $measure,
                    '施策の評価が可能になりました',
                    "施策「{$measure->title}」の評価が可能になりました。評価をお願いします。"
                );
            }
        }
    }

    /**
     * 指定された部署の全ユーザーに通知を送信する
     *
     * @param  \App\Models\Measure  $measure
     * @param  string  $title
     * @param  string  $body
     * @return void
     */
    protected function notifyDepartmentUsers(Measure $measure, string $title, string $body)
    {
        // Measure 作成時の部署IDに紐づく「部署管理者権限」を持つユーザーを取得
        $users = User::where('department_id', $measure->department_id)
                    ->where('administrator', true)
                    ->get();
    
        foreach ($users as $user) {
            // 通知の作成
            Notification::create([
                'user_id' => $user->id,
                'title'   => $title,
                'body'    => $body,
            ]);
    
            // ユーザーの通知を新しい順に取得
            $notifications = Notification::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
    
            // 通知件数が5件を超えている場合、古いもの（5件以降）を削除する
            if ($notifications->count() > 5) {
                $notificationsToDelete = $notifications->slice(5);
                foreach ($notificationsToDelete as $oldNotification) {
                    $oldNotification->delete();
                }
            }
        }
    }
}
