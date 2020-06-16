<?php

require_once __DIR__."/bootstrap.php";

//
// Init storage
//

if (! is_dir($config['storage_path'])) {
    echo sprintf('Prepare storage').PHP_EOL;
    if (! mkdir($concurrentDirectory = $config['storage_path'], 0777, true) && ! is_dir($concurrentDirectory)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
    }
    foreach ([$storage::TYPE_MESSAGE, $storage::TYPE_PARTNER] as $dir) {
        $path = $config['storage_path'].DIRECTORY_SEPARATOR.$dir;
        if (! is_dir($path)) {
            if (! mkdir($path, 0777, true) && ! is_dir($path)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $path));
            }
            echo sprintf('`%s` - created', $path).PHP_EOL;
        } else {
            echo sprintf('`%s` - already exists', $path).PHP_EOL;
        }
    }
}

//
// Init partners
//

echo sprintf('Prepare partners').PHP_EOL;

foreach ($config['partners'] as $partner) {
    if (empty($partner['id'])) {
        throw new \InvalidArgumentException('`id` required.');
    }

    $saved = $storage->savePartner(
        $storage->initPartner($partner)
    );

    if ($saved) {
        echo sprintf('Partner `%s` - OK', $partner['id']).PHP_EOL;
    } else {
        echo sprintf('Partner `%s` - FAILED', $partner['id']).PHP_EOL;
    }
}
