<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\OperatingSystem;

use Innmind\SilentCartographer\{
    SendActivity,
    Room\Program\Activity\Ports\PortOpened,
};
use Innmind\OperatingSystem\Ports as PortsInterface;
use Innmind\Url\Authority\PortInterface;
use Innmind\Socket\{
    Internet\Transport,
    Server,
};
use Innmind\IP\IP;

final class Ports implements PortsInterface
{
    private $ports;
    private $send;

    public function __construct(PortsInterface $ports, SendActivity $send)
    {
        $this->ports = $ports;
        $this->send = $send;
    }

    public function open(
        Transport $transport,
        IP $ip,
        PortInterface $port
    ): Server {
        ($this->send)(new PortOpened($transport, $ip, $port));

        return $this->ports->open($transport, $ip, $port);
    }
}
