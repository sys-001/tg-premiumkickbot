<?php

namespace PremiumKickBot\Handlers;

use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use SergiX44\Nutgram\Telegram\Exceptions\TelegramException;

class SuccessfulPaymentHandler
{

    public function __invoke(Nutgram $bot): void
    {
        if (!empty($_ENV['DONATION_THANKS']) and $bot->message()?->successful_payment?->invoice_payload == 'donate') {
            try {
                $bot->sendMessage($_ENV['DONATION_THANKS'], ['parse_mode' => ParseMode::MARKDOWN]);
            } catch (TelegramException) {
                // you'll receive the service message even if the bot is currently blocked
            }
        }
    }

}