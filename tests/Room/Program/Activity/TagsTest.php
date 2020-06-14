<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity;

use Innmind\SilentCartographer\Room\Program\Activity\Tags;
use PHPUnit\Framework\TestCase;
use Innmind\BlackBox\{
    PHPUnit\BlackBox,
    Set,
};

class TagsTest extends TestCase
{
    use BlackBox;

    public function testInterface()
    {
        $this
            ->forAll(Set\Sequence::of(Set\Unicode::strings()))
            ->then(function($tags): void {
                $this->assertSame(
                    $tags,
                    (new Tags(...$tags))->list(),
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
