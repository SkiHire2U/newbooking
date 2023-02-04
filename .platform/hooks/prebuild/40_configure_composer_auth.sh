#!/bin/bash

/usr/local/composer.phar self-update --2

# Converted to use the dependencies installed in the build step. This just forces composer to update the autoloader file paths.
/usr/local/composer.phar dump-autoload --no-dev --optimize
