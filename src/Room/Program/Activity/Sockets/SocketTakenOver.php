<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\Sockets;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\Socket\Address\Unix;

final class SocketTakenOver implements Activity
{
    private $address;
    private $tags;

    public function __construct(Unix $address)
    {
        $this->address = $address;
        $this->tags = new Tags('os', 'socket', 'unix');
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function __toString(): string
    {
        return "Socket taken over: {$this->address}";
    }
}