<?php

exec("php bin/console doctrine:schema:drop --env=test --force --full-database --no-interaction");
exec("php bin/console doctrine:schema:update --env=test --complete --force");
exec("php bin/console hautelook:fixtures:load -n --env=test");
