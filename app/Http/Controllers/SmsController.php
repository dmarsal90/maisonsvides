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
        $to = $request->input('to');
        $message = $request->input('message');

        $result = $this->lwsSmsService->sendSms($to, $message);

        if ($result) {
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error']);
        }
    }
}
