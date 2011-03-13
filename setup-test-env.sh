#!/bin/sh
phpunit --bootstrap "$(dirname $0)/test/setup/setup-test-env.php" test/setup/WordpressDbInstaller.php

