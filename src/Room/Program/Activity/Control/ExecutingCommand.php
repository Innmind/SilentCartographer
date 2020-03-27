<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\Control;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\Server\Control\Server\Command;

final class ExecutingCommand implements Activity
{
    private Command $command;
    private Tags $tags;

    public function __construct(Command $command)
    {
        $this->command = $command;
        $this->tags = new Tags('os', 'control', 'process');
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function __toString(): string
    {
        return "Executing command: {$this->command->toString()}";
    }
}
