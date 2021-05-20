<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;
use App\Models\Seller;
use App\Models\Date;
use App\Models\Estate;
use App\Models\EstateLog;
use App\Models\EstateTicket;
use App\Models\EstateStatus;
use App\Models\EstateReminder;

use App\Mail\MailableEmail;
use App\Mail\EmailT;
use Illuminate\Support\Facades\Mail;

use BadChoice\Handesk\Handesk;
use BadChoice\Handesk\Ticket;

class SendReminder extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'send:reminder';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send reminder to client';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		// To know is value was updated
		$this->updated = false;
		// Init object to use the class Ticket
		$this->objectTicket = new Ticket();
	}

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle()
	{
		$reminders = EstateReminder::where('sent', '!=', 1)->get();
		$datesSpecials = Date::select('date_special')->get();
		$auxDateSpecial = array();
		foreach ($datesSpecials as $value) {
			$pieces = explode('-', $value->date_special);
			$auxDateSpecial[] = date("Y-").$pieces[1].'-'.$pieces[0];
		}
		$currentDate = date("Y-m-d");
		if (!empty($reminders)) {
			foreach($reminders as $reminder) {
				$content = unserialize($reminder['content']);
				foreach ($content as $key => $value) {
					if ($currentDate == $value['date'] && $key == $reminder['next_reminder']) {
						if (!in_array($currentDate, $auxDateSpecial)) {
							if ($value['type'] == 'email' || $value['type'] == 'task') {
								$seller = $this->getSeller($value['seller_id']);
								$ticket_id = $this->objectTicket->create(
									[
										"name" => $seller['name'],// Name to which the ticket will be sent
										"email" => $seller['email'],// Email to which the ticket will be sent
									],
									$value['subject'],// Subject to the email
									$value['content'],// Content to the email
									[]
								);
								// Save the union of the ticket wiht the estate
								EstateTicket::create([
									'estate_id' => $reminder['estate_id'],
									'ticket_id' => $ticket_id
								]);
								// Updatate data to next reminder
								$updated = $this->updateData(app("App\\Models\\EstateReminder"), 'next_reminder', ($key + 1), $reminder['id'], $reminder['estate_id'], 'next_reminder', $reminder['user_id']);
							}
							if ($value['type'] == 'sms') {
								// $basic  = new \Nexmo\Client\Credentials\Basic('20c3b951', '3C9zf1Y4cH2UH5Xu');
								// $client = new \Nexmo\Client($basic);
								// // Sending SMS 
								// $message = $client->message()->send([
								// 	'to' => '527731951309', // $seller['phone']
								// 	'from' => 'Wesold',
								// 	'text' => $reminder['content']
								// ]);
								// Updatate data to next reminder
								$updated = $this->updateData(app("App\\Models\\EstateReminder"), 'next_reminder', ($key + 1), $reminder['id'], $reminder['estate_id'], 'next_reminder', $reminder['user_id']);
							}
						}
					}
				}
			}
		}
		// Code to send reminder of the visit at 08:30
		$objectTicket = new Ticket();
		$estates = Estate::where('send_reminder_half_past_eight', '=', 1)->get();
		if (!empty($estates)) {
			foreach ($estates as $estate) {
				$visit = explode("de", $estate->visit_date_at);
				$date = str_replace('Visite le', '', $visit[0]);
				$date = str_replace(' ', '', $date);
				if ($date == $currentDate) {
					dd('ok');
					$seller = $this->getSeller($estate->seller);
					$visit = explode("de", $estate->visit_date_at);
					$ticket_id = $this->objectTicket->create(
						[
							"name" => $seller['name'],// Name to which the ticket will be sent
							"email" => $seller['email'],// Email to which the ticket will be sent
						],
						"Rappel de ".$estate->visit_date_at,// Subject to the email
						"Nous vous rappelons que vous avez un rendez-vous aujourd'hui à partir de".$visit[1],// Content to the email
						[]
					);
					// Save the union of the ticket wiht the estate
					EstateTicket::create([
						'estate_id' => $estate->id,
						'ticket_id' => $ticket_id
					]);
					// Save information in the log
					EstateLog::create([
						'estate_id' => $data['estate_id'],
						'user_id' => 'Automatique',
						'old_value' => '',
						'new_value' => 'Rappel de visite envoyer à ' . $seller['email'],
						'field' => 'remindervisit'
					]);
				}
			}
		}
		return response(['isValidRequest' => true])->header('Content-Type', 'application/json');
		$this->info('Demo:Cron Cummand Run successfully!');
	}

	/**
	 * Get seller
	 */
	private function getSeller($seller_id) {
		$seller = Seller::where('id', '=', $seller_id)->get();
		$sellerArray = array();
		foreach ($seller as $_seller) {
			$sellerArray = array(
				'id' => $_seller->id,
				'name' => $_seller->name,
				'email' => $_seller->email,
				'phone' => $_seller->phone,
				'type' => $_seller->type,
				'contact_by' => $_seller->contact_by,
				'reason_sale' => $_seller->reason_sale,
				'looking_property' => $_seller->looking_property,
				'want_stay_tenant' => $_seller->want_stay_tenant,
				'when_to_buy' => $_seller->when_to_buy
			);
		}
		return $sellerArray;
	}

	/**
	 * Update Data generic
	 * @var model is model to update
	 * @var field is the field to update NOTE : the name of field should be exactly as database
	 * @var value is the new value to put
	 * @var id is the id for register to update
	 * @var estate_id is the estate id for register to update
	 * @var field_log is the value to the name of field without processing
	 */
	private function updateData($model, $field, $value, $id, $estate_id, $field_log, $user_id) {
		// Get all register
		$register = $model::find($id);
		$oldValue = $register->$field;
		// Verify if the new value is different to current value
		if($register->$field != $value) {
			try {
				// Update value where
				$model::where('id', '=', $id)
					->update([$field => $value]);
				// Updated true
				$this->updated = true;
				if ($field == 'category') {
					// Getting Last Status of this estate
					$lastStatus = $this->getLastStatus($id);
					// Update value where
					$date = date('Y-m-d H:i:s');
					EstateStatus::where('id', '=', $lastStatus['id'])
					->update(['stop_at' => $date]);
					// Create status log
					EstateStatus::create([
						'estate_id' => $id,
						'category_id' => $value,
						'user_id' => $user_id,
						'start_at' => $date,
						'stop_at' => null
					]);
				}
			}
			catch(\Exception $e) {
				// Updated false
				$this->updated = false;
			}
		}
		// Return updated
		return $this->updated;
	}
}
