<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\Ports;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\Socket\Internet\Transport;
use Innmind\IP\IP;
use Innmind\Url\Authority\Port;

final class PortOpened implements Activity
{
    private Transport $transport;
    private IP $ip;
    private Port $port;
    private Tags $tags;

    public function __construct(
        Transport $transport,
        IP $ip,
        Port $port
    ) {
        $this->transport = $transport;
        $this->ip = $ip;
        $this->port = $port;
        $this->tags = new Tags('os', 'socket', 'port');
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function __toString(): string
    {
        return "Port opened: {$this->transport->toString()}://{$this->ip->toString()}:{$this->port->toString()}";
    }
}
