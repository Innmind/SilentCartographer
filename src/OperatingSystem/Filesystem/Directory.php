<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\OperatingSystem\Filesystem;

use Innmind\SilentCartographer\{
    SendActivity,
    Room\Program\Activity\Filesystem\FileLoaded,
};
use Innmind\Filesystem\{
    Directory as DirectoryInterface,
    File as FileInterface,
    Name,
    Source,
    Adapter,
};
use Innmind\Stream\Readable;
use Innmind\MediaType\MediaType;
use Innmind\Url\Path;
use Innmind\Immutable\{
    Sequence,
    Set,
};

final class Directory implements DirectoryInterface, Source
{
    private DirectoryInterface $directory;
    private SendActivity $send;
    private Path $path;

    private function __construct(
        DirectoryInterface $directory,
        SendActivity $send,
        Path $path
    ) {
        $this->directory = $directory;
        $this->send = $send;
        $this->path = $path;
    }

    public static function load(
        DirectoryInterface $directory,
        SendActivity $send,
        Path $parent
    ): self {
        $path = $parent->resolve(Path::of($directory->name()->toString().'/'));
        $self = new self($directory, $send, $path);
        $send(new FileLoaded($path));

        return $self;
    }

    public function sourcedAt(Adapter $adapter, Path $path): bool
    {
        if (!$this->directory instanceof Source) {
            return false;
        }

        return $this->directory->sourcedAt($adapter, $path);
    }

    public function name(): Name
    {
        return $this->directory->name();
    }

    public function content(): Readable
    {
        return $this->directory->content();
    }

    public function mediaType(): MediaType
    {
        return $this->directory->mediaType();
    }

    public function add(FileInterface $file): DirectoryInterface
    {
        return new UnsourcedDirectory(
            $this->directory->add($file),
            $this->send,
            $this->path,
        );
    }

    public function get(Name $name): FileInterface
    {
        return $this->wrap($this->directory->get($name));
    }

    public function contains(Name $name): bool
    {
        return $this->directory->contains($name);
    }

    public function remove(Name $name): DirectoryInterface
    {
        $directory = $this->directory->remove($name);

        if ($directory === $this->directory) {
            return $this;
        }

        return new UnsourcedDirectory(
            $this->directory->remove($name),
            $this->send,
            $this->path,
        );
    }

    public function replaceAt(Path $path, FileInterface $file): DirectoryInterface
    {
        return new UnsourcedDirectory(
            $this->directory->replaceAt($path, $file),
            $this->send,
            $this->path,
        );
    }

    public function foreach(callable $function): void
    {
        $this->directory->foreach(function(FileInterface $file) use ($function): void {
            $function($this->wrap($file));
        });
    }

    public function filter(callable $predicate): Set
    {
        return $this->directory->filter(function(FileInterface $file) use ($predicate): bool {
            return $predicate($this->wrap($file));
        });
    }

    public function reduce($carry, callable $reducer)
    {
        /** @psalm-suppress MissingClosureParamType */
        return $this->directory->reduce(
            $carry,
            function($carry, FileInterface $file) use ($reducer) {
                /** @psalm-suppress MixedArgument */
                return $reducer($carry, $this->wrap($file));
            },
        );
    }

    public function modifications(): Sequence
    {
        return $this->directory->modifications();
    }

    private function wrap(FileInterface $file): FileInterface
    {
        if ($file instanceof DirectoryInterface) {
            return self::load(
                $file,
                $this->send,
                $this->path,
            );
        }

        return new File($file, $this->send, $this->path);
    }
}
