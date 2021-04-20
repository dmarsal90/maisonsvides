<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailT extends Mailable
{
	use Queueable, SerializesModels;

	/**
	 * The data object instance.
	 *
	 * @var data
	 */
	public $data;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct($data)
	{
		$this->data = $data;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		$email = $this->view('emails.email')
					->from($this->data->from, $this->data->fromName)
					->subject($this->data->subject);

		if (isset($this->data->emails)) {
			$emai->cc($this->data->emails);
		}

		if (isset($this->data->files)) {
			foreach ($this->data->files as $files) {
				$email->attach($files);
			}
		}
		return $email;
	}
}
