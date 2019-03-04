<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\Remote\Http;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\Http\Message\{
    Request,
    Request\Stringable,
};
use Innmind\Immutable\Str;

final class RequestSent implements Activity
{
    private $request;
    private $tags;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->tags = new Tags('os', 'remote', 'http');
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function __toString(): string
    {
        $request = Str::of((string) new Stringable($this->request))
            ->split("\n")
            ->first();

        return "Request sent: $request";
    }
}
