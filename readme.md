# docker-compose up
docker-compose up -d --build

# docker exec
docker exec -it cakephp_sample_web sh

# docker-compose run

docker-compose run --rm php_cli

# composer.pharの作成

curl -s https://getcomposer.org/installer | docker-compose run --rm php_cli
