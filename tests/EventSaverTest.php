<?php

use App\Actions\EventSaver;
use App\Models\Event;
use PHPUnit\Framework\TestCase;

/**
 * @covers EventSaver
 */
class EventSaverTest extends TestCase {
    /**
     * @dataProvider eventSaverDataProvider
     */
    public function testHandleCallCorrectInsertInModel(array $eventArray, array $expectedArray): void {
        $mock = $this->getMockBuilder(Event::class)
            ->setMethods(['insert'])
            ->disableOriginalConstructor()
            ->getMock();
        
        $mock->expects($this->once())->method("insert")->with(
            
                "name, receiver_id, text, minute, hour, day, month, day_of_week",
                $expectedArray
            
        );

        $eventSaver = new EventSaver($mock);
        $eventSaver->handle($eventArray);

        //$this->assertTrue(true);
    }

    public static function eventSaverDataProvider(): array {
        return [
            [
                [
                    'name' => "event name",
                    'receiver_id' => 1234,
                    'text' => "event text",
                    'minute' => "minute",
                    'hour' => "hour",
                    'day' => "day",
                    'month' => "month",
                    'day_of_week' => "weekd"
                ],
                [
                    "event name",
                    1234,
                    "event text",
                    "minute",
                    "hour",
                    "day",
                    "month",
                    "weekd"
                ]
            ],
            [
                [
                    'name' => "event name",
                    'receiver_id' => 1234,
                    'text' => "event text",
                    'minute' => null,
                    'hour' => null,
                    'day' => null,
                    'month' => null,
                    'day_of_week' => null
                ],
                [
                    "event name",
                    1234,
                    "event text",
                    null,
                    null,
                    null,
                    null,
                    null
                ]
            ]
        ];
    }
}