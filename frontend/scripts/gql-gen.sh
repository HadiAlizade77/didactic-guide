#!/bin/sh

cd $(dirname "$0")
cd ../src/graphql

rm -rf */*.hooks.tsx
cd ..
cd ..
yarn codegen

exec "$@"
