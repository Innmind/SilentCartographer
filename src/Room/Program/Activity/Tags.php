<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity;

use Innmind\Immutable\Stream;

final class Tags implements \Iterator
{
    private Stream $tags;

    public function __construct(string ...$tags)
    {
        $this->tags = Stream::of('string', ...$tags);
    }

    public function matches(string ...$tags): bool
    {
        foreach ($tags as $tag) {
            if (!$this->tags->contains($tag)) {
                return false;
            }
        }

        return true;
    }

    public function current(): string
    {
        return $this->tags->current();
    }

    public function key(): int
    {
        return $this->tags->key();
    }

    public function next(): void
    {
        $this->tags->next();
    }

    public function rewind(): void
    {
        $this->tags->rewind();
    }

    public function valid(): bool
    {
        return $this->tags->valid();
    }
}
