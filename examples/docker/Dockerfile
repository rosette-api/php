FROM jedisct1/phusion-baseimage-latest:16.04
MAINTAINER Fiona Hasanaj
ENV TZ=America/New_York
ENV LANGUAGE=php

# install necessary software

RUN apt-get -y update && apt-get install -y \
    git \
    language-pack-en-base \
    libxslt1-dev \
    python-software-properties \
    unzip \
    vim \
    wget \
    zip

RUN LC_ALL=en_US.UTF-8 add-apt-repository ppa:ondrej/php
RUN apt-get -y update && apt-get install -y \
    php7.1

# For non-root user
RUN mkdir -p /.composer && chmod 777 /.composer

RUN echo "memory_limit=-1" >> /etc/php/7.1/cli/php.ini && \
    echo "date.timezone=America/New_York" >> /etc/php/7.1/cli/php.ini

RUN wget -O /usr/local/bin/composer https://getcomposer.org/composer.phar && \
    chmod +x /usr/local/bin/composer

RUN mkdir /test-dev
COPY composer.json /test-dev/composer.json
WORKDIR /test-dev

RUN composer install

COPY run_php.sh /test-dev/run_php.sh
RUN chmod 755 /test-dev/run_php.sh

VOLUME ["/source"]

# allow interactive bash inside docker container
CMD ./run_php.sh $API_KEY $ALT_URL

