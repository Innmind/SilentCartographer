<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\Remote\Ssh;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\Server\Control\Server\Volumes\Name;
use Innmind\Url\Authority;

final class UnmountingVolume implements Activity
{
    private Authority $authority;
    private Name $name;
    private Tags $tags;

    public function __construct(Authority $authority, Name $name)
    {
        $this->authority = $authority;
        $this->name = $name;
        $this->tags = new Tags('os', 'remote', 'ssh', 'control', 'volume');
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function toString(): string
    {
        return "Unmounting volume: [{$this->authority->toString()}] {$this->name->toString()}";
    }
}
