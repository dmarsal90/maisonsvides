<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LwsSmsService;

class SmsController extends Controller
{
    protected $lwsSmsService;

    public function __construct(LwsSmsService $lwsSmsService)
    {
        $this->lwsSmsService = $lwsSmsService;
    }

    public function sendSmsReminder(Request $request)
    {
        $to = '+' . $request->input('to');
        $message = $request->input('body');

        // Reemplazar los marcadores de posición en el mensaje con los valores correspondientes
        $message = str_replace('{seller_name}', $request->input('seller_name'), $message);
        $message = str_replace('{estate_address}', $request->input('estate_address'), $message);
        $message = str_replace('{modal_date}', $request->input('modal_date'), $message);
        $message = str_replace('{modal_date_confirm_start}', $request->input('modal_date_confirm_start'), $message);
        $message = str_replace('{modal_date_confirm_end}', $request->input('modal_date_confirm_end'), $message);

        // Eliminar las etiquetas HTML del mensaje
        $message = strip_tags($message);

        try {
            $result = $this->lwsSmsService->sendSms($to, $message);

            if ($result) {
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Error sending SMS']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
