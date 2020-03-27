<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\Control;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\Server\Control\Server\Process\Pid;

final class ProcessKilled implements Activity
{
    private Pid $pid;
    private Tags $tags;

    public function __construct(Pid $pid)
    {
        $this->pid = $pid;
        $this->tags = new Tags('os', 'control', 'process');
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function __toString(): string
    {
        return "Process killed: {$this->pid}";
    }
}
