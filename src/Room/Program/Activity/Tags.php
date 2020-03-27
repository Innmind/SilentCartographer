<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity;

final class Tags implements \Iterator
{
    /** @var list<string> */
    private array $tags;

    public function __construct(string ...$tags)
    {
        $this->tags = $tags;
    }

    public function matches(string ...$tags): bool
    {
        foreach ($tags as $tag) {
            if (!\in_array($tag, $this->tags, true)) {
                return false;
            }
        }

        return true;
    }

    public function current(): string
    {
        return \current($this->tags);
    }

    public function key(): int
    {
        return \key($this->tags);
    }

    public function next(): void
    {
        \next($this->tags);
    }

    public function rewind(): void
    {
        \reset($this->tags);
    }

    public function valid(): bool
    {
        return \is_string(\current($this->tags));
    }
}
