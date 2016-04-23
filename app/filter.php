<?php

/**
 * 默认的 filter 包含 :
 *
 * string       带标签
 * email        删掉除字母、数字和 !#$%&*+-/=?^_`{|}~@.[] 外的全部字符
 * int          删掉除R数字、加号、减号外的全部字符
 * float        删掉除数字、点号和加号、减号外的全部字符
 * alphanum     删掉除[a-zA-Z0-9]外的全部字符
 * striptags    调用 strip_tags 方法
 * trim         调用 trim 方法
 * lower        调用 strtolower 方法
 * upper        调用 strtoupper 方法
 *
 * 自定义 filter 请填下面
 *
 * 键名 : filter 名称
 * 键值 : 当为字符时, 默认正则(preg_replace)操作; 可放置匿名函数
 */

return [
    //preg_replace('/[^0-9a-f]/', '', $value)
    //'md5' => '/[^0-9a-f]/',
    //也可以 , 代表: preg_replace('/[^0-9a-f]/', 'aaa', $value)
    //'_md5' => array('/[^0-9a-f]/', 'aaa'),
    //匿名函数
    'ipv4' => function($value) {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    },
];