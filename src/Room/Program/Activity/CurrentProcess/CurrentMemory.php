<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\CurrentProcess;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\Server\Status\Server\Memory\Bytes;

final class CurrentMemory implements Activity
{
    private Bytes $memory;
    private Tags $tags;

    public function __construct(Bytes $memory)
    {
        $this->memory = $memory;
        $this->tags = new Tags('os', 'process');
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function toString(): string
    {
        return "Process memory: {$this->memory->toString()}";
    }
}
