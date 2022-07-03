<?php

namespace PremiumKickBot\Commands;

use PremiumKickBot\Database;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;

class StatsCommand
{

    public function __construct(private Database $database)
    {
    }

    public function __invoke(Nutgram $bot): void
    {
        $bot->sendMessage(
            sprintf(
                'The bot has seen *%s users* and *%s chats* so far\.',
                $this->database->countUsers(),
                $this->database->countChats()
            ),
            ['parse_mode' => ParseMode::MARKDOWN]
        );
    }
}