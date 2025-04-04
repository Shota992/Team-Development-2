<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Measure;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class SendNextEvaluationNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:next-evaluation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications when the next evaluation date has arrived';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();

        // 次回評価日が設定されており、現在日付が次回評価日と同日または過ぎている施策を取得
        // ※ 必要に応じて条件を調整してください
        $measures = Measure::whereNotNull('next_evaluation_date')
            ->whereDate('next_evaluation_date', '<=', $now->toDateString())
            ->where('evaluation_status', 0) // 例: 評価がまだ行われていない状態
            ->get();

        foreach ($measures as $measure) {
            // 対象の部署に所属する全ユーザーに通知を送信
            $users = User::where('department_id', $measure->department_id)->get();

            foreach ($users as $user) {
                Notification::create([
                    'user_id' => $user->id,
                    'title'   => '次回評価日が到来しました',
                    'body'    => "施策「{$measure->title}」の次回評価日が到来しました。評価をお願いします。",
                ]);
            }

            // ※ 重複通知を防止するため、通知済みのフラグを立てるなどの処理を追加することも検討してください。
            // 例: $measure->update(['evaluation_notification_sent' => true]);
        }

        $this->info('Next evaluation notifications have been sent.');
        return 0;
    }
}
