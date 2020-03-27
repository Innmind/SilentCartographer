<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\Remote\Ssh;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\Server\Control\Server\Process\Pid;
use Innmind\Url\Authority;

final class ProcessKilled implements Activity
{
    private Authority $authority;
    private Pid $pid;
    private Tags $tags;

    public function __construct(Authority $authority, Pid $pid)
    {
        $this->authority = $authority;
        $this->pid = $pid;
        $this->tags = new Tags('os', 'remote', 'ssh', 'control', 'process');
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function toString(): string
    {
        return "Process killed: [{$this->authority->toString()}] {$this->pid->toString()}";
    }
}
