<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\Status;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\Server\Status\Server\Disk\Volume\MountPoint;

final class VolumeUsageAccessed implements Activity
{
    private MountPoint $mountPoint;
    private Tags $tags;

    public function __construct(MountPoint $mountPoint)
    {
        $this->mountPoint = $mountPoint;
        $this->tags = new Tags('os', 'status');
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function toString(): string
    {
        return "Volume usage accessed: {$this->mountPoint->toString()}";
    }
}
