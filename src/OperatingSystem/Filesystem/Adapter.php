<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\OperatingSystem\Filesystem;

use Innmind\SilentCartographer\{
    SendActivity,
    Room\Program\Activity\Filesystem\FilePersisted,
    Room\Program\Activity\Filesystem\FileLoaded,
    Room\Program\Activity\Filesystem\FileRemoved,
};
use Innmind\Filesystem\{
    Adapter as AdapterInterface,
    File,
    Name,
};
use Innmind\Url\Path;
use Innmind\Immutable\Set;

final class Adapter implements AdapterInterface
{
    private AdapterInterface $adapter;
    private SendActivity $send;
    private string $path;

    public function __construct(
        AdapterInterface $adapter,
        SendActivity $send,
        Path $path
    ) {
        $this->adapter = $adapter;
        $this->send = $send;
        $this->path = \rtrim($path->toString(), '/');
    }

    public function add(File $file): void
    {
        ($this->send)(new FilePersisted($this->path($file->name())));
        $this->adapter->add($file);
    }

    /**
     * {@inheritdoc}
     */
    public function get(Name $file): File
    {
        ($this->send)(new FileLoaded($this->path($file)));

        return $this->adapter->get($file);
    }

    public function contains(Name $file): bool
    {
        return $this->adapter->contains($file);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(Name $file): void
    {
        ($this->send)(new FileRemoved($this->path($file)));
        $this->adapter->remove($file);
    }

    /**
     * {@inheritdoc}
     */
    public function all(): Set
    {
        $all = $this->adapter->all();
        $all->foreach(function(File $file): void {
            ($this->send)(new FileLoaded($this->path($file->name())));
        });

        return $all;
    }

    private function path(Name $file): Path
    {
        return Path::of($this->path.'/'.$file->toString());
    }
}
