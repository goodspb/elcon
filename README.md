## elcon:

> elcon 是一个构建在 phalcon 基础上的框架, 能快速构建 phalcon 应用

## 说明

> 框架使用者需要熟悉 Phalcon 框架 , 一个C扩展构建的框架 [文档](https://docs.phalconphp.com/zh/)

## 配置

1. 安装phalcon扩展 ( 暂时只支持 php5.* 版本, php7 暂时未支持 )
2. `storage` 目录赋予可读写
3. 运行 `composer install` ; 当编写了新的类文件时, 需要运行 `./dump-autoload` 来确保类能自动加载
4. 配置文件: 复制 `app/config` 中的文件到 `app/config/production` 目录, 根据环境进行配置
5. enjoy it. 欢迎 issue .

## Todo List

<table>
<thead>
    <tr>
        <th>#</th>
        <th>内容</th>
        <th>完成</th>
    </tr>
</thead>
<tbody>
    <tr>
        <td>1</td>
        <td>文档编写</td>
        <td>NO</td>
    </tr>
</tbody>
</table>
