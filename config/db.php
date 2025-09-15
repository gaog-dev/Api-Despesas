<?php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=' . (getenv('DB_HOST') ?: 'db') . ';dbname=' . (getenv('DB_DATABASE') ?: 'despesas_pessoais'),
    'username' => getenv('DB_USERNAME') ?: 'admin',
    'password' => getenv('DB_PASSWORD') ?: 'admin123',
    'charset' => 'utf8mb4',
    // Schema cache options (for production environment)
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 60,
    'schemaCache' => 'cache',
];
