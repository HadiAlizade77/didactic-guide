FROM node:12.20-alpine

# update and install dependency
RUN apk update && apk upgrade
RUN apk add git

# create destination directory
RUN mkdir -p /var/www/html/backend
WORKDIR /var/www/html/backend

####################################
# copy the app, note .dockerignore
COPY package*.json /var/www/html/backend/
RUN npm install
