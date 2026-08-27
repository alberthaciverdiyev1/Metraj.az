<?php

namespace App\Console\Commands;

use App\Services\TelegramBotService;
use Illuminate\Console\Command;

class TelegramSetWebhookCommand extends Command
{
    protected $signature = 'telegram:set-webhook {url?}';

    protected $description = 'Set the Telegram bot webhook URL';

    public function handle(TelegramBotService $botService)
    {
        $url = $this->argument('url');

        if (!$url) {
            $url = rtrim(config('app.url'), '/') . '/api/telegram/webhook';
        }

        $this->info("Setting Telegram Webhook to: {$url} ...");

        if ($botService->setWebhook($url)) {
            $this->info("Telegram Webhook set successfully!");
        } else {
            $this->error("Failed to set Telegram Webhook.");
        }
    }
}
