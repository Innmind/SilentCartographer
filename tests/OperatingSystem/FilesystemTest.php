<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\OperatingSystem;

use Innmind\SilentCartographer\{
    OperatingSystem\Filesystem,
    SendActivity,
};
use Innmind\OperatingSystem\Filesystem as FilesystemInterface;
use Innmind\Url\PathInterface;
use PHPUnit\Framework\TestCase;

class FilesystemTest extends TestCase
{
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
            $this->createMock(SendActivity::class)
        );

        $this->assertInstanceOf(
            Filesystem\Adapter::class,
            $filesystem->mount($this->createMock(PathInterface::class))
        );
    }
}
