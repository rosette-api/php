#!/usr/bin/env bash
set -ex

this_script=$(basename "$0")

# Install some tools
echo "*** [${this_script}] Installing some OS packages"
apt-get update
apt-get install -y git zip

# The composer installation steps via https://getcomposer.org/download/
# and https://getcomposer.org/doc/faqs/how-to-install-composer-programmatically.md
echo "*** [${this_script}] Downloading composer-setup.php"
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
actual_checksum="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"

echo "*** [${this_script}] Verifying composer-setup.php checksum"
expected_checksum="$(php -r 'copy("https://composer.github.io/installer.sig", "php://stdout");')"
if [ "${expected_checksum}" != "${actual_checksum}" ]; then
    >&2 echo '*** [${this_script}] ERROR: Invalid installer checksum'
    exit 1
fi

echo "*** [${this_script}] Installing composer to /usr/local/bin"
php composer-setup.php --install-dir=/usr/local/bin --filename=composer

echo "*** [${this_script}] Removing composer-setup.php"
php -r "unlink('composer-setup.php');"

echo "*** [${this_script}] Changing to binding source directory"
pushd /source

# TODO:  Review if we should be using a compose.lock file instead.
echo "*** [${this_script}] Running composer update"
composer update

echo "*** [${this_script}] Running composer install"
composer install --prefer-source --no-interaction

echo "*** [${this_script}] Running phpspec"
bin/phpspec run --config=phpspec.yml --bootstrap=./vendor/autoload.php --no-interaction --format=pretty

echo "*** [${this_script}] Running examples"
pushd examples
for example in $(ls *.php); do
    echo "*** [${this_script}] Running ${example}"
    php ${example} --key ${ROSETTE_API_KEY}
    # TODO:  Capture output in a file and only print it for errors.
done

echo "*** [${this_script}] Finished!"
