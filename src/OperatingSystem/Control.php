<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\OperatingSystem;

use Innmind\SilentCartographer\SendActivity;
use Innmind\Server\Control\{
    Server,
    Server\Processes,
    Server\Volumes,
};

final class Control implements Server
{
    private Server $server;
    private SendActivity $send;
    private ?Control\Processes $processes = null;

    public function __construct(Server $server, SendActivity $send)
    {
        $this->server = $server;
        $this->send = $send;
    }

    public function processes(): Processes
    {
        return $this->processes ??= new Control\Processes(
            $this->server->processes(),
            $this->send,
        );
    }

    public function volumes(): Volumes
    {
        return $this->server->volumes();
    }

    public function reboot(): void
    {
        $this->server->reboot();
    }

    public function shutdown(): void
    {
        $this->server->shutdown();
    }
}
