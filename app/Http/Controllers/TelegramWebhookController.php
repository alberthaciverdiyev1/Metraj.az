<?php

namespace App\Http\Controllers;

use App\Services\TelegramBotService;
use Illuminate\Http\Request;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request, TelegramBotService $botService)
    {
        $botService->handleWebhook($request->all());

        return response()->json(['status' => 'success']);
    }
}
