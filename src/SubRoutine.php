<?php
declare(strict_types = 1);

namespace Innmind\SilentCartographer;

use Innmind\SilentCartographer\{
    IPC\Message\PanelActivated,
    IPC\Message\PanelDeactivated,
    Exception\UnknownProtocol,
};
use Innmind\IPC\{
    Server,
    Message,
    Client,
    Exception\Stop,
};
use Innmind\Json\Json;
use Innmind\Immutable\Map;

final class SubRoutine
{
    private Server $listen;
    private Protocol $protocol;
    private PanelActivated $panelActivated;
    /** @var Map<Client, list<string>> */
    private Map $panels;

    public function __construct(Server $listen, Protocol $protocol)
    {
        $this->listen = $listen;
        $this->protocol = $protocol;
        $this->panelActivated = new PanelActivated;
        /** @var Map<Client, list<string>> */
        $this->panels = Map::of(Client::class, 'array');
    }

    public function __invoke(): void
    {
        $this->panels = $this->panels->clear();

        ($this->listen)(function(Message $message, Client $client): void {
            if ($this->panelActivated->equals($message)) {
                /** @var array{message: string, tags: list<string>} */
                $content = Json::decode($message->content()->toString());
                $tags = $content['tags'];
                $this->register($client, ...$tags);
            } else if ($message->equals(new PanelDeactivated)) {
                $this->unregister($client);
            } else {
                $this->forward($message);
            }
        });
    }

    private function register(Client $client, string ...$tags): void
    {
        $this->panels = $this->panels->put($client, $tags);
    }

    private function unregister(Client $client): void
    {
        $this->panels = $this->panels->remove($client);

        if ($this->panels->empty()) {
            throw new Stop;
        }
    }

    private function forward(Message $message): void
    {
        try {
            $activity = $this->protocol->decode($message)->activity();
        } catch (UnknownProtocol $e) {
            // do not break sub routine when receiving invalid messages
            return;
        }

        $this->cleanupPanels();

        $this
            ->panels
            ->filter(static function(Client $client, array $tags) use ($activity): bool {
                return $activity->tags()->matches(...$tags);
            })
            ->foreach(static function(Client $client) use ($message): void {
                try {
                    $client->send($message);
                } catch (\Exception $e) {
                    // do not prevent other panels from receiving the message
                }
            });
    }

    private function cleanupPanels(): void
    {
        $this->panels = $this->panels->filter(static function(Client $client): bool {
            return !$client->closed();
        });
    }
}
