<?php

namespace App\Concerns;

enum InertiaNotificationType: string
{
    case Success = 'success';
    case Error = 'error';
    case Info = 'info';
    case Warning = 'warning';
}
