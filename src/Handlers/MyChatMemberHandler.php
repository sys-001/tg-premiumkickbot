<?php

namespace PremiumKickBot\Handlers;

use PremiumKickBot\Constants\ChatPermissions;
use PremiumKickBot\Database;
use PremiumKickBot\DB\Chat;
use PremiumKickBot\DB\User;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\ChatMemberStatus;
use SergiX44\Nutgram\Telegram\Attributes\ChatType;
use SergiX44\Nutgram\Telegram\Exceptions\TelegramException;

class MyChatMemberHandler
{

    public function __construct(private Database $database)
    {
    }

    public function __invoke(Nutgram $bot): void
    {
        switch ($bot->chatMember()?->chat->type) {
            case ChatType::PRIVATE:
                /** @var User|null $user */
                $user = $bot->getData('user');
                if (!empty($user)) {
                    $user->setIsBlocked($bot->chatMember()?->new_chat_member->status == ChatMemberStatus::KICKED);
                    $this->database->saveEntity($user);
                }
                break;
            case ChatType::GROUP:
            case ChatType::SUPERGROUP:
            case ChatType::CHANNEL:
                /** @var Chat|null $chat */
                $chat = $bot->getData('chat');
                $permissions = 0;
                if (!empty($chat)) {
                    switch ($bot->chatMember()?->new_chat_member->status) {
                        case ChatMemberStatus::ADMINISTRATOR:
                            if ($bot->chatMember()?->new_chat_member->can_restrict_members ?? false) {
                                $permissions |= ChatPermissions::CAN_RESTRICT_MEMBERS;
                            }
                            if ($bot->chatMember()?->new_chat_member->can_invite_users ?? false) {
                                $permissions |= ChatPermissions::CAN_INVITE_USERS;
                                if (empty($chat->getInviteLink())) {
                                    try {
                                        $inviteLink = $bot->createChatInviteLink(
                                            $chat->getId(),
                                            ['name' => 'Anti-premium invite', 'creates_join_request' => true]
                                        );
                                        $chat->setInviteLink($inviteLink?->invite_link);
                                    } catch (TelegramException) {
                                    }
                                }
                            } elseif ($chat->checkPermission(ChatPermissions::CAN_INVITE_USERS)) {
                                $chat->setInviteLink();
                            }
                            if ($bot->chatMember()?->new_chat_member->can_delete_messages ?? false) {
                                $permissions |= ChatPermissions::CAN_DELETE_MESSAGES;
                            }
                            if (0 === $permissions) {
                                $bot->leaveChat($chat->getId());
                                $this->database->deleteEntity($chat);
                                break 2;
                            }
                            $chat->setPermissions($permissions);
                            $this->database->saveEntity($chat);
                            break;
                        /** @noinspection PhpMissingBreakStatementInspection */
                        case ChatMemberStatus::MEMBER:
                            $bot->leaveChat($chat->getId());
                        case ChatMemberStatus::KICKED:
                        case ChatMemberStatus::LEFT:
                            $this->database->deleteEntity($chat);
                            break;
                    }
                }
                break;
        }
    }

}