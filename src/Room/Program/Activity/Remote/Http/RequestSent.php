<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\Remote\Http;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\Http\Message\Request;

final class RequestSent implements Activity
{
    private Request $request;
    private Tags $tags;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->tags = new Tags('os', 'remote', 'http');
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function toString(): string
    {
        $request = "{$this->request->method()->toString()} {$this->request->url()->toString()} HTTP/{$this->request->protocolVersion()->toString()}";

        return "Request sent: $request";
    }
}
