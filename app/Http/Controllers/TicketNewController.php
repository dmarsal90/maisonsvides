<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewTicketNotification;

class TicketNewController extends Controller
{
    public function assignAgent(Request $request)
    {
        // Verificar si el usuario autenticado tiene permiso para asignar agentes
        if (Auth::user()->type != 1 || Auth::user()->type != 2) {
            abort(403, 'Vous n\'êtes pas autorisé à attribuer des agents');
        }

        // Obtener el ID del ticket y el ID del agente de la solicitud
        $ticketId = $request->input('ticket_id');
        $agentId = $request->input('agent_id');

        // Buscar el ticket correspondiente en la base de datos
        $ticket = Ticket::findOrFail($ticketId);

        // Actualizar el ticket con el ID del agente
        $ticket->agent_id = $agentId;
        $ticket->save();

        return redirect()->back()->with('success', 'L\'agent a été affecté au ticket.');
    }

    public function store(Request $request)
    {
        // Validar los datos de la solicitud
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'subject' => 'required|string|max:255',
            'comment' => 'required|string',
            'estate_id' => 'integer|exists:estates,id',
        ]);

        // Si no se proporciona estate_id, establecerlo en null
        $estateId = $validatedData['estate_id'] ?? null;

        // Crear un nuevo ticket en la base de datos
        $ticket = Ticket::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'subject' => $validatedData['subject'],
            'comment' => $validatedData['comment'],
            'estate_id' => $estateId,
        ]);

        return redirect()->back()->with('success', 'Le ticket a été créé avec succès.');
    }

    public function createTicket(Request $request)
    {
        // Save all data of request
        $data = $request->all();
        try {
            // Check if ticket already exists
            $existingTicket = Ticket::where('name', $data['name_ticket'])
                ->where('email', $data['email'])
                ->where('subject', $data['subject'])
                ->where('comment', $data['body'])
                ->first();
            if ($existingTicket) {
                return back()->with('error', 'Le ticket existe déjà.');
            }

            // Save new ticket
            $ticket = Ticket::create([
                'name' => $data['name_ticket'],
                'email' => $data['email'],
                'subject' => $data['subject'],
                'comment' => $data['body'],
                'isSolved'=> 0,
                'agent_id'=> null,
            ]);


            // Send email notification
            Mail::to('david@flexvision.be')->send(new NewTicketNotification(
                $data['name_ticket'],
                $data['email'],
                $data['subject'],
                $data['body'],
                false,
                null,
            ));

            return back()->with('success', 'Le ticket a été créé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Le ticket n\'a pas pu être créé. Erreur: ' . $e->getMessage());
        }
    }
}
