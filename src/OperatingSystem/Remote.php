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
    UrlInterface,
    AuthorityInterface,
};
use Innmind\HttpTransport\Transport as HttpTransport;

final class Remote implements RemoteInterface
{
    private $remote;
    private $send;
    private $http;

    public function __construct(RemoteInterface $remote, SendActivity $send)
    {
        $this->remote = $remote;
        $this->send = $send;
    }

    public function ssh(UrlInterface $server): Server
    {
        return new Remote\Ssh(
            $this->remote->ssh($server),
            $server->authority(),
            $this->send
        );
    }

    public function socket(Transport $transport, AuthorityInterface $authority): Client
    {
        ($this->send)(new SocketOpened($transport, $authority));

        return $this->remote->socket($transport, $authority);
    }

    public function http(): HttpTransport
    {
        return $this->http ?? $this->http = new Remote\Http(
            $this->remote->http(),
            $this->send
        );
    }
}
