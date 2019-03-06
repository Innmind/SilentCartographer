<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Command;

use Innmind\SilentCartographer\{
    Command\SubRoutine,
    SubRoutine as Listen,
    Protocol,
};
use Innmind\CLI\{
    Command,
    Command\Arguments,
    Command\Options,
    Environment,
};
use Innmind\IPC\Server;
use PHPUnit\Framework\TestCase;

class SubRoutineTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Command::class,
            new SubRoutine(
                new Listen(
                    $this->createMock(Server::class),
                    $this->createMock(Protocol::class)
                )
            )
        );
    }

    public function testUsage()
    {
        $command = new SubRoutine(
            new Listen(
                $this->createMock(Server::class),
                $this->createMock(Protocol::class)
            )
        );
        $expected = <<<USAGE
sub-routine

Start the server that collects all activity messages and forward them to panels
USAGE;

        $this->assertSame($expected, (string) $command);
    }

    public function testInvokation()
    {
        $command = new SubRoutine(
            new Listen(
                $server = $this->createMock(Server::class),
                $this->createMock(Protocol::class)
            )
        );
        $server
            ->expects($this->once())
            ->method('__invoke');

        $this->assertNull($command(
            $this->createMock(Environment::class),
            new Arguments,
            new Options
        ));
    }
}
