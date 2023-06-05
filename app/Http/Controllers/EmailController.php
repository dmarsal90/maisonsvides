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

        try {
            Mail::send([], [], function ($message) use ($to, $subject, $body) {
                $message->from(env('MAIL_ADMIN_FROM_ADDRESS'), env('MAIL_ADMIN_FROM_NAME'))
                    ->to($to)
                    ->subject($subject)
                    ->setBody($body, 'text/html');
            });
        } catch (\Swift_TransportException $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ], 422);
        }

        return response()->json(['status' => 'success']);
    }

    public function sendTicketEmail(Request $request)
    {
        $to = $request->input('user_email');
        $subject = $request->input('subject');
        $body = $request->input('body');

        // Eliminar las etiquetas HTML del mensaje
        $body = strip_tags($body);
        try {
            // Enviar el correo electrónico utilizando la clase Mail
            Mail::send([], [], function ($message) use ($to, $subject, $body) {
                $message->from(env('MAIL_TICKETS_FROM_ADDRESS'), env('MAIL_TICKETS_FROM_NAME'))
                    ->to($to)
                    ->subject($subject)
                    ->setBody($body, 'text/html');
            });
        } catch (\Swift_TransportException $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ], 422);
        }

        return response()->json(['status' => 'success']);
    }
}
