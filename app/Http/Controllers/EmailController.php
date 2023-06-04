<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendConfirmationEmail(Request $request)
    {
        $to = $request->input('seller_email');
        $subject = $request->input('subject');
        $body = $request->input('body');

        // Eliminar las etiquetas HTML del mensaje
        $body = strip_tags($body);

        // Enviar el correo electrónico utilizando la clase Mail
        Mail::send([], [], function ($message) use ($to, $subject, $body) {
            $message->from('admin@maisonsvides.be', 'Administrateur')
                ->to($to)
                ->subject($subject)
                ->setBody($body, 'text/html');
        });

        return response()->json(['status' => 'success']);
    }

    public function sendTicketEmail(Request $request)
    {
        $to = $request->input('user_email');
        $subject = $request->input('subject');
        $body = $request->input('body');

        // Eliminar las etiquetas HTML del mensaje
        $body = strip_tags($body);

        // Enviar el correo electrónico utilizando la clase Mail
        Mail::send([], [], function ($message) use ($to, $subject, $body) {
            $message->from('tickets@maisonsvides.be', 'Tickets')
                ->to($to)
                ->subject($subject)
                ->setBody($body, 'text/html');
        });

        return response()->json(['status' => 'success']);
    }
}
