<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\OperatingSystem\Remote;

use Innmind\SilentCartographer\{
    SendActivity,
    Room\Program\Activity\Remote\Ssh\Reboot,
    Room\Program\Activity\Remote\Ssh\Shutdown,
};
use Innmind\Server\Control\{
    Server,
    Server\Processes,
    Server\Volumes,
};
use Innmind\Url\Authority;

final class Ssh implements Server
{
    private Server $server;
    private Authority $authority;
    private SendActivity $send;
    private ?Ssh\Processes $processes = null;
    private ?Ssh\Volumes $volumes = null;

    public function __construct(
        Server $server,
        Authority $authority,
        SendActivity $send
    ) {
        $this->server = $server;
        $this->authority = $authority;
        $this->send = $send;
    }

    public function processes(): Processes
    {
        return $this->processes ??= new Ssh\Processes(
            $this->server->processes(),
            $this->authority,
            $this->send
        );
    }

    public function volumes(): Volumes
    {
        return $this->volumes ??= new Ssh\Volumes(
            $this->server->volumes(),
            $this->authority,
            $this->send,
        );
    }

    public function reboot(): void
    {
        ($this->send)(new Reboot($this->authority));
        $this->server->reboot();
    }

    public function shutdown(): void
    {
        ($this->send)(new Shutdown($this->authority));
        $this->server->shutdown();
    }
}
