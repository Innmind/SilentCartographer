<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\OperatingSystem;

use Innmind\SilentCartographer\SendActivity;
use Innmind\Server\Control\{
    Server,
    Server\Processes,
};

final class Control implements Server
{
    private $server;
    private $send;
    private $processes;

    public function __construct(Server $server, SendActivity $send)
    {
        $this->server = $server;
        $this->send = $send;
    }

    public function processes(): Processes
    {
        return $this->processes ?? $this->processes = new Control\Processes(
            $this->server->processes(),
            $this->send
        );
    }
}
