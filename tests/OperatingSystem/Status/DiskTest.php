<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\OperatingSystem\Status;

use Innmind\SilentCartographer\{
    OperatingSystem\Status\Disk,
    SendActivity,
    Room\Program\Activity\Status\VolumeUsageAccessed,
};
use Innmind\Server\Status\Server\{
    Disk as DiskInterface,
    Disk\Volume,
    Disk\Volume\MountPoint,
    Disk\Volume\Usage,
    Memory\Bytes,
};
use Innmind\Immutable\Map;
use PHPUnit\Framework\TestCase;

class DiskTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            DiskInterface::class,
            new Disk(
                $this->createMock(DiskInterface::class),
                $this->createMock(SendActivity::class)
            )
        );
    }

    public function testVolumes()
    {
        $disk = new Disk(
            $inner = $this->createMock(DiskInterface::class),
            $send = $this->createMock(SendActivity::class)
        );
        $volume = new Volume(
            new MountPoint('/'),
            new Bytes(42),
            new Bytes(42),
            new Bytes(42),
            new Usage(42)
        );
        $send
            ->expects($this->once())
            ->method('__invoke')
            ->with(new VolumeUsageAccessed($volume->mountPoint()));
        $inner
            ->expects($this->once())
            ->method('volumes')
            ->willReturn(
                $all = Map::of('string', Volume::class)('/', $volume)
            );

        $this->assertSame($all, $disk->volumes());
    }

    public function testGet()
    {
        $disk = new Disk(
            $inner = $this->createMock(DiskInterface::class),
            $send = $this->createMock(SendActivity::class)
        );
        $volume = new Volume(
            new MountPoint('/'),
            new Bytes(42),
            new Bytes(42),
            new Bytes(42),
            new Usage(42)
        );
        $send
            ->expects($this->once())
            ->method('__invoke')
            ->with(new VolumeUsageAccessed($volume->mountPoint()));
        $inner
            ->expects($this->once())
            ->method('get')
            ->with($volume->mountPoint())
            ->willReturn($volume);

        $this->assertSame($volume, $disk->get($volume->mountPoint()));
    }
}
