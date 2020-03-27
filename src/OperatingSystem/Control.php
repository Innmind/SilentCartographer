<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\OperatingSystem;

use Innmind\SilentCartographer\{
    SendActivity,
    Room\Program\Activity\Control\Reboot,
    Room\Program\Activity\Control\Shutdown,
};
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
    private ?Control\Volumes $volumes = null;

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
        return $this->volumes ??= new Control\Volumes(
            $this->server->volumes(),
            $this->send,
        );
    }

    public function reboot(): void
    {
        ($this->send)(new Reboot);
        $this->server->reboot();
    }

    public function shutdown(): void
    {
        ($this->send)(new Shutdown);
        $this->server->shutdown();
    }
}
