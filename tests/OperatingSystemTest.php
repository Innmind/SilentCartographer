<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer;

use Innmind\SilentCartographer\{
    OperatingSystem,
    SendActivity,
};
use Innmind\OperatingSystem\OperatingSystem as OperatingSystemInterface;
use Innmind\TimeContinuum\TimeContinuumInterface;
use PHPUnit\Framework\TestCase;

class OperatingSystemTest extends TestCase
{
    public function testInterface()
    {
        $os = new OperatingSystem(
            $this->createMock(OperatingSystemInterface::class),
            $this->createMock(SendActivity::class)
        );

        $this->assertInstanceOf(OperatingSystemInterface::class, $os);
        $this->assertInstanceOf(TimeContinuumInterface::class, $os->clock());
        $this->assertInstanceOf(OperatingSystem\Filesystem::class, $os->filesystem());
        $this->assertInstanceOf(OperatingSystem\Status::class, $os->status());
        $this->assertInstanceOf(OperatingSystem\Control::class, $os->control());
        $this->assertInstanceOf(OperatingSystem\Ports::class, $os->ports());
        $this->assertInstanceOf(OperatingSystem\Sockets::class, $os->sockets());
        $this->assertInstanceOf(OperatingSystem\Remote::class, $os->remote());
        $this->assertInstanceOf(OperatingSystem\CurrentProcess::class, $os->process());
        $this->assertSame($os->filesystem(), $os->filesystem());
        $this->assertSame($os->status(), $os->status());
        $this->assertSame($os->control(), $os->control());
        $this->assertSame($os->ports(), $os->ports());
        $this->assertSame($os->sockets(), $os->sockets());
        $this->assertSame($os->remote(), $os->remote());
        $this->assertSame($os->process(), $os->process());
    }
}
