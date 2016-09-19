[![Build Status](https://travis-ci.org/rosette-api/php.svg?branch=master)](https://travis-ci.org/rosette-api/php)

# PHP client binding for Rosette API #
See the wiki for more information.

## Installation ##
`composer require "rosette/api: ~1.3"`

## Docker ##
A Docker image for running the examples against the compiled source library is available on Docker Hub.

Command: `docker run -e API_KEY=api-key -v "<binding root directory>:/source" rosetteapi/docker-python`

Additional environment settings:
`-e ALT_URL=<alternative URL>`
`-e FILENAME=<single filename>`

## Basic Usage ##
See [examples](examples)

## API Documentation ##
See [documentation](http://rosette-api.github.io/php)

## Additional Information ##
Visit [Rosette API site](https://developer.rosette.com)
