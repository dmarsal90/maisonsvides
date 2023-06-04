<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

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
}
