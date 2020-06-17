<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\OperatingSystem\Filesystem;

use Innmind\SilentCartographer\{
    SendActivity,
    Room\Program\Activity\Filesystem\FileLoaded,
};
use Innmind\Filesystem\{
    File as FileInterface,
    Name,
    Source,
    Adapter,
};
use Innmind\Stream\Readable;
use Innmind\MediaType\MediaType;
use Innmind\Url\Path;

final class File implements FileInterface, Source
{
    private FileInterface $file;

    public function __construct(
        FileInterface $file,
        SendActivity $send,
        Path $folder
    ) {
        $this->file = $file;
        $send(new FileLoaded($folder->resolve(Path::of($file->name()->toString()))));
    }

    public function sourcedAt(Adapter $adapter, Path $path): bool
    {
        if (!$this->file instanceof Source) {
            return false;
        }

        return $this->file->sourcedAt($adapter, $path);
    }

    public function name(): Name
    {
        return $this->file->name();
    }

    public function content(): Readable
    {
        return $this->file->content();
    }

    public function mediaType(): MediaType
    {
        return $this->file->mediaType();
    }
}
