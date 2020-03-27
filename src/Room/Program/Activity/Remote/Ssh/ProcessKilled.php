<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\Remote\Ssh;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\Server\Control\Server\Process\Pid;
use Innmind\Url\AuthorityInterface;

final class ProcessKilled implements Activity
{
    private AuthorityInterface $authority;
    private Pid $pid;
    private Tags $tags;

    public function __construct(AuthorityInterface $authority, Pid $pid)
    {
        $this->authority = $authority;
        $this->pid = $pid;
        $this->tags = new Tags('os', 'remote', 'ssh', 'control', 'process');
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function __toString(): string
    {
        return "Process killed: [{$this->authority}] {$this->pid}";
    }
}
