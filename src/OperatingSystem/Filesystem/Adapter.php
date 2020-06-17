<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\OperatingSystem\Filesystem;

use Innmind\SilentCartographer\{
    SendActivity,
    Room\Program\Activity\Filesystem\FilePersisted,
    Room\Program\Activity\Filesystem\FileRemoved,
};
use Innmind\Filesystem\{
    Adapter as AdapterInterface,
    File as FileInterface,
    Directory as DirectoryInterface,
    Name,
};
use Innmind\Url\Path;
use Innmind\Immutable\Set;

final class Adapter implements AdapterInterface
{
    private AdapterInterface $adapter;
    private SendActivity $send;
    private Path $path;

    public function __construct(
        AdapterInterface $adapter,
        SendActivity $send,
        Path $path
    ) {
        $this->adapter = $adapter;
        $this->send = $send;
        $this->path = $path;
    }

    public function add(FileInterface $file): void
    {
        ($this->send)(new FilePersisted($this->path($file->name())));
        $this->adapter->add($file);
    }

    /**
     * {@inheritdoc}
     */
    public function get(Name $file): FileInterface
    {
        return $this->wrap($this->adapter->get($file));
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
        return $this
            ->adapter
            ->all()
            ->map(fn(FileInterface $file): FileInterface => $this->wrap($file));
    }

    private function wrap(FileInterface $file): FileInterface
    {
        if ($file instanceof DirectoryInterface) {
            return Directory::load(
                $file,
                $this->send,
                $this->path,
            );
        }

        return new File($file, $this->send, $this->path);
    }

    private function path(Name $file): Path
    {
        return $this->path->resolve(Path::of($file->toString()));
    }
}
