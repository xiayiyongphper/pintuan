#!/bin/bash
PHP_HOME='/usr/local/php7'
${PHP_HOME}/bin/phpize
./configure --with-php-config=${PHP_HOME}/bin/php-config
make
make install