<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer;

use Innmind\SilentCartographer\{
    SubRoutine,
    Protocol,
    RoomActivity,
    Room,
    Room\Program,
    Room\Program\Activity,
    Room\Program\Activity\Tags,
    Room\Program\Type,
    IPC\Message\PanelActivated,
    IPC\Message\PanelDeactivated,
    Exception\UnknownProtocol,
};
use Innmind\IPC\{
    Server,
    Client,
    Message,
    Exception\Stop,
};
use Innmind\Server\Control\Server\Process\Pid;
use Innmind\Url\Url;
use Innmind\MediaType\MediaType;
use Innmind\Immutable\Str;
use PHPUnit\Framework\TestCase;

class SubRoutineTest extends TestCase
{
    public function testNothingHappensWhensReceivingMessageButNoPanel()
    {
        $subRoutine = new SubRoutine(
            $server = $this->createMock(Server::class),
            $protocol = $this->createMock(Protocol::class)
        );
        $message = new Message\Generic(
            new MediaType('application', 'json'),
            Str::of('{}'),
        );
        $program = $this->createMock(Client::class);
        $protocol
            ->expects($this->once())
            ->method('decode')
            ->with($message)
            ->willReturn(new RoomActivity(
                new Program(
                    new Pid(42),
                    Type::http(),
                    new Room(Url::of('file:///somewhere'))
                ),
                $this->createMock(Activity::class)
            ));
        $server
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->callback(static function(callable $listen) use ($message, $program): bool {
                $listen($message, $program);

                return true;
            }));

