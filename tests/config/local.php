<?php
/**
 * local.
 *
 * @see https://github.com/fanatov37/spav.git for the canonical source repository
 *
 * @copyright Copyright (c) 2015
 * @license SPAV (c)
 * @author VladFanatov
 */

return [
    'db' => [
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=spav;host=localhost',
        'username' => 'root',
        'password' => '140666',
        'driver_options' => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
        ],
    ],

    'service_manager' => [
        'factories' => [
            \Zend\Db\Adapter\Adapter::class => \Zend\Db\Adapter\AdapterServiceFactory::class,
        ],
    ],

    'log' => [
        'stream' => '/home/fan/workspace/spav.local/data/logs/logger.log',
    ],
];
