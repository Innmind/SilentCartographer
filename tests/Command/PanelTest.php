<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Command;

use Innmind\SilentCartographer\{
    Command\Panel,
    Protocol,
    RoomActivity,
    Room,
    Room\Program,
    Room\Program\Activity,
    Room\Program\Type,
    IPC\Message\PanelActivated,
    IPC\Message\PanelDeactivated,
};
use Innmind\IPC\{
    IPC,
    Process,
    Process\Name,
    Message,
    Exception\ConnectionClosed,
    Exception\RuntimeException,
};
use Innmind\OperatingSystem\CurrentProcess\Signals;
use Innmind\CLI\{
    Command,
    Command\Arguments,
    Command\Options,
    Environment,
};
use Innmind\Url\Url;
use Innmind\Stream\Writable;
use Innmind\Server\Status\Server\Process\Pid;
use Innmind\Signals\Signal;
use Innmind\Immutable\{
    Map,
    Stream,
    Str,
};
use PHPUnit\Framework\TestCase;

class PanelTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Command::class,
            new Panel(
                $this->createMock(IPC::class),
                new Name('sub_routine'),
                $this->createMock(Protocol::class),
                $this->createMock(Signals::class)
            )
        );
    }

    public function testUsage()
    {
        $command = new Panel(
            $this->createMock(IPC::class),
            new Name('sub_routine'),
            $this->createMock(Protocol::class),
            $this->createMock(Signals::class)
        );
        $expected = <<<USAGE
panel ...tags

Open a panel to display all activity that matches the given tags

When no tag provided it will display all messages
USAGE;

        $this->assertSame($expected, (string) $command);
    }

    public function testInvokation()
    {
        $command = new Panel(
            $ipc = $this->createMock(IPC::class),
            $subRoutine = new Name('sub_routine'),
            $protocol = $this->createMock(Protocol::class),
            $this->createMock(Signals::class)
        );
        $ipc
            ->expects($this->once())
            ->method('wait')
            ->with($subRoutine);
        $ipc
            ->expects($this->once())
            ->method('get')
            ->with($subRoutine)
            ->willReturn($process = $this->createMock(Process::class));
        $process
            ->expects($this->at(0))
            ->method('send')
            ->with(new PanelActivated('foo', 'bar'));
        $process
            ->expects($this->at(1))
            ->method('wait')
            ->willReturn($message = $this->createMock(Message::class));
        $protocol
            ->expects($this->once())
            ->method('decode')
            ->with($message)
            ->willReturn(new RoomActivity(
                new Program(
                    new Pid(42),
                    Type::http(),
                    new Room(
                        Url::fromString('file:///somewhere/on/filesystem')
                    )
                ),
                new Activity\Generic(
                    new Activity\Tags('foo', 'bar', 'baz'),
                    'message'
                )
            ));
        $env = $this->createMock(Environment::class);
        $env
            ->expects($this->once())
            ->method('output')
            ->willReturn($output = $this->createMock(Writable::class));
        $output
            ->expects($this->once())
            ->method('write')
            ->with(Str::of("[http][42][/somewhere/on/filesystem][foo/bar/baz] message\n"))
            ->will($this->returnSelf());
        $process
            ->expects($this->at(2))
            ->method('closed')
            ->willReturn(false);
        $process
            ->expects($this->at(3))
            ->method('wait')
            ->will($this->throwException(new ConnectionClosed));

        $this->assertNull($command(
            $env,
            new Arguments(
                Map::of('string', 'mixed')
                    ('tags', Stream::of('string', 'foo', 'bar'))
            ),
            new Options
        ));
    }

    public function testSignalCloseProcess()
    {
        $command = new Panel(
            $ipc = $this->createMock(IPC::class),
            $subRoutine = new Name('sub_routine'),
            $protocol = $this->createMock(Protocol::class),
            $signals = $this->createMock(Signals::class)
        );
        $ipc
            ->expects($this->once())
            ->method('wait')
            ->with($subRoutine);
        $ipc
            ->expects($this->once())
            ->method('get')
            ->with($subRoutine)
            ->willReturn($process = $this->createMock(Process::class));
        $process
            ->expects($this->exactly(7))
            ->method('send')
            ->with($this->logicalOr(
                $this->equalTo(new PanelActivated), // on first call (see testInvokation)
                $this->equalTo(new PanelDeactivated)
            ));
        $process
            ->expects($this->exactly(6))
            ->method('close');
        $signals
            ->expects($this->at(0))
            ->method('listen')
            ->with(
                Signal::hangup(),
                $this->callback(static function($listen): bool {
                    $listen();

                    return true;
                })
            );
        $signals
            ->expects($this->at(1))
            ->method('listen')
            ->with(
                Signal::interrupt(),
                $this->callback(static function($listen): bool {
                    $listen();

                    return true;
                })
            );
        $signals
            ->expects($this->at(2))
            ->method('listen')
            ->with(
                Signal::abort(),
                $this->callback(static function($listen): bool {
                    $listen();

                    return true;
                })
            );
        $signals
            ->expects($this->at(3))
            ->method('listen')
            ->with(
                Signal::terminate(),
                $this->callback(static function($listen): bool {
                    $listen();

                    return true;
                })
            );
        $signals
            ->expects($this->at(4))
            ->method('listen')
            ->with(
                Signal::terminalStop(),
                $this->callback(static function($listen): bool {
                    $listen();

                    return true;
                })
            );
        $signals
            ->expects($this->at(5))
            ->method('listen')
            ->with(
                Signal::alarm(),
                $this->callback(static function($listen): bool {
                    $listen();

                    return true;
                })
            );
        $process
            ->expects($this->once())
            ->method('wait')
            ->will($this->throwException(new ConnectionClosed));

        $this->assertNull($command(
            $this->createMock(Environment::class),
            new Arguments(
                Map::of('string', 'mixed')
                    ('tags', Stream::of('string'))
            ),
            new Options()
        ));
    }

    public function testHandleExceptionWhenClosingConnectionOnSignalHandling()
    {
        $command = new Panel(
            $ipc = $this->createMock(IPC::class),
            $subRoutine = new Name('sub_routine'),
            $protocol = $this->createMock(Protocol::class),
            $signals = $this->createMock(Signals::class)
        );
        $ipc
            ->expects($this->once())
            ->method('wait')
            ->with($subRoutine);
        $ipc
            ->expects($this->once())
            ->method('get')
            ->with($subRoutine)
            ->willReturn($process = $this->createMock(Process::class));
        $process
            ->expects($this->exactly(7))
            ->method('send')
            ->with($this->logicalOr(
                $this->equalTo(new PanelActivated), // on first call (see testInvokation)
                $this->equalTo(new PanelDeactivated)
            ));
        $process
            ->expects($this->exactly(6))
            ->method('close')
            ->will($this->throwException(new RuntimeException));
        $signals
            ->expects($this->at(0))
            ->method('listen')
            ->with(
                Signal::hangup(),
                $this->callback(static function($listen): bool {
                    $listen();

                    return true;
                })
            );
        $signals
            ->expects($this->at(1))
            ->method('listen')
            ->with(
                Signal::interrupt(),
                $this->callback(static function($listen): bool {
                    $listen();

                    return true;
                })
            );
        $signals
            ->expects($this->at(2))
            ->method('listen')
            ->with(
                Signal::abort(),
                $this->callback(static function($listen): bool {
                    $listen();

                    return true;
                })
            );
        $signals
            ->expects($this->at(3))
            ->method('listen')
            ->with(
                Signal::terminate(),
                $this->callback(static function($listen): bool {
                    $listen();

                    return true;
                })
            );
        $signals
            ->expects($this->at(4))
            ->method('listen')
            ->with(
                Signal::terminalStop(),
                $this->callback(static function($listen): bool {
                    $listen();

                    return true;
                })
            );
        $signals
            ->expects($this->at(5))
            ->method('listen')
            ->with(
                Signal::alarm(),
                $this->callback(static function($listen): bool {
                    $listen();

                    return true;
                })
            );
        $process
            ->expects($this->once())
            ->method('wait')
            ->will($this->throwException(new ConnectionClosed));

        $this->assertNull($command(
            $this->createMock(Environment::class),
            new Arguments(
                Map::of('string', 'mixed')
                    ('tags', Stream::of('string'))
            ),
            new Options()
        ));
    }
}
