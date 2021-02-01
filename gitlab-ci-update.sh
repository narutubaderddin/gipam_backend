#!/usr/bin/env bash

set -e

branch=$1
env=$2
phpbin=$3

echo "Gipam :: Pulling from $branch branch"
git pull origin ${branch}

echo "Gipam :: Running composer install"
composer install

echo "Gipam :: Clearing cache"
${phpbin} bin/console c:c --env=${env}
${phpbin} bin/console c:w --env=${env}

echo "Gipam :: Running Schema update"
${phpbin} bin/console doctrine:migrations:migrate -n --env=${env}
