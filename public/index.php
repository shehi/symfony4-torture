<?php

declare(strict_types=1);

use App\Kernel;

require dirname(__DIR__) . '/config/bootstrap.php';

return static function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
