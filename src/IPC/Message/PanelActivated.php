<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer\IPC\Message;

use Innmind\IPC\Message;
use Innmind\Json\Json;
use Innmind\MediaType\MediaType;
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
        $this->mediaType = new MediaType('application', 'json');
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
        if ($this->mediaType->toString() !== $message->mediaType()->toString()) {
            return false;
        }

        $data = Json::decode($message->content()->toString());

        return ($data['message'] ?? '') === 'panel_activated';
    }
}
