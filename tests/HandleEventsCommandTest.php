<?php

use PHPUnit\Framework\TestCase;

/**
 * @covers HandleEventsCommand
 */
class HandleEventsCommandTest extends TestCase
{
    /**
     * @dataProvider eventDtoDataProvider
     */
    public function testShouldEventBeRanReceiveEventDtoAndReturnCorrectBool(array $event, bool $shouldEventBeRan) {
        $handleEventsCommand = new \App\Commands\HandleEventsCommand(new \App\Application(dirname(__DIR__)));

        $result = $handleEventsCommand->shouldEventBeRan($event);
        
        self::assertEquals($result, $shouldEventBeRan);
    }

    public static function eventDtoDataProvider(): array {
        return [
            [
                [
                    'minute' => date("i"),
                    'hour' => date("H"),
                    'day' => date("d"),
                    'month' => date("m"),
                    'day_of_week' => date("w"),
                ],
                true
            ],
            [
                [
                    'minute' => "",
                    'hour' => date("H"),
                    'day' => date("d"),
                    'month' => date("m"),
                    'day_of_week' => date("w"),
                ],
                false
            ],
            [
                [
                    'minute' => date("i"),
                    'hour' => "",
                    'day' => date("d"),
                    'month' => date("m"),
                    'day_of_week' => date("w"),
                ],
                false
            ],
            [
                [
                    'minute' => date("i"),
                    'hour' => date("H"),
                    'day' => "",
                    'month' => date("m"),
                    'day_of_week' => date("w"),
                ],
                false
            ],
            [
                [
                    'minute' => date("i"),
                    'hour' => date("H"),
                    'day' => date("d"),
                    'month' => "",
                    'day_of_week' => date("w"),
                ],
                false
            ],
            [
                [
                    'minute' => date("i"),
                    'hour' => date("H"),
                    'day' => date("d"),
                    'month' => date("m"),
                    'day_of_week' => "",
                ],
                false
            ]
        ];
    }
}