<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\Status;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\Server\Status\Server\Cpu;

final class CpuUsageAccessed implements Activity
{
    private Cpu $cpu;
    private Tags $tags;

    public function __construct(Cpu $cpu)
    {
        $this->cpu = $cpu;
        $this->tags = new Tags('os', 'status');
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function __toString(): string
    {
        return (string) $this->cpu;
    }
}
