<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\Control;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};

final class Shutdown implements Activity
{
    private Tags $tags;

    public function __construct()
    {
        $this->tags = new Tags('os', 'control');
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function toString(): string
    {
        return 'Shutdown';
    }
}
