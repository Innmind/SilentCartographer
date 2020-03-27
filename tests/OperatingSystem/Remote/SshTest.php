<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\OperatingSystem\Remote;

use Innmind\SilentCartographer\{
    OperatingSystem\Remote\Ssh,
    SendActivity,
};
use Innmind\Server\Control\Server;
use Innmind\Url\Authority;
use PHPUnit\Framework\TestCase;

class SshTest extends TestCase
{
    public function testInterface()
    {
        $server = new Ssh(
            $this->createMock(Server::class),
            Authority::none(),
            $this->createMock(SendActivity::class)
        );

        $this->assertInstanceOf(Server::class, $server);
        $this->assertInstanceOf(Ssh\Processes::class, $server->processes());
        $this->assertSame($server->processes(), $server->processes());
        $this->assertInstanceOf(Ssh\Volumes::class, $server->volumes());
        $this->assertSame($server->volumes(), $server->volumes());
    }

    public function testReboot()
    {
        $server = new Ssh(
            $inner = $this->createMock(Server::class),
            Authority::none(),
            $send = $this->createMock(SendActivity::class),
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
        $server = new Ssh(
            $inner = $this->createMock(Server::class),
            Authority::none(),
            $send = $this->createMock(SendActivity::class),
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
