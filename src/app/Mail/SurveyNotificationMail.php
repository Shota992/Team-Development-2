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

    /**
     * Create a new message instance.
     */
    public function __construct($survey, $user)
    {
        $this->survey = $survey;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('【アンケート配信】' . $this->survey->name)
                    ->markdown('emails.survey.notify');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Survey Notification Mail',
        );
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
