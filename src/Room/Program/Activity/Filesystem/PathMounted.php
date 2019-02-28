<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\Filesystem;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\Url\PathInterface;

final class PathMounted implements Activity
{
    private $path;
    private $tags;

    public function __construct(PathInterface $path)
    {
        $this->path = $path;
        $this->tags = new Tags('os', 'filesystem');
    }

    public function path(): PathInterface
    {
        return $this->path;
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function __toString(): string
    {
        return "Path mounted: {$this->path}";
    }
}
