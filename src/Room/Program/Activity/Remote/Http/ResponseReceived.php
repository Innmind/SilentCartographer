<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\Remote\Http;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\Http\Message\Response;

final class ResponseReceived implements Activity
{
    private $response;
    private $tags;

    public function __construct(Response $response)
    {
        $this->response = $response;
        $this->tags = new Tags('os', 'remote', 'http');
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function __toString(): string
    {
        $response = "HTTP/{$this->response->protocolVersion()} {$this->response->statusCode()} {$this->response->reasonPhrase()}";

        return "Response received: $response";
    }
}
