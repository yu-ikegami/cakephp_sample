FROM alpine:3.8
RUN apk add --no-cache \
  php5 \
  php5-phar \
  php5-json \
  php5-openssl
RUN ln -s /usr/bin/php5 /usr/bin/php
ENTRYPOINT [ "php" ]
