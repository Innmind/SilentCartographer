<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\OperatingSystem\Remote;

use Innmind\SilentCartographer\{
    OperatingSystem\Remote\Http,
    SendActivity,
    Room\Program\Activity\Remote\Http\RequestSent,
    Room\Program\Activity\Remote\Http\ResponseReceived,
};
use Innmind\HttpTransport\Transport;
use Innmind\Http\Message\{
    Request,
    Response,
};
use PHPUnit\Framework\TestCase;

class HttpTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Transport::class,
            new Http(
                $this->createMock(Transport::class),
                $this->createMock(SendActivity::class)
            )
        );
    }

    public function testInvokation()
    {
        $fulfill = new Http(
            $inner = $this->createMock(Transport::class),
            $send = $this->createMock(SendActivity::class)
        );
        $request = $this->createMock(Request::class);
        $response = $this->createMock(Response::class);
        $inner
            ->expects($this->once())
            ->method('__invoke')
            ->with($request)
            ->willReturn($response);
        $send
            ->expects($this->exactly(2))
            ->method('__invoke')
            ->withConsecutive(
                [new RequestSent($request)],
                [new ResponseReceived($response)],
            );

        $this->assertSame($response, $fulfill($request));
    }
}
