<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer;

use function Innmind\SilentCartographer\bootstrap;
use Innmind\SilentCartographer\{
    Protocol,
    OperatingSystem,
    SendActivity,
};
use Innmind\OperatingSystem\OperatingSystem as OS;
use Innmind\Url\UrlInterface;
use Innmind\IPC\Process\Name;
use Innmind\ObjectGraph\{
    Assert\Stack,
    Graph,
};
use PHPUnit\Framework\TestCase;

class BootstrapTest extends TestCase
{
    public function testInterface()
    {
        $services = bootstrap();

        $this->assertIsArray($services);
        $this->assertCount(4, $services);
        $this->assertInstanceOf(Name::class, $services['sub_routine']);
        $this->assertInstanceOf(Protocol::class, $services['protocol']);
        $this->assertIsCallable($services['http_server']);
        $this->assertIsCallable($services['cli']);

        $httpServer = $services['http_server'](
            $this->createMock(OS::class),
            $this->createMock(UrlInterface::class)
        );
        $cli = $services['cli'](
            $this->createMock(OS::class),
            $this->createMock(UrlInterface::class)
        );

        $this->assertInstanceOf(OperatingSystem::class, $httpServer);
        $this->assertInstanceOf(OperatingSystem::class, $cli);

        $sendEveryAction = Stack::of(
            OperatingSystem::class,
            SendActivity\IPC::class
        );
        $stopWhenNoSubRoutine = Stack::of(
            OperatingSystem::class,
            SendActivity\DiscardSubsequentSend::class,
            SendActivity\IPC::class
        );

        $this->assertTrue($stopWhenNoSubRoutine((new Graph)($httpServer)));
        $this->assertFalse($stopWhenNoSubRoutine((new Graph)($cli)));
        $this->assertTrue($sendEveryAction((new Graph)($cli)));
    }
}
