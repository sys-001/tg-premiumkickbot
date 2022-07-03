<?php

namespace PremiumKickBot\Handlers;

use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use SergiX44\Nutgram\Telegram\Exceptions\TelegramException;

class ChatJoinRequestHandler
{

    public function __invoke(Nutgram $bot): void
    {
        $user = $bot->chatJoinRequest()?->from;
        $chatId = $bot->chatJoinRequest()?->chat?->id;
        if (empty($user) or empty($chatId)) {
            return;
        }
        if ($user->is_premium ?? false) {
            if (!empty($_ENV['DECLINE_MESSAGE'])) {
                try {
                    $bot->sendMessage(
                        sprintf(
                            $_ENV['DECLINE_MESSAGE'],
                            $bot->chatJoinRequest()->chat->title ?? $bot->chatJoinRequest(
                            )->chat->title ?? $bot->chatJoinRequest()->chat->id ?? 'this group'
                        ),
                        ['chat_id' => $user->id, 'parse_mode' => ParseMode::MARKDOWN]
                    );
                } catch (TelegramException) {
                }
            }
            $bot->declineChatJoinRequest($chatId, $user->id);
            return;
        }
        $bot->approveChatJoinRequest($chatId, $user->id);
    }

}