#!/usr/bin/env bash
set -ex

this_script=$(basename "$0")

echo "*** [${this_script}] Running composer self-update"
composer self-update

echo "*** [${this_script}] Running composer install"
composer install --prefer-source --no-interaction

echo "*** [${this_script}] Running phpspec"
bin/phpspec run --config=phpspec.yml --bootstrap=./vendor/autoload.php --no-interaction --format=pretty

echo "*** [${this_script}] Complete."
