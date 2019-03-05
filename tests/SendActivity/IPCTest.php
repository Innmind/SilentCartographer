<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\SendActivity;

use Innmind\SilentCartographer\{
    SendActivity\IPC,
    SendActivity,
    Room,
    Room\Program\Type,
    Room\Program\Activity,
    Protocol,
};
use Innmind\IPC\{
    IPC as IPCInterface,
    Process,
    Process\Name,
    Message,
};
use Innmind\OperatingSystem\CurrentProcess;
use Innmind\Url\UrlInterface;
use Innmind\Server\Status\Server\Process\Pid;
use PHPUnit\Framework\TestCase;

class IPCTest extends TestCase
{
    public function testInterface()
    {
        $send = new IPC(
            new Room($this->createMock(UrlInterface::class)),
            Type::cli(),
            $this->createMock(CurrentProcess::class),
            $this->createMock(Protocol::class),
            $this->createMock(IPCInterface::class),
            new Name('sub_routine')
        );

        $this->assertInstanceOf(SendActivity::class, $send);
    }

    public function testDoesntSendWhenSubRoutineDoesntExist()
    {
        $send = new IPC(
            new Room($this->createMock(UrlInterface::class)),
            Type::cli(),
            $process = $this->createMock(CurrentProcess::class),
            $this->createMock(Protocol::class),
            $ipc = $this->createMock(IPCInterface::class),
            $name = new Name('sub_routine')
        );
        $process
            ->expects($this->once())
            ->method('id')
            ->willReturn(new Pid(42));
        $ipc
            ->expects($this->once())
            ->method('exist')
            ->with($name)
            ->willReturn(false);
        $ipc
            ->expects($this->never())
            ->method('get');

        $this->assertNull($send($this->createMock(Activity::class)));
    }

    public function testSend()
    {
        $send = new IPC(
            $room = new Room($this->createMock(UrlInterface::class)),
            $type = Type::cli(),
            $process = $this->createMock(CurrentProcess::class),
            $protocol = $this->createMock(Protocol::class),
            $ipc = $this->createMock(IPCInterface::class),
            $name = new Name('sub_routine')
        );
        $activity = $this->createMock(Activity::class);
        $process
            ->expects($this->any())
            ->method('id')
            ->willReturn($pid = new Pid(42));
        $ipc
            ->expects($this->exactly(2))
            ->method('exist')
            ->with($name)
            ->willReturn(true);
        $ipc
            ->expects($this->once())
            ->method('get')
            ->with($name)
            ->willReturn($client = $this->createMock(Process::class));
        $protocol
            ->expects($this->exactly(2))
            ->method('encode')
            ->with($this->callback(static function($roomActivity) use ($activity, $room, $type, $pid): bool {
                return $roomActivity->activity() === $activity &&
                    $roomActivity->program()->id() === $pid &&
                    $roomActivity->program()->type() === $type &&
                    $roomActivity->program()->room() === $room;
            }))
            ->willReturn($message = $this->createMock(Message::class));
        $client
            ->expects($this->once())
            ->method('closed')
            ->willReturn(false);
        $client
            ->expects($this->exactly(2))
            ->method('send')
            ->with($message);

        $this->assertNull($send($activity));
        $this->assertNull($send($activity));
    }

    public function testDoesntSendOnceTheSubRoutineIsGone()
    {
        $send = new IPC(
            $room = new Room($this->createMock(UrlInterface::class)),
            $type = Type::cli(),
            $process = $this->createMock(CurrentProcess::class),
            $protocol = $this->createMock(Protocol::class),
            $ipc = $this->createMock(IPCInterface::class),
            $name = new Name('sub_routine')
        );
        $activity = $this->createMock(Activity::class);
        $process
            ->expects($this->any())
            ->method('id')
            ->willReturn($pid = new Pid(42));
        $ipc
            ->expects($this->exactly(2))
            ->method('exist')
            ->with($name)
            ->will($this->onConsecutiveCalls(true, false));
        $ipc
            ->expects($this->once())
            ->method('get')
            ->with($name)
            ->willReturn($client = $this->createMock(Process::class));
        $protocol
            ->expects($this->exactly(2))
            ->method('encode')
            ->with($this->callback(static function($roomActivity) use ($activity, $room, $type, $pid): bool {
                return $roomActivity->activity() === $activity &&
                    $roomActivity->program()->id() === $pid &&
                    $roomActivity->program()->type() === $type &&
                    $roomActivity->program()->room() === $room;
            }))
            ->willReturn($message = $this->createMock(Message::class));
        $client
            ->expects($this->never())
            ->method('closed');
        $client
            ->expects($this->once())
            ->method('send')
            ->with($message);

        $this->assertNull($send($activity));
        $this->assertNull($send($activity));
    }

    public function testRefetchProcessWhenClosed()
    {
        $send = new IPC(
            $room = new Room($this->createMock(UrlInterface::class)),
            $type = Type::cli(),
            $process = $this->createMock(CurrentProcess::class),
            $protocol = $this->createMock(Protocol::class),
            $ipc = $this->createMock(IPCInterface::class),
            $name = new Name('sub_routine')
        );
        $activity = $this->createMock(Activity::class);
        $process
            ->expects($this->any())
            ->method('id')
            ->willReturn($pid = new Pid(42));
        $ipc
            ->expects($this->exactly(3))
            ->method('exist')
            ->with($name)
            ->willReturn(true);
        $ipc
            ->expects($this->exactly(2))
            ->method('get')
            ->with($name)
            ->will($this->onConsecutiveCalls(
                $client1 = $this->createMock(Process::class),
                $client2 = $this->createMock(Process::class)
            ));
        $protocol
            ->expects($this->exactly(2))
            ->method('encode')
            ->with($this->callback(static function($roomActivity) use ($activity, $room, $type, $pid): bool {
                return $roomActivity->activity() === $activity &&
                    $roomActivity->program()->id() === $pid &&
                    $roomActivity->program()->type() === $type &&
                    $roomActivity->program()->room() === $room;
            }))
            ->willReturn($message = $this->createMock(Message::class));
        $client1
            ->expects($this->once())
            ->method('closed')
            ->willReturn(true);
        $client2
            ->expects($this->once())
            ->method('send')
            ->with($message);
        $client2
            ->expects($this->never())
            ->method('closed');
        $client2
            ->expects($this->once())
            ->method('send')
            ->with($message);

        $this->assertNull($send($activity));
        $this->assertNull($send($activity));
    }
}
