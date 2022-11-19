<?php
return [
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            // 数据库类型
            'driver'        => getenv('TYPE'),
            // 服务器地址
            'host'          => getenv('HOSTNAME'),
            // 数据库连接端口
            'port'          => getenv('HOSTPORT'),
            // 数据库名
            'database'      => getenv('DATABASE'),
            // 数据库用户名
            'username'      => getenv('USERNAME'),
            // 数据库密码
            'password'      => getenv('PASSWORD'),
            'unix_socket'   => '',
            // 数据库编码默认采用utf8
            'charset'       => getenv('CHARSET'),
            'collation'     => 'utf8mb4_general_ci',
            // 数据库表前缀
            'prefix'        => getenv('PREFIX'),
            'strict'        => true,
            'engine'        => null,
            'options'       => [
                \PDO::ATTR_TIMEOUT => 3
            ],
        ],
    ],
];
