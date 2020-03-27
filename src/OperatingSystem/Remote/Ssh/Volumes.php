<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\OperatingSystem\Remote\Ssh;

use Innmind\SilentCartographer\{
    SendActivity,
    Room\Program\Activity\Remote\Ssh\MountingVolume,
    Room\Program\Activity\Remote\Ssh\UnmountingVolume,
};
use Innmind\Server\Control\Server\{
    Volumes as VolumesInterface,
    Volumes\Name,
};
use Innmind\Url\{
    Path,
    Authority,
};

final class Volumes implements VolumesInterface
{
    private VolumesInterface $volumes;
    private Authority $authority;
    private SendActivity $send;

    public function __construct(
        VolumesInterface $volumes,
        Authority $authority,
        SendActivity $send
    ) {
        $this->volumes = $volumes;
        $this->authority = $authority;
        $this->send = $send;
    }

    public function mount(Name $name, Path $mountpoint): void
    {
        ($this->send)(new MountingVolume($this->authority, $name, $mountpoint));

        $this->volumes->mount($name, $mountpoint);
    }

    public function unmount(Name $name): void
    {
        ($this->send)(new UnmountingVolume($this->authority, $name));

        $this->volumes->unmount($name);
    }
}
