<?php

namespace PremiumKickBot\Handlers;

use SergiX44\Nutgram\Nutgram;
use Throwable;

class ExceptionHandler
{

    public function __invoke(Nutgram $bot, Throwable $e): void
    {
        $text = 'An exception "%s" occurred: "%s" (%s, line %s).' . PHP_EOL . 'Stack trace: %s';
        $bot->sendMessage(
            sprintf($text, get_class($e), $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString()),
            ['chat_id' => $_ENV['REPORT_ERRORS']]
        );
    }

}