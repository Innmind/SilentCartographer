<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\IPC\Message;

use Innmind\IPC\Message;
use Innmind\Json\Json;
use Innmind\Filesystem\MediaType;
use Innmind\Immutable\Str;

final class PanelActivated implements Message
{
    private Str $content;
    private MediaType $mediaType;

    public function __construct(string ...$tags)
    {
        $this->content = Str::of(Json::encode([
            'message' => 'panel_activated',
            'tags' => $tags,
        ]));
        $this->mediaType = new MediaType\MediaType('application', 'json');
    }

    public function mediaType(): MediaType
    {
        return $this->mediaType;
    }

    public function content(): Str
    {
        return $this->content;
    }

    public function equals(Message $message): bool
    {
        if ((string) $this->mediaType !== (string) $message->mediaType()) {
            return false;
        }

        $data = Json::decode((string) $message->content());

        return ($data['message'] ?? '') === 'panel_activated';
    }
}
