<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\OperatingSystem\Control;

use Innmind\SilentCartographer\{
    SendActivity,
    Room\Program\Activity\Control\MountingVolume,
    Room\Program\Activity\Control\UnmountingVolume,
};
use Innmind\Server\Control\Server\{
    Volumes as VolumesInterface,
    Volumes\Name,
};
use Innmind\Url\Path;

final class Volumes implements VolumesInterface
{
    private VolumesInterface $volumes;
    private SendActivity $send;

    public function __construct(VolumesInterface $volumes, SendActivity $send)
    {
        $this->volumes = $volumes;
        $this->send = $send;
    }

    public function mount(Name $name, Path $mountpoint): void
    {
        ($this->send)(new MountingVolume($name, $mountpoint));

        $this->volumes->mount($name, $mountpoint);
    }

    public function unmount(Name $name): void
    {
        ($this->send)(new UnmountingVolume($name));

        $this->volumes->unmount($name);
    }
}
