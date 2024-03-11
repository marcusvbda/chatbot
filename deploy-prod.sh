#!/bin/bash

composer install
yarn run build
php artisan optimize:clear
php artisan cache:clear
serverless deploy --stage="production"
