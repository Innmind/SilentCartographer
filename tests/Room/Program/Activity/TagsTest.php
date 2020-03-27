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
                    (new Tags($s1, $s2, $s3))->list(),
                );
            });
    }

    public function testMatches()
    {
        $tags = new Tags('foo', 'bar', 'baz');

        $this->assertTrue($tags->matches());
        $this->assertTrue($tags->matches('bar'));
        $this->assertTrue($tags->matches('baz', 'foo', 'bar'));
        $this->assertFalse($tags->matches('foo', 'foobar'));
    }
}
