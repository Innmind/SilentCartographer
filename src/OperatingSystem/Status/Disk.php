<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\OperatingSystem\Status;

use Innmind\SilentCartographer\{
    SendActivity,
    Room\Program\Activity\Status\VolumeUsageAccessed,
};
use Innmind\Server\Status\Server\{
    Disk as DiskInterface,
    Disk\Volume,
    Disk\Volume\MountPoint,
};
use Innmind\Immutable\Map;

final class Disk implements DiskInterface
{
    private DiskInterface $disk;
    private SendActivity $send;

    public function __construct(DiskInterface $disk, SendActivity $send)
    {
        $this->disk = $disk;
        $this->send = $send;
    }

    public function volumes(): Map
    {
        $volumes = $this->disk->volumes();
        $volumes->foreach(function(string $_, Volume $volume): void {
            ($this->send)(new VolumeUsageAccessed($volume->mountPoint()));
        });

        return $volumes;
    }

    public function get(MountPoint $point): Volume
    {
        ($this->send)(new VolumeUsageAccessed($point));

        return $this->disk->get($point);
    }
}
