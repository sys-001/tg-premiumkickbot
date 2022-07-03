# Premium Kick Bot

[![Bot](https://img.shields.io/badge/bot-%40PremiumKickBot-red)](https://t.me/PremiumKickBot)
[![Updates](https://img.shields.io/badge/updates-%40SysDevBlog-red)](https://t.me/SysDevBlog)
![GitHub](https://img.shields.io/github/license/sys-001/tg-premiumkickbot)
![Required PHP Version](https://img.shields.io/badge/php-%E2%89%A58.0-brightgreen)

:x: Automatically decline all join requests from Telegram Premium users, while accepting the others.

This bot is also capable to:

- :wastebasket: delete all new messages written by any premium user;
- :no_pedestrians: ban any premium user.

## :warning: Requirements

- PHP ≥ 8.0
    - ext-pdo_sqlite
    - ext-sqlite3
- [Composer](https://getcomposer.org/download/)

## :hammer: Deploy

1. Initialize the project:

```bash
  $ git clone https://github.com/sys-001/tg-premiumkickbot
  $ cd tg-premiumkickbot
  $ composer install
  $ cp .env.example .env
```

2. Edit the `.env` file according to your preferences (see [Environment variables](#-environment-variables) for more
   info).

4. You're now ready to start the bot:

```bash
  $ php bot.php
```

## :gear: Environment variables

```ini
BOT_TOKEN = 123456:abcdef # your bot token
BOT_USERNAME = PremiumKickBot # your bot username
DEV_MODE = true # doctrine dev mode, should be 'false' in production
REPORT_ERRORS = 123456789 # your Telegram user ID, the bot will send the exceptions there

DELETE_MESSAGES = true # true, if the bot should delete premium messages
RESTRICT_MEMBERS = false # true, if the bot should ban premium users

DECLINE_MESSAGE = "Sorry, but *%s* does not accept Telegram Premium users\\." # (optional) message sent when a premium user is declined
DONATION_PROVIDER_TOKEN = 123456:YOUR:tokenhere # (optional) Telegram payment provider token, used for donations
DONATION_KOFI = https://ko-fi.com/sys001 # (optional) ko-fi profile link
DONATION_LIBERAPAY = https://liberapay.com/sys001 # (optional) liberapay profile link
DONATION_DESCRIPTION = "Thank you, your support is really appreciated! ❤️" # (optional) description used for the /donate command
DONATION_IMAGE = https://example.com/example.jpg # (optional) image used in the /donate command
DONATION_THANKS = "*Thank you so much for your donation, it will greatly help me in developing new things\\!* ❤️" # (optional) message sent as a thanks for a received donation
```