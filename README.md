# Elcon 简介:

Elcon 是一个构建在 phalcon 基础上的框架, 能快速构建 phalcon 应用

## 构成

> 框架使用者需要熟悉 Phalcon 框架 , 一个C扩展构建的框架 [文档](https://docs.phalconphp.com/zh/)

## 配置

1.安装phalcon扩展 ( 暂时只支持 php5.* 版本, php7 暂时未支持 )

    https://docs.phalconphp.com/zh/latest/reference/install.html#linux-solaris-mac (官方)

    git clone --depth 1 --branch phalcon-v2.0.9 https://github.com/phalcon/cphalcon.git
    cd cphalcon/ext
    phpize
    ./configure --with-php-config=/usr/bin/php-config
    make && make install
    touch /etc/php5/mods-available/phalcon.ini
    echo -e "extension=phalcon.so" >> /etc/php5/mods-available/phalcon.ini
    ln -s /etc/php5/mods-available/phalcon.ini /etc/php5/fpm/conf.d/100-phalcon.ini
    ln -s /etc/php5/mods-available/phalcon.ini /etc/php5/cli/conf.d/100-phalcon.ini


2.在根目录新建 `storage` 赋予777权限

3.运行 `composer install` ; 当编写了新的类文件时, 需要运行 `composer dump-autoload` 来确保类能自动加载

4.生产环境新建 `app/common/config/production` 目录自动覆盖config对应文件的配置

5.enjoy it. 欢迎 issue .

## TODO

1. Phalcon 与 swoole 的结合

2. 文档编写