        $this->assertNull($subRoutine());
    }

    public function testDoNotForwardMessageOnClosedClient()
    {
        $subRoutine = new SubRoutine(
            $server = $this->createMock(Server::class),
            $protocol = $this->createMock(Protocol::class)
        );
        $message = new Message\Generic(
            new MediaType('application', 'json'),
            Str::of('{}'),
        );
        $program = $this->createMock(Client::class);
        $protocol
            ->expects($this->once())
            ->method('decode')
            ->with($message)
            ->willReturn(new RoomActivity(
                new Program(
                    new Pid(42),
                    Type::http(),
                    new Room(Url::of('file:///somewhere'))
                ),
                $this->createMock(Activity::class)
            ));
        $panel = $this->createMock(Client::class);
        $panel
            ->expects($this->once())
            ->method('closed')
            ->willReturn(true);
        $panel
            ->expects($this->never())
            ->method('send');
        $server
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->callback(static function(callable $listen) use ($message, $program, $panel): bool {
                $listen(new PanelActivated, $panel);
                $listen($message, $program);

                return true;
            }));

        $this->assertNull($subRoutine());
    }

    public function testDoNotForwardInvalidActivity()
    {
        $subRoutine = new SubRoutine(
            $server = $this->createMock(Server::class),
            $protocol = $this->createMock(Protocol::class)
        );
        $message = new Message\Generic(
            new MediaType('application', 'json'),
            Str::of('{}'),
        );
        $program = $this->createMock(Client::class);
        $protocol
            ->expects($this->once())
            ->method('decode')
            ->with($message)
            ->will($this->throwException(new UnknownProtocol));
        $panel = $this->createMock(Client::class);
        $panel
            ->expects($this->never())
            ->method('closed');
        $panel
            ->expects($this->never())
            ->method('send');
        $server
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->callback(static function(callable $listen) use ($message, $program, $panel): bool {
                $listen(new PanelActivated, $panel);
                $listen($message, $program);

                return true;
            }));

        $this->assertNull($subRoutine());
    }

    public function testDoNotForwardMessageWhenActivityDoesntMatchPanelTags()
    {
        $subRoutine = new SubRoutine(
            $server = $this->createMock(Server::class),
            $protocol = $this->createMock(Protocol::class)
        );
        $message = new Message\Generic(
            new MediaType('application', 'json'),
            Str::of('{}'),
        );
        $program = $this->createMock(Client::class);
        $protocol
            ->expects($this->once())
            ->method('decode')
            ->with($message)
            ->willReturn(new RoomActivity(
                new Program(
                    new Pid(42),
                    Type::http(),
                    new Room(Url::of('file:///somewhere'))
                ),
                new Activity\Generic(
                    new Tags('foo'),
                    'message'
                )
            ));
        $panel = $this->createMock(Client::class);
        $panel
            ->expects($this->once())
            ->method('closed')
            ->willReturn(false);
        $panel
            ->expects($this->never())
            ->method('send');
        $server
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->callback(static function(callable $listen) use ($message, $program, $panel): bool {
                $listen(new PanelActivated('bar'), $panel);
                $listen($message, $program);

                return true;
            }));

        $this->assertNull($subRoutine());
    }

    public function testDoNotForwardMessageWhenPanelDeactivated()
    {
        $subRoutine = new SubRoutine(
            $server = $this->createMock(Server::class),
            $protocol = $this->createMock(Protocol::class)
        );
        $message = new Message\Generic(
            new MediaType('application', 'json'),
            Str::of('{}'),
        );
        $program = $this->createMock(Client::class);
        $protocol
            ->expects($this->once())
            ->method('decode')
            ->with($message)
            ->willReturn(new RoomActivity(
                new Program(
                    new Pid(42),
                    Type::http(),
                    new Room(Url::of('file:///somewhere'))
                ),
                new Activity\Generic(
                    new Tags('foo'),
                    'message'
                )
            ));
        $panel = $this->createMock(Client::class);
        $panel
            ->expects($this->never())
            ->method('closed');
        $panel
            ->expects($this->never())
            ->method('send');
        $otherPanel = $this->createMock(Client::class);
        $server
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->callback(static function(callable $listen) use ($message, $program, $panel, $otherPanel): bool {
                $listen(new PanelActivated, $otherPanel);
                $listen(new PanelActivated, $panel);
                $listen(new PanelDeactivated, $panel);
                $listen($message, $program);

                return true;
            }));

        $this->assertNull($subRoutine());
    }

    public function testMessageReceivedBeforePanelActivationAreNotForwarded()
    {
        $subRoutine = new SubRoutine(
            $server = $this->createMock(Server::class),
            $protocol = $this->createMock(Protocol::class)
        );
        $message = new Message\Generic(
            new MediaType('application', 'json'),
            Str::of('{}'),
        );
        $program = $this->createMock(Client::class);
        $protocol
            ->expects($this->once())
            ->method('decode')
            ->with($message)
            ->willReturn(new RoomActivity(
                new Program(
                    new Pid(42),
                    Type::http(),
                    new Room(Url::of('file:///somewhere'))
                ),
                new Activity\Generic(
                    new Tags('foo'),
                    'message'
                )
            ));
        $panel = $this->createMock(Client::class);
        $panel
            ->expects($this->never())
            ->method('closed');
        $panel
            ->expects($this->never())
            ->method('send');
        $server
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->callback(static function(callable $listen) use ($message, $program, $panel): bool {
                $listen($message, $program);
                $listen(new PanelActivated, $panel);

                return true;
            }));

        $this->assertNull($subRoutine());
    }

    public function testForwardMessage()
    {
        $subRoutine = new SubRoutine(
            $server = $this->createMock(Server::class),
            $protocol = $this->createMock(Protocol::class)
        );
        $message = new Message\Generic(
            new MediaType('application', 'json'),
            Str::of('{}'),
        );
        $program = $this->createMock(Client::class);
        $protocol
            ->expects($this->once())
            ->method('decode')
            ->with($message)
            ->willReturn(new RoomActivity(
                new Program(
                    new Pid(42),
                    Type::http(),
                    new Room(Url::of('file:///somewhere'))
                ),
                new Activity\Generic(
                    new Tags('foo'),
                    'message'
                )
            ));
        $panel = $this->createMock(Client::class);
        $panel
            ->expects($this->once())
            ->method('closed')
            ->willReturn(false);
        $panel
            ->expects($this->once())
            ->method('send')
            ->with($message);
        $server
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->callback(static function(callable $listen) use ($message, $program, $panel): bool {
                $listen(new PanelActivated, $panel);
                $listen($message, $program);

                return true;
            }));

        $this->assertNull($subRoutine());
    }

    public function testStopSubRoutineWhenLastPanelIsDeactivated()
    {
        $subRoutine = new SubRoutine(
            $server = $this->createMock(Server::class),
            $this->createMock(Protocol::class)
        );
        $panel = $this->createMock(Client::class);
        $server
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->callback(static function(callable $listen) use ($panel): bool {
                $listen(new PanelActivated, $panel);

                try {
                    $listen(new PanelDeactivated, $panel);

                    return false;
                } catch (Stop $e) {
                    return true;
                }
            }));

        $this->assertNull($subRoutine());
    }
}
