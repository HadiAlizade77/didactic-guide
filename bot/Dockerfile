FROM ghcr.io/puppeteer/puppeteer:latest
USER root

RUN apt-get update && \
  DEBIAN_FRONTEND=noninteractive \
  apt-get install -y \
  libx11-dev \
  libxext-dev \
  curl \
  software-properties-common  \
  nano

ENV NODE_VERSION=16.13.0
RUN apt install -y curl
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash
ENV NVM_DIR=/root/.nvm
RUN . "$NVM_DIR/nvm.sh" && nvm install ${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm use v${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm alias default v${NODE_VERSION}
ENV PATH="/root/.nvm/versions/node/v${NODE_VERSION}/bin/:${PATH}"
RUN node --version
RUN npm --version

RUN npm install pm2 -g
RUN npm install cross-env -g
RUN npm install yarn -g

EXPOSE 3001

RUN mkdir /bot
WORKDIR /bot
ADD . /bot
ADD package.json yarn.lock /bot/
RUN ls
# RUN npm i

RUN yarn --pure-lockfile


EXPOSE 3331

CMD ["cross-env"," NODE_ENV=production","NODE_PORT=3001", "pm2-runtime", "start", "./src/index.js"]

