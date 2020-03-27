<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program;

use Innmind\SilentCartographer\Room\Program\Activity\Tags;

interface Activity
{
    public function tags(): Tags;
    public function toString(): string;
}
