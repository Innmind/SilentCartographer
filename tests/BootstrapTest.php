<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer;

use function Innmind\SilentCartographer\bootstrap;
use Innmind\SilentCartographer\{
    Protocol,
    OperatingSystem,
    SendActivity,
};
use Innmind\OperatingSystem\Factory;
use Innmind\Url\Url;
use Innmind\IPC\Process\Name;
use Innmind\CLI\Commands;
use Innmind\ObjectGraph\{
    Assert\Stack,
    Graph,
};
use PHPUnit\Framework\TestCase;

class BootstrapTest extends TestCase
{
    public function testInterface()
    {
        $services = bootstrap(Factory::build());

        $this->assertIsArray($services);
        $this->assertCount(5, $services);
        $this->assertInstanceOf(Name::class, $services['sub_routine']);
        $this->assertInstanceOf(Protocol::class, $services['protocol']);
        $this->assertIsCallable($services['http_server']);
        $this->assertIsCallable($services['cli']);
        $this->assertIsCallable($services['commands']);

        $httpServer = $services['http_server'](
            Url::of('file:///somewhere')
        );
        $cli = $services['cli'](
            Url::of('file:///somewhere')
        );
        $commands = $services['commands']();

        $this->assertInstanceOf(OperatingSystem::class, $httpServer);
        $this->assertInstanceOf(OperatingSystem::class, $cli);
        $this->assertInstanceOf(Commands::class, $commands);

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
