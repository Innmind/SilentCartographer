<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\SendActivity;

use Innmind\SilentCartographer\{
    SendActivity\DiscardSubsequentSend,
    SendActivity,
    Room\Program\Activity,
};
use Innmind\IPC\{
    IPC,
    Process\Name,
};
use PHPUnit\Framework\TestCase;

class DiscardSubsequentSendTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            SendActivity::class,
            new DiscardSubsequentSend(
                $this->createMock(SendActivity::class),
                $this->createMock(IPC::class),
                new Name('sub_routine')
            )
        );
    }

    public function testDiscard()
    {
        $send = new DiscardSubsequentSend(
            $inner = $this->createMock(SendActivity::class),
            $ipc = $this->createMock(IPC::class),
            $name = new Name('sub_routine')
        );
        $inner
            ->expects($this->never())
            ->method('__invoke');
        $ipc
            ->expects($this->once())
            ->method('exist')
            ->with($name)
            ->willReturn(false);

        $this->assertNull($send($this->createMock(Activity::class)));
    }

    public function testSend()
    {
        $send = new DiscardSubsequentSend(
            $inner = $this->createMock(SendActivity::class),
            $ipc = $this->createMock(IPC::class),
            $name = new Name('sub_routine')
        );
        $activity1 = $this->createMock(Activity::class);
        $activity2 = $this->createMock(Activity::class);
        $inner
            ->expects($this->at(0))
            ->method('__invoke')
            ->with($activity1);
        $inner
            ->expects($this->at(1))
            ->method('__invoke')
            ->with($activity2);
        $ipc
            ->expects($this->once())
            ->method('exist')
            ->with($name)
            ->willReturn(true);

        $this->assertNull($send($activity1));
        $this->assertNull($send($activity2));
    }
}
