<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Generic,
    Activity,
    Activity\Tags,
};
use PHPUnit\Framework\TestCase;
use Innmind\BlackBox\{
    PHPUnit\BlackBox,
    Set,
};

class GenericTest extends TestCase
{
    use BlackBox;

    public function testInterface()
    {
        $this
            ->forAll(
                Set\Unicode::strings(),
                Set\Unicode::strings(),
                Set\Unicode::strings()
            )
            ->then(function($s1, $s2, $s3): void {
                $activity = new Generic(
                    $tags = new Tags($s1, $s2),
                    $s3
                );

                $this->assertInstanceOf(Activity::class, $activity);
                $this->assertSame($tags, $activity->tags());
                $this->assertSame($s3, $activity->toString());
            });
    }
}
