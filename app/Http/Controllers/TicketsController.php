<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use BadChoice\Handesk\Handesk;
use BadChoice\Handesk\Ticket;

// Include models
use App\Models\Agent;
use App\Models\Estate;
use App\Models\Seller;

class TicketsController extends Controller
{
	private $objectTicket;

	public function __construct(){
		// Init object to use the class Ticket
		$this->objectTicket = new Ticket();
	}

	/**
	 * Show the list of tickets
	 */
	public function viewTickets() {
		// Get list of tickets of a user
		$tickets = $this->listTickets();
		if (Auth::user()->type == 2) {
			$tickets = $this->listTicketsToManager(Auth::user()->id);
		}
		$auxTickets = array();
		foreach ($tickets as $ticket) {
			foreach ($ticket as $value) {
				$auxTickets[] = $this->objectTicket->find($value->id);
			}
		}
		// Get list of tickets solved
		$ticketsSolved = $this->listTicketsSolved();
		if (Auth::user()->type == 2) {
			$ticketsSolved = $this->listTicketsSolvedToManager(Auth::user()->id);
		}
		$auxTicketsSolved= array();
		foreach ($ticketsSolved as $ticket) {
			foreach ($ticket as $value) {
				$auxTicketsSolved[] = $this->objectTicket->find($value->id);
			}
		}
		return view('tickets.tickets', ['auxTickets' => $auxTickets, 'auxTicketsSolved' => $auxTicketsSolved]);
	}

	/**
	 * Show the list of tickets
	 */
	public function viewOneTicket($id) {
		// Get list of tickets of a user
		$dataTicket = $this->objectTicket->find($id);
		$comments = $dataTicket->comments; //Includes the initial comment
		return view('tickets.one-ticket', ['dataTicket' => $dataTicket, 'comments' => $comments, 'id' => $id]);
	}

	/**
	 * Show the list of tickets of view dashboard
	 */
	public function viewOneTicketDash($id) {
		// Get list of tickets of a user
		$dataTicket = $this->objectTicket->find($id);
		$comments = $dataTicket->comments; //Includes the initial comment
		return view('tickets.oneticketdash', ['dataTicket' => $dataTicket, 'comments' => $comments, 'id' => $id]);
	}

	/**
	 * Show the list of tickets of view details estate
	 */
	public function viewOneTicketDetails($id, $estateid) {
		// Get list of tickets of a user
		$dataTicket = $this->objectTicket->find($id);
		$comments = $dataTicket->comments; //Includes the initial comment
		return view('tickets.one-ticketdetails', ['dataTicket' => $dataTicket, 'comments' => $comments, 'id' => $id, 'estateid' => $estateid]);
	}

	/**
	 * Get the list of tickets of a agent
	 */
	private function listTickets() {
		$estate = Estate::where('agent', '=', Auth::user()->id)->get();
		$estateArray = array();
		$tickets = array();
		foreach ($estate as $_estate) {
			$estateArray[] = $this->getSeller($_estate->seller)['email'];
		}
		$result = array_unique($estateArray);
		foreach ($result as $value) {
			if (!empty($this->objectTicket->get($value))) {
				$tickets[] = $this->objectTicket->get($value);
			}
		}
		return $tickets;
	}

	/**
	 * Get the list of tickets solved of a agent
	 */
	private function listTicketsSolved() {
		$estate = Estate::where('agent', '=', Auth::user()->id)->get();
		$estateArray = array();
		$tickets = array();
		foreach ($estate as $_estate) {
			$estateArray[] = $this->getSeller($_estate->seller)['email'];
		}
		$result = array_unique($estateArray);
		foreach ($result as $value) {
			if (!empty($this->objectTicket->get($value))) {
				$tickets[] = $this->objectTicket->get($value, 'solved');
			}
		}
		return $tickets;
	}

