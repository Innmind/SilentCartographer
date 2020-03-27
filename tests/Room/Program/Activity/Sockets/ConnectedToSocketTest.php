<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\Sockets;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Sockets\ConnectedToSocket,
    Activity,
};
use Innmind\Socket\Address\Unix;
use PHPUnit\Framework\TestCase;

class ConnectedToSocketTest extends TestCase
{
    public function testInterface()
    {
        $activity = new ConnectedToSocket(
            Unix::of('/tmp/foo.sock')
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'socket', 'unix'], \iterator_to_array($activity->tags()));
        $this->assertSame('Connected to socket: /tmp/foo.sock', $activity->toString());
    }
}
