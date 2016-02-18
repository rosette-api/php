FROM php:5.5-cli
MAINTAINER Fiona Hasanaj
ENV TZ=America/New_York

# install necessary software
RUN apt-get -y update && apt-get install -y vim  && apt-get install -y git && apt-get install -y libxslt1-dev

# copy over the necessary files
COPY composer.json /php-dev/examples/composer.json
COPY composer.json /php-dev/composer.json
WORKDIR /php-dev

# install mbstring
RUN docker-php-ext-install mbstring

#Add xsl in PHP containers image
RUN docker-php-ext-install xsl

# install composer.phar
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer
RUN composer install && cp -r vendor /php-dev/examples

# copy over the necessary files
COPY run_php.sh /php-dev/run_php.sh 
RUN chmod 755 /php-dev/run_php.sh

# specify docker volume
VOLUME ["/source"]

# allow interactive bash inside docker container
CMD ./run_php.sh $API_KEY $FILENAME $ALT_URL $GIT_USERNAME $VERSION