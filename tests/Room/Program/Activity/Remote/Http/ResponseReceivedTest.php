<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\Remote\Http;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Remote\Http\ResponseReceived,
    Activity,
};
use Innmind\Http\{
    Message\Response\Response,
    Message\StatusCode,
    ProtocolVersion,
};
use PHPUnit\Framework\TestCase;

class ResponseReceivedTest extends TestCase
{
    public function testInterface()
    {
        $activity = new ResponseReceived(
            new Response(
                $code = StatusCode::of('CREATED'),
                $code->associatedReasonPhrase(),
                new ProtocolVersion(2, 0)
            )
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'remote', 'http'], \iterator_to_array($activity->tags()));
        $this->assertSame('Response received: HTTP/2.0 201 Created', $activity->toString());
    }
}
