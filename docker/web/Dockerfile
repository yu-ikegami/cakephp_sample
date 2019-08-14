FROM alpine:3.8
RUN apk add --no-cache \
  apache2 \
  php5-apache2 \
  php5-phar \
  php5-json \
  php5-openssl \
  php5-intl \
  php5-xml \
  php5-pdo_mysql
RUN mkdir /run/apache2
COPY etc /etc
RUN ln -s /var/www/app/webroot /var/www/htdocs
CMD ["httpd", "-DFOREGROUND"]