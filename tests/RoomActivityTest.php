<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer;

use Innmind\SilentCartographer\{
    RoomActivity,
    Room,
    Room\Program,
    Room\Program\Type,
    Room\Program\Activity,
};
use Innmind\Url\UrlInterface;
use Innmind\Server\Status\Server\Process\Pid;
use PHPUnit\Framework\TestCase;

class RoomActivityTest extends TestCase
{
    public function testInterface()
    {
        $roomActivity = new RoomActivity(
            $program = new Program(
                new Pid(42),
                Type::cli(),
                new Room($this->createMock(UrlInterface::class))
            ),
            $activity = $this->createMock(Activity::class)
        );

        $this->assertSame($program, $roomActivity->program());
        $this->assertSame($activity, $roomActivity->activity());
    }
}