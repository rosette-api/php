FROM php:5.5-cli
MAINTAINER Fiona Hasanaj

# install necessary software
RUN apt-get -y update && apt-get install -y vim  && apt-get install -y git

# install mbstring
RUN docker-php-ext-install mbstring

COPY run_php.sh /php/examples/run_php.sh
RUN chmod 755 /php/examples/run_php.sh
WORKDIR /php/examples

# install composer.phar
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer && composer require "rosette/api: ~1.0.0"

# allow interactive bash inside docker container
CMD ./run_php.sh $API_KEY $ALT_URL; /bin/bash

VOLUME ["/source"]
