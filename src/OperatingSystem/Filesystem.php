<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\OperatingSystem;

use Innmind\SilentCartographer\{
    SendActivity,
    Room\Program\Activity\Filesystem\PathMounted,
};
use Innmind\OperatingSystem\Filesystem as FilesystemInterface;
use Innmind\Filesystem\Adapter;
use Innmind\FileWatch\Ping;
use Innmind\Url\Path;

final class Filesystem implements FilesystemInterface
{
    private FilesystemInterface $filesystem;
    private SendActivity $send;

    public function __construct(FilesystemInterface $filesystem, SendActivity $send)
    {
        $this->filesystem = $filesystem;
        $this->send = $send;
    }

    public function mount(Path $path): Adapter
    {
        ($this->send)(new PathMounted($path));

        return new Filesystem\Adapter(
            $this->filesystem->mount($path),
            $this->send,
            $path,
        );
    }

    public function contains(Path $path): bool
    {
        return $this->filesystem->contains($path);
    }

    public function watch(Path $path): Ping
    {
        return $this->filesystem->watch($path);
    }
}
