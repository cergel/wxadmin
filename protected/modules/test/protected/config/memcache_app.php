<?php
return array(
    /* 保存公告信息 */
    'class'=>'CMemCache',
    'servers'=>array(
        array(
            'host'=>'192.168.200.83',
            'port'=>11211,
            'weight'=>60,
        ),
        /*
        array(
            'host'=>'server2',
            'port'=>11211,
            'weight'=>40,
        ),
        */
    ),
		'keyPrefix' => '',
		'serializer'=>false,
		'hashKey' => false,
);