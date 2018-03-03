<?php

require __DIR__ . '/vendor/autoload.php';

try {
    $extractor = new \Keboola\OneDriveExtractor\Extractor();
    $extractor->run();
} catch(Exception $e) {
    // TODO handle user and system errors
    error_log($e->getMessage());
    exit(1);
} catch(Throwable $e) {
    // TODO handle user and system errors
    error_log('Fatal error');
    error_log($e->getMessage());
    exit(1);
}

exit(0);
