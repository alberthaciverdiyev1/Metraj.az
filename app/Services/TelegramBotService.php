<?php

namespace App\Services;

use App\Modules\Property\Models\Property;
use App\Modules\PropertyRequest\Models\PropertyRequest;
use App\Modules\Roommate\Models\RoommateListing;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TelegramBotService
{
    protected string $token = '8208871985:AAHJfMGc5x7J6xsrXkimozyWPwRle_cmoqk';
    protected string $apiUrl = 'https://api.telegram.org/bot';

    protected function getApiUrl(): string
    {
        return $this->apiUrl . $this->token;
    }

    public function setWebhook(string $url): bool
    {
        $response = Http::post($this->getApiUrl() . '/setWebhook', [
            'url' => $url,
        ]);

        return $response->json('ok', false);
    }

    public function sendNewListingNotification($model): void
    {
        $chatId = Cache::get('telegram_admin_chat_id');
        if (!$chatId) {
            Log::warning('Telegram admin chat ID not set. Send /start to the bot.');
            return;
        }

        $type = '';
        $details = '';
        $id = $model->id;
        $title = $this->getTranslatableString($model->title, 'Başlıqsız');
        $user = $model->user;
        
        $contactName = $this->getTranslatableString($model->contact_name ?? $user?->name, 'Qeyd olunmayıb');
        $contactPhone = $this->getTranslatableString($model->contact_phone, 'Qeyd olunmayıb');

        if ($model instanceof Property) {
            $type = 'property';
            $price = number_format($model->price) . ' ' . $model->currency;
            $city = $this->getTranslatableString($model->city?->name, '-');
            $district = $this->getTranslatableString($model->district?->name, '-');
            $rooms = $model->rooms ?? '-';
            $area = $model->area ? $model->area . ' m²' : '-';

            $details = "🏢 *YENİ ƏMLAK ELANI*\n"
                . "🏷️ *Başlıq:* {$title}\n"
                . "💰 *Qiymət:* {$price}\n"
                . "📍 *Məkan:* {$city} / {$district}\n"
                . "🚪 *Otaq:* {$rooms} otaqlı | *Sahə:* {$area}\n";
        } elseif ($model instanceof PropertyRequest) {
            $type = 'request';
            $budget = number_format($model->budget_min) . ' - ' . number_format($model->budget_max) . ' ' . $model->currency;
            $city = $this->getTranslatableString($model->city?->name, '-');
            $district = $this->getTranslatableString($model->district?->name, '-');

            $details = "🔍 *YENİ ƏMLAK TƏLƏBİ (AXTARIRAM)*\n"
                . "🏷️ *Başlıq:* {$title}\n"
                . "💰 *Büdcə:* {$budget}\n"
                . "📍 *Məkan:* {$city} / {$district}\n";
        } elseif ($model instanceof RoommateListing) {
            $type = 'roommate';
            $price = number_format($model->price) . ' ' . $model->currency;
            $city = $this->getTranslatableString($model->city?->name, '-');

            $details = "🤝 *YENİ OTAQ YOLDAŞI ELANI*\n"
                . "🏷️ *Başlıq:* {$title}\n"
                . "💰 *Qiymət:* {$price}\n"
                . "📍 *Məkan:* {$city}\n";
        }

        $message = $details
            . "👤 *Əlaqədar şəxs:* {$contactName}\n"
            . "📞 *Telefon:* {$contactPhone}\n"
            . "🆔 *Sistem ID:* #{$id}\n\n"
            . "Zəhmət olmasa bu elanı təsdiq edin və ya imtina edin.";

        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => '✅ Təsdiqlə', 'callback_data' => "approve_{$type}_{$id}"],
                    ['text' => '❌ İmtina et', 'callback_data' => "reject_prompt_{$type}_{$id}"],
                ]
            ]
        ];

        Http::post($this->getApiUrl() . '/sendMessage', [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode($keyboard),
        ]);
    }

    public function handleWebhook(array $update): void
    {
        // 1. Handle Callback Query (Buttons)
        if (isset($update['callback_query'])) {
            $callbackQuery = $update['callback_query'];
            $callbackId = $callbackQuery['id'];
            $chatId = $callbackQuery['message']['chat']['id'];
            $messageId = $callbackQuery['message']['message_id'];
            $data = $callbackQuery['data'];
            $originalText = $callbackQuery['message']['text'] ?? '';

            if (preg_match('/^approve_(property|request|roommate)_(\d+)$/', $data, $matches)) {
                $type = $matches[1];
                $id = $matches[2];

                $model = $this->getModelInstance($type, $id);
                if ($model) {
                    $model->status = $type === 'property' ? 'published' : 'published';
                    $model->save();

                    // Edit original message
                    $newText = $originalText . "\n\n🟢 *TƏSDİQLƏNDİ* (Admin tərəfindən qəbul edildi)";
                    Http::post($this->getApiUrl() . '/editMessageText', [
                        'chat_id' => $chatId,
                        'message_id' => $messageId,
                        'text' => $newText,
                        'parse_mode' => 'Markdown',
                    ]);

                    Http::post($this->getApiUrl() . '/answerCallbackQuery', [
                        'callback_query_id' => $callbackId,
                        'text' => 'Elan təsdiqləndi!',
                    ]);
                }
            } elseif (preg_match('/^reject_prompt_(property|request|roommate)_(\d+)$/', $data, $matches)) {
                $type = $matches[1];
                $id = $matches[2];

                // Set state in Cache
                Cache::put("telegram_state_{$chatId}", [
                    'action' => 'reject',
                    'type' => $type,
                    'id' => $id,
                    'message_id' => $messageId,
                    'original_text' => $originalText
                ], now()->addMinutes(10));

                // Send reply request
                Http::post($this->getApiUrl() . '/sendMessage', [
                    'chat_id' => $chatId,
                    'text' => "Lütfən #{$id} ID-li elan üçün imtina səbəbini yazın (Bu mesaja Cavab/Reply verərək yazın):",
                    'reply_markup' => json_encode([
                        'force_reply' => true,
                    ]),
                ]);

                Http::post($this->getApiUrl() . '/answerCallbackQuery', [
                    'callback_query_id' => $callbackId,
                ]);
            }
            return;
        }

        // 2. Handle Text Message
        if (isset($update['message'])) {
            $message = $update['message'];
            $chatId = $message['chat']['id'];
            $text = $message['text'] ?? '';

            $lowerText = strtolower(trim($text));
            if ($lowerText === '/start' || str_starts_with($lowerText, '/start@')) {
                Cache::forever('telegram_admin_chat_id', $chatId);
                
                $chatType = $message['chat']['type'] ?? 'private';
                $chatTitle = $message['chat']['title'] ?? 'Şəxsi Çat';
                
                $responseText = "Salam! Bu çat bildirişlərin göndərilməsi üçün yadda saxlanıldı.\n\n"
                    . "ℹ️ *Çat Məlumatı:*\n"
                    . "• *Növ:* {$chatType}\n"
                    . "• *Ad/Başlıq:* {$chatTitle}\n"
                    . "• *ID:* `{$chatId}`\n\n"
                    . "Artıq yeni elanlar və müraciətlər bu çata/qrupa gələcək.";
                
                Http::post($this->getApiUrl() . '/sendMessage', [
                    'chat_id' => $chatId,
                    'text' => $responseText,
                    'parse_mode' => 'Markdown',
                ]);
                return;
            }

            // Check if there is an active rejection state
            $state = Cache::get("telegram_state_{$chatId}");
            if ($state && $state['action'] === 'reject') {
                $type = $state['type'];
                $id = $state['id'];
                $origMsgId = $state['message_id'];
                $originalText = $state['original_text'];

                $model = $this->getModelInstance($type, $id);
                if ($model) {
                    $model->status = 'rejected';
                    $model->rejection_reason = $text;
                    $model->save();

                    // Update original notification message
                    $newText = $originalText . "\n\n🔴 *İMTİNA EDİLDİ*\n❌ *Səbəb:* {$text}";
                    Http::post($this->getApiUrl() . '/editMessageText', [
                        'chat_id' => $chatId,
                        'message_id' => $origMsgId,
                        'text' => $newText,
                        'parse_mode' => 'Markdown',
                    ]);

                    Http::post($this->getApiUrl() . '/sendMessage', [
                        'chat_id' => $chatId,
                        'text' => "✅ Elan imtina edildi və səbəb qeyd olundu.",
                        'reply_to_message_id' => $message['message_id'],
                    ]);

                    Cache::forget("telegram_state_{$chatId}");
                }
            }
        }
    }

    protected function getModelInstance(string $type, int $id)
    {
        switch ($type) {
            case 'property':
                return Property::find($id);
            case 'request':
                return PropertyRequest::find($id);
            case 'roommate':
                return RoommateListing::find($id);
        }
        return null;
    }

    protected function getTranslatableString($value, string $default = '-'): string
    {
        if (is_array($value)) {
            return $value['az'] ?? $value['ru'] ?? $value['en'] ?? reset($value) ?? $default;
        }
        return (string) ($value ?? $default);
    }
}
