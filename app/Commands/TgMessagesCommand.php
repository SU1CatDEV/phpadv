<?php

namespace App\Commands;

use App\Application;
use App\Telegram\TelegramApi;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use App\EventSender\EventSender;
use App\Database\SQLite;
use App\Models\Event;
use App\Helpers\Cron;
use App\Actions\EventSaver;

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
        
        // $eventSender = new EventSender(new TelegramApi($this->app->env("TELEGRAM_TOKEN")));
        
        // $cron = new Cron();

        // $eventModel = new Event(new SQLite($this->app));
        // $eventSaver = new EventSaver($eventModel);

        // $tgEvents = new TgEvents($cron, $eventSaver, $tgApi, $eventSender);

        // while (true) {
        //     $tgEvents->handle();

        //     sleep(1);
        // }
    }
}