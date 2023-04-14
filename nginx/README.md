generate ssl cert


openssl req -newkey rsa:2048 -nodes -keyout app.key -x509 -days 365 -out app.crt -subj "/CN=nuxt-app.org