<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer;

use Innmind\SilentCartographer\Room;
use Innmind\Url\UrlInterface;
use PHPUnit\Framework\TestCase;

class RoomTest extends TestCase
{
    public function testInterface()
    {
        $room = new Room(
            $location = $this->createMock(UrlInterface::class)
        );

        $this->assertSame($location, $room->location());
    }
}
