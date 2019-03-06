<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\OperatingSystem;

use Innmind\SilentCartographer\{
    SendActivity,
    Room\Program\Activity\Status\CpuUsageAccessed,
    Room\Program\Activity\Status\MemoryUsageAccessed,
    Room\Program\Activity\Status\LoadAverageAccessed,
};
use Innmind\Server\Status\{
    Server,
    Server\Cpu,
    Server\Memory,
    Server\Processes,
    Server\LoadAverage,
    Server\Disk,
};
use Innmind\Url\PathInterface;

final class Status implements Server
{
    private $server;
    private $send;
    private $processes;
    private $disk;

    public function __construct(Server $server, SendActivity $send)
    {
        $this->server = $server;
        $this->send = $send;
    }

    public function cpu(): Cpu
    {
        $cpu = $this->server->cpu();
        ($this->send)(new CpuUsageAccessed($cpu));

        return $cpu;
    }

    public function memory(): Memory
    {
        $memory = $this->server->memory();
        ($this->send)(new MemoryUsageAccessed($memory));

        return $memory;
    }

    public function processes(): Processes
    {
        return $this->processes ?? $this->processes = new Status\Processes(
            $this->server->processes(),
            $this->send
        );
    }

    public function loadAverage(): LoadAverage
    {
        $load = $this->server->loadAverage();
        ($this->send)(new LoadAverageAccessed($load));

        return $load;
    }

    public function disk(): Disk
    {
        return $this->disk ?? $this->disk = new Status\Disk(
            $this->server->disk(),
            $this->send
        );
    }

    public function tmp(): PathInterface
    {
        return $this->server->tmp();
    }
}
