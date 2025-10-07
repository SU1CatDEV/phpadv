<?php

namespace App\Commands;

use App\Application;
use App\Database\SQLite;
use App\Models\Event;

use App\Helpers\Cron;
use App\Exceptions\MalformedCronException;
use App\Actions\EventSaver;

//php runner -c save_event --name 'Имя события' --receiver 'Айди получателя, пока любой' --text 'Текст напоминания' --cron '* * * * *'
class SaveEventCommand extends Command
{

    protected Application $app;

    public function __construct(Application $app)

    {

        $this->app = $app;

    }
    public function run(array $options  = []): void

    {

        $options = $this->getGetoptOptionValues();

        if ($this->isNeedHelp($options)) {

            $this->showHelp();

            return;

        }

        $cron = new Cron();

        try {
            $cronValues = $cron->getCronValues($options['cron']);
        } catch (MalformedCronException $e) {
            $this->showHelp();
            // var_dump(99);
            return;
        }

        $params = [

            'name' => $options['name'],

            'text' => $options['text'],

            'receiver_id' => $options['receiver'],

            'minute' => $cronValues[0],

            'hour' => $cronValues[1],

            'day' => $cronValues[2],

            'month' => $cronValues[3],

            'day_of_week' => $cronValues[4]

        ];

        $eventModel = new Event(new SQLite($this->app));

        $eventSaver = new EventSaver($eventModel);
        $eventSaver->handle($params);
    }

    private function getGetoptOptionValues(): array

    {

        $shortopts = 'c:h:';

        $longopts = [

            "command:",

            "name:",

            "text:",

            "receiver:",

            "cron:",

            "help:",

        ];

        return getopt($shortopts, $longopts);

    }

    public function isNeedHelp(array $options): bool

    {
        // var_dump($options);
        return !isset($options['name']) ||

            !isset($options['text']) ||

            !isset($options['receiver']) ||

            !isset($options['cron']) ||

            isset($options['help']) ||

            isset($options['h']);

    }

    private function showHelp()

    {

        echo " Это тестовый скрипт добавления правил

	Чтобы добавить правило нужно перечислить следующие поля:

	--name Имя события

	--text Текст, который будет отправлен по событию

	--cron  Расписания отправки в формате cron

	--receiver Идентификатор получателя сообщения

	Для справки используйте флаги -h или --help

";

    }

    private function saveEvent(array $params): void

    {
        
    }

}