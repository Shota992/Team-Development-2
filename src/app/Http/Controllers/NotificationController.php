<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * 指定された通知を既読にする
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead(Notification $notification)
    {
        // 既読になっていない場合のみ更新
        if (is_null($notification->read_at)) {
            $notification->update(['read_at' => Carbon::now()]);
        }
        
        return back()->with('status', '通知を既読にしました');
    }
}
