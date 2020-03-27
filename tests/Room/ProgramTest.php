<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room;

use Innmind\SilentCartographer\{
    Room\Program,
    Room\Program\Type,
    Room,
};
use Innmind\Server\Control\Server\Process\Pid;
use Innmind\Url\Url;
use PHPUnit\Framework\TestCase;

class ProgramTest extends TestCase
{
    public function testInterface()
    {
        $program = new Program(
            $id = new Pid(42),
            Type::cli(),
            $room = new Room(Url::of('file:///somewhere'))
        );

        $this->assertSame($id, $program->id());
        $this->assertSame(Type::cli(), $program->type());
        $this->assertSame($room, $program->room());
    }
}
