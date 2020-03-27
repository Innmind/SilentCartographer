<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\OperatingSystem\Remote;

use Innmind\SilentCartographer\{
    SendActivity,
    Room\Program\Activity\Remote\Http\RequestSent,
    Room\Program\Activity\Remote\Http\ResponseReceived,
};
use Innmind\HttpTransport\Transport;
use Innmind\Http\Message\{
    Request,
    Response,
};

final class Http implements Transport
{
    private Transport $fulfill;
    private SendActivity $send;

    public function __construct(Transport $fulfill, SendActivity $send)
    {
        $this->fulfill = $fulfill;
        $this->send = $send;
    }

    public function __invoke(Request $request): Response
    {
        ($this->send)(new RequestSent($request));
        $response = ($this->fulfill)($request);
        ($this->send)(new ResponseReceived($response));

        return $response;
    }
}
