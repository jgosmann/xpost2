#!/bin/sh
cd $(dirname $0)
phpunit --bootstrap test/setup/setup-test-env.php test/setup/WordpressDbInstaller.php

