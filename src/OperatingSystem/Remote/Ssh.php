<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\OperatingSystem\Remote;

use Innmind\SilentCartographer\SendActivity;
use Innmind\Server\Control\{
    Server,
    Server\Processes,
};
use Innmind\Url\AuthorityInterface;

final class Ssh implements Server
{
    private $server;
    private $authority;
    private $send;
    private $processes;

    public function __construct(
        Server $server,
        AuthorityInterface $authority,
        SendActivity $send
    ) {
        $this->server = $server;
        $this->authority = $authority;
        $this->send = $send;
    }

    public function processes(): Processes
    {
        return $this->processes ?? $this->processes = new Ssh\Processes(
            $this->server->processes(),
            $this->authority,
            $this->send
        );
    }
}
