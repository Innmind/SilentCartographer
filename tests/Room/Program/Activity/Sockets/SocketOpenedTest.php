<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\Sockets;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Sockets\SocketOpened,
    Activity,
};
use Innmind\Socket\Address\Unix;
use PHPUnit\Framework\TestCase;

class SocketOpenedTest extends TestCase
{
    public function testInterface()
    {
        $activity = new SocketOpened(
            Unix::of('/tmp/foo.sock')
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'socket', 'unix'], $activity->tags()->list());
        $this->assertSame('Socket opened: /tmp/foo.sock', $activity->toString());
    }
}
