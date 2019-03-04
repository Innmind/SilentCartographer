<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\Remote\Http;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\Http\Message\{
    Response,
    Response\Stringable,
};
use Innmind\Immutable\Str;

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
        $response = Str::of((string) new Stringable($this->response))
            ->split("\n")
            ->first();

        return "Response received: $response";
    }
}
