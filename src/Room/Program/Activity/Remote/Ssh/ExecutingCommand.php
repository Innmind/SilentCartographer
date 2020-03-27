<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\Remote\Ssh;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\Server\Control\Server\Command;
use Innmind\Url\AuthorityInterface;

final class ExecutingCommand implements Activity
{
    private AuthorityInterface $authority;
    private Command $command;
    private Tags $tags;

    public function __construct(AuthorityInterface $authority, Command $command)
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
        return "Executing command: [{$this->authority}] {$this->command}";
    }
}
