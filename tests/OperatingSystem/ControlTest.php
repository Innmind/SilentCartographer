<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\OperatingSystem;

use Innmind\SilentCartographer\{
    OperatingSystem\Control,
    SendActivity,
};
use Innmind\Server\Control\Server;
use PHPUnit\Framework\TestCase;

class ControlTest extends TestCase
{
    public function testInterface()
    {
        $server = new Control(
            $this->createMock(Server::class),
            $this->createMock(SendActivity::class)
        );

        $this->assertInstanceOf(Server::class, $server);
        $this->assertInstanceOf(Control\Processes::class, $server->processes());
        $this->assertSame($server->processes(), $server->processes());
        $this->assertInstanceOf(Control\Volumes::class, $server->volumes());
        $this->assertSame($server->volumes(), $server->volumes());
    }

    public function testReboot()
    {
        $server = new Control(
            $inner = $this->createMock(Server::class),
            $send = $this->createMock(SendActivity::class)
        );
        $inner
            ->expects($this->once())
            ->method('reboot');
        $send
            ->expects($this->once())
            ->method('__invoke');

        $this->assertNull($server->reboot());
    }

    public function testShutdown()
    {
        $server = new Control(
            $inner = $this->createMock(Server::class),
            $send = $this->createMock(SendActivity::class)
        );
        $inner
            ->expects($this->once())
            ->method('shutdown');
        $send
            ->expects($this->once())
            ->method('__invoke');

        $this->assertNull($server->shutdown());
    }
}
