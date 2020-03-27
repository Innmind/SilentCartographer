<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\OperatingSystem;

use Innmind\SilentCartographer\{
    OperatingSystem\Filesystem,
    SendActivity,
};
use Innmind\OperatingSystem\Filesystem as FilesystemInterface;
use Innmind\FileWatch\Ping;
use Innmind\Url\Path;
use PHPUnit\Framework\TestCase;
use Fixtures\Innmind\Url;
use Innmind\BlackBox\{
    PHPUnit\BlackBox,
    Set,
};

class FilesystemTest extends TestCase
{
    use BlackBox;

    public function testInterface()
    {
        $this->assertInstanceOf(
            FilesystemInterface::class,
            new Filesystem(
                $this->createMock(FilesystemInterface::class),
                $this->createMock(SendActivity::class)
            )
        );
    }

    public function testMount()
    {
        $filesystem = new Filesystem(
            $this->createMock(FilesystemInterface::class),
            $send = $this->createMock(SendActivity::class)
        );
        $send
            ->expects($this->once())
            ->method('__invoke');

        $this->assertInstanceOf(
            Filesystem\Adapter::class,
            $filesystem->mount(Path::none())
        );
    }

    public function testContains()
    {
        $this
            ->forAll(
                Set\Elements::of(true, false),
                Url\Path::any(),
            )
            ->then(function($exist, $path) {
                $filesystem = new Filesystem(
                    $inner = $this->createMock(FilesystemInterface::class),
                    $send = $this->createMock(SendActivity::class)
                );
                $send
                    ->expects($this->never())
                    ->method('__invoke');
                $inner
                    ->method('contains')
                    ->with($path)
                    ->willReturn($exist);

                $this->assertSame($exist, $filesystem->contains($path));
            });
    }

    public function testWatch()
    {
        $filesystem = new Filesystem(
            $this->createMock(FilesystemInterface::class),
            $send = $this->createMock(SendActivity::class)
        );
        $send
            ->expects($this->once())
            ->method('__invoke');

        $this->assertInstanceOf(
            Ping::class,
            $filesystem->watch(Path::none())
        );
    }
}
