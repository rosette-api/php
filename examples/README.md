PHP Examples
============

## Endpoint Examples ##
These examples are scripts that can be run independently to demonstrate the Rosette API functionality.

Each example file demonstrates one of the capabilities of the Rosette Platform. Each example, when run, prints its output to the console.

Here are some methods for running the examples.  Each example will also accept an optional `--url` parameter for
overriding the default URL.

A note on prerequisites.  Rosette API only supports TLS 1.2 so ensure your toolchain also supports it.

### Packagist ###
You can now run your desired _endpoint_.php file to see it in action.  Before running the examples
for the first time:

1. ```cd examples```
2. ```composer update```
3. The examples are dual purpose in that they're used to test both source and packagist. In order to meet that requirement, the examples expect the vendor directory to be at the same level as examples/.
```cp -r ./vendor/. ../vendor``` or you can edit the example to reference the vendor directory that is in the examples directory.

For example, run `php categories.php` if you want to see the categories functionality demonstrated.

All files require you to input your Rosette API User Key after `--key` to run.
For example: `php ping.php --key 1234567890`

All also allow you to input your own service URL if applicable.
For example: `php ping.php --key 1234567890 --url http://www.myurl.com`

### Docker ###
Docker files can be found [here](https://github.com/rosette-api/docker/tree/develop/examples/docker)

#### Summary
To simplify the running of the PHP examples, the Dockerfile will build an image and install the latest rosette-api library.

#### Basic Usage
Build the docker image, e.g. `docker build -t basistech/php .`

Run an example as `docker run -e API_KEY=api-key -v "path-to-example-source:/source" basistech/php:1.1`

To test against a specific source file, add `-e FILENAME=filename` before the `-v`.

To test against an alternate url, add `-e ALT_URL=alternate_url` before the `-v`.
