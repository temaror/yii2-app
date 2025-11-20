<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => getenv('DB_DSN') ?: 'mysql:host=db;dbname=yii2_books;charset=utf8mb4',
    'username' => getenv('DB_USER') ?: 'yii',
    'password' => getenv('DB_PASS') ?: 'yii',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
