<?php

exec('php console doctrine:database:drop --force --env=test --no-interaction');
exec('php console doctrine:migrations:migrate --env=test --no-interaction');
exec('php console doctrine:fixtures:load --purge-with-truncate --env=test --no-interaction');
exec('php phpunit');