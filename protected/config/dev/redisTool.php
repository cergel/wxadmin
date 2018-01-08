<?php
/**
 * Created by PhpStorm.
 * User: liulong
 * Date: 17/3/17
 * Time: 15:05
 */
return [
    'test_test' => [//请不要使用，测试用的，给你们看看格式就行
        'common' => [
            'type' => 'default',//一致性哈希，取模是 modulo
            'db' => [
                [
                    'write' => ['host' => '10.104.10.206', 'port' => 8008, 'timeout' => 3, "prefix" => "test"],
                    'read' => ['host' => '10.104.10.206', 'port' => 8008, 'timeout' => 3, "prefix" => "test"],
                ],

            ],
        ],
    ],
    'star_meet' => [// 明星见面会排期
        'common' => [
            'type' => 'modulo',//一致性哈希，取模是 modulo
            'db' => [
                [
                    'write' => ['host' => '192.168.101.47', 'port' => 7002, 'timeout' => 3, "prefix" => "starmeet_"],
                    'read' => ['host' => '192.168.101.47', 'port' => 7002, 'timeout' => 3, "prefix" => "starmeet_"],
                ],
                [
                    'write' => ['host' => '192.168.101.47', 'port' => 6379, 'timeout' => 3, "prefix" => "starmeet_"],
                    'read' => ['host' => '192.168.101.47', 'port' => 6379, 'timeout' => 3, "prefix" => "starmeet_"],
                ],
                [
                    'write' => ['host' => '192.168.200.253', 'port' => 6379, 'timeout' => 3, "prefix" => "starmeet_"],
                    'read' => ['host' => '192.168.200.253', 'port' => 6379, 'timeout' => 3, "prefix" => "starmeet_"],
                ],
                [
                    'write' => ['host' => '10.104.10.206', 'port' => 8008, 'timeout' => 3, "prefix" => "starmeet_"],
                    'read' => ['host' => '10.104.10.206', 'port' => 8008, 'timeout' => 3, "prefix" => "starmeet_"],
                ],
            ],
        ],
    ],
];