<?php

require __DIR__ . '/vendor/autoload.php';

try {
    $component = new \Keboola\OneDriveExtractor\Component();
    $component->run();
} catch(Throwable $e) {
    error_log(get_class($e) . ': ' . $e->getMessage());
    error_log('File: ' . $e->getFile());
    error_log('Line: ' . $e->getLine());
    error_log('Code: ' . $e->getCode());
    error_log('Trace: ' . $e->getTraceAsString());

    error_log(json_encode($component->getConfig()->getAuthorization()));

    exit(1);
}

exit(0);
