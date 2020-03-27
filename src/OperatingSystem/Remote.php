<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\OperatingSystem;

use Innmind\SilentCartographer\{
    SendActivity,
    Room\Program\Activity\Remote\Sockets\SocketOpened,
};
use Innmind\OperatingSystem\Remote as RemoteInterface;
use Innmind\Server\Control\Server;
use Innmind\Socket\{
    Internet\Transport,
    Client,
};
use Innmind\Url\{
    Url,
    Authority,
};
use Innmind\HttpTransport\Transport as HttpTransport;

final class Remote implements RemoteInterface
{
    private RemoteInterface $remote;
    private SendActivity $send;
    private ?Remote\Http $http = null;

    public function __construct(RemoteInterface $remote, SendActivity $send)
    {
        $this->remote = $remote;
        $this->send = $send;
    }

    public function ssh(Url $server): Server
    {
        return new Remote\Ssh(
            $this->remote->ssh($server),
            $server->authority(),
            $this->send
        );
    }

    public function socket(Transport $transport, Authority $authority): Client
    {
        ($this->send)(new SocketOpened($transport, $authority));

        return $this->remote->socket($transport, $authority);
    }

    public function http(): HttpTransport
    {
        return $this->http ??= new Remote\Http(
            $this->remote->http(),
            $this->send
        );
    }
}
