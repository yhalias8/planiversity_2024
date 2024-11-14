#!/bin/bash
set -e

echo "Deployment started ..."

# Enter maintenance mode or return true
# if already is in maintenance mode
(php artisan down) || true

# Pull the latest version of the app
git pull origin main

# Source nvm directory
export NVM_DIR=~/.nvm
source ~/.nvm/nvm.sh


echo "Deployment finished!"
