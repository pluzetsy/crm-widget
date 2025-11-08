#!/bin/bash
set -e

if [ ! -d "vendor" ]; then
  echo "Installing composer dependencies..."
  composer install --no-interaction --prefer-dist
fi

php artisan serve --host=0.0.0.0 --port=8000
