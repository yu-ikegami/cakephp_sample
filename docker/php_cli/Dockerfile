FROM alpine:3.8 as build
RUN apk add --no-cache \
  php5-openssl \
  php5-pear \
  make \
  autoconf \
  gcc \
  libc-dev \
  php5-dev \
  zlib-dev \
  libmemcached-dev
RUN ln -s /usr/bin/php5 /usr/bin/php
RUN pecl channel-update pecl.php.net && pecl install \
  memcached-2.2.0 
  
FROM alpine:3.8
RUN apk add --no-cache \
  php5 \
  php5-phar \
  php5-json \
  php5-openssl \
  php5-intl \
  php5-xml \
  php5-pdo_mysql \
  php5-pdo_sqlite \
  libmemcached
COPY --from=build /usr/lib/php5/modules/memcached.so /usr/lib/php5/modules/
RUN echo "extension=memcached.so" > /etc/php5/conf.d/memcached.ini
RUN ln -s /usr/bin/php5 /usr/bin/php

