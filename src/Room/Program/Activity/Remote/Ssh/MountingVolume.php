<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\Remote\Ssh;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\Server\Control\Server\Volumes\Name;
use Innmind\Url\{
    Path,
    Authority,
};

final class MountingVolume implements Activity
{
    private Authority $authority;
    private Name $name;
    private Path $mountpoint;
    private Tags $tags;

    public function __construct(Authority $authority, Name $name, Path $mountpoint)
    {
        $this->authority = $authority;
        $this->name = $name;
        $this->mountpoint = $mountpoint;
        $this->tags = new Tags('os', 'remote', 'ssh', 'control', 'volume');
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function toString(): string
    {
        return "Mounting volume: [{$this->authority->toString()}] {$this->name->toString()}@{$this->mountpoint->toString()}";
    }
}
