<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\OperatingSystem;

use Innmind\SilentCartographer\{
    OperatingSystem\Status,
    SendActivity,
    Room\Program\Activity\Status\CpuUsageAccessed,
    Room\Program\Activity\Status\MemoryUsageAccessed,
    Room\Program\Activity\Status\LoadAverageAccessed,
};
use Innmind\Server\Status\{
    Server,
    Server\Cpu,
    Server\Cpu\Percentage,
    Server\Cpu\Cores,
    Server\Memory,
    Server\Memory\Bytes,
    Server\LoadAverage,
};
use Innmind\Url\PathInterface;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Server::class,
            new Status(
                $this->createMock(Server::class),
                $this->createMock(SendActivity::class)
            )
        );
    }

    public function testCpu()
    {
        $status = new Status(
            $server = $this->createMock(Server::class),
            $send = $this->createMock(SendActivity::class)
        );
        $cpu = new Cpu(
            new Percentage(10),
            new Percentage(10),
            new Percentage(10),
            new Cores(1)
        );
        $send
            ->expects($this->once())
            ->method('__invoke')
            ->with(new CpuUsageAccessed($cpu));
        $server
            ->expects($this->once())
            ->method('cpu')
            ->willReturn($cpu);

        $this->assertSame($cpu, $status->cpu());
    }

    public function testMemory()
    {
        $status = new Status(
            $server = $this->createMock(Server::class),
            $send = $this->createMock(SendActivity::class)
        );
        $memory = new Memory(
            new Bytes(10),
            new Bytes(10),
            new Bytes(10),
            new Bytes(10),
            new Bytes(10),
            new Bytes(10)
        );
        $send
            ->expects($this->once())
            ->method('__invoke')
            ->with(new MemoryUsageAccessed($memory));
        $server
            ->expects($this->once())
            ->method('memory')
            ->willReturn($memory);

        $this->assertSame($memory, $status->memory());
    }

    public function testLoadAverage()
    {
        $status = new Status(
            $server = $this->createMock(Server::class),
            $send = $this->createMock(SendActivity::class)
        );
        $loadAverage = new LoadAverage(1, 5, 15);
        $send
            ->expects($this->once())
            ->method('__invoke')
            ->with(new LoadAverageAccessed($loadAverage));
        $server
            ->expects($this->once())
            ->method('loadAverage')
            ->willReturn($loadAverage);

        $this->assertSame($loadAverage, $status->loadAverage());
    }

    public function testProcesses()
    {
        $status = new Status(
            $this->createMock(Server::class),
            $this->createMock(SendActivity::class)
        );

        $this->assertInstanceOf(Status\Processes::class, $status->processes());
    }

    public function testDisk()
    {
        $status = new Status(
            $this->createMock(Server::class),
            $this->createMock(SendActivity::class)
        );

        $this->assertInstanceOf(Status\Disk::class, $status->disk());
    }

    public function testTmp()
    {
        $status = new Status(
            $server = $this->createMock(Server::class),
            $send = $this->createMock(SendActivity::class)
        );
        $send
            ->expects($this->never())
            ->method('__invoke');
        $server
            ->expects($this->once())
            ->method('tmp')
            ->willReturn($tmp = $this->createMock(PathInterface::class));

        $this->assertSame($tmp, $status->tmp());
    }
}
