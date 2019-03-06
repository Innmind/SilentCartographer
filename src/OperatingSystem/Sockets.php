<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\OperatingSystem;

use Innmind\SilentCartographer\{
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

final class Sockets implements SocketsInterface
{
    private $sockets;
    private $send;

    public function __construct(SocketsInterface $sockets, SendActivity $send)
    {
        $this->sockets = $sockets;
        $this->send = $send;
    }

    /**
     * {@inheritdoc}
     */
    public function open(Unix $address): Server
    {
        ($this->send)(new SocketOpened($address));

        return $this->sockets->open($address);
    }

    /**
     * {@inheritdoc}
     */
    public function takeOver(Unix $address): Server
    {
        ($this->send)(new SocketTakenOver($address));

        return $this->sockets->takeOver($address);
    }

    public function connectTo(Unix $address): Client
    {
        ($this->send)(new ConnectedToSocket($address));

        return $this->sockets->connectTo($address);
    }
}
