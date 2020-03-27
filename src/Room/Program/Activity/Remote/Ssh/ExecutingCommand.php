<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\Remote\Ssh;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\Server\Control\Server\Command;
use Innmind\Url\Authority;

final class ExecutingCommand implements Activity
{
    private Authority $authority;
    private Command $command;
    private Tags $tags;

    public function __construct(Authority $authority, Command $command)
    {
        $this->authority = $authority;
        $this->command = $command;
        $this->tags = new Tags('os', 'remote', 'ssh', 'control', 'process');
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function __toString(): string
    {
        return "Executing command: [{$this->authority->toString()}] {$this->command->toString()}";
    }
}
