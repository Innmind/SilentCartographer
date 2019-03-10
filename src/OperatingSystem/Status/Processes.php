<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\OperatingSystem\Status;

use Innmind\SilentCartographer\{
    SendActivity,
    Room\Program\Activity\Status\ProcessStatusAccessed,
};
use Innmind\Server\Status\Server\{
    Processes as ProcessesInterface,
    Process,
    Process\Pid,
};
use Innmind\Immutable\MapInterface;

final class Processes implements ProcessesInterface
{
    private $processes;
    private $send;

    public function __construct(ProcessesInterface $processes, SendActivity $send)
    {
        $this->processes = $processes;
        $this->send = $send;
    }

    /**
     * {@inheritdoc}
     */
    public function all(): MapInterface
    {
        return $this->processes->all()->foreach(function(int $pid, Process $process): void {
            ($this->send)(new ProcessStatusAccessed($process->pid()));
        });
    }

    public function get(Pid $pid): Process
    {
        ($this->send)(new ProcessStatusAccessed($pid));

        return $this->processes->get($pid);
    }
}
