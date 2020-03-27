<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\OperatingSystem\Status;

use Innmind\SilentCartographer\{
    OperatingSystem\Status\Processes,
    SendActivity,
    Room\Program\Activity\Status\ProcessStatusAccessed,
};
use Innmind\Server\Status\Server\{
    Processes as ProcessesInterface,
    Process,
    Process\Pid,
    Process\User,
    Process\Memory,
    Process\Command,
    Cpu\Percentage,
};
use Innmind\TimeContinuum\PointInTime;
use Innmind\Immutable\Map;
use PHPUnit\Framework\TestCase;

class ProcessesTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            ProcessesInterface::class,
            new Processes(
                $this->createMock(ProcessesInterface::class),
                $this->createMock(SendActivity::class)
            )
        );
    }

    public function testAll()
    {
        $processes = new Processes(
            $inner = $this->createMock(ProcessesInterface::class),
            $send = $this->createMock(SendActivity::class)
        );
        $process = new Process(
            new Pid(42),
            new User('root'),
            new Percentage(42),
            new Memory(42),
            $this->createMock(PointInTime::class),
            new Command('echo')
        );
        $send
            ->expects($this->once())
            ->method('__invoke')
            ->with(new ProcessStatusAccessed($process->pid()));
        $inner
            ->expects($this->once())
            ->method('all')
            ->willReturn(
                $all = Map::of('int', Process::class)(42, $process)
            );

        $this->assertSame($all, $processes->all());
    }

    public function testGet()
    {
        $processes = new Processes(
            $inner = $this->createMock(ProcessesInterface::class),
            $send = $this->createMock(SendActivity::class)
        );
        $process = new Process(
            new Pid(42),
            new User('root'),
            new Percentage(42),
            new Memory(42),
            $this->createMock(PointInTime::class),
            new Command('echo')
        );
        $send
            ->expects($this->once())
            ->method('__invoke')
            ->with(new ProcessStatusAccessed($process->pid()));
        $inner
            ->expects($this->once())
            ->method('get')
            ->with($process->pid())
            ->willReturn($process);

        $this->assertSame($process, $processes->get($process->pid()));
    }
}
