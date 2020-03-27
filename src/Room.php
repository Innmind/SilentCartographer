<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer;

use Innmind\Url\Url;

final class Room
{
    private Url $location;

    public function __construct(Url $location)
    {
        $this->location = $location;
    }

    public function location(): Url
    {
        return $this->location;
    }
}
