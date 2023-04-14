#!/bin/sh

cd ../$(dirname "$0")
rm -rf dist && mkdir dist && chmod 777 ./dist
yarn install && yarn build

exec "$@"
