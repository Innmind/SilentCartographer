<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\OperatingSystem\Remote\Ssh;

use Innmind\SilentCartographer\{
    OperatingSystem\Remote\Ssh\Processes,
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
use Innmind\Url\Authority;
use PHPUnit\Framework\TestCase;

class ProcessesTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            ProcessesInterface::class,
            new Processes(
                $this->createMock(ProcessesInterface::class),
                Authority::none(),
                $this->createMock(SendActivity::class)
            )
        );
    }

    public function testExecute()
    {
        $processes = new Processes(
            $inner = $this->createMock(ProcessesInterface::class),
            $authority = Authority::none(),
            $send = $this->createMock(SendActivity::class)
        );
        $command = Command::foreground('php');
        $send
            ->expects($this->once())
            ->method('__invoke')
            ->with(new ExecutingCommand($authority, $command));
        $inner
            ->expects($this->once())
            ->method('execute')
            ->with($command)
            ->willReturn($process = $this->createMock(Process::class));

        $this->assertSame($process, $processes->execute($command));
    }

    public function testKill()
    {
        $processes = new Processes(
            $inner = $this->createMock(ProcessesInterface::class),
            $authority = Authority::none(),
            $send = $this->createMock(SendActivity::class)
        );
        $pid = new Pid(42);
        $send
            ->expects($this->once())
            ->method('__invoke')
            ->with(new ProcessKilled($authority, $pid));
        $inner
            ->expects($this->once())
            ->method('kill')
            ->with($pid, Signal::kill());

        $this->assertNull($processes->kill($pid, Signal::kill()));
    }
}
