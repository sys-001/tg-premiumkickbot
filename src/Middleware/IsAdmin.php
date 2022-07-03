<?php

namespace PremiumKickBot\Middleware;

use PremiumKickBot\DB\User;
use SergiX44\Nutgram\Nutgram;

class IsAdmin
{
    public function __invoke(Nutgram $bot, $next): void
    {
        /** @var User|null $user */
        $user = $bot->getData('user');
        if ($user?->isAdmin() ?? false) {
            $next($bot);
        }
    }
}