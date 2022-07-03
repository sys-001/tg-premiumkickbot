<?php

namespace PremiumKickBot\Handlers;

use SergiX44\Nutgram\Nutgram;

class PreCheckoutQueryHandler
{

    public function __invoke(Nutgram $bot): void
    {
        if ($bot->preCheckoutQuery()?->invoice_payload == 'donate') {
            $bot->answerPreCheckoutQuery(true);
        }
    }

}