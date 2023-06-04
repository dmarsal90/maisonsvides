<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendConfirmationEmail(Request $request)
    {
        $para = $request->input('seller_email');
        $asunto = $request->input('subject');
        $mensaje = $request->input('body');

        // Eliminar las etiquetas HTML del mensaje
        $mensaje = strip_tags($mensaje);

        // Enviar el correo electrónico utilizando la clase Mail
        Mail::send([], [], function ($message) use ($para, $asunto, $mensaje) {
            $message->to($para)
                ->subject($asunto)
                ->setBody($mensaje, 'text/html');
        });

        return response()->json(['status' => 'success']);
    }
}
