<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\Status;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\Server\Status\Server\Memory;

final class MemoryUsageAccessed implements Activity
{
    private $memory;
    private $tags;

    public function __construct(Memory $memory)
    {
        $this->memory = $memory;
        $this->tags = new Tags('os', 'status');
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function __toString(): string
    {
        return \sprintf(
            'Memory usage: total(%s) wired(%s) active(%s) free(%s) swap(%s) used(%s)',
            $this->memory->total(),
            $this->memory->wired(),
            $this->memory->active(),
            $this->memory->free(),
            $this->memory->swap(),
            $this->memory->used()
        );
    }
}
