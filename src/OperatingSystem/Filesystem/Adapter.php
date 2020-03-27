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
};
use Innmind\Url\{
    PathInterface,
    Path,
};
use Innmind\Immutable\MapInterface;

final class Adapter implements AdapterInterface
{
    private AdapterInterface $adapter;
    private SendActivity $send;
    private string $path;

    public function __construct(
        AdapterInterface $adapter,
        SendActivity $send,
        PathInterface $path
    ) {
        $this->adapter = $adapter;
        $this->send = $send;
        $this->path = \rtrim((string) $path, '/');
    }

    public function add(File $file): AdapterInterface
    {
        ($this->send)(new FilePersisted($this->path((string) $file->name())));
        $this->adapter->add($file);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $file): File
    {
        ($this->send)(new FileLoaded($this->path($file)));

        return $this->adapter->get($file);
    }

    public function has(string $file): bool
    {
        return $this->adapter->has($file);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $file): AdapterInterface
    {
        ($this->send)(new FileRemoved($this->path($file)));
        $this->adapter->remove($file);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function all(): MapInterface
    {
        return $this->adapter->all()->foreach(function(string $name): void {
            ($this->send)(new FileLoaded($this->path($name)));
        });
    }

    private function path(string $file): PathInterface
    {
        return new Path($this->path.'/'.$file);
    }
}
