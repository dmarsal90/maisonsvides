<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewTicketNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.new-ticket-notification')
            ->with(['message' => 'Se ha créé un nouveau ticket, veuillez l\'assigner à un agent.'])
            ->subject('Nouveau ticket créé')
            ->from(env('MAIL_TICKETS_FROM_ADDRESS'), env('MAIL_TICKETS_FROM_NAME'));;
    }
}
