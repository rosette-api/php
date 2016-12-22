FROM phusion/baseimage
MAINTAINER Fiona Hasanaj
ENV TZ=America/New_York
ENV LANGUAGE=php

# install necessary software

RUN apt-get -y update && apt-get install -y \
    git \
    language-pack-en-base \
    libxslt1-dev \
    python-software-properties \
    vim \
    wget

RUN LC_ALL=en_US.UTF-8 add-apt-repository ppa:ondrej/php5-5.6
RUN apt-get -y update && apt-get install -y \
    php-dev \
    php-cli \
    php-curl \
    libapache2-mod-php


RUN echo "memory_limit=-1" >> /etc/php/7.0/cli/php.ini && \
    echo "date.timezone=America/New_York" >> /etc/php/7.0/cli/php.ini

RUN wget -O /usr/local/bin/composer https://getcomposer.org/composer.phar && \
    chmod +x /usr/local/bin/composer && composer self-update

# install mbstring
# RUN docker-php-ext-install mbstring

COPY run_php.sh /php/examples/run_php.sh
RUN chmod 755 /php/examples/run_php.sh
WORKDIR /php/examples

# allow interactive bash inside docker container
CMD ./run_php.sh $API_KEY $ALT_URL

VOLUME ["/source"]
