<?php
$output = [];

exec('php console doctrine:database:drop --force --env=test --no-interaction', $output);
exec('php console doctrine:migrations:migrate --env=test --no-interaction', $output);
exec('php console doctrine:fixtures:load --purge-with-truncate --env=test --no-interaction', $output);
exec('php phpunit', $output);

echo PHP_EOL . 'TEST.PHP OUTPUT' . PHP_EOL . '--------' . PHP_EOL . PHP_EOL;
array_map(function ($value) { echo $value . PHP_EOL; }, $output);