<?php
namespace App\Telegram;

interface TelegramInterface
{
    public function getMessages(int $offset): array;

    public function sendMessage(string $chatId, string $text);
}