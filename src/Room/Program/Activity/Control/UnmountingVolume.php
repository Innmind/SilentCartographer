<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\Control;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\Server\Control\Server\Volumes\Name;

final class UnmountingVolume implements Activity
{
    private Name $name;
    private Tags $tags;

    public function __construct(Name $name)
    {
        $this->name = $name;
        $this->tags = new Tags('os', 'control', 'volume');
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function toString(): string
    {
        return "Unmounting volume: {$this->name->toString()}";
    }
}
