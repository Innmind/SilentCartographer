<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer;

use Innmind\IPC\Message;

interface Protocol
{
    public function encode(RoomActivity $roomActivity): Message;
    public function decode(Message $message): RoomActivity;
}
