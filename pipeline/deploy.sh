#!/bin/bash

cd "/staging/fut"
git add .
git commit -am "Updated by the deploy.sh file."
git pull
#php artisan migrate --seed
#php artisan config:cache
#php artisan queue:restart
#npm run dev
