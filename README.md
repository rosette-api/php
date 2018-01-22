[![Build Status](https://travis-ci.org/rosette-api/php.svg?branch=develop)](https://travis-ci.org/rosette-api/php)

# PHP client binding for Rosette API #
See the wiki for more information.

## Installation ##
`composer require "rosette/api: ~1.9"`

If the version you are using is not [the latest from Packagist](https://packagist.org/packages/rosette/api),
please check for its [**compatibilty with api.rosette.com**](https://developer.rosette.com/features-and-functions?php).
If you have an on-premise version of Rosette API server, please contact support for
binding compatibility with your installation.

## Docker ##
A Docker image for running the examples against the compiled source library is available on Docker Hub.

Command: `docker run -e API_KEY=api-key -v "<binding root directory>:/source" rosetteapi/docker-php`

Additional environment settings:
`-e ALT_URL=<alternative URL>`
`-e FILENAME=<single filename>`

## Basic Usage ##
See [examples](examples)

## API Documentation ##
See [documentation](http://rosette-api.github.io/php)

## Release Notes
See the [wiki](https://github.com/rosette-api/php/wiki/Release-Notes)

## Additional Information ##
Visit [Rosette API site](https://developer.rosette.com)
