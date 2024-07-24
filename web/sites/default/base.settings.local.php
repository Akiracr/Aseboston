<?php

$databases['default']['default'] = array (
  'database' => 'aseboston_d10',
  'username' => 'root',
  'password' => 'mmv',
  'prefix' => '',
  'host' => 'localhost',
  'port' => '3306',
  'isolation_level' => 'READ COMMITTED',
  'driver' => 'mysql',
  'namespace' => 'Drupal\\mysql\\Driver\\Database\\mysql',
  'autoload' => 'core/modules/mysql/src/Driver/Database/mysql/',
);

$settings['trusted_host_patterns'] = [
  '^.+\.aseboston\.com$',
  '^127\.0\.0\.1$',
];
