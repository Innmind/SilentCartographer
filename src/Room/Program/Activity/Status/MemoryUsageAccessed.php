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
    private Memory $memory;
    private Tags $tags;

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
            $this->memory->total()->toString(),
            $this->memory->wired()->toString(),
            $this->memory->active()->toString(),
            $this->memory->free()->toString(),
            $this->memory->swap()->toString(),
            $this->memory->used()->toString(),
        );
    }
}
