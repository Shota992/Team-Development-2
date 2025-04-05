<?php
namespace App\Mail;

use App\Models\Survey;
use App\Models\SurveyUserToken;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SurveyReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $survey;
    public $url;

    public function __construct($user, Survey $survey, $url)
    {
        $this->user = $user;
        $this->survey = $survey;
        $this->url = $url;
    }

    public function build()
    {
        return $this->view('emails.survey_reminder')
            ->with([
                'user' => $this->user,
                'survey' => $this->survey,
                'url' => $this->url,
            ]);
    }
}