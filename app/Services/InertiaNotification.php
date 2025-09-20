<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Request;

final class InertiaNotification
{
    protected string $type;

    protected string $message;

    protected ?string $title = null;

    public function __construct(protected Request $request, protected ?string $key) {}

    public static function make(?string $name = null): InertiaNotification
    {
        return app(InertiaNotification::class, [
            'key' => $name ?? 'notification',
        ]);
    }

    public function success(): self
    {
        $this->type = 'success';

        return $this;
    }

    public function error(): self
    {
        $this->type = 'error';

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

    /**
     * @throws Exception
     */
    public function send(string $key = 'notification'): void
    {
        if (! $this->type) {
            throw new Exception('Notification type is required.');
        }

        if (! $this->message) {
            throw new Exception('Notification message is required.');
        }

        $this->request->session()->flash($key, [
            'type'    => $this->type,
            'title'   => $this->title,
            'message' => $this->message,
        ]);
    }
}
