FROM php:5.5-cli
MAINTAINER Sam Hausmann

# install necessary software
RUN apt-get -y update && apt-get install -y vim  && apt-get install -y git

# clone the Rosette API repo from github
RUN git clone https://github.com/rosette-api/php.git
COPY composer.json /php/examples/composer.json
WORKDIR /php/examples

# install mbstring
RUN docker-php-ext-install mbstring

# install composer.phar
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer
RUN composer install

# copy API script
COPY run_php.sh /php/examples/run_php.sh 
RUN chmod 755 /php/examples/run_php.sh

# specify docker volume
VOLUME ["/source"]

# allow interactive bash inside docker container
CMD ./run_php.sh $API_KEY $FILENAME $ALT_URL