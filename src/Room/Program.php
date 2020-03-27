<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room;

use Innmind\SilentCartographer\{
    Room,
    Room\Program\Type,
};
use Innmind\Server\Status\Server\Process\Pid;

final class Program
{
    private Pid $id;
    private Type $type;
    private Room $room;

    public function __construct(Pid $id, Type $type, Room $room)
    {
        $this->id = $id;
        $this->type = $type;
        $this->room = $room;
    }

    public function id(): Pid
    {
        return $this->id;
    }

    public function type(): Type
    {
        return $this->type;
    }

    public function room(): Room
    {
        return $this->room;
    }
}
