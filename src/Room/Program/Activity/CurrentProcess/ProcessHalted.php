<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\CurrentProcess;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\TimeContinuum\PeriodInterface;
use Innmind\TimeWarp\PeriodToMilliseconds;

final class ProcessHalted implements Activity
{
    private $period;
    private $tags;

    public function __construct(PeriodInterface $period)
    {
        $this->period = (new PeriodToMilliseconds)($period);
        $this->tags = new Tags('os', 'process');
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function __toString(): string
    {
        return "Process halted: {$this->period}ms";
    }
}
