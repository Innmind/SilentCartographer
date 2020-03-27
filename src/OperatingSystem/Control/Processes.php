<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\OperatingSystem\Control;

use Innmind\SilentCartographer\{
    SendActivity,
    Room\Program\Activity\Control\ExecutingCommand,
    Room\Program\Activity\Control\ProcessKilled,
};
use Innmind\Server\Control\Server\{
    Processes as ProcessesInterface,
    Process,
    Process\Pid,
    Command,
    Signal,
};

final class Processes implements ProcessesInterface
{
    private ProcessesInterface $processes;
    private SendActivity $send;

    public function __construct(ProcessesInterface $processes, SendActivity $send)
    {
        $this->processes = $processes;
        $this->send = $send;
    }

    public function execute(Command $command): Process
    {
        ($this->send)(new ExecutingCommand($command));

        return $this->processes->execute($command);
    }

    public function kill(Pid $pid, Signal $signal): ProcessesInterface
    {
        ($this->send)(new ProcessKilled($pid));
        $this->processes->kill($pid, $signal);

        return $this;
    }
}
