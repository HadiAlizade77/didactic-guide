"# docker-template"

# Run Development

docker-compose -f docker-compose-dev.yml up --build --force-recreate

# Set up backend

docker-compose -f docker-compose-staging.yml run --rm composer install --no-scripts --ignore-platform-reqs &&
docker-compose -f docker-compose-staging.yml run --rm artisan key:generate &&
docker-compose -f docker-compose-staging.yml run --rm artisan migrate:fresh --seed &&
docker-compose -f docker-compose-staging.yml run --rm artisan passport:install &&
docker-compose -f docker-compose-staging.yml run --rm artisan horizon:install &&
docker-compose -f docker-compose-staging.yml run --rm artisan telescope:install

# Run Staging

docker-compose -f docker-compose-staging.yml up --build

# Run production

docker-compose -f docker-compose.yml up --build

# Artisan

docker-compose -f docker-compose-dev.yml run --rm artisan -----

# Composer

docker-compose -f docker-compose-staging.yml run --rm composer ----- --no-scripts --ignore-platform-reqs

# NPM - Backend

docker-compose -f docker-compose-dev.yml run --rm nodepackage -----

# PHPUnit Test

docker-compose -f docker-compose-dev.yml run --rm phpunit ----

# Behat Test

docker-compose -f docker-compose-dev.yml run --rm behat ----

# JEST Test

docker-compose -f docker-compose-dev.yml run --rm jest ----

# Cypress Test

docker-compose -f docker-compose-dev.yml run --rm cypress
docker-compose -f docker-compose-dev.yml run --rm cypress_browser

# Cypress Browser Test

docker network ls - Get the network name

**_ MAC CONFIG _**
IP=$(ipconfig getifaddr en0)
/usr/X11/bin/xhost + $IP

**_ LINUX CONFIG _**
export DISPLAY=:0
xhost +si:localuser:root

docker run --network docker-nuxt-gol_laravel -it -v $PWD:/e2e -v /tmp/.X11-unix:/tmp/.X11-unix -w /e2e -e DISPLAY=$IP:0 --entrypoint cypress cypress/included:6.8.0 open --project .

# Remove a container/image

docker rm cypress_lucyp && docker rm jest_lucyp && docker rm npm_lucyp && docker stop phpunit_lucyp && docker rm phpunit_lucyp && docker stop behat_lucyp && docker rm behat_lucyp && docker rm cypress_browser_lucyp

# Restart docker

sudo service docker restart

# Clear out volumes

docker system prune -a --volumes

# Get the log information about a container/image

docker logs <id>

# SSH into docker container

docker exec -it <name> ash
