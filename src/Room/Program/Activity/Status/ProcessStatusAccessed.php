<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\Status;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\Server\Status\Server\Process\Pid;

final class ProcessStatusAccessed implements Activity
{
    private Pid $pid;
    private Tags $tags;

    public function __construct(Pid $pid)
    {
        $this->pid = $pid;
        $this->tags = new Tags('os', 'status');
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function __toString(): string
    {
        return "Process status accessed: {$this->pid}";
    }
}
