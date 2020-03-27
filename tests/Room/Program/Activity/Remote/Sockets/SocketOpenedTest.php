<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\Remote\Sockets;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Remote\Sockets\SocketOpened,
    Activity,
};
use Innmind\Socket\Internet\Transport;
use Innmind\Url\Url;
use PHPUnit\Framework\TestCase;

class SocketOpenedTest extends TestCase
{
    public function testInterface()
    {
        $activity = new SocketOpened(
            Transport::tcp(),
            Url::of('tcp://user:pwd@foo:443/')->authority()
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'remote', 'socket'], \iterator_to_array($activity->tags()));
        $this->assertSame('Socket opened: tcp://user:pwd@foo:443', (string) $activity);
    }
}
