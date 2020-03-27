<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\Control;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\Server\Control\Server\Volumes\Name;
use Innmind\Url\Path;

final class MountingVolume implements Activity
{
    private Name $name;
    private Path $mountpoint;
    private Tags $tags;

    public function __construct(Name $name, Path $mountpoint)
    {
        $this->name = $name;
        $this->mountpoint = $mountpoint;
        $this->tags = new Tags('os', 'control', 'volume');
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function toString(): string
    {
        return "Mounting volume: {$this->name->toString()}@{$this->mountpoint->toString()}";
    }
}
