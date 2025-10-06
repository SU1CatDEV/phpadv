<?php

namespace App\Commands;

use App\Application;
use App\Telegram\TelegramApi;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

// im not doing this. okay bye
class TgMessagesCommand extends Command
{
    protected Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    function run(array $options = []): void {
        $tgApi = new TelegramApi($this->app->env("TELEGRAM_TOKEN"));
        $tgApi->sendMessage(7021003536, "lalallala");
    }
}