<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\Room\Program\Activity\Remote\Sockets;

use Innmind\SilentCartographer\Room\Program\{
    Activity,
    Activity\Tags,
};
use Innmind\Socket\Internet\Transport;
use Innmind\Url\AuthorityInterface;

final class SocketOpened implements Activity
{
    private Transport $transport;
    private AuthorityInterface $authority;
    private Tags $tags;

    public function __construct(Transport $transport, AuthorityInterface $authority)
    {
        $this->transport = $transport;
        $this->authority = $authority;
        $this->tags = new Tags('os', 'remote', 'socket');
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function __toString(): string
    {
        return "Socket opened: {$this->transport}://{$this->authority}";
    }
}
