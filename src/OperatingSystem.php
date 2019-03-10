<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer;

use Innmind\OperatingSystem\{
    OperatingSystem as OperatingSystemInterface,
    Filesystem,
    Ports,
    Sockets,
    Remote,
    CurrentProcess,
};
use Innmind\Server\Status\Server as ServerStatus;
use Innmind\Server\Control\Server as ServerControl;
use Innmind\TimeContinuum\TimeContinuumInterface;

final class OperatingSystem implements OperatingSystemInterface
{
    private $os;
    private $send;
    private $filesystem;
    private $status;
    private $control;
    private $ports;
    private $sockets;
    private $remote;
    private $process;

    public function __construct(
        OperatingSystemInterface $os,
        SendActivity $send
    ) {
        $this->os = $os;
        $this->send = $send;
    }

    public function clock(): TimeContinuumInterface
    {
        return $this->os->clock();
    }

    public function filesystem(): Filesystem
    {
        return $this->filesystem ?? $this->filesystem = new OperatingSystem\Filesystem(
            $this->os->filesystem(),
            $this->send
        );
    }

    public function status(): ServerStatus
    {
        return $this->status ?? $this->status = new OperatingSystem\Status(
            $this->os->status(),
            $this->send
        );
    }

    public function control(): ServerControl
    {
        return $this->control ?? $this->control = new OperatingSystem\Control(
            $this->os->control(),
            $this->send
        );
    }

    public function ports(): Ports
    {
        return $this->ports ?? $this->ports = new OperatingSystem\Ports(
            $this->os->ports(),
            $this->send
        );
    }

    public function sockets(): Sockets
    {
        return $this->sockets ?? $this->sockets = new OperatingSystem\Sockets(
            $this->os->sockets(),
            $this->send
        );
    }

    public function remote(): Remote
    {
        return $this->remote ?? $this->remote = new OperatingSystem\Remote(
            $this->os->remote(),
            $this->send
        );
    }

    public function process(): CurrentProcess
    {
        return $this->process ?? $this->process = new OperatingSystem\CurrentProcess(
            $this->os->process(),
            $this->send
        );
    }
}
