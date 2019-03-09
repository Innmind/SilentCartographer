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
use Innmind\IPC\{
    IPC,
    Server,
    Process\Name,
};
use PHPUnit\Framework\TestCase;

class SubRoutineTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Command::class,
            new SubRoutine(
                $this->createMock(IPC::class),
                new Name('foo'),
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
            $this->createMock(IPC::class),
            new Name('foo'),
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
            $ipc = $this->createMock(IPC::class),
            $name = new Name('foo'),
            new Listen(
                $server = $this->createMock(Server::class),
                $this->createMock(Protocol::class)
            )
        );
        $ipc
            ->expects($this->once())
            ->method('exist')
            ->with($name)
            ->willReturn(false);
        $server
            ->expects($this->once())
            ->method('__invoke');

        $this->assertNull($command(
            $this->createMock(Environment::class),
            new Arguments,
            new Options
        ));
    }

    public function testDoesntListenIfRoutineAlreadyStarted()
    {
        $command = new SubRoutine(
            $ipc = $this->createMock(IPC::class),
            $name = new Name('foo'),
            new Listen(
                $server = $this->createMock(Server::class),
                $this->createMock(Protocol::class)
            )
        );
        $ipc
            ->expects($this->once())
            ->method('exist')
            ->with($name)
            ->willReturn(true);
        $server
            ->expects($this->never())
            ->method('__invoke');

        $this->assertNull($command(
            $this->createMock(Environment::class),
            new Arguments,
            new Options
        ));
    }
}
