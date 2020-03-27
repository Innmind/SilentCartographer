<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\Remote\Http;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Remote\Http\RequestSent,
    Activity,
};
use Innmind\Http\{
    Message\Request\Request,
    Message\Method,
    ProtocolVersion,
};
use Innmind\Url\Url;
use PHPUnit\Framework\TestCase;

class RequestSentTest extends TestCase
{
    public function testInterface()
    {
        $activity = new RequestSent(
            new Request(
                Url::of('https://example.com/foo'),
                Method::get(),
                new ProtocolVersion(2, 0)
            )
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'remote', 'http'], $activity->tags()->list());
        $this->assertSame('Request sent: GET https://example.com/foo HTTP/2.0', $activity->toString());
    }
}
