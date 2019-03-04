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
            new Unix('/tmp/foo.sock')
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'socket', 'unix'], \iterator_to_array($activity->tags()));
        $this->assertSame('Socket opened: /tmp/foo.sock', (string) $activity);
    }
}
