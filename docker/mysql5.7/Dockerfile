FROM mysql:5.7
ENV MYSQL_ROOT_PASSWORD secret
ENV MYSQL_DATABASE my_app
ENV MYSQL_USER my_app
ENV MYSQL_PASSWORD secret
COPY docker-entrypoint-initdb.d /docker-entrypoint-initdb.d
