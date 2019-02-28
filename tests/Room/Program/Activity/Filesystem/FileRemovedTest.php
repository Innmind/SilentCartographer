<?php
declare(strict_types = 1);

namespace Tests\Innmind\SilentCartographer\Room\Program\Activity\Filesystem;

use Innmind\SilentCartographer\Room\Program\{
    Activity\Filesystem\FileRemoved,
    Activity,
};
use Innmind\Url\Path;
use PHPUnit\Framework\TestCase;

class FileRemovedTest extends TestCase
{
    public function testInterface()
    {
        $activity = new FileRemoved(
            $path = new Path('foo')
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame(['os', 'filesystem'], \iterator_to_array($activity->tags()));
        $this->assertSame($path, $activity->path());
        $this->assertSame('File removed: foo', (string) $activity);
    }
}