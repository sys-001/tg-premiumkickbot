<?php

namespace PremiumKickBot\Commands;

use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class DonateCommand
{

    public function __invoke(Nutgram $bot): void
    {
        if (!empty($_ENV['DONATION_PROVIDER_TOKEN'])) {
            $keyboard = InlineKeyboardMarkup::make();
            $keyboard->addRow(InlineKeyboardButton::make('💳 Stripe', pay: true));
            if (!empty($_ENV['DONATION_KOFI'])) {
                $keyboard->addRow(InlineKeyboardButton::make('☕️ Ko-fi', url: $_ENV['DONATION_KOFI']));
            }
            if (!empty($_ENV['DONATION_LIBERAPAY'])) {
                $keyboard->addRow(InlineKeyboardButton::make('💲 Liberapay', url: $_ENV['DONATION_LIBERAPAY']));
            }
            $options = [
                'max_tip_amount' => 10000,
                'reply_markup' => $keyboard,
            ];
            if (!empty($_ENV['DONATION_IMAGE'])) {
                $options += [
                    'photo_url' => $_ENV['DONATION_IMAGE'],
                    'photo_width' => 200,
                    'photo_height' => 200
                ];
            }
            $bot->sendInvoice(
                'Donate',
                $_ENV['DONATION_DESCRIPTION'] ?? 'Sample description.',
                'donate',
                $_ENV['DONATION_PROVIDER_TOKEN'],
                'EUR',
                [['label' => 'Minimum donation', 'amount' => 150]],
                $options
            );
        }
    }

}