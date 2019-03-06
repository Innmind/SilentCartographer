<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\OperatingSystem\Remote\Ssh;

use Innmind\SilentCartographer\{
    SendActivity,
    Room\Program\Activity\Remote\Ssh\ExecutingCommand,
    Room\Program\Activity\Remote\Ssh\ProcessKilled,
};
use Innmind\Server\Control\Server\{
    Processes as ProcessesInterface,
    Process,
    Process\Pid,
    Command,
    Signal,
};
use Innmind\Url\AuthorityInterface;

final class Processes implements ProcessesInterface
{
    private $processes;
    private $authority;
    private $send;

    public function __construct(
        ProcessesInterface $processes,
        AuthorityInterface $authority,
        SendActivity $send
    ) {
        $this->processes = $processes;
        $this->authority = $authority;
        $this->send = $send;
    }

    public function execute(Command $command): Process
    {
        ($this->send)(new ExecutingCommand($this->authority, $command));

        return $this->processes->execute($command);
    }

    public function kill(Pid $pid, Signal $signal): ProcessesInterface
    {
        ($this->send)(new ProcessKilled($this->authority, $pid));
        $this->processes->kill($pid, $signal);

        return $this;
    }
}
