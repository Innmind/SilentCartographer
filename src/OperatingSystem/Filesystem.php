<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\OperatingSystem;

use Innmind\SilentCartographer\{
    SendActivity,
    Room\Program\Activity\Filesystem\PathMounted,
};
use Innmind\OperatingSystem\Filesystem as FilesystemInterface;
use Innmind\Filesystem\Adapter;
use Innmind\Url\PathInterface;

final class Filesystem implements FilesystemInterface
{
    private $filesystem;
    private $send;

    public function __construct(FilesystemInterface $filesystem, SendActivity $send)
    {
        $this->filesystem = $filesystem;
        $this->send = $send;
    }

    public function mount(PathInterface $path): Adapter
    {
        ($this->send)(new PathMounted($path));

        return new Filesystem\Adapter(
            $this->filesystem->mount($path),
            $this->send,
            $path
        );
    }
}
