#!/usr/bin/env sh
set -eu

# As of version 1.19, the official Nginx Docker image supports templates with
# variable substitution. But that uses `envsubst`, which does not allow for
# defaults for missing variables. Here, first use the regular command shell
# to set the defaults:
export NGINX_HOST=${NGINX_HOST}

# Due to `set -u` this would fail if not defined and no default was set above
echo "HOST: ${NGINX_HOST}"

# Next, let the original entry point do its work
/docker-entrypoint.sh "$@"
