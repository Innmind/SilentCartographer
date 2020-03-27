<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity;

final class Tags
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

    /**
     * @return list<string>
     */
    public function list(): array
    {
        return $this->tags;
    }
}
