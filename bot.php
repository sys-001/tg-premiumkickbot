<?php

require_once __DIR__ . '/bootstrap.php';

use PremiumKickBot\Commands\{DonateCommand, StartCommand, StatsCommand};
use PremiumKickBot\Handlers\{ChatJoinRequestHandler,
    ExceptionHandler,
    MessageHandler,
    MyChatMemberHandler,
    PreCheckoutQueryHandler,
    SuccessfulPaymentHandler
};
use PremiumKickBot\Middleware\{IsAdmin, IsPrivate, PopulateDatabase};
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\MessageTypes;


try {
    $bot = new Nutgram($_ENV['BOT_TOKEN']);
} catch (Throwable $e) {
    echo 'Could not create bot instance: ' . $e->getMessage();
    exit(1);
}

$me = $bot->getMe();

if (empty($me)) {
    echo 'Could not retrieve bot info.';
    exit(1);
}

// global middlewares
$bot->middleware(new PopulateDatabase($database, $me));

// update handlers
$bot->onMyChatMember(new MyChatMemberHandler($database));
$bot->onMessage(MessageHandler::class);
$bot->onMessageType(MessageTypes::SUCCESSFUL_PAYMENT, SuccessfulPaymentHandler::class);
$bot->onChatJoinRequest(ChatJoinRequestHandler::class);
$bot->onPreCheckoutQuery(PreCheckoutQueryHandler::class);

// normal commands
$bot->onCommand('start', StartCommand::class)->middleware(IsPrivate::class);
$bot->onCommand('donate', DonateCommand::class)->middleware(IsPrivate::class);

// admin commands
$bot->onCommand('stats', new StatsCommand($database))->middleware(IsAdmin::class);

// exception handler
$bot->onException(ExceptionHandler::class);


try {
    $bot->run();
} catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
    echo 'Could not start bot: ' . $e->getMessage();
    exit(1);
}
