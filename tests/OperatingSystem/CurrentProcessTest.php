<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\OperatingSystem;

use Innmind\SilentCartographer\{
    OperatingSystem\CurrentProcess,
    SendActivity,
    Room\Program\Activity\CurrentProcess\ProcessForked,
    Room\Program\Activity\CurrentProcess\ProcessHalted,
};
use Innmind\OperatingSystem\{
    CurrentProcess as CurrentProcessInterface,
    CurrentProcess\ForkSide,
    CurrentProcess\Children,
    CurrentProcess\Signals,
};
use Innmind\Server\Control\Server\Process\Pid;
use Innmind\TimeContinuum\Period;
use PHPUnit\Framework\TestCase;

class CurrentProcessTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            CurrentProcessInterface::class,
            new CurrentProcess(
                $this->createMock(CurrentProcessInterface::class),
                $this->createMock(SendActivity::class)
            )
        );
    }

    public function testId()
    {
        $process = new CurrentProcess(
            $inner = $this->createMock(CurrentProcessInterface::class),
            $send = $this->createMock(SendActivity::class)
        );
        $send
            ->expects($this->never())
            ->method('__invoke');
        $inner
            ->expects($this->once())
            ->method('id')
            ->willReturn($pid = new Pid(42));

        $this->assertSame($pid, $process->id());
    }

    public function testSendForkActivityInParentSide()
    {
        $process = new CurrentProcess(
            $inner = $this->createMock(CurrentProcessInterface::class),
            $send = $this->createMock(SendActivity::class)
        );
        $send
            ->expects($this->once())
            ->method('__invoke')
            ->with(new ProcessForked(new Pid(42)));
        $inner
            ->expects($this->once())
            ->method('fork')
            ->willReturn($side = ForkSide::of(42));

        $this->assertSame($side, $process->fork());
    }

    public function testForkActivityNotSentInChildSide()
    {
        $process = new CurrentProcess(
            $inner = $this->createMock(CurrentProcessInterface::class),
            $send = $this->createMock(SendActivity::class)
        );
        $send
            ->expects($this->never())
            ->method('__invoke');
        $inner
            ->expects($this->once())
            ->method('fork')
            ->willReturn($side = ForkSide::of(0));

        $this->assertSame($side, $process->fork());
    }

    public function testChildren()
    {
        $process = new CurrentProcess(
            $inner = $this->createMock(CurrentProcessInterface::class),
            $send = $this->createMock(SendActivity::class)
        );
        $send
            ->expects($this->never())
            ->method('__invoke');
        $inner
            ->expects($this->once())
            ->method('children')
            ->willReturn($children = new Children);

        $this->assertSame($children, $process->children());
    }

    public function testSignals()
    {
        $process = new CurrentProcess(
            $inner = $this->createMock(CurrentProcessInterface::class),
            $send = $this->createMock(SendActivity::class)
        );
        $send
            ->expects($this->never())
            ->method('__invoke');
        $inner
            ->expects($this->once())
            ->method('signals')
            ->willReturn($signals = $this->createMock(Signals::class));

        $this->assertSame($signals, $process->signals());
    }

    public function testHalt()
    {
        $process = new CurrentProcess(
            $inner = $this->createMock(CurrentProcessInterface::class),
            $send = $this->createMock(SendActivity::class)
        );
        $period = $this->createMock(Period::class);
        $send
            ->expects($this->once())
            ->method('__invoke')
            ->with(new ProcessHalted($period));
        $inner
            ->expects($this->once())
            ->method('halt')
            ->with($period);

        $this->assertNull($process->halt($period));
    }
}
