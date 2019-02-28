<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\Status;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\Server\Status\Server\LoadAverage;

final class LoadAverageAccessed implements Activity
{
    private $load;
    private $tags;

    public function __construct(LoadAverage $load)
    {
        $this->load = $load;
        $this->tags = new Tags('os', 'status');
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function __toString(): string
    {
        return \sprintf(
            'Load average: %s, %s, %s',
            $this->load->lastMinute(),
            $this->load->lastFiveMinutes(),
            $this->load->lastFifteenMinutes()
        );
    }
}