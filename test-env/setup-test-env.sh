#!/bin/sh

cat <<EOT
This script setups the Xpost 2 test environment needed to run the end to end
tests. It will setup multiple Wordpress installations.

This script needs the following to be installed:
- PHP <http://www.php.net/>
    - including Gettext support <http://www.php.net/manual/de/book.gettext.php>
    - including OpenSSL support <http://php.net/manual/en/book.openssl.php>
    - including Readline support <http://php.net/manual/de/book.readline.php>
- PHP Unit Testing Framework <https://github.com/sebastianbergmann/phpunit/>
- Subversion/SVN <http://subversion.tigris.org/>
- MySQL, including mysqldump <http://mysql.com/>
- A webserver, e.g. Apache HTTP Server <http://httpd.apache.org/>
- Selenium Server <http://seleniumhq.org/>
- A browser compatible with Selenium, e.g. Firefox <http://www.mozilla.org/>

Make sure ...
- that php and phpunit are in the search path.
- that you copied test-env/sample-config-test-env.php to config-test-env.php and
  filled in the correct settings.
- that you have your webserver, MySQL and Selenium running.

EOT

cd $(dirname $0)/..

php test-env/create-test-env-dirs.php
php wp-installer/wp-cli-multi-install.php
phpunit wp-installer/wp-phpunit-db-install.php
phpunit wp-installer/wp-phpunit-enable-xmlrpc.php
php test-env/create-db-dumps.php

echo "All done."

