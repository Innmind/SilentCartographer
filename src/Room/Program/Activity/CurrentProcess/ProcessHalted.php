<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\CurrentProcess;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\TimeContinuum\Period;
use Innmind\TimeWarp\PeriodToMilliseconds;

final class ProcessHalted implements Activity
{
    private int $period;
    private Tags $tags;

    public function __construct(Period $period)
    {
        $this->period = (new PeriodToMilliseconds)($period);
        $this->tags = new Tags('os', 'process');
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function toString(): string
    {
        return "Process halted: {$this->period}ms";
    }
}
