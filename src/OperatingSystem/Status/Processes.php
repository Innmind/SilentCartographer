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
use Innmind\Immutable\Map;

final class Processes implements ProcessesInterface
{
    private ProcessesInterface $processes;
    private SendActivity $send;

    public function __construct(ProcessesInterface $processes, SendActivity $send)
    {
        $this->processes = $processes;
        $this->send = $send;
    }

    public function all(): Map
    {
        $processes =  $this->processes->all();
        $processes->foreach(function(int $pid, Process $process): void {
            ($this->send)(new ProcessStatusAccessed($process->pid()));
        });

        return $processes;
    }

    public function get(Pid $pid): Process
    {
        ($this->send)(new ProcessStatusAccessed($pid));

        return $this->processes->get($pid);
    }
}
