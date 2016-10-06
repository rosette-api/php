# PHP client binding for Rosette API #
See the wiki for more information.

## Installation ##
`composer require "rosette/api: ~1.4"`

=======
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

## Additional Information ##
Visit [Rosette API site](https://developer.rosette.com)
