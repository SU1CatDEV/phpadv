<?php

use PHPUnit\Framework\TestCase;

/**
 * @covers SaveEventCommand
 */
class SaveEventCommandTest extends TestCase
{
    /**
     * @dataProvider isNeedHelpDataProvider
     */
    public function testIsNeedHelp(array $options, bool $isNeedHelp) {
        $saveEventCommand = new \App\Commands\SaveEventCommand(new \App\Application(dirname(__DIR__)));

        $result = $saveEventCommand->isNeedHelp($options);
        
        self::assertEquals($result, $isNeedHelp);
    }

    public function isNeedHelpDataProvider() {
        return [
            [
                [
                    "name" => "yay",
                    "text" => "asdfghjkl",
                    "receiver" => 123456789,
                    "cron" => "* * * * *",
                ],
                false
            ],
            [
                [
                    "name" => "yay",
                    "text" => "asdfghjkl",
                    "receiver" => 123456789,
                    "cron" => "* * * * *",
                    "help" => true
                ],
                true
            ],
            [
                [
                    "name" => "yay",
                    "text" => "asdfghjkl",
                    "receiver" => 123456789,
                    "cron" => "* * * * *",
                    "h" => true
                ],
                true
            ],
            [
                [
                    "name" => "yay",
                    "text" => null,
                    "receiver" => 123456789,
                    "cron" => "* * * * *",
                ],
                true
            ],
            [
                [
                    "name" => "yay",
                    "text" => "asdfghjkl",
                    "receiver" => null,
                    "cron" => "* * * * *",
                ],
                true
            ]
        ];
    }
}