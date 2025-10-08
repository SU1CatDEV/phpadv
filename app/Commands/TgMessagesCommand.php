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
use App\Cache\Redis;

use Predis\Client;

// im not doing this. okay bye
class TgMessagesCommand extends Command
{
    protected Application $app;

    private int $offset;
    private array|null $oldMessages;
    private Redis $redis;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->offset = 0;
        $this->oldMessages = [];

        $client = new Client([
            "scheme" => "tcp",
            "host" => "127.0.0.1",
            "port" => 6379,
        ]);
        $this->redis = new Redis($client); // uhhhh client?
    }

    function run(array $options = []): void {
        echo json_encode($this->receiveNewMessages());
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

    // public function handle(): void {
    //     $messages = $this->receiveNewMessages();

    //     foreach ($messages as $userId => $userMessages) {
    //         $answerMessage = $this->handleMessagesAndReturnAnswer($userMessages);

    //         $this->eventSender->sendMessage($userId, $answerMessage);
    //     }
    // }

    protected function getTelegramApi() {
        return new TelegramApi($this->app->env("TELEGRAM_TOKEN"));
    }

    private function receiveNewMessages(): array {
        $this->offset = $this->redis->get('tg_messages:offset', 0);

        $tgApi = $this->getTelegramApi();
        $result = $tgApi->getMessages($this->offset);

        $this->redis->set("tg_messages:offset", $results['offset'] ?? 0);

        $this->oldMessages = json_decode($this->redis->get("tg_messages:old_messages"));

        $messages = [];

        foreach ($result["result"] ?? [] as $chatId => $newMessage) {
            if (isset($this->oldMessages[$chatId])) {
                $this->oldMessages[$chatId] = [...$this->oldMessages[$chatId], ...$newMessage];
            } else {
                $this->oldMessages[$chatId] = $newMessage;
            }
        }

        $this->redis->set("tg_messages:old_messages", json_encode($messages)); // $this->oldMessages?

        return $messages;
    }
}