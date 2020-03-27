<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer;

use Innmind\SilentCartographer\Room\{
    Program,
    Program\Activity,
};

final class RoomActivity
{
    private Program $program;
    private Activity $activity;

    public function __construct(Program $program, Activity $activity)
    {
        $this->program = $program;
        $this->activity = $activity;
    }

    public function program(): Program
    {
        return $this->program;
    }

    public function activity(): Activity
    {
        return $this->activity;
    }
}
