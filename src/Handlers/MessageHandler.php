<?php

namespace PremiumKickBot\Handlers;

use PremiumKickBot\Constants\ChatPermissions;
use PremiumKickBot\DB\Chat;
use PremiumKickBot\DB\User;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Exceptions\TelegramException;

class MessageHandler
{

    public function __invoke(Nutgram $bot): void
    {
        /** @var Chat|null $chat */
        $chat = $bot->getData('chat');
        /** @var User|null $user */
        $user = $bot->getData('user');
        switch ($bot->chat()?->type) {
            case 'private':
                break;
            case 'group':
            case 'supergroup':
                if ($bot->user()?->is_premium ?? false and !empty($chat) and !empty($user) and !$user->isAdmin()
                    and !$user->isWhitelisted()) {
                    $chatId = $chat->getId();
                    $userId = $user->getId();
                    $messageId = $bot->messageId();
                    $shouldDeleteMessages = filter_var($_ENV['DELETE_MESSAGES'], FILTER_VALIDATE_BOOLEAN);
                    $shouldRestrictMembers = filter_var($_ENV['RESTRICT_MEMBERS'], FILTER_VALIDATE_BOOLEAN);

                    if ($shouldRestrictMembers and $chat->checkPermission(ChatPermissions::CAN_RESTRICT_MEMBERS)) {
                        try {
                            $bot->banChatMember($chatId, $userId);
                        } catch (TelegramException) {
                        }
                    }
                    if ($shouldDeleteMessages and $chat->checkPermission(ChatPermissions::CAN_DELETE_MESSAGES)
                        and !empty($messageId)) {
                        try {
                            $bot->deleteMessage($chatId, $messageId);
                        } catch (TelegramException) {
                        }
                    }
                }
                break;
        }
    }

}