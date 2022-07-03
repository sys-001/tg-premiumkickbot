<?php

namespace PremiumKickBot\Middleware;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use PremiumKickBot\Database;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\User\User;

class PopulateDatabase
{

    public function __construct(private Database $database, private User $me)
    {
    }

    public function __invoke(Nutgram $bot, $next): void
    {
        $userId = $bot->userId();
        $chatId = $bot->chatId() ?? $bot->chatMember()?->chat->id;
        $chat = null;
        if (!empty($chatId) and $chatId < 0 and $bot->message()?->left_chat_member?->id != $this->me->id) {
            try {
                $chat = $this->database->getChat($chatId);
            } catch (OptimisticLockException|ORMException $e) {
                echo 'Unable to retrieve chat: ' . $e->getMessage();
            }
        }
        if (!empty($userId)) {
            try {
                $user = $this->database->getUser($userId);
            } catch (OptimisticLockException|ORMException $e) {
                echo 'Unable to retrieve user: ' . $e->getMessage();
            }
        }
        $bot->setData('chat', $chat ?? null);
        $bot->setData('user', $user ?? null);
        $next($bot);
    }
}