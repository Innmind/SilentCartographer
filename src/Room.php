<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer;

use Innmind\Url\UrlInterface;

final class Room
{
    private $location;

    public function __construct(UrlInterface $location)
    {
        $this->location = $location;
    }

    public function location(): UrlInterface
    {
        return $this->location;
    }
}
