<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Generic,
    Activity,
    Activity\Tags,
};
use PHPUnit\Framework\TestCase;
use Eris\{
    Generator,
    TestTrait,
};

class GenericTest extends TestCase
{
    use TestTrait;

    public function testInterface()
    {
        $this
            ->forAll(
                Generator\string(),
                Generator\string(),
                Generator\string()
            )
            ->then(function($s1, $s2, $s3): void {
                $activity = new Generic(
                    $tags = new Tags($s1, $s2),
                    $s3
                );

                $this->assertInstanceOf(Activity::class, $activity);
                $this->assertSame($tags, $activity->tags());
                $this->assertSame($s3, (string) $activity);
            });
    }
}
