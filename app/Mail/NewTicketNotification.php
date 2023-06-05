<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewTicketNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $subject;
    public $comment;
    public $isSolved;
    public $agent_id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $email, $subject, $comment, $isSolved, $agent_id)
    {
        $this->name = $name;
        $this->email = $email;
        $this->subject = $subject;
        $this->comment = $comment;
        $this->isSolved = $isSolved;
        $this->agent_id = $agent_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.new-ticket-notification')
            ->with([
                'ticket' => [
                    'name' => $this->name,
                    'email' => $this->email,
                    'subject' => $this->subject,
                    'comment' => $this->comment,
                    'isSolved' => $this->isSolved,
                    'agent_id' => $this->agent_id
                ]
            ])
            ->subject('Nouveau ticket créé')
            ->from(env('MAIL_TICKETS_FROM_ADDRESS'), env('MAIL_TICKETS_FROM_NAME'));
    }
}
