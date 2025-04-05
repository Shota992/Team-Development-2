<?php

namespace App\Jobs;

use App\Mail\SurveyNotificationMail;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendSurveyEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $survey;
    protected $user;
    protected $token;

    /**
     * Create a new job instance.
     */
    public function __construct(Survey $survey, User $user, string $token)
    {
        $this->survey = $survey;
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // メール送信
        Mail::to($this->user->email)->send(new SurveyNotificationMail($this->survey, $this->user, $this->token));
    }
}
