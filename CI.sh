#!/usr/bin/env bash
set -ex

this_script=$(basename "$0")

# Install some tools
echo "*** [${this_script}] Installing some OS packages"
apt-get update
apt-get install -y git zip

# The composer installation steps via https://getcomposer.org/download/
echo "*** [${this_script}] Downloading composer-setup.php"
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"

echo "*** [${this_script}] Verifying composer-setup.php checksum"
php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"

echo "*** [${this_script}] Installing composer to /usr/local/bin"
php composer-setup.php --install-dir=/usr/local/bin --filename=composer

echo "*** [${this_script}] Removing composer-setup.php"
php -r "unlink('composer-setup.php');"

echo "*** [${this_script}] Changing to binding source directory"
pushd /source

echo "*** [${this_script}] Running composer install"
composer install --prefer-source --no-interaction

echo "*** [${this_script}] Running phpspec"
bin/phpspec run --config=phpspec.yml --bootstrap=./vendor/autoload.php --no-interaction --format=pretty

echo "*** [${this_script}] Running examples"
pushd examples
for example in $(ls *.php); do
    echo "*** [${this_script}] Running ${example}"
    php ${example} --key ${ROSETTE_API_KEY}
done

echo "*** [${this_script}] Finished!"
