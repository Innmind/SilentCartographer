<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\OperatingSystem;

use Innmind\SilentCartographer\{
    OperatingSystem\Control,
    SendActivity,
};
use Innmind\Server\Control\Server;
use PHPUnit\Framework\TestCase;

class SshTest extends TestCase
{
    public function testInterface()
    {
        $server = new Control(
            $this->createMock(Server::class),
            $this->createMock(SendActivity::class)
        );

        $this->assertInstanceOf(Server::class, $server);
        $this->assertInstanceOf(Control\Processes::class, $server->processes());
        $this->assertSame($server->processes(), $server->processes());
    }
}
