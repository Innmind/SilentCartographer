<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\CurrentProcess;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\Server\Control\Server\Process\Pid;

final class ProcessForked implements Activity
{
    private Pid $child;
    private Tags $tags;

    public function __construct(Pid $child)
    {
        $this->child = $child;
        $this->tags = new Tags('os', 'process');
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function toString(): string
    {
        return "Process forked: {$this->child->toString()}";
    }
}
