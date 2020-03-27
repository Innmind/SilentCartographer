<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\OperatingSystem;

use Innmind\SilentCartographer\{
    OperatingSystem\Remote,
    SendActivity,
    Room\Program\Activity\Remote\Sockets\SocketOpened,
};
use Innmind\OperatingSystem\Remote as RemoteInterface;
use Innmind\Socket\{
    Client,
    Internet\Transport,
};
use Innmind\Url\{
    Url,
    Authority,
};
use PHPUnit\Framework\TestCase;

class RemoteTest extends TestCase
{
    public function testInterface()
    {
        $remote = new Remote(
            $this->createMock(RemoteInterface::class),
            $this->createMock(SendActivity::class)
        );

        $this->assertInstanceOf(RemoteInterface::class, $remote);
        $this->assertInstanceOf(
            Remote\Ssh::class,
            $remote->ssh(Url::of('ssh://example.com'))
        );
        $this->assertInstanceOf(Remote\Http::class, $remote->http());
        $this->assertSame($remote->http(), $remote->http());
    }

    public function testSocket()
    {
        $remote = new Remote(
            $inner = $this->createMock(RemoteInterface::class),
            $send = $this->createMock(SendActivity::class)
        );
        $transport = Transport::tcp();
        $authority = Authority::none();
        $inner
            ->expects($this->once())
            ->method('socket')
            ->with($transport, $authority)
            ->willReturn($client = $this->createMock(Client::class));
        $send
            ->expects($this->once())
            ->method('__invoke')
            ->with(new SocketOpened($transport, $authority));

        $this->assertSame($client, $remote->socket($transport, $authority));
    }
}
