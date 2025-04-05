<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SurveyNotificationMail extends Mailable
{
    use Queueable, SerializesModels;


    public $survey;
    public $user;
    public $token;

    /**
     * Create a new message instance.
     */
    public function __construct($survey, $user, $token)
    {
        $this->survey = $survey;
        $this->user = $user;
        $this->token = $token;
    }

    public function build()
    {
        return $this->subject('【アンケートのお知らせ】' . $this->survey->name)
                    ->view('emails.survey.notify')
                    ->with([
                        'survey' => $this->survey,
                        'user' => $this->user,
                        'token' => $this->token,
                    ]);
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.survey.notify',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
