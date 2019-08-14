FROM alpine:3.8
RUN apk add --no-cache \
  apache2 \
  php5-apache2
RUN mkdir /run/apache2
COPY etc /etc
RUN ln -s /var/www/app/public /var/www/htdocs
CMD ["httpd", "-DFOREGROUND"]