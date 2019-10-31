## Endpoint Examples
These examples are scripts that can be run independently to demonstrate the Rosette API functionality.

Each example file demonstrates one of the capabilities of the Rosette Platform. Each example, when run, prints its output to the console.

Here are some methods for running the examples.  Each example will also accept an optional `--url` parameter for
overriding the default URL.

Also, the examples are dual purpose in that they're used to test both source and packagist.  The instructions include steps to address this depending on what you are testing.

A note on prerequisites.  Rosette API only supports TLS 1.2 so ensure your toolchain also supports it.

#### Docker/Latest Version From Packagist

```
git clone git@github.com:rosette-api/php.git
cd php
docker run -it -v $(pwd):/source --entrypoint bash php:7.3-cli

apt-get update
apt-get install -y git zip

curl -s -o /usr/local/bin/composer https://getcomposer.org/composer.phar
chmod +x /usr/local/bin/composer
composer self-update

cd /source/examples
composer require "rosette/api"
mv vendor/ ../.

php ping.php --key $API_KEY

```

#### Docker/Latest Source

```
git clone git@github.com:rosette-api/php.git
cd php
docker run -it -v $(pwd):/source --entrypoint bash php:7.3-cli

apt-get update
apt-get install -y git zip

curl -s -o /usr/local/bin/composer https://getcomposer.org/composer.phar
chmod +x /usr/local/bin/composer
composer self-update

cd /source
composer install
cd examples

php ping.php --key $API_KEY

```
