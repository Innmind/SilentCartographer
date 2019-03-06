<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\OperatingSystem\Remote;

use Innmind\SilentCartographer\{
    OperatingSystem\Remote\Ssh,
    SendActivity,
};
use Innmind\Server\Control\Server;
use Innmind\Url\AuthorityInterface;
use PHPUnit\Framework\TestCase;

class SshTest extends TestCase
{
    public function testInterface()
    {
        $server = new Ssh(
            $this->createMock(Server::class),
            $this->createMock(AuthorityInterface::class),
            $this->createMock(SendActivity::class)
        );

        $this->assertInstanceOf(Server::class, $server);
        $this->assertInstanceOf(Ssh\Processes::class, $server->processes());
        $this->assertSame($server->processes(), $server->processes());
    }
}
