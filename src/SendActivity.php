<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer;

use Innmind\SilentCartographer\Room\Program\Activity;

interface SendActivity
{
    public function __invoke(Activity $activity): void;
}
