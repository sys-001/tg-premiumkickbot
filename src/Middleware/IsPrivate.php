<?php

namespace PremiumKickBot\Middleware;

use SergiX44\Nutgram\Nutgram;

class IsPrivate
{

    public function __invoke(Nutgram $bot, $next): void
    {
        if ($bot->chatId() > 0) {
            $next($bot);
        }
    }

}