<?php

set_error_handler(function($errno, $errstr) {
    return strpos($errstr, 'ini_set()') === 0;
}, E_DEPRECATED);

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
