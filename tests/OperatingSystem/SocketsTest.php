<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\OperatingSystem;

use Innmind\SilentCartographer\{
    OperatingSystem\Sockets,
    SendActivity,
    Room\Program\Activity\Sockets\SocketOpened,
    Room\Program\Activity\Sockets\SocketTakenOver,
    Room\Program\Activity\Sockets\ConnectedToSocket,
};
use Innmind\OperatingSystem\Sockets as SocketsInterface;
use Innmind\Socket\{
    Address\Unix,
    Server,
    Client,
};
use PHPUnit\Framework\TestCase;

class SocketsTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            SocketsInterface::class,
            new Sockets(
                $this->createMock(SocketsInterface::class),
                $this->createMock(SendActivity::class)
            )
        );
    }

    public function testOpen()
    {
        $sockets = new Sockets(
            $inner = $this->createMock(SocketsInterface::class),
            $send = $this->createMock(SendActivity::class)
        );
        $name = Unix::of('/tmp/foo');
        $send
            ->expects($this->once())
            ->method('__invoke')
            ->with(new SocketOpened($name));
        $inner
            ->expects($this->once())
            ->method('open')
            ->with($name)
            ->willReturn($expected = $this->createMock(Server::class));

        $this->assertSame($expected, $sockets->open($name));
    }

    public function testTakeOver()
    {
        $sockets = new Sockets(
            $inner = $this->createMock(SocketsInterface::class),
            $send = $this->createMock(SendActivity::class)
        );
        $name = Unix::of('/tmp/foo');
        $send
            ->expects($this->once())
            ->method('__invoke')
            ->with(new SocketTakenOver($name));
        $inner
            ->expects($this->once())
            ->method('takeOver')
            ->with($name)
            ->willReturn($expected = $this->createMock(Server::class));

        $this->assertSame($expected, $sockets->takeOver($name));
    }

    public function testConnectTo()
    {
        $sockets = new Sockets(
            $inner = $this->createMock(SocketsInterface::class),
            $send = $this->createMock(SendActivity::class)
        );
        $name = Unix::of('/tmp/foo');
        $send
            ->expects($this->once())
            ->method('__invoke')
            ->with(new ConnectedToSocket($name));
        $inner
            ->expects($this->once())
            ->method('connectTo')
            ->with($name)
            ->willReturn($expected = $this->createMock(Client::class));

        $this->assertSame($expected, $sockets->connectTo($name));
    }
}
