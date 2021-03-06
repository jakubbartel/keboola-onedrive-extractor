<?php

require __DIR__ . '/vendor/autoload.php';

use Keboola\Component\UserException;

try {
    $component = new \Keboola\OneDriveExtractor\Component();
    $component->run();
} catch(UserException $e) {
    error_log($e->getMessage());

    exit(1);
} catch(Throwable $e) {
    error_log(get_class($e) . ': ' . $e->getMessage());
    error_log('File: ' . $e->getFile());
    error_log('Line: ' . $e->getLine());
    error_log('Code: ' . $e->getCode());
    error_log('Trace: ' . $e->getTraceAsString());

    exit(2);
}

exit(0);