	/**
	 * Get the list to the manager can see all tickets of his agents
	 */
	private function listTicketsToManager($managerid) {
		$agents = $this->getAgentManager($managerid);
		$estate = array();
		foreach ($agents as $value) {
			$estate[] = Estate::where('agent', '=', $value['agent_id'])->get();
		}
		$estateArray = array();
		$tickets = array();
		foreach ($estate as $_estate) {
			foreach ($_estate as $value) {
				$estateArray[] = $this->getSeller($value->seller)['email'];
			}
		}
		$result = array_unique($estateArray);
		foreach ($result as $value) {
			if (!empty($this->objectTicket->get($value))) {
				$tickets[] = $this->objectTicket->get($value);
			}
		}
		return $tickets;
	}

	/**
	 * Get the list of tickets solved of all the agents of a manager
	 */
	private function listTicketsSolvedToManager($managerid) {
		$agents = $this->getAgentManager($managerid);
		$estate = array();
		foreach ($agents as $value) {
			$estate[] = Estate::where('agent', '=', $value['agent_id'])->get();
		}
		$estateArray = array();
		$tickets = array();
		foreach ($estate as $_estate) {
			foreach ($_estate as $value) {
				$estateArray[] = $this->getSeller($value->seller)['email'];
			}
		}

		$result = array_unique($estateArray);
		foreach ($result as $value) {
			if (!empty($this->objectTicket->get($value))) {
				$tickets[] = $this->objectTicket->get($value, 'solved');
			}
		}
		return $tickets;
	}

	/**
	 * Get agents relation with his manager
	 */
	private function getAgentManager($managerid) {
		$agents = Agent::where('maneger_id', '=', $managerid)->get();
		$agentsArray = array();
		foreach ($agents as $agent) {
			$agentsArray[] = array(
				'agent_id' => $agent->agent_id,
				'maneger_id' => $agent->maneger_id
			);
		}
		return $agentsArray;
	}

	/**
	 * Get seller
	 */
	private function getSeller($seller_id) {
		$seller = Seller::where('id', '=', $seller_id)->get();
		$sellerArray = array();
		foreach ($seller as $_seller) {
			$sellerArray = array(
				'email' => $_seller->email
			);
		}
		return $sellerArray;
	}

	/**
	 * Create new ticket
	 */
	public function createTicket(Request $request) {
		// Save all data of request
		$data = $request->all();
		//Init the response
		$response = array(
			'status' => false,
			'message' => 'Le ticket n\'a pas pu être créé. Réessayez plus tard.'
		);
		try {
			$ticket_id = $this->objectTicket->create(
				[
					"name" => $data['name_ticket'],
					"email" => $data['email'],
				],
				$data['subject'],
				$data['body'],
				[]
			);
			$response = array(
				'status' => true,
				'message' => 'Le ticket a été créée',
				'data' => $data,
			);
		} catch (\Exception $e) {
			$message = "";
			if ($e->getCode() == 23000) {
				$message = 'Le ticket existe déjà';
			}
			$response = array(
				'status' => false,
				'message' => $message,
				'data' => $data
			);
		}
		//Return response
		return response($response)->header('Content-Type', 'application/json');
	}

	/**
	 * Create new ticket
	 */
	public function comment(Request $request) {
		// Save all data of request
		$data = $request->all();
		//Init the response
		$response = array(
			'status' => false,
			'message' => 'Le commentaire n\'a pas pu être créé. Réessayez plus tard.'
		);
		try {
			$oneTicket = $this->objectTicket->find($data['ticket_id']);
			if (isset($data['resolved'])) {
				$oneTicket->addComment($data['comment'], true);
			} else {
				$oneTicket->addComment($data['comment']);
			}
			$response = array(
				'status' => true,
				'message' => 'Le commentaire a été créée',
				'data' => $data,
			);
		} catch (\Exception $e) {
			$message = "";
			if ($e->getCode() == 23000) {
				$message = 'Le commentaire existe déjà';
			}
			$response = array(
				'status' => false,
				'message' => $message,
				'data' => $data
			);
		}
		//Return response
		return response($response)->header('Content-Type', 'application/json');
	}
}
