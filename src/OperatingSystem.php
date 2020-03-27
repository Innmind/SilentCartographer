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
use Innmind\TimeContinuum\Clock;

final class OperatingSystem implements OperatingSystemInterface
{
    private OperatingSystemInterface $os;
    private SendActivity $send;
    private ?OperatingSystem\Filesystem $filesystem = null;
    private ?OperatingSystem\Status $status = null;
    private ?OperatingSystem\Control $control = null;
    private ?OperatingSystem\Ports $ports = null;
    private ?OperatingSystem\Sockets $sockets = null;
    private ?OperatingSystem\Remote $remote = null;
    private ?OperatingSystem\CurrentProcess $process = null;

    public function __construct(
        OperatingSystemInterface $os,
        SendActivity $send
    ) {
        $this->os = $os;
        $this->send = $send;
    }

    public function clock(): Clock
    {
        return $this->os->clock();
    }

    public function filesystem(): Filesystem
    {
        return $this->filesystem ??= new OperatingSystem\Filesystem(
            $this->os->filesystem(),
            $this->send
        );
    }

    public function status(): ServerStatus
    {
        return $this->status ??= new OperatingSystem\Status(
            $this->os->status(),
            $this->send
        );
    }

    public function control(): ServerControl
    {
        return $this->control ??= new OperatingSystem\Control(
            $this->os->control(),
            $this->send
        );
    }

    public function ports(): Ports
    {
        return $this->ports ??= new OperatingSystem\Ports(
            $this->os->ports(),
            $this->send
        );
    }

    public function sockets(): Sockets
    {
        return $this->sockets ??= new OperatingSystem\Sockets(
            $this->os->sockets(),
            $this->send
        );
    }

    public function remote(): Remote
    {
        return $this->remote ??= new OperatingSystem\Remote(
            $this->os->remote(),
            $this->send
        );
    }

    public function process(): CurrentProcess
    {
        return $this->process ??= new OperatingSystem\CurrentProcess(
            $this->os->process(),
            $this->send
        );
    }
}
