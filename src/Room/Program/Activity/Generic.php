<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity;

use Innmind\SilentCartographer\Room\Program\Activity;

final class Generic implements Activity
{
    private Tags $tags;
    private string $message;

    public function __construct(Tags $tags, string $message)
    {
        $this->tags = $tags;
        $this->message = $message;
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function toString(): string
    {
        return $this->message;
    }
}
