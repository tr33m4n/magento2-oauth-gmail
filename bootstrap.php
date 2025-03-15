<?php

declare(strict_types=1);

$vendorPath = realpath(__DIR__ . '/vendor/');

file_put_contents(
    "$vendorPath/magento/magento2-base/app/autoload.php",
    "<?php return '$vendorPath';"
);

require_once sprintf('%s/magento/magento2-base/dev/tests/unit/framework/bootstrap.php', $vendorPath);
