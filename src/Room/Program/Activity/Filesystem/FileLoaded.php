<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\Filesystem;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\Url\Path;

final class FileLoaded implements Activity
{
    private Path $path;
    private Tags $tags;

    public function __construct(Path $path)
    {
        $this->path = $path;
        $this->tags = new Tags('os', 'filesystem');
    }

    public function path(): Path
    {
        return $this->path;
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function toString(): string
    {
        return "File loaded: {$this->path->toString()}";
    }
}
