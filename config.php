<?php
$config = [
  'db' => [
    'host' => 'localhost',
    'user' => 'root',
    'pass' => '121212',
    'name' => 'devengo',
    'options' => [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
  ]
];

try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
} catch (PDOException $error) {
  $error = $error->getMessage();
}

return $config;