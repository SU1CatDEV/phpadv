<?php

namespace App\Commands;

use App\Application;

use App\Database\SQLite;

use App\EventSender\EventSender;

use App\Telegram\TelegramApi;

use App\Models\Event;

use App\Queue\RabbitMQ;

//use App\Models\EventDto;

class HandleEventsCommand extends Command

{

    protected Application $app;

    public function __construct(Application $app)

    {

        $this->app = $app;

    }

    public function run(array $options = []): void

    {

        $event = new Event(new SQLite($this->app));

        $events = $event->select();
        
        $queue = new RabbitMQ('eventSender');
        $eventSender = new EventSender(new TelegramApi($this->app->env('TELEGRAM_TOKEN')), $queue);
        foreach ($events as $event) {
            // var_dump($event);
            if ($this->shouldEventBeRan($event)) {
                $eventSender->sendMessage($event["receiver_id"], $event["text"]);
            }

        }

    }

    public function shouldEventBeRan($event): bool
    {
        $currentMinute = date("i");
        $currentHour = date("H");
        $currentDay = date("d");
        $currentMonth = date("m");
        $currentWeekday = date("w");

        return (
            ($event['minute'] === null || $event['minute'] == $currentMinute) &&
            ($event['hour'] === null || $event['hour'] == $currentHour) &&
            ($event['day'] === null || $event['day'] == $currentDay) &&
            ($event['month'] === null || $event['month'] == $currentMonth) &&
            ($event['day_of_week'] === null || $event['day_of_week'] == $currentWeekday)
        );
    }


}