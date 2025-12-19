<?php

namespace App\Services;

use App\Concerns\InertiaNotificationType;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

final class InertiaNotification
{
    protected string $icon;

    protected string $color;

    protected InertiaNotificationType|string $type;

    protected string $message;

    protected ?string $title = null;

    public function __construct(protected Request $request, protected ?string $key) {}

    public static function make(?string $name = null): InertiaNotification
    {
        return resolve(InertiaNotification::class, [
            'key' => $name ?? 'notification',
        ]);
    }

    public function success(): self
    {
        $this->type = InertiaNotificationType::Success;
        $this->icon = 'i-lucide-badge-check';
        $this->color = 'success';

        return $this;
    }

    public function error(): self
    {
        $this->type = InertiaNotificationType::Error;
        $this->icon = 'i-lucide-circle-x';
        $this->color = 'error';

        return $this;
    }

    public function info(): self
    {
        $this->type = InertiaNotificationType::Info;
        $this->icon = 'i-lucide-info';
        $this->color = 'info';

        return $this;
    }

    public function warning(): self
    {
        $this->type = InertiaNotificationType::Warning;
        $this->icon = 'i-lucide-alert-triangle';
        $this->color = 'warning';

        return $this;
    }

    public function message(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function type(InertiaNotificationType|string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /* @see https://ui.nuxt.com/docs/getting-started/integrations/icons/vue#icon-component */
    public function icon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /* @see https://ui.nuxt.com/docs/getting-started/theme/design-system#semantic-colors */
    public function color(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function send(): void
    {

        if (! $this->message) {
            throw new Exception('Notification message is required.');
        }

        Inertia::flash($this->key, [
            'type'    => $this->type,
            'icon'    => $this->icon,
            'color'   => $this->color,
            'title'   => $this->title,
            'message' => $this->message,
        ]);
    }
}
