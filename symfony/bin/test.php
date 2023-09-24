<?php
$output = [];

exec('php /app/bin/console doctrine:migrations:migrate --env=test --no-interaction', $output);
exec('php /app/bin/console doctrine:fixtures:load --purge-with-truncate --env=test --no-interaction', $output);
exec('php /app/bin/phpunit /app/tests -c /app/phpunit.xml.dist', $output);

echo PHP_EOL . 'TEST.PHP OUTPUT' . PHP_EOL . '--------' . PHP_EOL . PHP_EOL;
array_map(function ($value) { echo $value . PHP_EOL; }, $output);
