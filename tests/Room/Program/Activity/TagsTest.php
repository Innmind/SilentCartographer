<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity;

use Innmind\SilentCartographer\Room\Program\Activity\Tags;
use PHPUnit\Framework\TestCase;
use Eris\{
    Generator,
    TestTrait,
};

class TagsTest extends TestCase
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
                $this->assertSame(
                    [$s1, $s2, $s3],
                    \iterator_to_array(new Tags($s1, $s2, $s3))
                );
            });
    }
}
