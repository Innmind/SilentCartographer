<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\Remote\Ssh;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\Url\Authority;

final class Reboot implements Activity
{
    private Authority $authority;
    private Tags $tags;

    public function __construct(Authority $authority)
    {
        $this->authority = $authority;
        $this->tags = new Tags('os', 'remote', 'ssh', 'control');
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function toString(): string
    {
        return "Reboot: {$this->authority->toString()}";
    }
}
