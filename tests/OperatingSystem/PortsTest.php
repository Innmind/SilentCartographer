<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\OperatingSystem;

use Innmind\SilentCartographer\{
    OperatingSystem\Ports,
    SendActivity,
    Room\Program\Activity\Ports\PortOpened,
};
use Innmind\OperatingSystem\Ports as PortsInterface;
use Innmind\Socket\{
    Internet\Transport,
    Server,
};
use Innmind\IP\IP;
use Innmind\Url\Authority\PortInterface;
use PHPUnit\Framework\TestCase;

class PortsTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            PortsInterface::class,
            new Ports(
                $this->createMock(PortsInterface::class),
                $this->createMock(SendActivity::class)
            )
        );
    }

    public function testOpen()
    {
        $ports = new Ports(
            $inner = $this->createMock(PortsInterface::class),
            $send = $this->createMock(SendActivity::class)
        );
        $transport = Transport::tcp();
        $ip = $this->createMock(IP::class);
        $port = $this->createMock(PortInterface::class);
        $inner
            ->expects($this->once())
            ->method('open')
            ->with($transport, $ip, $port)
            ->willReturn($server = $this->createMock(Server::class));
        $send
            ->expects($this->once())
            ->method('__invoke')
            ->with(new PortOpened($transport, $ip, $port));

        $this->assertSame($server, $ports->open($transport, $ip, $port));
    }
}
